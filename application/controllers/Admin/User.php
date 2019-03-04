<?php

class User extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('User_Model', 'User');
        $this->load->model('UserCashJournal_Model', 'UserCashJournal');
    }

    /**
     * 列表
     */
    public function index()
    {
        //分页
        $page = isset($_GET['page']) ? $_GET['page'] : $this->page;
        $pageSize = isset($_GET['pageSize']) ? $_GET['pageSize'] : $this->pageSize;
        //查询条件
        $data = $this->input->get();
        $where = [];
        if ($data) {
            unset($data['page'], $data['pageSize']);
            foreach ($data as $key => $val) {
                if (strlen($val) > 0) {
                    $where[$key] = $val;
                }
            }
        }
        //查询
        $data['user'] = $this->User->_get('*', $where, [], ['created_at' => 'asc'],
            ['page' => $page, 'count' => $pageSize]);
        $data['count'] = $this->User->_count($where);
        //生成分页
        $data['page'] = $this->pageInit($data['count'], $pageSize);

        $this->display('User/index', $data);
    }

    /**
     * 账户明细
     */
    public function cashJournal()
    {
        //分页
        $page = isset($_GET['page']) ? $_GET['page'] : $this->page;
        $pageSize = isset($_GET['pageSize']) ? $_GET['pageSize'] : $this->pageSize;
        //查询条件
        $data = $this->input->get();
        $where = [];
        if ($data) {
            unset($data['page'], $data['pageSize']);
            foreach ($data as $key => $val) {
                if (strlen($val) > 0) {
                    switch ($key) {
                        case 'start_time':
                            $where['t_user_cash_journal.created_at >='] = strtotime($val . ' 00:00:00');
                            break;
                        case 'end_time':
                            $where['t_user_cash_journal.created_at <='] = strtotime($val . '  23:59:59');
                            break;
                        case 'type':
                            $where['t_user_cash_journal.type'] = $val;
                            break;
                        default:
                            $where[$key] = $val;
                    }
                }
            }
        }
        //查询
        $data['list'] = $this->UserCashJournal->_get(
            't_user_cash_journal.*,t_user.mobile,t_user.nickname',
            $where,
            [],
            ['t_user_cash_journal.id' => 'desc'],
            ['page' => $page, 'count' => $pageSize],
            ['t_user' => 't_user.id = t_user_cash_journal.user_id']
        );
        $data['count'] = $this->UserCashJournal->_count($where);
        //生成分页
        $data['page'] = $this->pageInit($data['count'], $pageSize);

        $this->display('User/cash_journal', $data);
    }

    /**
     * 加入拉黑或移除黑名单
     */
    public function block()
    {
        $user_id = $this->input->get('id') ?? 0;
        $status = $this->input->get('status');
        if ($user_id < 1 || !$status) {
            $error['msg'] = '参数不正确，操作失败！';
            return $this->error($error);
        }
        $res = $this->User->editUser(
            ['status' => $status],
            ['id' => $user_id]
        );
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