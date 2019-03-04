<?php
/**
 * Created by PhpStorm
 * Desc: Data.php
 * User: guochao
 * Date: 2018/8/8
 * Time: 下午4:28
 */

class Data extends Api_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('RewardOrder_Model', 'RewardOrder');
        $this->load->model('ContentComment_model', 'ContentComment');

        $this->load->library('Redislib');
    }

    /**
     * 更新评论打赏金额到redis
     */
    public function updateDashangToRedis()
    {
        $data = $this->RewardOrder->_get(
            'comment_id,t_content_comment.course_items_id,sum(t_reward_order.money) as total',
            ['pay_status' => 2, 'type' => 1],
            [],
            ['total' => 'desc'],
            [],
            ['t_content_comment' => 't_content_comment.id = t_reward_order.comment_id'],
            '',
            'comment_id'
        );

        foreach ($data as $val) {
            $key = $this->config->item('redis_keys')['rankCommentMoney'] . $val['course_items_id'];
            $this->redislib->zAdd($key, $val['total'], $val['comment_id']);

            $this->ContentComment->_update(['money' => $val['total'], 'updated_at' => time()],
                ['id' => $val['comment_id']]);
        }

        dump($data);
        exit;
    }
}