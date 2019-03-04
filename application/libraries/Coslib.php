<?php
/**
 * 存储服务
 * Created by PhpStorm.
 * User: huzhenping
 * Date: 2018/7/18
 * Time: 上午11:22
 */

use Qcloud\Cos\Client;

class Coslib
{
    protected $cosClient;

    public function __construct()
    {
        $config = array(
            'region' => "ap-shanghai",
            'credentials' => array(
                'appId' => '1256016893',
                'secretId' => 'AKIDkMlwufBYfZUMD1qYGvX5XmocEcnll6Wj',
                'secretKey' => 'HNI0VnsjNp45sSpztXu1sSnrfDVWIn20',
            ),
        );

        $this->cosClient = new Client($config);
    }

    # 上传文件
    ## putObject(上传接口，最大支持上传5G文件)
    public function cos_upload($data)
    {
        set_time_limit(0);//防止上传大文件超时
        try {
            $i = 1;
            $imgName = array();
            foreach ($data as $key => $item) {
                if ($item['error'] == 0) {
                    $arr = explode(".", $item['name']);
                    $hzName = $arr[count($arr) - 1];//后缀名
                    if (!in_array($hzName, ['jpg', 'jpeg', 'png', 'gif'])) {
                        continue;
                    }
                    $fixName = time() . "-{$i}.{$arr[count($arr) - 1]}";
                    $result = $this->cosClient->putObject(array(
                        'Bucket' => 'renrenbianju',
                        'Key' => $fixName,
                        'Body' => fopen($item['tmp_name'], 'rb'),
                        'ContentDisposition' => 'inline;filename=FileName.txt'
                    ));
                    if ($result) {
                        $imgName[$key] = $fixName;
                    }
                    $i++;
                }
            }
            return $imgName;

        } catch (\Exception $e) {
            echo "$e\n";
        }
    }


    public function getUploadSign()
    {
        return $this->cosClient->createPresignedUrl($this->cosClient->put(),7200);
    }

    public function getUploadSign1()
    {
        $bucket =  'renrenbianju-1256016893';
        $key = 'hello.txt';

        return $this->cosClient->getObjectUrl($bucket, $key, 7200);
    }
}