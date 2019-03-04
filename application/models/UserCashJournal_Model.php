<?php

class UserCashJournal_Model extends MY_Model
{
    public $_table = 'user_cash_journal';

    /**
     * 添加
     * @param $data  array
     * @return bool
     */
    public function addUserCashJournal($data)
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
    public function editUserCashJournal($data, $where = [])
    {
        $res = $this->_update($data, $where);
        if ($res == FALSE) {
            return false;
        } else {
            return true;
        }
    }

} 