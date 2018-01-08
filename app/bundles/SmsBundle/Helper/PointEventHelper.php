<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\SmsBundle\Helper;

use Mautic\CoreBundle\Factory\MauticFactory;
use Mautic\LeadBundle\Entity\Lead;

/**
 * Class PointEventHelper.
 */
class PointEventHelper
{
    /**
     * @param               $event
     * @param Lead $lead
     * @param MauticFactory $factory
     *
     * @return bool
     */
    public static function sendSms($event, Lead $lead, MauticFactory $factory)
    {
        $properties = $event['properties'];
        $smsId = (int)$properties['sms'];

        /** @var \Mautic\EmailBundle\Model\EmailModel $model */
        $model = $factory->getModel('sms');
        $sms = $model->getEntity($smsId);

        //make sure the email still exists and is published
        if ($sms != null && $sms->isPublished()) {
            $emailSent = $model->sendSms($sms, $lead);
            return is_array($emailSent) ? false : true;
        }

        return false;
    }
}
