<?php

class Order extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('CourseOrder_Model', 'CourseOrder');
        $this->load->model('RewardOrder_Model', 'RewardOrder');
    }

    /**
     * 课程订单
     */
    public function courseList()
    {
        //分页
        $page = isset($_GET['page']) ? $_GET['page'] : $this->page;
        $pageSize = isset($_GET['pageSize']) ? $_GET['pageSize'] : $this->pageSize;
        //查询条件
        $data = $this->input->get();
        $where = ['pay_status' => [1, 2, 3]];
        if ($data) {
            unset($data['page'], $data['pageSize']);
            foreach ($data as $key => $val) {
                if (strlen($val) > 0) {
                    switch ($key){
                        case 'start_time':
                            $where['t_course_order.created_at >='] = strtotime($val . ' 00:00:00');
                            break;
                        case 'end_time':
                            $where['t_course_order.created_at <='] = strtotime($val . '  23:59:59');
                            break;
                        default:
                            $where[$key] = $val;
                    }
                }
            }
        }
        //关联
        $join = [
            't_user' => 't_user.id = t_course_order.user_id',
            't_course' => 't_course.id = t_course_order.course_id'
        ];
        //查询
        $data['data'] = $this->CourseOrder->_get(
            't_course_order.*,t_user.nickname,t_course.course_title',
            $where,
            [],
            ['t_course_order.created_at' => 'desc'],
            ['page' => $page, 'count' => $pageSize],
            $join);

        $data['count'] = $this->CourseOrder->_count($where, '', '*', $join);
        //生成分页
        $data['page'] = $this->pageInit($data['count'], $pageSize);

        $this->display('Order/course_index', $data);
    }

    /**
     * 课程订单详情
     */
    public function courseDetail()
    {

    }

}