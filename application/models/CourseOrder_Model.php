<?php

class CourseOrder_Model extends MY_Model
{
    public $_table = 'course_order';

    public $msg = array(
        'pay_status' => array(
            '1' => '支付中',
            '2' => '支付完成',
            '3' => '支付失败',
            '4' => '人为删除'
        )
    );

    /**
     * 添加
     * @param $data  array
     * @return bool
     */
    public function addCourseOrder($data)
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
    public function editCourseOrder($data, $where = [])
    {
        $res = $this->_update($data, $where);
        if ($res == FALSE) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 人为删除订单
     * @param int $id
     * @return bool|mixed
     */
    public function delCourseOrder(int $id)
    {
        return $this->_update(['pay_status' => 4], ['id' => $id]);
    }


} 