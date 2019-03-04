<?php
/**
 * 短信服务
 * Created by PhpStorm.
 * User: guochao
 * Date: 2018/7/18
 * Time: 上午11:22
 */

use Qcloud\Sms\SmsSingleSender;


class Smslib
{
    public function sendSms(string $phone, array $params)
    {
        try {
            $sender = new SmsSingleSender('1400114473', 'fbdd99d56c89e0dc0772bc25c15333cb');
            $templateId = '158412';
            $result = $sender->sendWithParam("86", $phone, $templateId, $params);
            $response = json_decode($result);
            if (isset($response->result) && $response->result === 0) {
                return true;
            } else {
                throw new \Exception($result);
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}