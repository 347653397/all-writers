<?php

/**
 * Created by PhpStorm.
 * User: symphp <symphp@foxmail.com>
 * Date: 2017/7/3
 * Time: 23:04
 */
class MY_Loader extends CI_Loader
{
	/**
	 * 设置视图路径方便前后台调用
	 * @param string $path 路径
	 */
	public function set_ci_view_dir($path = HOME_VIEW_DIR)
	{
		$this->_ci_view_paths = array(APPPATH . $path => TRUE);
	}
}