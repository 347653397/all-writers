<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm
 * Desc: 用户相关
 * User: guochao
 * Date: 2018/7/21
 * Time: 下午10:00
 */
class User extends Api_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('Smslib');

        $this->load->model('Feedback_Model', 'Feedback');
        $this->load->model('Withdraw_Model', 'Withdraw');
        $this->load->model('RewardOrder_Model', 'RewardOrder');
        $this->load->model('UserCashJournal_Model', 'UserCashJournal');

        $this->load->model('CourseOrderDetails_Model', 'CourseOrderDetails');

        $this->load->library('Redislib');
    }

    /**
     * 发送短信,将验证码写入redis,3分钟有效
     */
    public function sendSms()
    {
        $mobile = $this->input->post('mobile');
        if (!isMobile($mobile))
            $this->responseToJson(502, '手机格式错误');
        $key = "LAST_SMSCODE_{$mobile}";

        if ($this->cache->redis->get($key))
            $this->responseToJson(502, '你已发送验证码，请勿频繁操作，该验证码3分钟内有效!');

        $code = rand(100000, 999999);
        try {
            $this->smslib->sendSms($mobile, [$code, 3]);
            $this->cache->redis->save($key, $code, 180);
            $this->responseToJson(200, '发送成功');
        } catch (\Exception $exception) {
            $this->responseToJson(502, $exception->getMessage());
        }
    }

    /**
     * 用户绑定手机号
     */
    public function bindingMobile()
    {
        //获取用户uid
        $user_id = $this->user_info['id'];

        $mobile = $this->input->post('mobile');
        $code = $this->input->post('code');
        $name = $this->input->post('name');

        if (!$mobile || !$code || !$name)
            $this->responseToJson(502, '参数缺少');
        if (!isMobile($mobile))
            $this->responseToJson(502, '手机格式错误');
        if (strlen($code) != 6)
            $this->responseToJson(502, '验证码错误');
        //验证码校验
        $key = "LAST_SMSCODE_{$mobile}";
        $redis_code = $this->cache->redis->get($key);
        if (!$redis_code) $this->responseToJson(502, '验证码已过期');
        if ($redis_code != $code) $this->responseToJson(502, '验证码错误');
        //用户绑定手机号判定
        $User_Model = new User_Model();
        $user_data = $this->user_info;
        if (!$user_data) $this->responseToJson(502, '该用户还没注册');
        if (isset($user_data['mobile']) && $user_data['mobile'])
            $this->responseToJson(502, '该用户已经绑定手机号');
        //绑定手机号
        if ($User_Model->editUser(
            ['mobile' => $mobile, 'name' => htmlspecialchars(trim($name)), 'updated_at' => time()],
            ['id' => $user_id])
        ) {
            $this->responseToJson(200, '操作成功');
        } else {
            $this->responseToJson(502, '操作失败');
        }
    }

    /**
     * 用户个人中心  加5个红点提示
     */
    public function userCenter()
    {
        try {
            $data = $this->user_info;
            $data['nickname'] = emoji_to_string($data['nickname']);
            //我的钱包 被打赏
            $key1 = $this->config->item('redis_keys')['walletDotPrex'] . $data['id'];
            $data['wallet_dot'] = $this->redislib->get($key1) ? 1 : 0;
            //订单
            $key2 = $this->config->item('redis_keys')['orderDotPrex'] . $data['id'];
            $data['order_dot'] = $this->redislib->get($key2) ? 1 : 0;
            //评论回复
            $key3 = $this->config->item('redis_keys')['commentDotPrex'] . $data['id'];
            $data['comment_dot'] = $this->redislib->get($key3) ? 1 : 0;
            //投稿
            $key4 = $this->config->item('redis_keys')['contributeDotPrex'] . $data['id'];
            $data['contribute_dot'] = $this->redislib->get($key4) ? 1 : 0;
            //拍卖
            $key5 = $this->config->item('redis_keys')['auctionDotPrex'] . $data['id'];
            $data['auction_dot'] = $this->redislib->get($key5) ? 1 : 0;

            $this->responseToJson(200, '获取成功', $data);
        } catch (\Exception $exception) {
            $this->responseToJson(502, '获取失败');
        }
    }

    /**
     * 用户申请提现
     * @author
     */
    public function applyWithdraw()
    {
        //获取用户
        $user = $this->user_info;
        //提现金额
        $money = $this->input->post('money');
        if (!$money) {
            $this->responseToJson(502, '参数错误');
        } elseif (!is_numeric($money) || strstr($money, '.') || $money < 10) {
            $this->responseToJson(502, '申请提现金额不对');
        } elseif ($user['cash_balance'] < $money) {
            $this->responseToJson(502, '你账户余额不足无法申请该金额');
        } elseif ($user['status'] == 'frozen') {
            $this->responseToJson(502, '你账户已被冻结无法申请提现');
        }

        //有提现中的记录不给操作
        $Withdraw = $this->Withdraw->_getOne('id', ['user_id' => $user['id'], 'status' => 1]);
        if ($Withdraw) {
            $this->responseToJson(502, '你有一笔在提现中的款项,请等处理完成后再来操作');
        }

        //操作之后的余额
        $current_balance = $user['cash_balance'] - $money;

        $this->db->trans_begin();
        try {
            //插入提现记录
            $res_1 = $this->Withdraw->_add([
                'user_id' => $user['id'],
                'money' => $money,
                'created_at' => time()
            ], 'id');
            //更新用户表
            $res_2 = $this->User->editUser([
                'updated_at' => time(),
                'cash_balance' => $current_balance
            ],
                ['id' => $user['id']]
            );
            //插入流水记录 提现申请
            $res_3 = $this->UserCashJournal->addUserCashJournal([
                'user_id' => $user['id'],
                'trade_type' => 5, //提现申请
                'type' => 2,  //交易方式 1=>微信  2=>个人账户
                'money' => $user['cash_balance'],
                'inorout' => 2, // 1=>进  2=出  3=>不进不出
                'out_id' => $res_1,
                'original_balance' => $user['cash_balance'],
                'current_balance' => $current_balance,
                'created_at' => time()
            ]);

            if ($res_1 && $res_2 && $res_3) {
                $this->db->trans_commit();
                $this->responseToJson(200, '申请提现提交成功,等待后续处理结果');
            } else {
                $this->db->trans_rollback();
                throw new \Exception('申请提交接口异常');
            }
        } catch (\Exception $exception) {
            $this->db->trans_rollback();
            log_message('error', '申请提交接口异常' . $exception->getMessage());
            $this->responseToJson(502, $exception->getMessage());
        }
    }

    /**
     * 提交建议反馈
     */
    public function submitFeedback()
    {
        $content = $this->input->post('content');
        if (!$content) {
            $this->responseToJson(502, '提交内容为空');
        }

        if ($this->Feedback->addFeedback([
            'user_id' => $this->user_info['id'],
            'content' => htmlspecialchars(trim($content)),
            'created_at' => time()
        ], 'id')) {
            $this->responseToJson(200, '提交成功');
        } else {
            $this->responseToJson(502, '提交失败');
        }
    }


    /**
     * 获取我被打赏的收入明细
     */
    public function toRewardDetails()
    {
        $where = ['to_user_id' => $this->user_info['id'], 't_reward_order.type' => 1, 'pay_status' => 2];
        $total = $this->RewardOrder->_count($where);

        $list = $this->RewardOrder->_get('t_user.nickname,t_reward_order.money,t_reward_order.updated_at as time',
            $where,
            [],
            ['t_reward_order.updated_at' => 'desc'],
            ['page' => $_POST['page'] ?? 1, 'count' => $_POST['pageSize'] ?? 10],
            ['t_user' => 't_user.id = t_reward_order.user_id']
        );

        if ($list) {
            foreach ($list as $key => $val) {
                $list[$key]['nickname'] = emoji_to_string($val['nickname']);
            }
        }

        //红点处理
        $key = $this->config->item('redis_keys')['walletDotPrex'] . $this->user_info['id'];
        $this->redislib->del($key);

        $this->responseToJson(200, '获取成功', ['total' => $total, 'list' => $list]);
    }

    /**
     * 获取我的钱包 3=>评论被打赏收入 7=>用户投稿被购买收入  8=>用户投稿被打赏收入   0.1/0.2/0.3
     */
    public function myWallet()
    {
        $where = ['user_id' => $this->user_info['id'],'trade_type' => [3, 7, 8]];
        $total = (int)$this->UserCashJournal->_count($where);

        $list = $this->UserCashJournal->_get('trade_type,id,money,created_at,out_id', $where, [],
            ['created_at' => 'desc'],
            ['page' => $_POST['page'] ?? 1, 'count' => $_POST['pageSize'] ?? 10]
        );

        if ($list) {
            foreach ($list as $key => &$val) {
                switch ($val['trade_type']) {
                    case 3://评论被打赏收入 评论打赏
                        $data = $this->RewardOrder->_getOne('t_content_comment.content as title,t_user.nickname',
                            ['t_reward_order.id' => $val['out_id']],
                            [],
                            ['t_content_comment' => 't_content_comment.id = t_reward_order.comment_id',
                                't_user' => 't_user.id = t_reward_order.user_id']
                        );
                        break;

                    case 7://用户投稿被购买  文章购买
                        $data = $this->CourseOrderDetails->_getOne('t_course_items.title,t_user.nickname',
                            ['t_course_order_details.course_order_id' => $val['out_id']],
                            [],
                            ['t_course_items' => 't_course_items.id = t_course_order_details.course_items_id',
                                't_user' => 't_user.id = t_course_order_details.user_id']
                        );
                        break;

                    case 8://用户投稿被打赏收入 文章打赏
                        $data = $this->RewardOrder->_getOne('t_course_items.title,t_user.nickname',
                            ['t_reward_order.id' => $val['out_id']],
                            [],
                            ['t_course_items' => 't_course_items.id = t_reward_order.course_items_id',
                                't_user' => 't_user.id = t_reward_order.user_id']
                        );
                        break;
                }

                $val['nickname'] = emoji_to_string($data['nickname']);
                $val['title'] = $data['title'];
            }
        }

        //红点处理
        $key = $this->config->item('redis_keys')['walletDotPrex'] . $this->user_info['id'];
        $this->redislib->del($key);

        $this->responseToJson(200, '获取成功', ['cash_balance' => $this->user_info['cash_balance'],
            'total' => $total, 'list' => $list]);

    }
}
