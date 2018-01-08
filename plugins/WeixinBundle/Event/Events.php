<?php
/**
 * Created by PhpStorm.
 * User: meng
 * Date: 17-11-9
 * Time: 下午10:50
 */

namespace MauticPlugin\WeixinBundle\Event;

class Events
{

    const WEIXIN_SUBSCRIBE = 'weixin.subscribe';
    const WEIXIN_UNSUBSCRIBE = 'weixin.unsubscribe';
    const WEIXIN_SCAN = 'weixin.scan';
    const WEIXIN_TEXT = 'weixin.text';
    const WEIXIN_CLICK = 'weixin.click';
}
