<?php
/**
 * Created by PhpStorm.
 * User: meng
 * Date: 17-11-9
 * Time: 下午10:50
 */

namespace MauticPlugin\WeixinBundle\Event;

class Event extends \Symfony\Component\EventDispatcher\Event
{

    private $msg;

    private $weixin;

    public function __construct($weixin, $msg)
    {
        $this->msg = $msg;
        $this->weixin = $weixin;
    }

    /**
     * @return mixed
     */
    public function getMsg()
    {
        return $this->msg;
    }

    /**
     * @return mixed
     */
    public function getWeixin()
    {
        return $this->weixin;
    }

}