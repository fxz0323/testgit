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

use Mautic\CampaignBundle\CampaignEvents;
use Mautic\CampaignBundle\Event\CampaignBuilderEvent;
use Mautic\CampaignBundle\Event\CampaignDecisionEvent;
use Mautic\CampaignBundle\Event\CampaignExecutionEvent;
use Mautic\CoreBundle\EventListener\CommonSubscriber;

/**
 * Class CampaignSubscriber.
 */
class CampaignSubscriber extends CommonSubscriber
{

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            CampaignEvents::CAMPAIGN_ON_BUILD        => ['onCampaignBuild', 0],
            CampaignEvents::ON_EVENT_DECISION_TRIGGER => ['onCampaignTriggerDecision', 0],
        ];
    }

    /**
     * Add event triggers and actions.
     *
     * @param CampaignBuilderEvent $event
     */
    public function onCampaignBuild(CampaignBuilderEvent $event)
    {
        //Add trigger
        $pageHitTrigger = [
            'label'          => 'mautic.weixin.campaign.event',
            'description'    => 'mautic.weixin.campaign.event.descr',
            'formType'       => 'campaignevent_weixin',
            'eventName'      => CampaignEvents::ON_EVENT_DECISION_TRIGGER,
            'channel'        => 'weixin',
            'channelIdField' => 'weixins',
        ];
        $event->addDecision('weixin.event', $pageHitTrigger);
    }

    /**
     * @param CampaignExecutionEvent $event
     */
    public function onCampaignTriggerDecision(CampaignDecisionEvent $event)
    {

        $event = current(current($event->getEvents()));

        $weixinIds = $event['properties']['properties']['weixins'];
        $types = $event['properties']['properties']['type'];
        $lead = $event->getLead();

        foreach ($lead->getWeixinActions() as $action) {
            if(in_array($action->getWeixin()->getId(), $weixinIds) && in_array($action->getEvent(), $types)) {
                return $event->setDecisionAlreadyTriggered(true);
            }
        }

        return $event->setDecisionAlreadyTriggered(false);
    }
}
