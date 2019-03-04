<?php defined('BASEPATH') || exit('No direct script access allowed');

//支付状态 1=>待支付  2=>支付完成  3=>支付失败
if (!function_exists('pay_status')) {
    function pay_status()
    {
        return array(1 => '待支付', 2 => '支付完成', 3 => '支付失败', 4 => '人为删除');
    }
}

//提现状态 1=>待处理  2=>提现完成  3=>不给提现
if (!function_exists('withdraw_status')) {
    function withdraw_status()
    {
        return array(1 => '待处理', 2 => '提现完成', 3 => '不给提现');
    }
}

//交易类型 1=>充值 2=>音频订单成功支付 3=>打赏收入  4=>打赏订单成功支付  5=>提现申请  6=>提现失败
if (!function_exists('trade_type')) {
    function trade_type()
    {
        return array(
            1 => '充值',
            2 => '音频订单支付',
            3 => '打赏收入',
            4 => '打赏订单支付',
            5 => '提现申请',
            6 => '提现失败'
        );
    }
}

//交易方式
if (!function_exists('journal_type')) {
    function journal_type()
    {
        return array(
            1 => '微信',
            2 => '个人账户'
        );
    }
}

//课程类型
if (!function_exists('up_type')) {
    function up_type()
    {
        return array(
            1 => '单集',
            2 => '连载'
        );
    }
}

//课程是否原创
if (!function_exists('is_original')) {
    function is_original()
    {
        return array(
            1 => '否',
            2 => '是'
        );
    }
}
?>