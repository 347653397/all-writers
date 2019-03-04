<?php
/**
 * Created by PhpStorm.
 * User: symphp
 * Date: 2017/7/14
 * Time: 15:25
 * 公共函数库
 */
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('ajaxReturn')) {
    /**
     * Ajax方式返回数据到客户端
     * @param $data mixed 需要返回的数据
     * @param string $type Ajax返回数据格式
     */
    function ajaxReturn($data, $type = '')
    {
        $type = $type ? $type : 'JSON';
        switch (strtoupper($type)) {
            case 'JSON' :
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode($data));
            case 'XML'  :
                // 返回xml格式数据
                header('Content-Type:text/xml; charset=utf-8');
                exit(xml_encode($data));
            case 'JSONP':
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:application/json; charset=utf-8');
                $handler = isset($_GET[C('VAR_JSONP_HANDLER')]) ? $_GET[C('VAR_JSONP_HANDLER')] : C('DEFAULT_JSONP_HANDLER');
                exit($handler . '(' . json_encode($data) . ');');
            case 'EVAL' :
                // 返回可执行的js脚本
                header('Content-Type:text/html; charset=utf-8');
                exit($data);
        }
    }
}

if (!function_exists('hashPass')) {
    /**
     * hash password加密
     * @param $password string 未加密的密码
     * @param string $salt string 盐
     * @param $mode string $mode sha256, sha512, md5, sha1等加密方式
     * @return bool|mixed|string
     * @todo 常用的几种密码加密算法 http://www.cnblogs.com/sunbjj/p/6139724.html
     */
    function hashPass($password, $salt = '', $mode = 'sha256')
    {
        return hash($mode, $password . $salt);
    }
}

if (!function_exists('getClientIP')) {
    /**
     * 一个可靠的用户ip获取方法
     * @return string
     */
    function getClientIP()
    {
        if (array_key_exists('HTTP_ALI_CDN_REAL_IP', $_SERVER)) {
            return $_SERVER["HTTP_ALI_CDN_REAL_IP"];
        } else if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
            return $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else if (array_key_exists('REMOTE_ADDR', $_SERVER)) {
            return $_SERVER["REMOTE_ADDR"];
        } else if (array_key_exists('HTTP_CLIENT_IP', $_SERVER)) {
            return $_SERVER["HTTP_CLIENT_IP"];
        }
        return '';
    }
}

if (!function_exists('dump')) {
    function dump($data)
    {
        // 定义样式
        $str = '<pre style="display: block;padding: 9.5px;margin: 44px 0 0 0;font-size: 13px;line-height: 1.42857;color: #333;word-break: break-all;word-wrap: break-word;background-color: #F5F5F5;border: 1px solid #CCC;border-radius: 4px;">';
        // 如果是boolean或者null直接显示文字；否则print
        if (is_bool($data)) {
            $show_data = $data ? 'true' : 'false';
        } elseif (is_null($data)) {
            $show_data = 'null';
        } else {
            $show_data = print_r($data, true);
        }
        $str .= $show_data;
        $str .= '</pre>';
        echo $str;
    }
}

if (!function_exists('getAid')) {
    /**
     * 获取一个广告ID
     * @param int $id int 项目ID
     * @param string $key string 广告KEY
     * @return int
     */
    function getAid($id, $key = AD_KYE)
    {
        $aid = base64_encode($id + 9999);
        return $aid;
    }
}

if (!function_exists('decAid')) {
    /**
     * 解析一个广告ID
     * @param int $id int 项目ID
     * @param string $key string 广告KEY
     * @return int
     */
    function decAid($id, $key = AD_KYE)
    {
        $aid = base64_decode($id) - 9999;
        return $aid;
    }
}

//生成验证码
if (!function_exists('generate_code')) {
    function generate_code($length = 4)
    {
        return rand(pow(10, ($length - 1)), pow(10, $length) - 1);
    }
}

//手机号验证
if (!function_exists('isMobile')) {
    function isMobile($mobile)
    {
        if (!is_numeric($mobile)) {
            return false;
        }

        return preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,1,3,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $mobile) ? true : false;
    }
}

//主键生成
if (!function_exists('uuid')) {
    function uuid()
    {
        $chars = md5(uniqid(mt_rand(), true));

        $uuid = $chars;

        return $uuid;
    }
}

// php获取当前访问的完整url地址
if (!function_exists('GetCurUrl')) {
    function GetCurUrl()
    {
        $url = 'http://';
        if (isset ($_SERVER ['HTTPS']) && $_SERVER ['HTTPS'] == 'on') {
            $url = 'https://';
        }
        if ($_SERVER ['SERVER_PORT'] != '80') {
            $url .= $_SERVER ['HTTP_HOST'] . ':' . $_SERVER ['SERVER_PORT'] . $_SERVER ['REQUEST_URI'];
        } else {
            $url .= $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'];
        }

        return $url;
    }
}

if (!function_exists('isCreditNo')) {
    /**
     * 判断是否为合法的身份证号码
     * @param $mobile
     * @return int
     */
    function isCreditNo($vStr)
    {
        $vCity = array(
            '11', '12', '13', '14', '15', '21', '22',
            '23', '31', '32', '33', '34', '35', '36',
            '37', '41', '42', '43', '44', '45', '46',
            '50', '51', '52', '53', '54', '61', '62',
            '63', '64', '65', '71', '81', '82', '91'
        );
        if (!preg_match('/^([\d]{17}[xX\d]|[\d]{15})$/', $vStr)) return false;
        if (!in_array(substr($vStr, 0, 2), $vCity)) return false;
        $vStr = preg_replace('/[xX]$/i', 'a', $vStr);
        $vLength = strlen($vStr);
        if ($vLength == 18) {
            $vBirthday = substr($vStr, 6, 4) . '-' . substr($vStr, 10, 2) . '-' . substr($vStr, 12, 2);
        } else {
            $vBirthday = '19' . substr($vStr, 6, 2) . '-' . substr($vStr, 8, 2) . '-' . substr($vStr, 10, 2);
        }
        if (date('Y-m-d', strtotime($vBirthday)) != $vBirthday) return false;
        if ($vLength == 18) {
            $vSum = 0;
            for ($i = 17; $i >= 0; $i--) {
                $vSubStr = substr($vStr, 17 - $i, 1);
                $vSum += (pow(2, $i) % 11) * (($vSubStr == 'a') ? 10 : intval($vSubStr, 11));
            }
            if ($vSum % 11 != 1) return false;
        }
        return true;
    }
}

//城市格式化（1 只显示 ‘市’前面的字符  2= 转拼音）
if (!function_exists('formatCity')) {
    function formatCity($city, $type)
    {
        if ($type == '1') {
            $city_arr = explode(',', $city);
            foreach ($city_arr as $value) {
                return substr($value, 0, strrpos($value, "市"));
            }
        } else {

        }

    }
}


//腾讯AI接口签名
if (!function_exists('getAiReqSign')) {
    function getAiReqSign(array $params, string $appkey)
    {
        // 1. 字典升序排序
        ksort($params);
        // 2. 拼按URL键值对
        $str = '';
        foreach ($params as $key => $value) {
            if ($value !== '') {
                $str .= $key . '=' . urlencode($value) . '&';
            }
        }
        // 3. 拼接app_key
        $str .= 'app_key=' . $appkey;
        // 4. MD5运算+转换大写，得到请求签名
        $sign = strtoupper(md5($str));
        return $sign;
    }
}

//表情转字符串
if (!function_exists('emoji_to_string')) {
    function emoji_to_string($str)
    {
        $text = json_encode($str); //暴露出unicode
        $text = preg_replace_callback('/\\\\\\\\/i', function ($str) {
            return '\\';
        }, $text); //将两条斜杠变成一条，其他不动

        $data = json_decode($text);
        $data = "{$data}";
        return $data;
    }
}

//emoji表情转unicode
if (!function_exists('replace_emoji')) {
    function replace_emoji($str)
    {
        if (strlen($str) == 0) return "";

        $text = $str; //可能包含二进制emoji表情字符串
        $tmpStr = json_encode($text); //暴露出unicode

        $tmpStr = preg_replace_callback("/(\\\u[ed][0-9a-f]{3})/i", function ($str) {
            return addslashes($str[0]);
        }, $tmpStr);
        $text = json_decode($tmpStr);

        return $text;
    }
}

/**
 * 数据导出
 * @param $data
 * @param $head_fields
 * @param null $filename
 */
if (!function_exists('import_data')) {
    function import_data($data, $head_fields, $filename = null)
    {
        $filename = $filename ? $filename : date("YmdHis");
        if (!$data)
            return;
        $header = implode("\",\"", array_values($head_fields));
        $header = "\"" . $header;
        $header .= "\"\r\n";

        header("Expires: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("X-DNS-Prefetch-Control: off");
        header("Cache-Control: private, no-cache, must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/force-download");
        header("Content-Disposition: attachment; filename={$filename}.csv");

        $header = iconv("UTF-8", "GB2312//IGNORE", $header);
        echo $header;
        foreach ($data as $val) {
            $val = array_values($val);
            $content = "";
            $new_arr = array();
            foreach ($head_fields as $tk => $tv) {
                $value = '';
                if (array_key_exists($tk, $val)) {
                    $value = $val [$tk];
                }
                array_push($new_arr, preg_replace("/\"/", "\"\"", "\t" . $value));
            }
            $line = implode("\",\"", $new_arr);
            $line = "\"" . $line;
            $line .= "\"\r\n";
            $content .= $line;
            $content = iconv("UTF-8", "GB2312//IGNORE", $content);
            echo $content;
        }
        exit ();
    }
}