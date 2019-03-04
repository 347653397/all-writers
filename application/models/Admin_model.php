<?php

/**
 * Created by PhpStorm.
 * User: symphp <symphp@foxmail.com>
 * Date: 2017/7/8
 * Time: 22:49
 */
class Admin_model extends MY_Model
{
	public $_table = 'admin';

	public $_msg   = array(
		'status' => array(
			'1' => '显示',
			'2' => '不显示'
		),
		'sex' => array(
			'1' => '男',
			'2' => '女',
			'3' => '保密'
		)
	);
	/**
	 * 获取账号信息
	 * @param $username
	 * @param $password
	 * @return mixed
	 */
	public function checkUser($username,$password)
	{
		$where['username'] = $username;
		$user_info = $this->_getOne('*',$where);
		if(!$user_info) {
			return false;
		}
		if(hashPass($password,$user_info['salt']??'') != $user_info['password']){    //判断密码是否正确
			return false;
		}
		return $user_info;
	}

	/**
	 * 获取角色权限
	 * @param int $admin_id
	 * @param array $where
	 * @return bool|mixed
	 */
	public function getAdminAuth(int $admin_id,$where = [],$filed = 'auth.*')
	{
		if(!$admin_id) {
			return false;
		} else {
			$where['admin.id']  = $admin_id;
			$where['ar.status'] = 1;
			$where['auth.status'] = 1;

			$join['admin_role_accos as ara'] = 'on ara.admin_id = admin.id';    //用户角色关系表
			$join['admin_role as ar'] = 'on ar.role_id = ara.role_id';    //角色表
			$join['auth_role_accos as arac'] = 'on arac.role_id = ar.role_id';    //权限角色关系表
			$join['auth'] = 'on auth.auth_id = arac.auth_id';    //权限表
			return $this->_get($filed,$where,'',['sort'=>'asc','id'=>'desc'],'',$join);
		}
 	}

	/**
	 * 判断是否是超级管理员
	 * @param int $admin_id
	 * @return bool
	 */
	public function SuperAdmin(int $admin_id)
	{
		$sql = "SELECT role_id FROM `t_admin_role_accos`"
			   ."WHERE `admin_id` = $admin_id";
		$res = $this->_query($sql);
		if($res == false) {
			return false;
		} else {
			if ($res[0]['role_id'] != 1) {
				return false;
			} else {
				return true;
			}
		}
	}

}