<?php
namespace App\Libraries;

use Response;
use itbdw\QiniuStorage\QiniuStorage;
use Qiniu\Auth;

class Qiniu
{
	public static function getToken(){
		$accessKey = 'm93pGG2UN4gw5WvAXbK2nOE-rB0EqsXQs7uqhkPg';
		$secretKey = 'ixkiDC_lTopAmuaohHY6iTs1QBWDtZi468xQes_l';
		$auth = new Auth($accessKey, $secretKey);
		
		// 空间名  http://developer.qiniu.com/docs/v6/api/overview/concepts.html#bucket
		$bucket = 'yppphoto';
		
		// 生成上传Token
		$token = $auth->uploadToken($bucket);
		return $token;
	}
	
	public static function uploadImage($file){

		$disk = QiniuStorage::disk('qiniu');

		if(!$file->isValid()){
			echo json_encode(array(
				'status' => false,
				'msg' => "上传的文件无效！",
			));
			exit;
		}

		$allowed_extensions = ["png", "jpg", "jpeg"];
		if ($file->getClientOriginalExtension() && !in_array(strtolower($file->getClientOriginalExtension()), $allowed_extensions)) {
			echo json_encode(array(
				'status' => false,
				'msg' => "上传文件格式不正确！",
			));
			exit;
		}

		$savePath = 'uploads/images/';
		$extension = $file->getClientOriginalExtension();
		$fileName = uuid().'.'.$extension;
		$file->move($savePath, $fileName);

		$res = $disk->putFile("upload/".$fileName, $savePath.$fileName);

		if(is_array($res) && $res['hash'] && ($res['key'] == "upload/".$fileName)){
			unlink($savePath.$fileName);//删除本地文件
			return "upload/".$fileName;
		}else{
			echo json_encode(array(
				'status' => false,
				'msg' => "上传文件出错！",
			));
			exit;
		}
	}

	//本地画图上传七牛
	public static function localUpload($local_file,$token)
	{
		$disk = QiniuStorage::disk('qiniu');
		$fileName = "{$token}.png";
		$res = $disk->putFile("upload/".$fileName, $local_file);
		if(is_array($res) && $res['hash'] && ($res['key'] == "upload/".$fileName)){
			unlink($local_file);//删除本地文件
			return "upload/".$fileName;
		}else{
			return '';
		}
	}

    //上传图片base64编码
    public static function uploadBasecode($baseCodeData){
        $disk = QiniuStorage::disk('qiniu');

        if(isset($baseCodeData) && $baseCodeData) {
            $key = 'img_' . date("YmdHis") . rand(1000, 9999) . '.jpg';
            $res = $disk->putFile("upload/".$key, $baseCodeData);
            if(is_array($res) && $res['hash'] && ($res['key'] == "upload/".$key)){
                return "upload/".$key;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
}