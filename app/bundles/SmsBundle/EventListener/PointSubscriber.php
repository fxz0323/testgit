<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\SmsBundle\EventListener;

use Mautic\CoreBundle\EventListener\CommonSubscriber;
use Mautic\PointBundle\Event\PointBuilderEvent;
use Mautic\PointBundle\Event\TriggerBuilderEvent;
use Mautic\PointBundle\Model\PointModel;
use Mautic\PointBundle\PointEvents;
use Mautic\SmsBundle\SmsEvents;

/**
 * Class PointSubscriber.
 */
class PointSubscriber extends CommonSubscriber
{
    /**
     * @var PointModel
     */
    protected $pointModel;

    /**
     * PointSubscriber constructor.
     *
     * @param PointModel $pointModel
     */
    public function __construct(PointModel $pointModel)
    {
        $this->pointModel = $pointModel;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            PointEvents::POINT_ON_BUILD   => ['onPointBuild', 0],
            PointEvents::TRIGGER_ON_BUILD => ['onTriggerBuild', 0],
            SmsEvents::SMS_ON_CLICK_URL    => ['onUrlClick', 0],
        ];
    }

    /**
     * @param PointBuilderEvent $event
     */
    public function onPointBuild(PointBuilderEvent $event)
    {
        $action = [
            'group'    => 'mautic.sms.actions',
            'label'    => 'mautic.sms.point.action.url',
            'formType' => 'smsopen_list',
        ];

        $event->addAction('sms.url', $action);
    }

    /**
     * @param TriggerBuilderEvent $event
     */
    public function onTriggerBuild(TriggerBuilderEvent $event)
    {
        $sendEvent = [
            'group'           => 'mautic.sms.point.trigger',
            'label'           => 'mautic.sms.point.trigger.sendsms',
            'callback'        => ['\\Mautic\\SmsBundle\\Helper\\PointEventHelper', 'sendSms'],
            'formType'        => 'smssend_list',
//            'formTypeOptions' => ['update_select' => 'pointtriggerevent_properties_email'],
            'formTheme'       => 'MauticEmailBundle:FormTheme\EmailSendList',
        ];

        $event->addEvent('sms.send', $sendEvent);
    }

    public function onUrlClick()
    {
        $this->pointModel->triggerAction('sms.url');
    }
}
