<?php

class Comment extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('ContentComment_model', 'ContentComment');
    }

    /**
     * 列表
     */
    public function index()
    {
        //分页
        $page = isset($_GET['page']) ? $_GET['page'] : $this->page;
        $pageSize = isset($_GET['pageSize']) ? $_GET['pageSize'] : 10;
        //查询条件
        $data = $this->input->get();
        $where = [];
        if ($data) {
            unset($data['page'], $data['pageSize']);
            foreach ($data as $key => $val) {
                if (strlen($val) > 0) {
                    switch ($key) {
                        case 'start_time':
                            $where['t_content_comment.created_at >='] = strtotime($val . ' 00:00:00');
                            break;
                        case 'end_time':
                            $where['t_content_comment.created_at <='] = strtotime($val . '  23:59:59');
                            break;
                        default:
                            $where[$key] = $val;
                    }
                }
            }
        }
        //关联
        $join = [
            't_user' => 't_user.id = t_content_comment.user_id',
            't_course_items' => 't_course_items.id = t_content_comment.course_items_id'
        ];
        //查询
        $data['data'] = $this->ContentComment->_get(
            't_content_comment.*,t_user.nickname,t_course_items.title',
            $where,
            [],
            ['t_content_comment.created_at' => 'desc'],
            ['page' => $page, 'count' => $pageSize],
            $join
        );

        $data['count'] = $this->ContentComment->_count($where, '', '*', $join);
        //生成分页
        $data['page'] = $this->pageInit($data['count'], $pageSize, '*', $join);

        $this->display('Comment/index', $data);
    }

    /**
     * 更新评论状态
     */
    public function update()
    {
        $id = $this->input->get('id')??0;
        $status = $this->input->get('status')??1;
        if ($id < 1) {
            $error['msg'] = '参数不正确，删除失败！';
            return $this->error($error);
        }
        $res = $this->ContentComment->_update(['status'=>$status],['id'=>$id]);
        if ($res == false) {
            $error['msg'] = '操作失败';
            return $this->error($error);
        } else {
            $success['msg'] = '操作成功';
            $success['url'] = 'index';
            return $this->success($success);
        }
    }
}