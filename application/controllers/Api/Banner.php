<?php
/**
 * Created by PhpStorm.
 * User: huzhenping
 * Date: 2018/07/16
 * Time: 下午9:28
 */


class Banner extends Api_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Banner_model', 'Banner');
    }

    //获取广告位列表
    public function getBannerList (){

		$params = [
			'status'=>'1'
		];
		//获取广告位列表
		$banners = $this->Banner->_get('banner_pic,jump_url,sort',$params);
        foreach ($banners as &$banner){
            $banner['banner_pic'] = isset($banner['banner_pic'])?IMG_URL.$banner['banner_pic']:'';
        }
		$data = [
			'banner' =>$banners,
            'count'=>count($banners)
		];

		$this->responseToJson(200, '请求成功',$data);
    }





































































}