<?php
/**
 * Created by PhpStorm.
 * User: huzhenping
 * Date: 2018/07/16
 * Time: 下午9:28
 */


class Setting extends Api_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('CourseItem_model', 'CourseItem');
        $this->load->model('Category_model','Category');
        $this->load->library('Redislib');
    }

    //获取首页标签列表
    public function getCategoryList()
    {
        $category = $this->Category->_get('cat_name,id as cid',false,false,['id'=>'asc']);
        $data = ['category' => $category ];
        $this->responseToJson(200, '请求成功', $data);
    }


    //记录播放数量
    public function savePlayNum()
    {
        $pdata = $this->input->post();
        if (empty($pdata['item_id'])) {
            $this->responseToJson(502, '参数错误');
        }
        $courseItem = $this->CourseItem->_getOne('course_id', ['id' => $pdata['item_id']]);

        if (!$courseItem) {
            $this->responseToJson(502, '获取课程失败-p');
        }
        //存储课程榜单
        $datatime = date('Y-m-d');
        $bkey = "RANK_{$datatime}";
        $this->redislib->zAdd($bkey,'1',$courseItem['course_id']);
        //存储课程播放放数量
        $key1 = $this->config->item('redis_keys')['playPrex'].$courseItem['course_id'];
        //存储音频播放放数量
        $key2 = $this->config->item('redis_keys')['playPrex'].$pdata['item_id'];
        $num1 = $this->redislib->get($key1);
        $num2 = $this->redislib->get($key2);
        if ($num1) {
            $this->redislib->incr($key1);
        } else {
            $this->redislib->set($key1, 1);
        }
        if ($num2) {
            $this->redislib->incr($key2);
        } else {
            $this->redislib->set($key2, 1);
        }

        $this->responseToJson(200, '请求成功', ['num' => $num2+1]);

    }


}