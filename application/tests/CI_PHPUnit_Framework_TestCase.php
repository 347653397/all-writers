<?php
use PHPUnit\Framework\TestCase;
/**
 * @author: symphp
 * @Date  : 2016/7/10
 * @Time  : 11:12
 */
class CI_PHPUnit_Framework_TestCase extends TestCase
{
	/**
	 * @var CI_Controller
	 */
	public $CI = null;

	protected function setUp()
	{
		$this->CI = & get_instance();
	}

	/**
	 * 加载控制器
	 * @param $class
	 * @param null $prefix
	 */
	public function requireController($class, $prefix = null)
	{
		$filepath = APPPATH.'controllers'.DIRECTORY_SEPARATOR.$prefix.DIRECTORY_SEPARATOR.$class.'.php';
		if (file_exists($filepath))
		{
			require_once($filepath);
		}
	}
}