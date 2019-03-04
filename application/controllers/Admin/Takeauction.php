<?php

class Takeauction extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('TakeAuction_model', 'Takeauction');
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
                            $where['t_take_auction.created_at >='] = strtotime($val . ' 00:00:00');
                            break;
                        case 'end_time':
                            $where['t_take_auction.created_at <='] = strtotime($val . '  23:59:59');
                            break;
                        case 'user_id':
                            $where['t_take_auction.user_id'] = $val;
                            break;
                        default:
                            $where[$key] = $val;
                    }
                }
            }
        }
        //关联
        $join = [
            't_user' => 't_user.id = t_take_auction.user_id',
            't_course_items' => 't_course_items.id = t_take_auction.course_items_id'
        ];
        //查询
        $data['data'] = $this->Takeauction->_get(
            't_take_auction.*,t_user.nickname,t_user.mobile,t_course_items.title,t_course_items.author',
            $where,
            [],
            ['t_take_auction.created_at' => 'desc'],
            ['page' => $page, 'count' => $pageSize],
            $join
        );
        $data['count'] = $this->Takeauction->_count($where, '', '*', $join);
        //生成分页
        $data['page'] = $this->pageInit($data['count'], $pageSize);

        $this->display('Takeauction/index', $data);
    }


    public function deal()
    {
        $id = $this->input->post('id') ?? 0;
        $remark = $this->input->post('remark');

        if ($id < 1 || !$remark) {
            $this->responseToJson(0, '参数不正确');
        }
        $res = $this->Takeauction->_update(
            ['remark' => $remark, 'updated_at' => time()],
            ['id' => $id]
        );

        if ($res == false) {
            $this->responseToJson(0, '操作失败');
        } else {
            $this->responseToJson(1, '操作成功');
        }
    }

}