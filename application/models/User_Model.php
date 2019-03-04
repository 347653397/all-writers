<?php

/**
 * Created by PhpStorm.
 * User: huzhenping
 * Date: 10/17
 * Time: 15:26
 */
class User_Model extends MY_Model
{
    public $_table = 'user';

    public $msg = array(
        'status' => array(
            '1' => '显示',
            '2' => '不显示'
        )
    );

    /**
     * 添加用户
     * @param $data  array 用户信息
     * @return bool
     */
    public function addUser($data)
    {
        $user_id = $this->_add($data,'id');    //添加用户

        if($user_id == false){
            return false;
        }else{
            return true;
        }

    }

    /**
     * 编辑用户
     * @param $data array 修改的数据
     * @param $where array 条件
     * @return bool
     */
    public function editUser($data,$where = [])
    {
        $res = $this->_update($data,$where);
        if($res == FALSE) {
            return false;
        } else {
            return true;
        }
    }
} 