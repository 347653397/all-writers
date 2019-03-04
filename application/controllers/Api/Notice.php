<?php
/**
 * Created by PhpStorm
 * Desc: 注意该类不继承Api_Controller  微信异步通知
 * User: guochao
 * Date: 2018/7/20
 * Time: 下午6:25
 */

class Notice extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->config("wechat");
        $this->wechat = new \EasyWeChat\Foundation\Application(config_item("wechat"));

        $this->load->library('form_validation');

        $this->load->model('CourseOrder_Model', 'CourseOrder');
        $this->load->model('CourseOrderDetails_Model', 'CourseOrderDetails');

        $this->load->model('UserCashJournal_Model', 'UserCashJournal');

        $this->load->model('RewardOrder_Model', 'RewardOrder');

        $this->load->model('ContentComment_model', 'ContentComment');

        $this->load->library('Redislib');
    }

    /**
     * 课程购买 微信异步通知
     */
    public function payCourseNotify()
    {
        log_message('debug', '课程支付-微信支付-异步通知接口时间：' . date('Y-m-d H:i:s'));

        $response = $this->wechat->payment->handleNotify(function ($notify, $successful) {

            $CourseOrder = $this->CourseOrder->_getOne('*', ['out_trade_no' => $notify->out_trade_no]);
            if (!$CourseOrder) return 'order is not exist';

            //已处理
            if ($CourseOrder['pay_status'] == 2) return true;
            //返回订单金额
            if ((100 * $CourseOrder['pay_fee']) != $notify->total_fee) {
                log_message('error', '微信异步通知接口返回订单金额不对');
                return false;
            }

            //用户是否支付
            if ($successful) {  //支付成功
                $this->db->trans_begin();
                //改订单状态
                $user_money = $CourseOrder['items_user_id'] ?
                    (1 - $CourseOrder['cut_rate']) * $CourseOrder['pay_fee'] : 0; //用户投稿被购买收入

                $res_1 = $this->CourseOrder->editCourseOrder(
                    ['pay_status' => 2, 'transaction_id' => $notify->transaction_id,
                        'payment_at' => time(), 'user_money' => $user_money, 'updated_at' => time()],
                    ['id' => $CourseOrder['id']]
                );
                //改详情表状态
                $res_2 = $this->CourseOrderDetails->editCourseOrderDetails(
                    ['pay_status' => 2, 'updated_at' => time()],
                    ['course_order_id' => $CourseOrder['id']]
                );
                //插入流水记录
                $User = $this->User->_getOne('*', ['id' => $CourseOrder['user_id']]);
                $res_3 = $this->UserCashJournal->addUserCashJournal([
                    'user_id' => $User['id'],
                    'trade_type' => 2, //
                    'type' => 1,  //交易方式 1=>微信  2=>个人账户
                    'money' => $CourseOrder['pay_fee'],
                    'inorout' => 3, //不进不出
                    'out_id' => $CourseOrder['id'],
                    'original_balance' => $User['cash_balance'],
                    'current_balance' => $User['cash_balance'],
                    'created_at' => time()
                ]);

                //若是用户投稿
                if (!empty($CourseOrder['items_user_id'])) {
                    $items_user_id = $CourseOrder['items_user_id'];
                    $items_user = $this->User->_getOne('*', ['id' => $items_user_id]);
                    if (!$items_user) throw new \Exception('投稿用户记录不存在');
                    $current_balance = $items_user['cash_balance'] + $user_money;
                    $res_4 = $this->User->_update(
                        [
                            'cash_balance' => $current_balance,
                            'updated_at' => time()
                        ], ['id' => $items_user_id]);
                    if (!$res_4) throw new \Exception('更新投稿用户金额失败');
                    //记录投稿被购买收入流水
                    $res_4 = $this->UserCashJournal->addUserCashJournal([
                        'user_id' => $items_user_id,
                        'trade_type' => 7, //投稿被购买收入
                        'type' => 2,  //交易方式 1=>微信  2=>个人账户
                        'money' => $user_money,
                        'inorout' => 1, //进
                        'out_id' => $CourseOrder['id'],
                        'original_balance' => $items_user['cash_balance'],
                        'current_balance' => $current_balance,
                        'created_at' => time()
                    ]);
                    if (!$res_4) throw new \Exception('记录投稿被购买收入流水失败');
                    $key1 = $this->config->item('redis_keys')['walletDotPrex'] . $items_user_id;
                    $this->redislib->set($key1, 1);
                }

                if ($res_1 && $res_2 && $res_3) {
                    $this->db->trans_commit();
                    return true;
                } else {
                    log_message('error', '更新数据异常');
                    $this->db->trans_rollback();
                }
            } else {
                $this->CourseOrder->editCourseOrder(
                    ['pay_status' => 3, 'updated_at' => time()],
                    ['id' => $CourseOrder['id']]
                );

                log_message('error', '微信异步用户支付失败');
                return 'user not pay success';
            }
        });

        return $response;
    }


    /**
     * 支付打赏 微信异步通知
     */
    public function payRewardNotify()
    {
        log_message('debug', '打赏支付-微信支付-异步通知接口时间：' . date('Y-m-d H:i:s'));

        $response = $this->wechat->payment->handleNotify(function ($notify, $successful) {

            $RewardOrder = $this->RewardOrder->_getOne('*', ['out_trade_no' => $notify->out_trade_no]);
            if (!$RewardOrder) return 'order is not exist';

            //已处理
            if ($RewardOrder['pay_status'] == 2) return true;
            //返回订单金额
            if ((100 * $RewardOrder['money']) != $notify->total_fee) {
                log_message('error', '打赏支付-微信异步通知接口返回订单金额不对');
                return false;
            }

            //用户是否支付
            if ($successful) {  //支付成功
                $this->db->trans_begin();
                try {
                    //改订单状态及 平台收入金额
                    $platform_money = $RewardOrder['money'] * $RewardOrder['cut_rate'];
                    $to_money = $RewardOrder['money'] * (1 - $RewardOrder['cut_rate']);
                    $res_1 = $this->RewardOrder->editRewardOrder(
                        [
                            'pay_status' => 2,
                            'transaction_id' => $notify->transaction_id,
                            'platform_money' => $platform_money,
                            'to_money' => $to_money,
                            'updated_at' => time()
                        ],
                        ['id' => $RewardOrder['id']]
                    );
                    if (!$res_1) throw new \Exception('更改订单状态失败');

                    //记录打赏人微信支付流水
                    $from_user = $this->User->_getOne('*', ['id' => $RewardOrder['user_id']]);
                    $res_2 = $this->UserCashJournal->addUserCashJournal([
                        'user_id' => $from_user['id'],
                        'trade_type' => 4,  //打赏订单支付
                        'type' => 1,        //交易方式 1=>微信  2=>个人账户
                        'money' => $RewardOrder['money'],
                        'inorout' => 3,      //不进不出 个人账户流水方向
                        'out_id' => $RewardOrder['id'],
                        'original_balance' => $from_user['cash_balance'],
                        'current_balance' => $from_user['cash_balance'],
                        'created_at' => time()
                    ]);
                    if (!$res_2) throw new \Exception('记录打赏人微信支付流水失败');

                    //给被打赏人加钱  评论时才处理
                    if ($RewardOrder['type'] == 1 && $RewardOrder['to_user_id']) {
                        $to_user_id = $RewardOrder['to_user_id'];
                        $to_user = $this->User->_getOne('*', ['id' => $to_user_id]);
                        if (!$to_user) throw new \Exception('被打赏人记录不存在');

                        $current_balance = $to_user['cash_balance'] + $to_money;
                        $res_3 = $this->User->_update(
                            [
                                'cash_balance' => $current_balance,
                                'updated_at' => time()
                            ], ['id' => $to_user_id]);
                        if (!$res_3) throw new \Exception('更新被打赏人金额失败');
                        //记录被打赏人流水
                        $res_4 = $this->UserCashJournal->addUserCashJournal([
                            'user_id' => $to_user_id,
                            'trade_type' => 3, //打赏收入
                            'type' => 2,  //交易方式 1=>微信  2=>个人账户
                            'money' => $to_money,
                            'inorout' => 1, //进
                            'out_id' => $RewardOrder['id'],
                            'original_balance' => $to_user['cash_balance'],
                            'current_balance' => $current_balance,
                            'created_at' => time()
                        ]);
                        if (!$res_4) throw new \Exception('记录被打赏人流水失败');
                        //记录评论被打赏红点
                        $key1 = $this->config->item('redis_keys')['walletDotPrex'] . $to_user_id;
                        $this->redislib->set($key1, 1);
                        //汇总该评论被打赏总金额 有序集合
                        $key = $this->config->item('redis_keys')['rankCommentMoney'] . $RewardOrder['course_items_id'];
                        $this->redislib->zIncrBy($key, $RewardOrder['money'], $RewardOrder['comment_id']);
                        //入库
                        $money = $this->redislib->zScore($key, $RewardOrder['comment_id']);
                        $this->ContentComment->_update(
                            ['money' => $money, 'updated_at' => time()], ['id' => $RewardOrder['comment_id']]);

                    } else if ($RewardOrder['type'] == 2) {
                        //汇总该音频被打赏总金额
                        $key = $this->config->item('redis_keys')['audioToReward'] . $RewardOrder['course_items_id'];
                        $this->redislib->incrbyfloat($key, $RewardOrder['money']);
                        //用户投稿被打赏
                        if ($RewardOrder['to_user_id']) {
                            $to_user_id = $RewardOrder['to_user_id'];
                            $to_user = $this->User->_getOne('*', ['id' => $to_user_id]);
                            if (!$to_user) throw new \Exception('用户投稿记录不存在');

                            $current_balance = $to_user['cash_balance'] + $to_money;
                            $res_3 = $this->User->_update(
                                [
                                    'cash_balance' => $current_balance,
                                    'updated_at' => time()
                                ], ['id' => $to_user_id]);
                            if (!$res_3) throw new \Exception('更新用户投稿被打赏金额失败');
                            //记录被打赏人流水
                            $res_4 = $this->UserCashJournal->addUserCashJournal([
                                'user_id' => $to_user_id,
                                'trade_type' => 8, //用户投稿被打赏收入
                                'type' => 2,  //交易方式 1=>微信  2=>个人账户
                                'money' => $to_money,
                                'inorout' => 1, //进
                                'out_id' => $RewardOrder['id'],
                                'original_balance' => $to_user['cash_balance'],
                                'current_balance' => $current_balance,
                                'created_at' => time()
                            ]);
                            if (!$res_4) throw new \Exception('用户投稿被打赏人流水失败');
                            $key1 = $this->config->item('redis_keys')['walletDotPrex'] . $to_user_id;
                            $this->redislib->set($key1, 1);
                        }
                    }

                    $this->db->trans_commit();
                    return true;

                } catch (\Exception $exception) {
                    $this->db->trans_rollback();
                    log_message('error', '创建打赏预充值订单接口异常' . $exception->getMessage());
                }
            } else {
                $this->CourseOrder->editRewardOrder(
                    ['pay_status' => 3, 'updated_at' => time()],
                    ['id' => $RewardOrder['id']]
                );

                log_message('error', '打赏订单-微信异步用户支付失败');
                return 'user not pay success';
            }
        });

        return $response;
    }
}