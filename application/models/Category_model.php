<?php

/**
 * Created by PhpStorm.
 * User: huzhenping
 * Date: 10/17
 * Time: 15:26
 */
class Category_model extends MY_Model
{
    public $_table = 'category';

    /**
     * 添加广告位
     * @param $data  array 广告位信息
     * @return bool
     */
    public function addCategory($data)
    {
        $category_id = $this->_add($data,'id');   

        if($category_id == false){
            return false;
        }else{
            return true;
        }

    }

    /**
     * 编辑Category
     * @param $data array 修改的数据
     * @param $where array 条件
     * @param $rolesArr array 对应权限id
     * @return bool
     */
    public function editCategory($data,$where = [])
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
    public function delCategory(int $id)
    {
        return $this->_del(['id'=>$id]);
    }
}