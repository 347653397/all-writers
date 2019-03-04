<?php

class RewardOrder_Model extends MY_Model
{
    public $_table = 'reward_order';

    public $msg = array(
        'pay_status' => array(
            '1' => '待支付',
            '2' => '支付完成',
            '3' => '支付失败'
        )
    );

    /**
     * 添加
     * @param $data  array
     * @return bool
     */
    public function addRewardOrder($data)
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
    public function editRewardOrder($data, $where = [])
    {
        $res = $this->_update($data, $where);
        if ($res == FALSE) {
            return false;
        } else {
            return true;
        }
    }
} 