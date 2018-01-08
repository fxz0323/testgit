<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\WeixinBundle\Subscriber;

use Mautic\CoreBundle\EventListener\CommonSubscriber;
use Mautic\FormBundle\Event\SubmissionEvent;
use Mautic\FormBundle\FormEvents;
use Mautic\LeadBundle\Model\LeadModel;
use Mautic\PointBundle\Event\PointBuilderEvent;
use Mautic\PointBundle\Model\PointModel;
use Mautic\PointBundle\PointEvents;
use MauticPlugin\WeixinBundle\Event\Event;
use MauticPlugin\WeixinBundle\Event\Events;

/**
 * Class PointSubscriber.
 */
class PointSubscriber extends CommonSubscriber
{
    /**
     * @var PointModel
     */
    protected $pointModel;
    protected $em;

    /**
     * PointSubscriber constructor.
     *
     * @param PointModel $pointModel
     */
    public function __construct(PointModel $pointModel, $doctrine)
    {
        $this->pointModel = $pointModel;
        $this->em = $doctrine->getManager();
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            PointEvents::POINT_ON_BUILD => ['onPointBuild', 0],
            Events::WEIXIN_UNSUBSCRIBE  => ['onUnsubscribe', 0],
            Events::WEIXIN_SUBSCRIBE  => ['onSubscribe', 0],
            Events::WEIXIN_SCAN  => ['onScan', 0],
            Events::WEIXIN_CLICK  => ['onClick', 0],
            Events::WEIXIN_TEXT  => ['onText', 0],
        ];
    }

    /**
     * @param PointBuilderEvent $event
     */
    public function onPointBuild(PointBuilderEvent $event)
    {
        $action = [
            'group'       => 'mautic.weixin.point.action',
            'label'       => 'mautic.weixin.point.action.unsubscribe',
            'description' => 'mautic.weixin.point.action.unsubscribe_descr',
            'formType'    => 'pointaction_weixin',
        ];
        $event->addAction('weixin.unsubscribe', $action);

        $action = [
            'group'       => 'mautic.weixin.point.action',
            'label'       => 'mautic.weixin.point.action.subscribe',
            'description' => 'mautic.weixin.point.action.subscribe_descr',
            'formType'    => 'pointaction_weixin',
        ];
        $event->addAction('weixin.subscribe', $action);

        $action = [
            'group'       => 'mautic.weixin.point.action',
            'label'       => 'mautic.weixin.point.action.scan',
            'description' => 'mautic.weixin.point.action.scan_descr',
            'formType'    => 'pointaction_weixin',
        ];
        $event->addAction('weixin.scan', $action);

        $action = [
            'group'       => 'mautic.weixin.point.action',
            'label'       => 'mautic.weixin.point.action.click',
            'description' => 'mautic.weixin.point.action.click_descr',
            'formType'    => 'pointaction_weixin',
        ];
        $event->addAction('weixin.click', $action);

        $action = [
            'group'       => 'mautic.weixin.point.action',
            'label'       => 'mautic.weixin.point.action.text',
            'description' => 'mautic.weixin.point.action.text_descr',
            'formType'    => 'pointaction_weixin',
        ];
        $event->addAction('weixin.text', $action);
    }

    public function onSubscribe(Event $event)
    {
        $message = $event->getMsg();
        $leads = $this->em->getRepository('Mautic\LeadBundle\Entity\Lead')->getLeadsByFieldValue('wechat_openid', $message['FromUserName']);
        if(empty(key($leads))) {
            return;
        }
        $lead = $this->em->getRepository('Mautic\LeadBundle\Entity\Lead')->find(key($leads));
        $this->pointModel->triggerAction('weixin.subscribe', $event->getMsg(), null, $lead);
    }

    public function onUnsubscribe(Event $event)
    {
        $message = $event->getMsg();
        $leads = $this->em->getRepository('Mautic\LeadBundle\Entity\Lead')->getLeadsByFieldValue('wechat_openid', $message['FromUserName']);
        if(empty(key($leads))) {
            return;
        }
        $lead = $this->em->getRepository('Mautic\LeadBundle\Entity\Lead')->find(key($leads));
        $this->pointModel->triggerAction('weixin.unsubscribe', $event->getMsg(), null, $lead);
    }

    public function onScan(Event $event)
    {
        $message = $event->getMsg();
        $leads = $this->em->getRepository('Mautic\LeadBundle\Entity\Lead')->getLeadsByFieldValue('wechat_openid', $message['FromUserName']);
        if(empty(key($leads))) {
            return;
        }
        $lead = $this->em->getRepository('Mautic\LeadBundle\Entity\Lead')->find(key($leads));
        $this->pointModel->triggerAction('weixin.scan', $event->getMsg(), null, $lead);
    }

    public function onClick(Event $event)
    {
        $message = $event->getMsg();
        $leads = $this->em->getRepository('Mautic\LeadBundle\Entity\Lead')->getLeadsByFieldValue('wechat_openid', $message['FromUserName']);
        if(empty(key($leads))) {
            return;
        }
        $lead = $this->em->getRepository('Mautic\LeadBundle\Entity\Lead')->find(key($leads));
        $this->pointModel->triggerAction('weixin.click', $event->getMsg(), null, $lead);
    }

    public function onText(Event $event)
    {
        $message = $event->getMsg();
        $leads = $this->em->getRepository('Mautic\LeadBundle\Entity\Lead')->getLeadsByFieldValue('wechat_openid', $message['FromUserName']);
        if(empty(key($leads))) {
            return;
        }
        $lead = $this->em->getRepository('Mautic\LeadBundle\Entity\Lead')->find(key($leads));
        $this->pointModel->triggerAction('weixin.text', $event->getMsg(), null, $lead);
    }
}
