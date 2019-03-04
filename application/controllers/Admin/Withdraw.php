<?php

class Withdraw extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Withdraw_Model', 'Withdraw');
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
                    switch ($key) {
                        case 'start_time':
                            $where['t_withdraw.created_at >='] = strtotime($val . ' 00:00:00');
                            break;
                        case 'end_time':
                            $where['t_withdraw.created_at <='] = strtotime($val . '  23:59:59');
                            break;
                        case 'status':
                            $where['t_withdraw.status'] = $val;
                            break;
                        default:
                            $where[$key] = $val;
                    }
                }
            }
        }
        //关联
        $join = [
            't_user' => 't_user.id = t_withdraw.user_id'
        ];
        //查询
        $data['data'] = $this->Withdraw->_get(
            't_withdraw.*,t_user.nickname,t_user.mobile,t_user.name',
            $where,
            [],
            ['t_withdraw.created_at' => 'desc'],
            ['page' => $page, 'count' => $pageSize],
            $join
        );

        $data['count'] = $this->Withdraw->_count($where, '', '*', $join);
        //生成分页
        $data['page'] = $this->pageInit($data['count'], $pageSize);

        $this->display('Withdraw/index', $data);
    }

    /**
     * 处理提现  不给提现  退余额
     */
    public function deal()
    {
        $id = $this->input->post('id') ?? 0;
        $status = $this->input->post('status');
        $remark = $this->input->post('remark');

        if ($id < 1 || !$status) {
            $this->responseToJson(0, '参数不正确');
        }

        $withdraw = $this->Withdraw->_getOne('id,user_id,money', ['id' => $id]);
        if (!$withdraw || !$withdraw['user_id']) {
            $this->responseToJson(0, '该提现记录不存在');
        }

        if ($status == 3) {  //不给提现  退余额
            $user = $this->User->_getOne('*', ['id' => $withdraw['user_id']]);

            $this->db->trans_begin();
            try {
                //更新用户表
                $res1 = $this->User->editUser(
                    ['updated_at' => time(), 'cash_balance' => $user['cash_balance'] + $withdraw['money']],
                    ['id' => $user['id']]
                );
                //插入流水记录 提现失败
                $res2 = $this->UserCashJournal->addUserCashJournal([
                    'user_id' => $user['id'],
                    'trade_type' => 6, //提现失败
                    'type' => 2,  //交易方式 1=>微信  2=>个人账户
                    'money' => $withdraw['money'],
                    'inorout' => 1, // 1=>进  2=出  3=>不进不出
                    'out_id' => $withdraw['id'],
                    'original_balance' => $user['cash_balance'],
                    'current_balance' => $user['cash_balance'] + $withdraw['money'],
                    'created_at' => time()
                ]);

                $res3 = $this->Withdraw->_update(
                    ['status' => $status, 'remark' => $remark, 'updated_at' => time()],
                    ['id' => $id]
                );

                if ($res1 && $res2 && $res3) {
                    $this->db->trans_commit();
                    $this->responseToJson(1, '操作成功');
                } else {
                    $this->db->trans_rollback();
                    throw new \Exception('操作接口异常');
                }
            } catch (\Exception $exception) {
                $this->db->trans_rollback();
                $this->responseToJson(0, $exception->getMessage());
            }

        } elseif ($status == 2) {  //提现完成
            $res = $this->Withdraw->_update(
                ['status' => $status, 'remark' => $remark, 'updated_at' => time()],
                ['id' => $id]
            );

            if ($res == false) {
                $this->responseToJson(0, '操作失败');
            } else {
                $this->responseToJson(1, '操作成功');
            }
        }
    }
}