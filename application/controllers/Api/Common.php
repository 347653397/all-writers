<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm
 * Desc: Comm.php
 * User: guochao
 * Date: 2018/8/24
 * Time: 上午10:13
 */
class Common extends Api_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Coslib');
    }

    public function getUploadSign()
    {
        $sign = $this->coslib->getUploadSign1();

        $Authorization = explode('sign=',urldecode($sign))[1];

        $this->responseToJson(200, '获取成功', ['Authorization' => $Authorization]);
    }
}