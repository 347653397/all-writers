<?php
/**
 * Created by PhpStorm
 * Desc: comment.php
 * User: guochao
 * Date: 2018/7/23
 * Time: 下午6:38
 */

class Comment extends Api_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('CourseItem_model', 'CourseItem');
        $this->load->model('ContentComment_model', 'ContentComment');
        $this->load->model('CourseOrderDetails_Model', 'CourseOrderDetails');
        $this->load->model('CommentReply_model', 'CommentReply');
        $this->load->model('RewardOrder_Model', 'RewardOrder');

        $this->load->library('Redislib');
    }

    /**
     * 发表评论-改动 1.试听／免费的可以直接评论  2.购买后评论
     */
    public function comment()
    {
        $course_items_id = $this->input->post('course_items_id');
        $content = $this->input->post('content');

        if (!$course_items_id || !trim($content)) {
            $this->responseToJson(502, '参数缺少');
        }

        $user_id = $this->user_info['id'];

        $CourseItem = $this->CourseItem->_getOne('*', ['id' => $course_items_id]);
        if (!$CourseItem) {
            $this->responseToJson(502, '该音频记录不存在');
        } elseif ($CourseItem['type'] == 1) {
            $CourseOrderDetails = $this->CourseOrderDetails->_getOne('id', [
                'course_items_id' => $course_items_id,
                'pay_status' => 2,
                'user_id' => $user_id,
            ]);

            if (!$CourseOrderDetails) {
                $this->responseToJson(502, '该音频需要购买后才能评论');
            }
        }

        $this->db->trans_begin();
        try {
            //插入评论
            $res1 = $this->ContentComment->_add([
                'user_id' => $user_id,
                'course_items_id' => $course_items_id,
                'content' => htmlspecialchars(trim($content)),
                'created_at' => time()
            ], 'id');
            if (!$res1) throw new \Exception('插入评论失败');

            $this->db->trans_commit();
            $this->responseToJson(200, '操作成功');

        } catch (\Exception $exception) {
            $this->db->trans_rollback();
            $this->responseToJson(502, $exception->getMessage());
        }
    }


    /**
     * 评论点赞及取消  redis set 集合实现
     */
    public function likeComment()
    {
        $comment_id = $this->input->post('comment_id');
        $type = $this->input->post('type'); //type 1=>点赞 2=>取消点赞

        if (!$comment_id || !$type) {
            $this->responseToJson(502, '参数缺少');
        } elseif (!in_array($type, [1, 2])) {
            $this->responseToJson(502, 'type参数错误');
        }

        $ContentComment = $this->ContentComment->_getOne('id', ['id' => $comment_id]);
        if (!$ContentComment) $this->responseToJson(502, '该评论记录不存在');

        $user_id = $this->user_info['id'];
        $key = $this->config->item('redis_keys')['commentPraise'] . $comment_id;

        if ($type == 1) {
            if ($this->redislib->sIsMember($key, $user_id)) {
                $this->responseToJson(502, '你对该评论已经点赞过');
            } else {
                if ($this->redislib->sAdd($key, $user_id)) {
                    $this->responseToJson(200, '操作成功');
                } else {
                    $this->responseToJson(502, '操作失败');
                }
            }
        } else {  //取消点赞
            if (!$this->redislib->sIsMember($key, $user_id)) {
                $this->responseToJson(502, '你未对该评论点赞过');
            } else {
                if ($this->redislib->sRem($key, $user_id)) {
                    $this->responseToJson(200, '操作成功');
                } else {
                    $this->responseToJson(502, '操作失败');
                }
            }
        }
    }


    /**
     * 获取我的评论
     */
    public function myComment()
    {
        $page = $this->input->post('page');
        $pageSize = $this->input->post('pageSize');
        if (!$page || !$pageSize) {
            $this->responseToJson(502, '参数错误');
        }

        $where = ['t_content_comment.user_id' => $this->user_info['id']];

        $data['total'] = $this->ContentComment->_count($where);
        $data['list'] = $this->ContentComment->_get(
            't_content_comment.content,t_content_comment.created_at,title',
            $where,
            [],
            ['t_content_comment.created_at' => 'desc'],
            ['page' => $page, 'count' => $pageSize],
            ['t_course_items' => 't_course_items.id = t_content_comment.course_items_id']
        );

        //红点处理
        $key1 = $this->config->item('redis_keys')['commentDotPrex'] . $this->user_info['id'];
        $this->redislib->del($key1);

        $this->responseToJson(200, '获取成功', $data);
    }

    /**
     * 获取评论回复详情列表
     */
    public function getCommentReplyList()
    {
        $page = $this->input->post('page');
        $pageSize = $this->input->post('pageSize');
        $comment_id = $this->input->post('comment_id');

        if (!$page || !$pageSize || !$comment_id) {
            $this->responseToJson(502, '参数错误');
        }

        $one_comment = $this->ContentComment->_getOne(
            't_user.headimgurl,t_user.nickname,content,money,t_content_comment.created_at',
            ['t_content_comment.id' => $comment_id],
            [],
            ['t_user' => 't_user.id = t_content_comment.user_id']
        );
        if (!$one_comment) {
            $this->responseToJson(502, '该评论记录不存在');
        }
        $one_comment['nickname'] = emoji_to_string($one_comment['nickname']);

        $key = $this->config->item('redis_keys')['commentPraise'] . $comment_id;
        $one_comment['likeTotal'] = $this->redislib->sSize($key);

        $likeMember = $this->redislib->sMembers($key);

        $likeDetail = [];
        foreach ($likeMember as $key => $val) {
            if ($key >= 5) continue;
            $likeDetail[] = $this->User->_getOne('headimgurl', ['id' => $val]);
        }
        $one_comment['likeDetail'] = $likeDetail;

        $one_comment['rewardTotal'] = (int)$this->RewardOrder->_count(
            ['comment_id' => $comment_id], [], "distinct(user_id)");
        $one_comment['rewardDetail'] = $this->RewardOrder->_get(
            "t_user.headimgurl",
            ['comment_id' => $comment_id],
            [],
            ['t_reward_order.created_at' => 'desc'],
            ['page' => 1, 'count' => 5],
            ['t_user' => 't_user.id = t_reward_order.user_id'],
            'left',
            't_reward_order.user_id'
        );
        //作者是否点赞
        $is_star = 2;
        $key2 = $this->config->item('redis_keys')['commentPraise'] . $comment_id;
        if ($this->redislib->sIsMember($key2, $this->user_info['id'])) {
            $is_star = 1;
        }
        $one_comment['is_star'] = $is_star;

        $data['one_comment'] = $one_comment;

        $data['total'] = $this->CommentReply->_count(['comment_id' => $comment_id]);
        $data['comment_reply'] = $this->CommentReply->_get(
            't_user.headimgurl,t_user.nickname,content,t_comment_reply.created_at',
            ['comment_id' => $comment_id],
            [],
            ['t_comment_reply.created_at' => 'desc'],
            ['page' => $page, 'count' => $pageSize],
            ['t_user' => 't_user.id = t_comment_reply.user_id']
        );

        $this->responseToJson(200, '获取成功', $data);
    }


    /**
     * 评论点赞列表
     */
    public function commentLikeList()
    {
        $page = $this->input->post('page');
        $pageSize = $this->input->post('pageSize');
        $comment_id = $this->input->post('comment_id');

        if (!$page || !$pageSize || !$comment_id) {
            $this->responseToJson(502, '参数错误');
        }

        $key = $this->config->item('redis_keys')['commentPraise'] . $comment_id;
        $all = $this->redislib->sMembers($key);

        $data = array_slice($all, ($page - 1) * $pageSize, $pageSize);

        $list = [];
        foreach ($data as $val) {
            $list[] = $this->User->_getOne('headimgurl,nickname', ['id' => $val]);
        }

        $this->responseToJson(200, '获取成功', ['total' => count($all), 'list' => $list]);
    }

    /**
     * 评论打赏列表
     */
    public function commentRewardList()
    {
        $page = $this->input->post('page');
        $pageSize = $this->input->post('pageSize');
        $comment_id = $this->input->post('comment_id');

        if (!$page || !$pageSize || !$comment_id) {
            $this->responseToJson(502, '参数错误');
        }

        $total = $this->RewardOrder->_count(['comment_id' => $comment_id]);
        $list = $this->RewardOrder->_get(
            "t_user.headimgurl,t_user.nickname,t_user.nickname,money",
            ['comment_id' => $comment_id, 'pay_status' => 2],
            [],
            ['t_reward_order.created_at' => 'desc'],
            ['page' => $page, 'count' => $pageSize],
            ['t_user' => 't_user.id = t_reward_order.user_id']
        );

        $this->responseToJson(200, '获取成功', ['total' => $total, 'list' => $list]);
    }


    /**
     * 评论回复
     */
    public function commentReply()
    {
        $comment_id = $this->input->post('comment_id');
        $content = $this->input->post('content');

        if (!$comment_id || !trim($content)) {
            $this->responseToJson(502, '参数缺少');
        }

        $ContentComment = $this->ContentComment->_getOne('*', ['id' => $comment_id]);
        if (!$ContentComment || ($ContentComment['status'] != 1)) {
            $this->responseToJson(502, '该评论记录不存在');
        }

        //插入评论回复
        $res = $this->CommentReply->_add([
            'user_id' => $this->user_info['id'],
            'comment_id' => $comment_id,
            'content' => htmlspecialchars(trim($content)),
            'created_at' => time()
        ], 'id');
        if ($res) {
            $key = $this->config->item('redis_keys')['commentDotPrex'] . $ContentComment['user_id'];
            $this->redislib->set($key,1);

            $this->responseToJson(200, '回复成功');
        } else {
            $this->responseToJson(200, '回复失败');
        }
    }


    /**
     * 获取音频的评论
     */
    public function audioComment()
    {
        $pdata = $this->input->post();
        $page = isset($pdata['page']) ? $pdata['page'] : $this->page;//查询页数
        $pageSize = isset($pdata['pageSize']) ? $pdata['pageSize'] : $this->pageSize;//数量
        $course_items_id = isset($pdata['item_id']) ? $pdata['item_id'] : '';//音频ID
        if ($course_items_id == '') {
            $this->responseToJson(502, '参数错误');
        }
        $user_id = $this->user_info['id'];
        $filed = 'content_comment.id,user.nickname,user.headimgurl as head_pic,content_comment.user_id,content_comment.content,content_comment.created_at';
        $params = ['content_comment.course_items_id' => $course_items_id, 'content_comment.content !=' => '', 'content_comment.status' => '1', 'user.status !=' => '2'];
        $join = ['user' => 'user.id = content_comment.user_id'];
        $limit = ['page' => $page, 'count' => $pageSize];
        //获取音频评论
        $data['comment'] = $this->ContentComment->_get($filed, $params, false, ['content_comment.id' => 'desc'], $limit, $join);
        //土豪排行key
        $key = $this->config->item('redis_keys')['rankCommentMoney'] . $course_items_id;
        foreach ($data['comment'] as &$item) {
            //获取金额
            $money = $this->redislib->zScore($key, $item['id']);
            $item['money'] = isset($money) ? sprintf("%.2f", $money) : '0';//打赏金额
            $item['is_star'] = 2;
            $item['nickname'] = emoji_to_string($item['nickname']);
            $key2 = $this->config->item('redis_keys')['commentPraise'] . $item['id'];
            if ($this->redislib->sIsMember($key2, $user_id)) {
                $item['is_star'] = 1;
            }
            //评论回复数
            $item['comment_reply'] = (int)$this->CommentReply->_count(['comment_id' => $item['id']]);
            $item['like_num'] = $this->redislib->sSize($key2);
        }
        //土豪排行
        $data['money_rank'] = [];
        $money_rank = $this->redislib->zRevRangeByScore($key, '+inf', 10, ['withscores' => TRUE]);
        if ($money_rank) {
            foreach ($money_rank as $key => $value) {
                $info = $this->ContentComment->_getOne($filed, ['content_comment.id' => $key, 'content_comment.status' => '1'], ['content_comment.id' => 'desc'], $join);
                if ($info) {
                    $info['money'] = sprintf("%.2f", $value);
                    $info['is_star'] = 2;
                    $info['nickname'] = emoji_to_string($info['nickname']);
                    $key2 = $this->config->item('redis_keys')['commentPraise'] . $info['id'];
                    if ($this->redislib->sIsMember($key2, $user_id)) {
                        $info['is_star'] = 1;
                    }
                    //评论回复数
                    $info['comment_reply'] = (int)$this->CommentReply->_count(['comment_id' => $info['id']]);
                    //点赞数
                    $info['like_num'] = $this->redislib->sSize($key2);

                    $data['money_rank'][] = $info;
                }
            }
        }

        $data['total'] = $this->ContentComment->_count($params, false, '1', $join, 'left');

        $this->responseToJson(200, '获取成功', $data);
    }
}