<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Weixin extends Api_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->config("wechat");
        $this->wechat = new \EasyWeChat\Foundation\Application(config_item("wechat"));
    }

    /**
     * 获取wx.config
     */
    public function weixinShareConfig()
    {
        $url = urldecode($this->input->post('url'));
        if (!$url) $this->responseToJson(502, 'url参数缺少');
        log_message('info', 'getWxConfig获取到的url:' . $url);

        try {
            $jssdk = $this->wechat->js;
            $jssdk->setUrl($url);
            $data = $jssdk->config(
                ['onMenuShareQQ', 'onMenuShareWeibo', 'onMenuShareQZone', 'chooseWXPay', 'chooseImage',
                    'previewImage', 'uploadImage', 'downloadImage', 'getLocalImgData', 'getNetworkType',
                    'onMenuShareAppMessage','onMenuShareTimeline'
                ],
                true, false, false);
            $this->responseToJson(200, '获取成功', $data);
        } catch (\Exception $exception) {
            $this->responseToJson(502, $exception->getMessage());
        }
    }

    /**
     * 批量获取材料
     */
    public function getMaterial()
    {
        $data = $this->wechat->material->lists('news',0,5000);
        dump($data);
    }
}
