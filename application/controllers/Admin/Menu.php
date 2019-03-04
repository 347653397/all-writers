<?php
/**
 * Created by PhpStorm.
 * User: symphp <symphp@foxmail.com>
 * Date: 2017/7/20
 * Time: 16:47
 */
class Menu extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$data['menus'] = $this->get_all_menu($this->admin['id']);    //获取树状菜单列表
		$this->display('/Menu/index',$data);
	}

	/**
	 * 添加权限菜单
	 */
	public function add()
	{
		if (IS_POST) {
			$data['title']  = trim($this->input->post('title'));    //操作规则中文名称
			$data['name']   = $this->input->post('name');    //操作名（控制器/方法）
			$data['icon']   = $this->input->post('icon')??'';    //操作规则图标
			$data['sort']   = $this->input->post('sort')??1;    //排序
			$data['status'] = $this->input->post('status') == 'on'?1:2;    //是否显示
			$data['explain']= $this->input->post('explain')??'';    //描述
			$data['pid']    = $this->input->post('pid')??0;    //父级id
			$res = $this->Auth->_add($data);
			if ($res == false) {
				$error['msg'] = '添加菜单失败，请稍后再试！';
				return $this->error($error);
			} else {
				$success['msg'] = '添加菜单成功！';
				$success['url'] = 'index';
				return $this->success($success);
			}
 		} else {
			$data['menus'] = $this->get_all_menu($this->admin['id']);    //获取树状菜单列表
			$this->display('/Menu/add',$data);
		}
	}

	/**
	 * 编辑权限菜单
	 */
	public function edit()
	{
		if (IS_POST) {
			$data['title']  = trim($this->input->post('title'));    //操作规则中文名称
			$data['name']   = $this->input->post('name');    //操作名（控制器/方法）
			$data['icon']   = $this->input->post('icon')??'';    //操作规则图标
			$data['sort']   = $this->input->post('sort')??1;    //排序
			$data['status'] = $this->input->post('status')??1;    //是否显示
			$data['explain']= $this->input->post('explain')??'';    //描述
			$data['pid']    = $this->input->post('pid')??0;    //父级id
			$auth_id = $this->input->post('id')??0;    //auth_id
			/** -------------- 判断当前修改的auth是否属于当前用户 ---------------- **/
			if (!$this->Admin->SuperAdmin(($this->admin['id']))) {
				$auth = $this->Admin->getAdminAuth($this->admin['id'],['auth.auth_id'=>$auth_id]);    //获取当前菜单
			} else {
				$auth = true;
			}
			if (!$auth) {
				$error['msg'] = '参数不正确，请稍后再试！';
				return $this->error($error);
			} else {
				$res = $this->Auth->_update($data,['auth_id'=>$auth_id]);
				if ($res == false) {
					$error['msg'] = '编辑失败，请稍后再试！';
					return $this->error($error);
				} else {
					$success['msg'] = '编辑菜单成功！';
					$success['url'] = 'index';
					return $this->success($success);
				}
			}
		} else {
			$id = $this->input->get('id')??false;
			if (!$id) {
				$error['msg'] = '参数不正确，请稍后再试！';
				return $this->error($error);
			}
			$data['menus'] = $this->get_all_menu($this->admin['id']);    //获取树状菜单列表
			//判断是否是超级管理员
			if ($this->Admin->SuperAdmin(($this->admin['id']))) {
				$auth = $this->Auth->_get('*',['status'=>1,'auth_id'=>$id]);
			} else {
				$auth = $this->Admin->getAdminAuth($this->admin['id'],['auth.auth_id'=>$id]);    //获取当前菜单
			}
			if ($auth != false) {
				$data['auth'] = $auth[0];
				$this->display('/Menu/edit',$data);
			} else {
				$error['msg'] = '当前菜单不正确，请稍后再试！';
				return $this->error($error);
			}
		}
	}

	/**
	 * 删除权限菜单（逻辑删除）
	 */
	public function del()
	{
		$auth_id = $this->input->get('id')??false;    //权限id
		if (!$this->Admin->SuperAdmin(($this->admin['id']))) {
			$auth = $this->Admin->getAdminAuth($this->admin['id'],['auth.auth_id'=>$auth_id]);    //获取当前菜单
		} else {
			$auth = true;
		}
		if (!$auth) {
			$error['msg'] = '参数不正确，删除失败！';
			return $this->error($error);
		} else {
			$res = $this->Auth->_update(['status'=>'2'],['auth_id'=>$auth_id]);
			if (!$res) {
				$error['msg'] = '删除失败！';
				return $this->error($error);
			} else {
				$success['msg'] = '删除成功！';
				$success['url'] = 'index';
				return $this->success($success);
			}
		}
	}

	/**
	 * 获取树形菜单列表
	 * @param $id int 管理员id
	 * @return mixed
	 */
	public function get_all_menu($id)
	{
		$menus = $this->Admin->getAdminAuth($id);    //获取菜单权限列表
		return $this->get_menu_tree($menus);
	}


}