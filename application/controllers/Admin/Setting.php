<?php
/**
 * Created by PhpStorm.
 * User: symphp <symphp@foxmail.com>
 * Date: 2017/7/27
 * Time: 17:21
 */
class Setting extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Banner_model','Banner');
        $this->load->model('Category_model','Category');
		$this->load->library('Coslib');
	}

	/**
	 * 网站设置
	 */
	public function setting()
	{
		if (IS_POST) {
			foreach ($_POST as $key=>$value) {
				$arr[$key]['key'] = trim($key);
                $arr[$key]['value'] = trim($value);
			}
			$res = $this->Setting->_update_batch($arr,'key');
			if ($res == false) {
				$error['msg'] = '网站设置失败，请稍后再试！';
				return $this->error($error);
			} else {
				$success['msg'] = '网站设置成功！';
				$success['url'] = 'setting';
				return $this->success($success);
			}
		} else {
			$data['settings'] = $this->Setting->_get('*');
			$this->display('Setting/setting',$data);
		}
	}

	/**
	 * 广告位Banner列表
	 */
	public function banner()
	{

		$banners = $this->Banner->_get('*');
		foreach ($banners as &$banner){
            $banner['banner_pic'] = isset($banner['banner_pic'])?IMG_URL.$banner['banner_pic']:'';
        }
        $data['banners'] = $this->changeMsg($banners);
		$this->display('Setting/banner',$data);
		
	}

	/**
	 * 广告位Banner配置
	 */
	public function addBanner()
	{
		if (IS_POST) {
            $pdata = $this->input->post();
            $pdata['status']    = $this->input->post('status') == 'on'?1:2;    //是否显示
            $pdata['create_time'] = date('Y-m-d H:i:s',time());
            if(!empty($_FILES)){
                $imgarr = $this->coslib->cos_upload($_FILES);  //cos图片上传
                $pdata = array_merge($pdata,$imgarr);
            }
            $res = $this->Banner->addBanner($pdata);    //添加Banner
            if ($res == false) {
                $error['msg'] = '添加Banner失败';
                return $this->error($error);
            } else {
                $success['msg'] = '添加Banner成功';
                $success['url'] = 'banner';
                return $this->success($success);
            }
        } else {
            $this->display('Setting/addbanner');
        }
		
	}

	/**
     * 删除Banner
     */
    public function delBanner()
    {
        $id = $this->input->get('id')??0;
        if ($id < 1) {
            $error['msg'] = '参数不正确，删除失败！';
            return $this->error($error);
        }
        $res = $this->Banner->delBanner($id);
        if ($res == false) {
            $error['msg'] = '删除Banner失败';
            return $this->error($error);
        } else {
            $success['msg'] = '删除Banner成功';
            $success['url'] = 'banner';
            return $this->success($success);
        }
    }


    /**
     * 编辑Banner
     */
    public function editBanner()
    {
        if (IS_POST) {
            $pdata = $this->input->post();
            $pdata['status']    = $this->input->post('status') == 'on'?1:2;    //是否上架
            $pdata['update_time'] = date('Y-m-d H:i:s',time());

            if(!empty($_FILES) && $_FILES['banner_pic']['error'] == '0'){
                $imgarr = $this->coslib->cos_upload($_FILES);  //cos图片上传
                $pdata = array_merge($pdata,$imgarr);
            }
            $res = $this->Banner->editBanner($pdata,['id'=>$pdata['id']]);    //编辑Banner
            if ($res == false) {
                $error['msg'] = '编辑Banner失败';
                return $this->error($error);
            } else {
                $success['msg'] = '编辑Banner成功';
                $success['url'] = 'banner';
                return $this->success($success);
            }
        } else {
            $id = $this->input->get('id')??0;
            $banner = $this->Banner->_getOne('*',['id'=>$id]);    //Banner信息
            if ($banner == false) {
                $error['msg'] = '获取Banner信息失败';
                return $this->error($error);
            }
            $banner['banner_pic'] = !empty($banner['banner_pic'])?IMG_URL.$banner['banner_pic']:'';

            $data['banner'] = $banner;
            $data['tags'] = array('精选','听故事','世间听','小牛参谋长');
//            dump($data);exit;
            $this->display('Setting/editbanner',$data);
        }
    }

    /**
     * 首页分类标签列表
     */
    public function category()
    {

        $categorys = $this->Category->_get('*',false,false,['sort'=>'desc']);
        $data['categorys'] = $this->changeMsg($categorys);
        $this->display('Setting/category',$data);
        
    }

    /**
     * 添加分类配置
     */
    public function addCategory()
    {
        if (IS_POST) {
            $pdata = $this->input->post();
            $pdata['status']    = $this->input->post('status') == 'on'?1:2;    //是否显示
            $res = $this->Category->addCategory($pdata);    //添加Banner
            if ($res == false) {
                $error['msg'] = '添加标签失败';
                return $this->error($error);
            } else {
                $success['msg'] = '添加标签成功';
                $success['url'] = 'category';
                return $this->success($success);
            }
        } else {
            $this->display('Setting/addcategory');
        }
        
    }

    /**
     * 删除Banner
     */
    public function delCategory()
    {
        $id = $this->input->get('id')??0;
        if ($id < 1) {
            $error['msg'] = '参数不正确，删除失败！';
            return $this->error($error);
        }
        $res = $this->Category->delCategory($id);
        if ($res == false) {
            $error['msg'] = '删除标签失败';
            return $this->error($error);
        } else {
            $success['msg'] = '删除标签成功';
            $success['url'] = 'category';
            return $this->success($success);
        }
    }


    /**
     * 编辑标签
     */
    public function editCategory()
    {
        if (IS_POST) {
            $pdata = $this->input->post();
            $pdata['status']    = $this->input->post('status') == 'on'?1:2;    //是否上架
            $res = $this->Category->editCategory($pdata,['id'=>$pdata['id']]);    //编辑标签
            if ($res == false) {
                $error['msg'] = '编辑标签失败';
                return $this->error($error);
            } else {
                $success['msg'] = '编标签成功';
                $success['url'] = 'category';
                return $this->success($success);
            }
        } else {
            $id = $this->input->get('id')??0;
            $category = $this->Category->_getOne('*',['id'=>$id]);    //Banner信息
            if ($category == false) {
                $error['msg'] = '获取标签信息失败';
                return $this->error($error);
            }
            $data['category'] = $category;
            $this->display('Setting/editcategory',$data);
        }
    }

}