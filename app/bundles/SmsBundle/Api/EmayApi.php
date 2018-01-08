<?php
/**
 * Created by PhpStorm.
 * User: hackc
 * Date: 2017-07-14
 * Time: 15:37
 */

namespace Mautic\SmsBundle\Api;


use Mautic\CoreBundle\Helper\PhoneNumberHelper;
use Mautic\PageBundle\Model\TrackableModel;
use Mautic\PluginBundle\Helper\IntegrationHelper;
use Monolog\Logger;

class EmayApi extends AbstractSmsApi
{
    /**
     * @var \Chxj1992\YimeiSms\App\Client;
     */
    protected $client;

    /**
     * @var Logger
     */
    protected $logger;

    protected $keys;


    /**亿美软通接口 构造函数
     * EmayApi constructor.
     * @param TrackableModel $pageTrackableModel 追踪模型
     * @param PhoneNumberHelper $phoneNumberHelper 电话号码助手
     * @param IntegrationHelper $integrationHelper 积分助手
     * @param Logger $logger 记录
     */
    public function __construct(TrackableModel $pageTrackableModel, PhoneNumberHelper $phoneNumberHelper,IntegrationHelper $integrationHelper,Logger $logger)
    {
        $this->logger = $logger;

        $integration = $integrationHelper->getIntegrationObject('Emay');

        if($integration && $integration->getIntegrationSettings()->getIsPublished()) {
            $keys = $integration->getDecryptedApiKeys();
            $this->keys = $keys;

            $config =  [
                'gwUrl' => 'http://hprpt2.eucp.b2m.cn:8080/sdk/SDKService?wsdl',
                'serialNumber' => $keys['username'],
                'password' => $keys['password'],
                'sessionKey' => $keys['password'],
            ];
//            加载亿美通的配置信息
            $this->client = \Chxj1992\YimeiSms\App\ClientFactory::instance($config);
        }

        parent::__construct($pageTrackableModel);
    }

    /**
     * @param $number
     * @return mixed
     */
    protected function sanitizeNumber($number)
    {
        return $number;
    }
    /*发送信息
     *
     * */
    public function sendSms($number, $content, $type = 1)
    {
        if ($type == 2) {
            $config =  [
                'gwUrl' => 'http://hprpt2.eucp.b2m.cn:8080/sdk/SDKService?wsdl',
                'serialNumber' => $this->keys['username2'],
                'password' => $this->keys['password2'],
                'sessionKey' => $this->keys['password2'],
            ];
            $this->client = \Chxj1992\YimeiSms\App\ClientFactory::instance($config);

        }

        if($number == null){
            return false;
        }

        $ret = $this->client->sendSMS([$number],$content);
//        dump($ret);
        if($ret == "0")
            return true;
        else
            return $ret;
    }
    /*
     * 得到上行短信状态报告  (注:此方法必须为已登录状态下方可操作)
     *
     * */
    public function getMo()
    {
        $moResult = $this->client->getMO();
     
        /*     echo "发送者附加码:".$mo->getAddSerial();
        *      echo "接收者附加码:".$mo->getAddSerialRev();
        *      echo "通道号:".$mo->getChannelnumber();
        *      echo "手机号:".$mo->getMobileNumber();
        *      echo "发送时间:".$mo->getSentTime();
        *      echo "短信内容:".$mo->getSmsContent();
        * }
        */
        return $moResult;
    }
}