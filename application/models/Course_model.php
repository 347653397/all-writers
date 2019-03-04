<?php

/**
 * Created by PhpStorm.
 * User: huzhenping
 * Date: 10/17
 * Time: 15:26
 */
class Course_model extends MY_Model
{
    public $_table = 'course';

    public $msg = array(
        'status' => array(
            '1' => '显示',
            '2' => '不显示'
        )
    );

    //获取课程详情
    public function getCourseInfo($params){
     
        $where = '1=1';

        if(isset($params['update_status'])){
            $where .= " and c.update_status = {$params['update_status']}";
        }

        if(isset($params['status'])){
            $where .= " and c.status = {$params['status']}";
        }else{
            $where .= " and c.status = 1";
        }

        $sql = "SELECT * FROM $this->_table c where {$where}";
        $query = $this->db->query($sql);
        if(!$query){
            return false;
        }else{
            $row = $query->row_array();
            return $row;
        }
        
        
        
    }



    /**
     * 添加课程
     * @param $data  array 课程信息
     * @return bool
     */
    public function addCourse($data)
    {
        $course_id = $this->_add($data,'id');    //添加课程
        if($course_id == false){
            return false;
        }else{
            return $course_id;
        }

    }

    /**
     * 编辑课程
     * @param $data array 修改的数据
     * @param $where array 条件
     * @return bool
     */
    public function editCourse($data,$where = [])
    {
        $res = $this->_update($data,$where);

        if($res == FALSE) {
            return false;
        } else {
            return $res;
        }
    }

    /**
     * 删除课程 
     * @param int $id
     * @return bool|mixed
     */
    public function delCourse(int $id)
    {
        return $this->_del(['id'=>$id]);
    }

}