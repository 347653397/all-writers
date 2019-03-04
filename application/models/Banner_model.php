<?php

/**
 * Created by PhpStorm.
 * User: huzhenping
 * Date: 10/17
 * Time: 15:26
 */
class Banner_model extends MY_Model
{
    public $_table = 'banner';

    public $msg = array(
        'status' => array(
            '1' => '显示',
            '2' => '不显示'
        )
    );


    /**
     * 添加广告位
     * @param $data  array 广告位信息
     * @return bool
     */
    public function addBanner($data)
    {
        $banner_id = $this->_add($data,'id');   

        if($banner_id == false){
            return false;
        }else{
            return true;
        }

    }

    /**
     * 编辑Banner
     * @param $data array 修改的数据
     * @param $where array 条件
     * @param $rolesArr array 对应权限id
     * @return bool
     */
    public function editBanner($data,$where = [])
    {
        $res = $this->_update($data,$where);
        if($res == FALSE) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 删除Banenr 
     * @param int $id
     * @return bool|mixed
     */
    public function delBanner(int $id)
    {
        return $this->_del(['id'=>$id]);
    }

}