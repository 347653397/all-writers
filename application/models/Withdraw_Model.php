<?php

class Withdraw_Model extends MY_Model
{
    public $_table = 'withdraw';

    /**
     * 添加
     * @param $data  array
     * @return bool
     */
    public function addWithdraw($data)
    {
        $user_id = $this->_add($data, 'id');

        if ($user_id == false) {
            return false;
        } else {
            return true;
        }

    }

    /**
     * 编辑
     * @param $data array 修改的数据
     * @param $where array 条件
     * @return bool
     */
    public function editWithdraw($data, $where = [])
    {
        $res = $this->_update($data, $where);
        if ($res == FALSE) {
            return false;
        } else {
            return true;
        }
    }

} 