<?php

/**
 * Created by PhpStorm.
 * User: huzhenping
 * Date: 10/17
 * Time: 15:26
 */
class Audio_model extends MY_Model
{
    public $_table = 'course_items';

    public $msg = array(
        'status' => array(
            '1' => '显示',
            '2' => '不显示'
        )
    );

    /**
     * 添加音频
     * @param $data  array 课程信息
     * @return bool
     */
    public function addAudio($data)
    {
        $audio_id = $this->_add($data,'id');    //添加角色

        if($audio_id == false){
            return false;
        }else{
            return true;
        }

    }

    /**
     * 编辑音频
     * @param $data array 修改的数据
     * @param $where array 条件
     * @return bool
     */
    public function editAudio($data,$where = [])
    {
        $res = $this->_update($data,$where);
        if($res == FALSE) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 删除音频
     * @param int $id
     * @return bool|mixed
     */
    public function delAudio(int $id)
    {
        return $this->_del(['id'=>$id]);
    }

}