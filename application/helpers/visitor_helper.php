<?php
//这个类似用来获取访客信息的
//方便统计
if (!function_exists('getIp'))
{
 //获取访客ip
  function getIp()
  {
    $ip=false;
    if(!empty($_SERVER["HTTP_CLIENT_IP"])){
      $ip = $_SERVER["HTTP_CLIENT_IP"];
    }
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
          if ($ip) { array_unshift($ips, $ip); $ip = FALSE; }
         for ($i = 0; $i < count($ips); $i++) {
          if (!eregi ("^(10│172.16│192.168).", $ips[$i])) {
           $ip = $ips[$i];
           break;
          }
         }
    }
    return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
  }
}

if (!function_exists('findCityByIp'))
{
 //根据ip获取城市、网络运营商等信息
 function findCityByIp($ip){
  $data = file_get_contents('http://ip.taobao.com/service/getIpInfo.php?ip='.$ip);
  return json_decode($data,$assoc=true);
 }
}

if (!function_exists('getBrowser'))
{
 //获取用户浏览器类型
 function getBrowser(){
  $agent=$_SERVER["HTTP_USER_AGENT"];
  if(strpos($agent,'MSIE')!==false || strpos($agent,'rv:11.0')) //ie11判断
   return "ie";
  else if(strpos($agent,'Firefox')!==false)
   return "firefox";
  else if(strpos($agent,'Chrome')!==false)
   return "chrome";
  else if(strpos($agent,'Opera')!==false)
   return 'opera';
  else if((strpos($agent,'Chrome')==false)&&strpos($agent,'Safari')!==false)
   return 'safari';
  else
   return 'unknown';
 }
}

if (!function_exists('getFromPage'))
{
 //获取网站来源
 function getFromPage(){
  return $_SERVER['HTTP_REFERER']??'';
 }
}



