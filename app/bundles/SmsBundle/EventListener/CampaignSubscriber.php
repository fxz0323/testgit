<?php

/*
 * @copyright   2016 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\SmsBundle\EventListener;

use Mautic\CampaignBundle\CampaignEvents;
use Mautic\CampaignBundle\Event\CampaignBuilderEvent;
use Mautic\CampaignBundle\Event\CampaignDecisionEvent;
use Mautic\CampaignBundle\Event\CampaignExecutionEvent;
use Mautic\CoreBundle\EventListener\CommonSubscriber;
use Mautic\EmailBundle\Form\Type\CampaignEventSmsType;
use Mautic\PluginBundle\Helper\IntegrationHelper;
use Mautic\SmsBundle\Model\SmsModel;
use Mautic\SmsBundle\SmsEvents;

/**
 * Class CampaignSubscriber.
 */
class CampaignSubscriber extends CommonSubscriber
{
    /**
     * @var IntegrationHelper
     */
    protected $integrationHelper;

    /**
     * @var SmsModel
     */
    protected $smsModel;

    /**
     * CampaignSubscriber constructor.
     *
     * @param IntegrationHelper $integrationHelper
     * @param SmsModel $smsModel
     */
    public function __construct(
        IntegrationHelper $integrationHelper,
        SmsModel $smsModel
    )
    {
        $this->integrationHelper = $integrationHelper;
        $this->smsModel = $smsModel;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            CampaignEvents::CAMPAIGN_ON_BUILD => ['onCampaignBuild', 0],
            SmsEvents::ON_CAMPAIGN_TRIGGER_ACTION => ['onCampaignTriggerAction', 0],
            CampaignEvents::ON_EVENT_DECISION_TRIGGER => ['onCampaignSmsTriggerDecision', 0]
        ];
    }

    /**
     * @param CampaignBuilderEvent $event
     */
    public function onCampaignBuild(CampaignBuilderEvent $event)
    {
//        $integration = $this->integrationHelper->getIntegrationObject('Twilio');

//        if ($integration && $integration->getIntegrationSettings()->getIsPublished()) {

        $event->addDecision(
            'sms.event',
            [
                'label' => 'mautic.sms.campaign.event',
                'description' => 'mautic.sms.campaign.event_descr',
                'eventName' => CampaignEvents::ON_EVENT_DECISION_TRIGGER,
                'formType' => 'campaignevent_sms',
            ]
        );

        $event->addAction(
            'sms.send_text_sms',
            [
                'label' => 'mautic.campaign.sms.send_text_sms',
                'description' => 'mautic.campaign.sms.send_text_sms.tooltip',
                'eventName' => SmsEvents::ON_CAMPAIGN_TRIGGER_ACTION,
                'formType' => 'smssend_list',
                'formTypeOptions' => ['update_select' => 'campaignevent_properties_sms'],
                'formTheme' => 'MauticSmsBundle:FormTheme\SmsSendList',
                'timelineTemplate' => 'MauticSmsBundle:SubscribedEvents\Timeline:index.html.php',
                'channel' => 'sms',
                'channelIdField' => 'sms',
            ]
        );
        $event->addAction(
            'sms.send_internal_sms',
            [
                'label' => 'mautic.campaign.sms.send_internal_sms',
                'description' => 'mautic.campaign.sms.send_internal_sms.tooltip',
                'eventName' => SmsEvents::ON_CAMPAIGN_TRIGGER_ACTION,
                'formType' => 'smssendinternal_list',
                'formTypeOptions' => ['update_select' => 'campaignevent_properties_sms'],
                'formTheme' => 'MauticSmsBundle:FormTheme\SmsSendList',
                'timelineTemplate' => 'MauticSmsBundle:SubscribedEvents\Timeline:index.html.php',
                'channel' => 'sms',
                'channelIdField' => 'sms',
            ]
        );
//        }
    }

    /**
     * @param CampaignExecutionEvent $event
     *
     * @return mixed
     */
    public function onCampaignTriggerAction(CampaignExecutionEvent $event)
    {
        $lead = $event->getLead();
        $smsId = (int)$event->getConfig()['sms'];
        $telephone = isset($event->getConfig()['telephone']) ? $event->getConfig()['telephone'] : null;
        $sms = $this->smsModel->getEntity($smsId);

        if (!$sms) {
            return $event->setFailed('mautic.sms.campaign.failed.missing_entity');
        }

        if(!empty($telephone)) {
            $lead->setMobile($telephone);
        }

        $result = $this->smsModel->sendSms($sms, $lead, ['channel' => ['campaign.event', $event->getEvent()['id']]])[$lead->getId()];

        if ('Authenticate' === $result['status']) {
            // Don't fail the event but reschedule it for later
            return $event->setResult(false);
        }

        if (!empty($result['sent'])) {
            $event->setChannel('sms', $sms->getId());
            $event->setResult($result);
        } else {
            $result['failed'] = true;
            $result['reason'] = $result['status'];
            $event->setResult($result);
        }
    }

    public function onCampaignSmsTriggerDecision(CampaignDecisionEvent $event)
    {
        $event = current(current($event->getEvents()));

        $smsids = $event['properties']['properties']['smss'];
        $type = $event['properties']['properties']['type'];
        $lead = $event->getLead();

        $smsRepo = $this->em->getRepository('Mautic\SmsBundle\Entity\Sms');

        switch ($type) {
            case CampaignEventSmsType::RECEIVE:
                foreach ($smsids as $smsid) {
                    $smsStatis = $this->smsModel->getSmsStatByLeadId($smsid, $lead->getId());
                    foreach ($smsStatis as $smsStati) {
                        return $event->setDecisionAlreadyTriggered(true);
                    }
                }
                return $event->setDecisionAlreadyTriggered(false);
            case CampaignEventSmsType::DNC:
                return $event->setDecisionAlreadyTriggered(false);
            case CampaignEventSmsType::CLICK:
                foreach ($smsids as $smsid) {
                    $clicks = $this->smsModel->getSmsClickStats($smsid);
                    if(count($clicks)) {
                        return $event->setDecisionAlreadyTriggered(true);
                    }
                }
                return $event->setDecisionAlreadyTriggered(false);
        }
    }
}
