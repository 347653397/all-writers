<?php
/**
 * Created by PhpStorm.
 * User: symphp <symphp@foxmail.com>
 * Date: 2017/8/2
 * Time: 15:57
 */
class Role extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('AdminRole_model','AdminRole');
		$this->load->model('AuthRoleAccos_model','AuthRoleAccos');
	}

	/**
	 * 角色列表
	 */
	public function index()
	{
		$roles = $this->AdminRole->_get('*');

		$data['roles'] = $this->changeMsg($roles);
		$this->display('Role/index',$data);
	}

	/**
	 * 添加角色
	 */
		public function add()
	{
		if (IS_POST) {
			$data['role_name'] = trim($this->input->post('role_name'));    //角色名称
			$data['status']    = $this->input->post('status') == 'on'?1:2;    //是否显示
			$data['explain']   = trim($this->input->post('explain'));    //角色说明
			$roles             = $this->input->post('role')??array();    //权限
			if (is_array($roles) && count($roles) > 0) {
				foreach ($roles as $role) {
					$rolesArr[]['auth_id'] = $role;    //转换成多维数组进行批量插入
				}
			} else {
				$rolesArr = array();
			}
			$res = $this->AdminRole->addRole($data,$rolesArr);    //添加角色
			if ($res == false) {
				$error['msg'] = '添加角色失败';
				return $this->error($error);
			} else {
				$success['msg'] = '添加角色成功';
				$success['url'] = 'index';
				return $this->success($success);
			}
		} else {
			$res = $this->Auth->_get('*',['status'=>1],[],['sort'=>'desc','auth_id'=>'desc']);
			if ($res == false) {
				$data['menus'] = array();
			} else {
				$data['menus'] = $this->get_menu_tree($res);    //获取所有权限菜单
			}
			$this->display('Role/add',$data);
		}
	}

	/**
	 * 删除角色（逻辑删除）
	 */
	public function del()
	{
		$role_id = $this->input->get('id')??0;
		if ($role_id < 1) {
			$error['msg'] = '参数不正确，删除失败！';
			return $this->error($error);
		}
		$res = $this->AdminRole->delRole($role_id);
		if ($res == false) {
			$error['msg'] = '删除角色失败';
			return $this->error($error);
		} else {
			$success['msg'] = '删除角色成功';
			$success['url'] = 'index';
			return $this->success($success);
		}
	}

	/**
	 * 编辑角色
	 */
	public function edit()
	{
		if (IS_POST) {
			$data['role_name'] = trim($this->input->post('role_name'));    //角色名称
			$data['status']    = $this->input->post('status') == 'on'?1:2;    //是否显示
			$data['explain']   = trim($this->input->post('explain'));    //角色说明
			$roles             = $this->input->post('role')??array();    //权限
			$id                = $this->input->post('role_id')??0;    //角色id
			if (is_array($roles) && count($roles) > 0) {
				foreach ($roles as $role) {
					$rolesArr[]['auth_id'] = $role;    //转换成多维数组进行批量插入
				}
			} else {
				$rolesArr = array();
			}
			$res = $this->AdminRole->editRole($data,['role_id'=>$id],$rolesArr);    //编辑角色
			if ($res == false) {
				$error['msg'] = '编辑角色失败';
				return $this->error($error);
			} else {
				$success['msg'] = '编辑角色成功';
				$success['url'] = 'index';
				return $this->success($success);
			}
		} else {
			$id = $this->input->get('id')??0;

			$res = $this->Auth->_get('*',['status'=>1],[],['sort'=>'desc','auth_id'=>'desc']);
			if ($res == false) {
				$data['menus'] = array();
			} else {
				$data['menus'] = $this->get_menu_tree($res);    //获取所有权限菜单
			}

			$auth_ids = $this->AuthRoleAccos->_get('*',['role_id'=>$id]);    //获取当前角色权限id
			foreach ($auth_ids as $auth_id) {
				$data['auth_ids'][] = $auth_id['auth_id'];
			}
			$admin_role = $this->AdminRole->_getOne('*',['role_id'=>$id]);    //角色信息
			if ($admin_role == false) {
				$error['msg'] = '编辑角色失败';
				return $this->error($error);
			}
			$data['admin_role']= $this->changeMsg($admin_role);
			$this->display('Role/edit',$data);
		}
	}

	/**
	 * 转换模型中对应的字段说明 如 status 1 2 显示 隐藏
	 */
	public function changeMsg($data)
	{
		if (count($data) == count($data, 1)) {
			$data['status'] = $this->AdminRole->msg['status'][$data['status']];
		} else {
			foreach ($data as $key => $value) {
				$data[$key]['status'] = $this->AdminRole->msg['status'][$value['status']];    //获取对应说明
			}
		}
		return $data;
	}
}