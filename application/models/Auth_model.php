<?php
/**
 * Created by PhpStorm.
 * User: symphp <symphp@foxmail.com>
 * Date: 2017/7/20
 * Time: 16:53
 */

class Auth_model extends MY_Model
{
	public $_table = 'auth';

	/**
	 * 获取当前菜单信息
	 * @param int $auth_id
	 * @return bool
	 */
	public function get_current_auth(int $auth_id)
	{
		$sql = "SELECT s.*,p.pid as ppid,p.title as ptitle,p.name as pname
				FROM t_auth s 
				LEFT JOIN t_auth p on p.auth_id=s.pid
				where s.auth_id=$auth_id";
		$res = $this->_query($sql);

		if($res == false)
			return false;

		return $res[0];
	}
}