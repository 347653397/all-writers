<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config["wechat"] = [
    /*
     * Debug 模式，bool 值：true/false
     *
     * 当值为 false 时，所有的日志都不会记录
     */
    'debug' => true,

    /*
     * 账号基本信息，请从微信公众平台/开放平台获取
     */
    'app_id' => '',     // AppID
    'secret' => '',     // AppSecret
    'token' => 't9HazcCJVOcB4Rh666',          // Token     没用到
    'aes_key' => 'B6p0ftox4hMMDedbAueuCKqweUAQOVA3uPxxtLIBm0c',// EncodingAESKey 没用到

    /*
     * 日志配置
     *
     * level: 日志级别，可选为：
     * debug/info/notice/warning/error/critical/alert/emergency
     * file：日志文件位置(绝对路径!!!)，要求可写权限
     */
    'log' => [
        'level' => 'debug',
        'file' => APPPATH . 'logs/wechat.log',
    ],

    /*
     * OAuth 配置
     * scopes：公众平台（snsapi_userinfo / snsapi_base），开放平台：snsapi_login
     * callback：OAuth授权完成后的回调页地址(如果使用中间件，则随便填写。。。)
     */
    'oauth' => [
        'scopes' => ['snsapi_userinfo'],
        'callback' => 'home/oauthBack'
    ],

    /**
     * 微信支付
     */
    'payment' => [
        'merchant_id' => '1509960191',
        'key' => 'ZPsKZDbff2IqeirdfFtj4JM9cACppMss',
        'cert_path' => APPPATH . 'key/wechat/apiclient_cert.pem',  //绝对路径
        'key_path' => APPPATH . 'key/wechat/apiclient_key.pem',   //绝对路径
        'notify_url' => ''
    ],

    'guzzle' => [
        'timeout' => 3.0, // 超时时间（秒）
        //'verify' => false, // 关掉 SSL 认证（强烈不建议！！！）
    ]
];
