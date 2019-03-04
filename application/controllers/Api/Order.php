<?php
/**
 * Created by PhpStorm
 * Desc: Order.php
 * User: guochao
 * Date: 2018/7/18
 * Time: 下午10:46
 */

class Order extends Api_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->library('form_validation');

        $this->load->model('CourseOrder_Model', 'CourseOrder');
        $this->load->model('CourseOrderDetails_Model', 'CourseOrderDetails');

        $this->load->model('Course_model', 'Course');
        $this->load->model('CourseItem_model', 'CourseItem');

        $this->load->model('RewardOrder_Model', 'RewardOrder');
        $this->load->model('ContentComment_model', 'ContentComment');
        $this->load->model('Setting_model', 'Setting');

        $this->load->config("wechat");
        $this->wechat = new \EasyWeChat\Foundation\Application(config_item("wechat"));
    }

    /**
     * 音频购买 兼容多个音频
     */
    public function buyAudio()
    {
        $courseItemsIds = $this->input->post('courseItemsIds');//音频
        $buy_type = $this->input->post('buy_type');//购买方式 1=>单集 2=>连载

        if (!$courseItemsIds || !$buy_type) {
            $this->responseToJson(502, '参数错误');
        } elseif (!is_array($courseItemsIds) || !count($courseItemsIds)) {
            $this->responseToJson(502, 'courseItemsIds要求是一个数组且必须有一个值');
        } elseif (!in_array($buy_type, [1, 2])) {
            $this->responseToJson(502, '购买方式参数错误');
        }

        //单集方式购买音频只能有一个
        if (($buy_type == 1) && (count($courseItemsIds) != 1)) {
            $this->responseToJson(502, '单集方式购买音频只能有一个');
        }

        $money = 0.00;
        $course_id = null;
        $openid = $this->user_info['openid'];
        $user_id = $this->user_info['id'];
        $out_trade_no = uuid();
        $OrderDetails = [];
        $time = time();

        foreach ($courseItemsIds as $val) {
            $courseItem = $this->CourseItem->_getOne('*', ['id' => $val]);
            if (empty($courseItem)) {
                $this->responseToJson(502, '音频记录不存在');
            } else if ($courseItem['status'] != 1) {
                $this->responseToJson(502, '该音频已经被冻结无法购买');
            } else if (($courseItem['type'] != 1) || ($courseItem['price'] <= 0.00)) {  //检测是否都是非免费的音频
                $this->responseToJson(502, '试听课程不需要购买哦');
            }

            if (($courseItem['is_tg'] == 2) && ($courseItem['audit_status'] != 2)) {
                $this->responseToJson(502, '该用户投稿未通过审核,无法被购买');
            }

            //已购买的不可再买
            $oneCourse = $this->CourseOrderDetails->_getOne('id,pay_status',
                ['course_items_id' => $courseItem['id'], 'user_id' => $user_id, 'pay_status' => 2]);
            if ($oneCourse) {
                $this->responseToJson(502, '其中有音频已经购买过,请核对');
            }

            $money += $courseItem['price'];

            $course_id = $courseItem['course_id'];
            $OrderDetails[] = [
                'user_id' => $user_id,
                'course_id' => $course_id,
                'course_items_id' => $courseItem['id'],
                'created_at' => $time
            ];
        }

        if ($money <= '0.00') {
            $this->responseToJson(502, '订单金额不对');
        } else {
            $original_fee = $pay_fee = $money;
        }

        $attributes = [
            'trade_type' => 'JSAPI',
            'body' => '人人编剧课程购买',
            'detail' => '用户id:' . $user_id . '|人人编剧课程购买',
            'out_trade_no' => $out_trade_no,
            'total_fee' => intval($money * 100), // 单位：分
            'notify_url' => base_url() . '/Api/Notice/payCourseNotify', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            'openid' => $openid, // trade_type=JSAPI
            'limit_pay' => 'no_credit'  //no_credit--限制用户不能使用信用卡支付
        ];

        $this->db->trans_begin();
        try {
            //单集 用户投稿特殊处理
            if ((count($courseItemsIds) == 1) && isset($courseItem['is_tg']) &&
                ($courseItem['is_tg'] == 2) && $courseItem) {
                //用户投稿平台抽成比例
                $cut_rate = $this->Setting->_getOne('value', ['key' => 'items_buy_cut']);
                $cut_rate = $cut_rate['value'] ?? 1.0;

                $addArr = [
                    'user_id' => $user_id,
                    'order_num' => time(),
                    'course_id' => $course_id,
                    'buy_type' => $buy_type,
                    'original_fee' => $original_fee,
                    'pay_fee' => $pay_fee,
                    'out_trade_no' => $out_trade_no,
                    'created_at' => $time,
                    'items_user_id' => $courseItem['user_id'],
                    'cut_rate' => $cut_rate
                ];
            } else {
                $addArr = [
                    'user_id' => $user_id,
                    'order_num' => time(),
                    'course_id' => $course_id,
                    'buy_type' => $buy_type,
                    'original_fee' => $original_fee,
                    'pay_fee' => $pay_fee,
                    'out_trade_no' => $out_trade_no,
                    'created_at' => $time
                ];
            }

            //创建主订单
            $order_id = $this->CourseOrder->_add($addArr, 'id');

            if (!$order_id) throw new \Exception('订单创建失败');
            foreach ($OrderDetails as &$val) {
                $val['course_order_id'] = $order_id;
            }
            //批量创建订单
            $res = $this->CourseOrderDetails->_add($OrderDetails);
            if (!$res) throw new \Exception('订单创建失败');

            $order = new \EasyWeChat\Payment\Order($attributes);
            $payment = $this->wechat->payment;
            $result = $payment->prepare($order);
            if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS') {
                $this->db->trans_commit();
                $prepayId = $result->prepay_id;
                $data = $payment->configForPayment($prepayId, false);
                $this->responseToJson(200, '创建成功', [
                    'weixin_pay' => $data,
                    'out_trade_no' => $out_trade_no
                ]);
            } else {
                throw new Exception($result->return_msg);
            }

        } catch (\Exception $exception) {
            $this->db->trans_rollback();
            log_message('error', '创建课程购买预充值订单接口异常' . $exception->getMessage());
            $this->responseToJson(502, $exception->getMessage());
        }
    }


    /**
     * 我的订单列表 连载详情表有多条音频记录 单集只一条
     */
    public function myOrderlist()
    {
        try {
            //支付成功 支付失败
            $CourseOrder = $this->CourseOrder->_get(
                'order_num,pay_status,course_id,id,buy_type',
                ['user_id' => $this->user_info['id'], 'pay_status' => [2, 3]],
                [],
                ['created_at' => "desc"]
            );
            if (!$CourseOrder) $this->responseToJson(200, '获取成功');


            $data = [];
            foreach ($CourseOrder as $val) {
                $course = $this->Course->_getOne('up_type,course_title,course_pic,author', ['id' => $val['course_id']]);
                $CourseOrderDetails = $this->CourseOrderDetails->_getOne('course_items_id',
                    ['course_order_id' => $val['id']]);
                if ($course) {
                    if ($course['up_type'] == '1') {
                        $info = $this->CourseItem->_getOne('audio_pic as course_pic,title as course_title,author', ['course_id' => $val['course_id']]);
                        $course['course_pic'] = $info['course_pic'];
                        $course['course_title'] = $info['course_title'];
                        $course['author'] = $info['author'];
                    }
                    $data[] = [
                        'order_num' => $val['order_num'],
                        'up_type' => $val['buy_type'],  //课程类型 1=>单集 2=>连载
                        'pay_status' => $val['pay_status'],
                        'buy_items' => $this->CourseOrderDetails->_count([
                            'course_order_id' => $val['id'],
                            'pay_status' => 2
                        ]),

                        'course_title' => $course['course_title'],
                        'course_pic' => IMG_URL . $course['course_pic'],
                        'author' => $course['author'],
                        'course_id' => $val['course_id'],
                        'item_id' => $val['buy_type'] == 1 ? $CourseOrderDetails['course_items_id'] : null
                    ];
                }
            }

            $this->responseToJson(200, '获取成功', $data);
        } catch (\Exception $exception) {
            $this->responseToJson(502, '获取失败');
        }
    }

    /**
     * 删除支付失败的订单
     */
    public function deleteFailOrder()
    {
        $order_id = $this->input->post('order_id');
        if (!$order_id) {
            $this->responseToJson(502, '参数缺少');
        }

        $CourseOrder = $this->CourseOrder->_getOne('id,pay_status', ['id' => $order_id]);
        if (!$CourseOrder) {
            $this->responseToJson(502, '该订单记录不存在');
        } else if ($CourseOrder['pay_status'] != 3) {
            $this->responseToJson(502, '该订单不满足删除的条件');
        }

        $res1 = $this->CourseOrder->_update(
            ['pay_status' => 4, 'updated_at' => time()],
            ['id' => $order_id]
        );

        $res2 = $this->CourseOrderDetails->_update(
            ['pay_status' => 4, 'updated_at' => time()],
            ['course_order_id' => $order_id]
        );

        if ($res1 && $res2) {
            $this->responseToJson(200, '操作成功');
        } else {
            $this->responseToJson(502, '操作失败');
        }
    }


    /**
     * 对评论或音频打赏 a对b打赏 改动
     */
    public function reward()
    {
        $course_items_id = $this->input->post('course_items_id');
        $comment_id = $this->input->post('comment_id');
        $money = $this->input->post('money');
        $type = $this->input->post('type');//打赏类型 1=>对评论打赏  2=>对音频打赏

        if (!$money || !$type) {
            $this->responseToJson(502, 'money或type参数缺少');
        } elseif (!in_array($type, [1, 2])) {
            $this->responseToJson(502, 'type不对');
        }

        if (($type == 1) && !$comment_id) {  //对评论打赏
            $this->responseToJson(502, '对评论打赏参数不对');
        }

        if (($type == 2) && !$course_items_id && ($comment_id != 0)) {  //对音频打赏
            $this->responseToJson(502, '对音频打赏参数不对');
        }

        $cut_rate = 1.0;
        $to_user_id = 0;
        //打赏类型 1=>对评论打赏  2=>对音频打赏
        if ($type == 1) {
            $ContentComment = $this->ContentComment->_getOne('id,user_id,course_items_id', ['id' => $comment_id]);
            if (!$ContentComment || !$ContentComment['user_id'])
                $this->responseToJson(502, '该评论记录不存在');
            //评论音频id
            $course_items_id = $ContentComment['course_items_id'];
            //对评论打赏 平台抽成
            $cut_rate = $this->Setting->_getOne('value', ['key' => 'cut']);
            $cut_rate = $cut_rate['value'] ?? 1.0;
            //被打赏用户
            $to_user_id = $ContentComment['user_id'];
        } elseif ($type == 2) { //对音频打赏
            $CourseItem = $this->CourseItem->_getOne('id,is_original,is_tg,user_id', ['id' => $course_items_id]);
            if (!$CourseItem) {
                $this->responseToJson(502, '该音频记录不存在');
            } elseif ($CourseItem['is_original'] != 2) {  //原创可打赏，不是原创不可打赏
                $this->responseToJson(502, '不是原创音频不可打赏');
            }
            //用户投稿被打赏
            if($CourseItem['is_tg'] == 2 && $CourseItem['user_id']){
                $to_user_id = $CourseItem['user_id'];
                $cut_rate = $this->Setting->_getOne('value', ['key' => 'items_reward_cut']);
                $cut_rate = $cut_rate['value'] ?? 1.0;
            }
        }

        //打赏金额判断
        if ($money < 0) {
            $this->responseToJson(502, '打赏金额不对');
        } elseif (!preg_match('/^[0-9]+([.]{1}[0-9]{1,2})?$/', $money)) {//正整数或一位小数或者俩位小数的正则
            $this->responseToJson(502, '你输入金额格式不对');
        }

        $out_trade_no = uuid();
        $user_id = $this->user_info['id'];
        $attributes = [
            'trade_type' => 'JSAPI',
            'body' => '人人编剧打赏',
            'detail' => '用户id:' . $user_id . '|人人编剧打赏',
            'out_trade_no' => $out_trade_no,
            'total_fee' => intval($money * 100), // 单位：分
            'notify_url' => base_url() . '/Api/Notice/payRewardNotify', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            'openid' => $this->user_info['openid'], // trade_type=JSAPI
            'limit_pay' => 'no_credit'  //no_credit--限制用户不能使用信用卡支付
        ];

        $this->db->trans_begin();
        try {
            //创建订单
            $order_id = $this->RewardOrder->_add([
                'user_id' => $user_id,
                'type' => $type,
                'cut_rate' => $cut_rate, //平台抽成比例
                'course_items_id' => $course_items_id,
                'comment_id' => $comment_id,
                'to_user_id' => $to_user_id,
                'money' => $money,
                'out_trade_no' => $out_trade_no,
                'created_at' => time()
            ], 'id');

            if (!$order_id) throw new \Exception('订单创建失败');

            $order = new \EasyWeChat\Payment\Order($attributes);
            $payment = $this->wechat->payment;
            $result = $payment->prepare($order);
            if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS') {
                $this->db->trans_commit();
                $prepayId = $result->prepay_id;
                $data = $payment->configForPayment($prepayId, false);
                $this->responseToJson(200, '创建成功', [
                    'weixin_pay' => $data,
                    'out_trade_no' => $out_trade_no
                ]);
            } else {
                throw new Exception($result->return_msg);
            }
        } catch (\Exception $exception) {
            $this->db->trans_rollback();
            log_message('error', '创建打赏预充值订单接口异常' . $exception->getMessage());
            $this->responseToJson(502, $exception->getMessage());
        }
    }
}