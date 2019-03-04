<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use EasyWeChat\Foundation\Application;

class Home extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->config("wechat");
        $this->wechat = new Application(config_item("wechat"));
        $this->load->library('Vodlib');
    }

    //初始化页面 发起授权
    public function index()
    {
        //兼容分享 要带上 t 参数
        if (isset($_GET['t']) && $_GET['t'] && ($_GET['t'] != '/')) {
            $target_url = base_url('#') . ($_GET['t'] ?? '');

            $_SESSION['target_url'] = $target_url;
            redirect($target_url);
        }

        if (!$this->session->has_userdata($this->wechat_user)) {
            $response = $this->wechat->oauth->redirect();
            $response->send();
        }

        $path = FCPATH . '/dist/index.html';

        return $this->load->file($path);
    }

    //授权回调 用户注册
    public function oauthBack()
    {
        $oauth = $this->wechat->oauth;
        //获取OAuth授权结果用户信息
        $user = $oauth->user()->getOriginal();
        //首次注册
        $User = $this->User->_get('*', ['openid' => $user['openid']]);
        if (!$User) {
            if (!$this->User->_add([
                'openid' => $user['openid'],
                'nickname' => replace_emoji($user['nickname']),
                'sex' => $user['sex'],
                'headimgurl' => $user['headimgurl'],
                'created_at' => time()
            ])) {
                log_message('info', '注册失败的用户openid' . $user['openid']);
            };
        }
        //存session
        $this->session->set_userdata([$this->wechat_user => $user]);

        $targetUrl = empty($_SESSION['target_url']) ? '/' : $_SESSION['target_url'];
        $_SESSION['target_url'] = null;

        redirect($targetUrl);
    }

    //获取视频解密密钥
    public function getDrmDataKey()
    {
        $edk = $this->input->get_post('edk') ?? ''; //加密后的数据密钥
        //虽然不知道这后面两个参数是干嘛用但是先留着把
        $fileId = $this->input->get_post('fileId'); //文件ID
        $keySource = $this->input->get_post('keySource'); //加密方式

        if ($edk == '') {
            echo "edk 不能为空！";
            exit;
        }
        $res = json_decode($this->vodlib->getDrmDataKey($edk), 1);
        if ($res['data'] == null) {
            echo "参数错误";
            exit;
        }
        //返回DK
        return base64_encode($res['data']['keyList']['dk']);
    }
}
