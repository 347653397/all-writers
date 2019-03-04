<?php
/**
 * Created by PhpStorm.
 * User: symphp <symphp@foxmail.com>
 * Date: 2017/8/1
 * Time: 15:22
 */

class Setting_model extends MY_Model
{
	public $_table = 'setting';

	/**
	 * 获取系统设置
	 * @param string $filed
	 * @param array $where
	 * @return bool
	 */
	public function getAll($filed = '*',$where = [])
	{
		$settings = $this->_get($filed,$where);
		if($settings == false) {
			return false;
		} else {
			foreach ($settings as $setting) {
				$arr[$setting['key']] = $setting;    //以key作为键值
			}
			return $arr;
		}
	}
}