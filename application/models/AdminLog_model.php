<?php

/**
 * Created by PhpStorm.
 * User: symphp <symphp@foxmail.com>
 * Date: 2017/7/17
 * Time: 22:28
 */
class AdminLog_model extends MY_Model
{
	public $_table = 'admin_log';

	/**
	 * 添加日志记录
	 * @param $log string 操作说明
	 * @param string $admin_id int 操作id
	 * @return bool|mixed
	 */
	public function add($log,$admin_id = '')
	{
		$data['ip']       = getClientIP();
		$data['log']      = $log;
		$data['time']     = date('Y-m-d H:i:s',time());
		$data['admin_id'] = $admin_id;
		return $this->_add($data);
	}
}