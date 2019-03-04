<?php
/**
 * Created by PhpStorm.
 * User: symphp <symphp@foxmail.com>
 * Date: 2017/8/17
 * Time: 16:16
 */
class Admin extends Admin_Controller
{	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Admin_model','Admin');
		$this->load->model('AdminRole_model','AdminRole');
		$this->load->model('AdminRoleAccos_model','AdminRoleAccos');
	}

	/**
	 * 个人资料
	 */
	public function info()
	{
		if (IS_POST) {
			$params['sex']     = $this->input->post('sex');
			$params['phone']   = $this->input->post('phone');
			$params['email']   = $this->input->post('email');
			//选择图片后上传
			if (!empty($_FILES['head_pic']['tmp_name'])) {
				$upload = $this->headUpload();
				if ($upload['success'] == false) {
					$error['msg'] = $upload['info'];
					return $this->error($error);
				} else {
					$params['head_pic'] = json_encode($upload['info']);
					//删除旧的头像
					if ($this->admin['head_pic']) {
						$aged_head = substr($this->admin['head_pic'],1);
						if(file_exists($aged_head))
							unlink($aged_head);
					}
				}
			}
			$where['id'] = $this->admin['id'];
			//更新个人资料
			$res = $this->Admin->_update($params,$where);
			if ($res == false) {
				$error['msg'] = '更新个人资料失败';
				return $this->error($error);
			} else {
				$success['msg'] = '更新个人资料成功！';
				$success['url'] = 'info';
				return $this->success($success);
			}
		} else {
			$data['admin']  = $this->admin;
			$data['selects'] = array(
				array('status' => '3', 'msg' => '保密'),
				array('status' => '2', 'msg' => '男'),
				array('status' => '1', 'msg' => '女')
			);
			$this->display('Admin/info',$data);
		}
	}

	/**
	 * 管理员管理
	 */
	public function index()
	{
		$field = 'id,username,sex,phone,email,reg_time,ar.role_name';
		$condition['admin.status'] = 1;

		$join['admin_role_accos as ara'] = ' on ara.admin_id = admin.id';    //角色关系表
		$join['admin_role as ar'] = ' on ar.role_id = ara.role_id';    //角色表
		//查询出所有的用户
		$admins = $this->Admin->_get($field,$condition,[],[],[],$join);
		if ($admins == false) {
			$error['msg'] = '不存在用户信息！';
			return $this->error($error);
		} else {
			foreach ($admins as $admin) {
				$admin['sex'] = $this->Admin->_msg['sex'][$admin['sex']];
				$data['admins'][] = $admin;
			}
		}
		$this->display('Admin/index',$data);
	}

	/**
	 * 新增管理员
	 */
	public function add()
	{
		if (IS_POST) {
			$params['sex']     = $this->input->post('sex');    //性别
			$params['phone']   = $this->input->post('phone');    //手机
			$params['email']   = $this->input->post('email');    //邮箱
			$params['username']= $this->input->post('username');    //用户名
			$params['reg_time']= time();   //注册时间
			$params['salt']    = rand(000000,999999);    //盐
			$params['password']= hashPass($this->input->post('password'),$params['salt']);   //密码

			$role_id = $this->input->post('role_id')??'';    //角色id

			if ($role_id == '') {
				$error['msg'] = '未选择角色！';
				return $this->error($error);
			}
			//判断会员是否已经注册
			$res = $this->Admin->_getOne('id',['username' => $params['username']]);
			if ($res) {
				$error['msg'] = '用户名已存在！';
				return $this->error($error);
			}

			//选择图片后上传
			if (!empty($_FILES['head_pic']['tmp_name'])) {
				$upload = $this->headUpload();
				if ($upload['success'] == false) {
					$error['msg'] = $upload['info'];
					return $this->error($error);
				} else {
					$params['head_pic'] = json_encode($upload['info']);
					//删除旧的头像
					if ($this->admin['head_pic']) {
						$aged_head = substr($this->admin['head_pic'],1);
						if(file_exists($aged_head))
							unlink($aged_head);
					}
				}
			}

			/** ---------------- 添加会员表以及角色关系表 ----------------**/
			$this->db->trans_begin();

			$id = $this->Admin->_add($params,'id');
			$this->AdminRoleAccos->_add(['admin_id' => $id,'role_id' => $role_id]);

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$error['msg'] = '添加会员失败';
				return $this->error($error);
			} else {
				$this->db->trans_commit();
				$success['msg'] = '添加会员成功';
				$success['url'] = '/Admin/User/index';
				return $this->success($success);
			}
		} else {
			$data['selects'] = array(
				array('status' => '3', 'msg' => '保密'),
				array('status' => '2', 'msg' => '女'),
				array('status' => '1', 'msg' => '男')
			);
			$data['roles'] = $this->AdminRole->_get('role_id,role_name',['status' => 1]);
			$this->display('Admin/add',$data);
		}
	}

	/**
	 * 编辑管理员资料
	 */
	public function edit()
	{
		if (IS_POST) {
			$id = $this->input->post('id')??'';

			if ($id == '') {
				$error['msg'] = '参数错误！';
				return $this->error($error);
			} else if ($id == 1 && $this->admin['id'] != 1) {
				$error['msg'] = '管理员信息不允许修改！';
				return $this->error($error);
			}

			$conditions['id'] = $id;

			/** -------------- 查询用户是否存在 ---------------- **/

			$join['admin_role_accos as ara'] = ' on ara.admin_id = admin.id';    //角色关系表
			$join['admin_role as ar'] = ' on ar.role_id = ara.role_id';    //角色表
			$admin = $this->Admin->_get('id,head_pic',$conditions,[],[],[],$join);

			$params['sex']     = $this->input->post('sex');
			$params['phone']   = $this->input->post('phone');
			$params['email']   = $this->input->post('email');
			$params['username']= $this->input->post('username');
			//选择图片后上传
			if (!empty($_FILES['head_pic']['tmp_name'])) {
				$upload = $this->headUpload();
				if ($upload['success'] == false) {
					$error['msg'] = $upload['info'];
					return $this->error($error);
				} else {
					$params['head_pic'] = json_encode($upload['info']);
					//删除旧的头像
					if (isset($admin['head_pic'])) {
						$aged_head = substr($admin['head_pic'],1);
						if(file_exists($aged_head))
							unlink($aged_head);
					}
				}
			}
			$this->db->trans_begin();
			/** ---------------- 更新admin表 ----------------**/
			$res = $this->Admin->_update($params,$conditions);
			/** ---------------- 更新角色关系表 ----------------**/
			$current_role_id = $this->input->post('current_role_id');    //当前的角色id
			$role_id = $this->input->post('role_id');    //新角色id
			if ($current_role_id != $role_id) {
				$this->AdminRoleAccos->_update(['role_id' => $role_id],['admin_id' => $id]);
			}
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$error['msg'] = '更新资料失败';
				return $this->error($error);
			} else {
				$this->db->trans_commit();
				$success['msg'] = '更新资料成功！';
				$success['url'] = 'index';
				return $this->success($success);
			}
		} else {
			$id = $this->input->get('id')??'';
			if ($id == '') {
				$error['msg'] = '参数错误！';
				return $this->error($error);
			} else {
				/** ---------------- 获取账号信息 ----------------**/
				$join['admin_role_accos as ara'] = ' on ara.admin_id = admin.id';    //角色关系表
				$join['admin_role as ar'] = ' on ar.role_id = ara.role_id';    //角色表
				$res = $this->Admin->_get('*,ar.role_id,ar.role_name',['admin.status' => 1,'admin.id' => $id],[],[],[],$join);
				if (isset($res[0]))
					$admin = $res[0];
				if (!empty($admin['head_pic']))
					$admin['head_pic'] = json_decode($admin['head_pic']);
			}
			$data['admin_info'] = $admin;
			/** ---------------- 获取所有角色 ----------------**/
			$data['roles'] = $this->AdminRole->_get('role_id,role_name',['status' => 1]);
			$data['selects'] = array(
				array('status' => '3', 'msg' => '保密'),
				array('status' => '2', 'msg' => '女'),
				array('status' => '1', 'msg' => '男')
			);
			$this->display('Admin/edit',$data);
		}
	}

	/**
	 * 删除管理员(逻辑删除)
	 */
	public function del()
	{
		$id = $this->input->get('id')??'';
		if ($id == '') {
			$error['msg'] = '参数错误！';
			return $this->error($error);
		} else if ($id == 1) {
			$error['msg'] = '管理员账号不允许删除！';
			return $this->error($error);
		}
		$conditions['id'] = $id;
		/** ---------------- 逻辑删除 ----------------**/
		$params['status'] = 2;
		$res = $this->Admin->_update($params,$conditions);
		if ($res == false) {
			$error['msg'] = '删除失败';
			return $this->error($error);
		} else {
			$success['msg'] = '删除成功！';
			$success['url'] = 'index';
			return $this->success($success);
		}
	}

	/**
	 * 修改密码
	 */
	public function changePass()
	{
		if (IS_POST) {
			$old_pass = $this->input->post('old_pass');    //原密码
			$new_pass = $this->input->post('new_pass');    //新密码
			$confirm_pass = $this->input->post('confirm_pass');    //确认密码

			if ($new_pass !== $confirm_pass) {
				$error['msg'] = '新密码和确认密码不相同！';
				return $this->error($error);
			}

			if ($old_pass == $new_pass) {
				$error['msg'] = '新密码不能与旧密码相同！';
				return $this->error($error);
			}

			/** -------------- 判断原密码是否正确 ---------------- **/
			$admin_info = $this->Admin->checkUser($this->admin['username'],$old_pass);
			if ($admin_info == false) {
				$error['msg'] = '旧密码不正确！';
				return $this->error($error);
			}

			//加密新密码
			$params['password'] = hashPass($new_pass,$admin_info['salt']);
			$conditions['id'] = $this->admin['id'];
			$res = $this->Admin->_update($params,$conditions);

			if ($res == false) {
				$error['msg'] = '旧密码不正确！';
				return $this->error($error);
			} else {
				$success['msg'] = '修改密码成功！';
				$success['url'] = '/Admin/index';
				return $this->success($success);
			}
		} else {
			$this->display('Admin/pass');
		}
	}


	/**
	 * 头像上传
	 */
	public function headUpload()
	{
		$config['upload_path']      = './public/img/uploads/head_pic/';
		$config['allowed_types']    = 'gif|jpg|png|jpeg';
		$config['max_size']     = 2048;
		$config['file_name'] = '97Admin_' . time();

		if (!file_exists($config['upload_path'])) {
			mkdir($config['upload_path'],0777,true);
		}

		$this->load->library('upload', $config);
		if (!$this->upload->do_upload('head_pic')) {
			$data = array('success' => false,'info' => $this->upload->display_errors());
		} else {
			$data = array('success' => true,'info' => substr($config['upload_path'],1).$this->upload->data()['file_name']);
		}
		return $data;
	}

}