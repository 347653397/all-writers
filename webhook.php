<?php
/**
 * Created by PhpStorm.
 * User: huzhenping
 * Date: 8/28
 * Time: 11:00
 */

error_reporting(1);

$target = '/www/wwwroot/test.startechsoft.cn'; // 生产环境web目录

$token = 'woaiguochao';
$wwwUser = 'www';
$wwwGroup = 'www';

#$json = json_decode(file_get_contents('php://input'), true);

#if (empty($json['token']) || $json['token'] !== $token) {
#    exit('error request');
#}

$cmd = "cd $target && sudo git checkout . && sudo git checkout develop && sudo git pull";

$result = shell_exec($cmd);

echo $result;exit;
//$cmd = "cd $target && git pull";
//
//echo shell_exec($cmd);
//test1123111   123123
