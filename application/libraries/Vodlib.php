<?php
/**
 * 点播服务
 * Created by PhpStorm.
 * User: huzhenping
 * Date: 2018/7/18
 * Time: 上午11:22
 */

use Vod\VodApi;

class Vodlib
{

    protected $vod_config = false;

    public function __construct ()
    {
//        $this->vod_config = array(
//            'region' => "sh",
//            'secretId' => 'AKIDkMlwufBYfZUMD1qYGvX5XmocEcnll6Wj',
//            'secretKey' => 'HNI0VnsjNp45sSpztXu1sSnrfDVWIn20',
//            'url'=>"https://vod.api.qcloud.com/v2/index.php?",
//            'host'=>"vod.api.qcloud.com/v2/index.php?",
//        );

        VodApi::initConf("AKIDkMlwufBYfZUMD1qYGvX5XmocEcnll6Wj", "HNI0VnsjNp45sSpztXu1sSnrfDVWIn20");
    }

    //视频上传
    public function applyUpload($filePath){
        $result = VodApi::upload(
            array (
                'videoPath' => $filePath
            ),
            array (
                'storageRegion' => 'sh'
            )
        );
        return $result;
//        echo "upload to vod result: " . json_encode($result) . "\n";exit;
    }

    //获取视频解密密钥
    public function getDrmDataKey($edk ='1'){
        /*
         * 建议： TODO待完善
         *由于EDK所对应的的DK总是固定的，故而APP后台可以缓存（甚至永久保存）EDK和DK之间的对应关系，以降低调用KMS系统的次数（即减少架构图中第5步的调用次数）；
         *APP后台给客户端的应答，可以增加HTTP缓存控制参数（例如Cache-Control），以降低客户端到APP后台获取DK的次数（即减少架构图中第4步的调用次数）。
         *
         */
        $config = array(
            'SecretId'       => 'AKIDkMlwufBYfZUMD1qYGvX5XmocEcnll6Wj',
            'SecretKey'      => 'HNI0VnsjNp45sSpztXu1sSnrfDVWIn20',
            'RequestMethod'  => 'GET',
            'DefaultRegion'  => 'sh');

        $vod = QcloudApi::load(QcloudApi::MODULE_VOD, $config);

        $package = array('edkList.0'=>$edk,'offset' => 0, 'limit' => 3, 'SignatureMethod' =>'HmacSHA256');

//        print_r($package);exit;
        //请求接口
        $a = $vod->DescribeDrmDataKey($package);
        //获取请求的URL -可以用来DEBUG
//        $a = $vod->generateUrl('DescribeDrmDataKey', $package);

        if ($a === false) {
            $error = $vod->getError();
            echo "Error code:" . $error->getCode() . ".\n";
            echo "message:" . $error->getMessage() . ".\n";
            echo "ext:" . var_export($error->getExt(), true) . ".\n";
        } else {
            return $vod->getLastResponse();
        }
//
//        echo "\nRequest :" . $vod->getLastRequest();
//        echo "\nResponse :" . $vod->getLastResponse();
//        echo "\n";

    }

    //获取视频详情
    public function getAudioInfo($fileId = ''){
        $config = array(
            'SecretId'       => 'AKIDkMlwufBYfZUMD1qYGvX5XmocEcnll6Wj',
            'SecretKey'      => 'HNI0VnsjNp45sSpztXu1sSnrfDVWIn20',
            'RequestMethod'  => 'GET',
            'DefaultRegion'  => 'sh');

        $vod = QcloudApi::load(QcloudApi::MODULE_VOD, $config);
        $package = array('fileId'=>$fileId,'infoFilter.0'=>'basicInfo');
        $a = $vod->GetVideoInfo($package);
        //获取请求的URL -可以用来DEBUG
//        $a = $vod->generateUrl('GetVideoInfo', $package);

        if ($a === false) {
            $error = $vod->getError();
            echo "Error code:" . $error->getCode() . ".\n";
            echo "message:" . $error->getMessage() . ".\n";
            echo "ext:" . var_export($error->getExt(), true) . ".\n";
        } else {
            return $vod->getLastResponse();
        }
    }
}