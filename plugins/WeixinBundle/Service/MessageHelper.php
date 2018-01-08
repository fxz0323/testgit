<?php
/**
 * Created by PhpStorm.
 * User: meng
 * Date: 17-10-23
 * Time: 下午10:11
 */

namespace MauticPlugin\WeixinBundle\Service;

use MauticPlugin\WeixinBundle\Entity\Message;
use MauticPlugin\WeixinBundle\Entity\Rule;
use MauticPlugin\WeixinBundle\Entity\Weixin;

class MessageHelper
{
    private $weixinApi;
    private $rootDir;

    public function __construct(Api $weixinApi, $rootDir)
    {
        $this->weixinApi = $weixinApi;
        $this->rootDir = $rootDir;
    }

    public function handleMessageImage(Weixin $weixin, Message $message, $file)
    {
        if ($file && in_array($message->getMsgType(), [Message::MSG_TYPE_IMG, Message::MSG_TYPE_IMGTEXT])) {

            $result = $this->weixinApi->uploadImage($weixin, $file);
            $message->setImageId($result['media_id']);
            $message->setImageUrl($result['url']);

            $fileName = md5(uniqid()) . '.' . $file->guessExtension();

            $file->move(
                $this->rootDir . '/../uploads',
                $fileName
            );

            $message->setImage('uploads/' . $fileName);
        }
    }
}