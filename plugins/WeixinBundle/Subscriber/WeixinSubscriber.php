<?php
/**
 * Created by PhpStorm.
 * User: meng
 * Date: 17-11-9
 * Time: 下午11:00
 */

namespace MauticPlugin\WeixinBundle\Subscriber;

use Mautic\LeadBundle\Entity\Lead;
use Mautic\LeadBundle\Entity\Tag;
use MauticPlugin\WeixinBundle\Entity\LeadWeixin;
use MauticPlugin\WeixinBundle\Entity\LeadWeixinAction;
use MauticPlugin\WeixinBundle\Event\Event;
use MauticPlugin\WeixinBundle\Event\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class WeixinSubscriber implements EventSubscriberInterface
{

    private $em;
    private $api;
    private $leadModel;

    public function __construct($doctrine, $api, $leadModel)
    {
        $this->api = $api;
        $this->em = $doctrine->getManager();
        $this->leadModel = $leadModel;
    }

    public static function getSubscribedEvents()
    {
        return [
            Events::WEIXIN_SUBSCRIBE => ['onSubscribe', 100],
            Events::WEIXIN_UNSUBSCRIBE => ['onUnSubscribe', 100],
            Events::WEIXIN_SCAN => ['onScan', 100],
            Events::WEIXIN_CLICK => ['onClick', 100],
            Events::WEIXIN_TEXT => ['onText', 100],
            KernelEvents::TERMINATE => 'onTerminate'
        ];
    }

    public function onSubscribe(Event $event)
    {
        $weixin = $event->getWeixin();
        $message = $event->getMsg();

        $leads = $this->em->getRepository('Mautic\LeadBundle\Entity\Lead')->getLeadsByFieldValue('wechat_openid', $message['FromUserName']);
        if (!empty(key($leads))) {
            $lead = $this->em->getRepository('Mautic\LeadBundle\Entity\Lead')->find(key($leads));
        }

        $userInfos = $this->api->getUserInfos($weixin, $message['FromUserName']);

        if (!isset($lead)) {
            $lead = new Lead();

            $this->leadModel->setFieldValues($lead, [
                'firstname' => $userInfos['nickname'],
                'wechat_openid' => $userInfos['openid'],
                'wechat_nickname' => $userInfos['nickname'],
                'city' => $userInfos['city'],
                'province' => $userInfos['province'],
                'origin_from' => $weixin->getAccountName() . '公众号导入'
            ], true);

            $lead->setDateIdentified(new \DateTime());
        }

        $leadWeixin = $this->em->getRepository('MauticPlugin\WeixinBundle\Entity\LeadWeixin')
            ->findOneBy(['contact' => $lead, 'weixin' => $weixin]);
        if(!$leadWeixin) {
            $leadWeixin = new LeadWeixin();
            $leadWeixin->setContact($lead);
            $leadWeixin->setWeixin($weixin);
            $leadWeixin->setOpenid($message['FromUserName']);
            $this->em->persist($leadWeixin);
        }

        if (isset($message['EventKey']) && false !== strstr($message->EventKey, 'qrscene')) {
            $qrnb = ltrim($message->EventKey, 'qrscene_');
            $qrcode = $this->em->getRepository('MauticPlugin\WeixinBundle\Entity\Qrcode')->findOneBy([
                'weixin' => $weixin,
                'nb' => $qrnb,
            ]);
            if ($qrcode->getLeadField1()) {
                $lead->addUpdatedField($qrcode->getLeadField1(), $qrcode->getLeadField1Value());
            }
            if ($qrcode->getLeadField2()) {
                $lead->addUpdatedField($qrcode->getLeadField2(), $qrcode->getLeadField2Value());
            }
            if ($qrcode->getTag()) {
                $tag = $this->em->getRepository('Mautic\LeadBundle\Entity\Tag')->findOneByTag($qrcode->getTag());
                if(!$tag) {
                    $tag = new Tag();
                    $tag->setTag($qrcode->getTag());
                    $this->em->persist($tag);
                }
                $lead->addTag($tag);
            }
        }

        $lead->setOwner($weixin->getOwner());
        $this->leadModel->saveEntity($lead);
        $time = new \DateTime('@' . $userInfos['subscribe_time']);
        $this->createWeixinAction($weixin, $lead, $message, $time, Events::WEIXIN_SUBSCRIBE);
    }

    public function onUnsubscribe(Event $event)
    {
        $weixin = $event->getWeixin();
        $message = $event->getMsg();
        $leads = $this->em->getRepository('Mautic\LeadBundle\Entity\Lead')->getLeadsByFieldValue('wechat_openid', $message['FromUserName']);
        if (empty(key($leads))) {
            return;
        }
        $lead = $this->em->getRepository('Mautic\LeadBundle\Entity\Lead')->find(key($leads));

        $this->createWeixinAction($weixin, $lead, $message, new \DateTime(), Events::WEIXIN_UNSUBSCRIBE);
    }

    public function onScan(Event $event)
    {
        $weixin = $event->getWeixin();
        $message = $event->getMsg();
        $leads = $this->em->getRepository('Mautic\LeadBundle\Entity\Lead')->getLeadsByFieldValue('wechat_openid', $message['FromUserName']);
        if (empty(key($leads))) {
            return;
        }
        $lead = $this->em->getRepository('Mautic\LeadBundle\Entity\Lead')->find(key($leads));

        $this->createWeixinAction($weixin, $lead, $message, new \DateTime(), Events::WEIXIN_SCAN);
    }

    public function onClick(Event $event)
    {
        $weixin = $event->getWeixin();
        $message = $event->getMsg();
        $leads = $this->em->getRepository('Mautic\LeadBundle\Entity\Lead')->getLeadsByFieldValue('wechat_openid', $message['FromUserName']);
        if (empty(key($leads))) {
            return;
        }
        $lead = $this->em->getRepository('Mautic\LeadBundle\Entity\Lead')->find(key($leads));

        $this->createWeixinAction($weixin, $lead, $message, new \DateTime(), Events::WEIXIN_CLICK);
    }

    public function onText(Event $event)
    {
        $weixin = $event->getWeixin();
        $message = $event->getMsg();
        $leads = $this->em->getRepository('Mautic\LeadBundle\Entity\Lead')->getLeadsByFieldValue('wechat_openid', $message['FromUserName']);
        if (empty(key($leads))) {
            return;
        }
        $lead = $this->em->getRepository('Mautic\LeadBundle\Entity\Lead')->find(key($leads));

        $this->createWeixinAction($weixin, $lead, $message, new \DateTime(), Events::WEIXIN_TEXT);
    }

    private function createWeixinAction($weixin, $lead, $message, $time, $event)
    {
        $leadWeixinAction = new LeadWeixinAction();
        $leadWeixinAction->setWeixin($weixin);
        $leadWeixinAction->setContact($lead);
        $leadWeixinAction->setMessage(json_decode(json_encode($message), true));
        $leadWeixinAction->setEvent($event);
        $leadWeixinAction->setTime($time);

        $this->em->persist($leadWeixinAction);
        $this->em->flush();
    }

    public function onTerminate(PostResponseEvent $event)
    {
        $route = $event->getRequest()->get('_route');
        if (in_array($route, ['mautic_weixin_open_auth_return', 'mautic_weixin_sych_users'])) {
            ini_set('max_execution_time', -1);
            $currentWeixinId = $event->getRequest()->get('id');
            $currentWeixin = $this->em->getRepository('MauticPlugin\WeixinBundle\Entity\Weixin')->find($currentWeixinId);
            if ($currentWeixin) {
                $this->api->sychUsers($currentWeixin);
            }
        }
    }
}

