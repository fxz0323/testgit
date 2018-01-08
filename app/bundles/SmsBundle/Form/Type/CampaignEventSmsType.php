<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\SmsBundle\Form\Type;

use MauticPlugin\WeixinBundle\Event\Events;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


class CampaignEventSmsType extends AbstractType
{

    const RECEIVE = 'campaign.sms.recieve';
    const DNC = 'campaign.sms.dnc';
    const CLICK = 'campaign.sms.click';

    private $tokenStorage;
    private $em;

    public function __construct($tokenStorage, $doctrine)
    {
        $this->tokenStorage = $tokenStorage;
        $this->em = $doctrine->getManager();
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $this->tokenStorage->getToken()->getUser();

        $smss = $this->em->getRepository('Mautic\SmsBundle\Entity\Sms')->getSmsList('', 0, 0, true);

        $choices = [];
        foreach ($smss as $sms) {
            $choices[$sms['id']] = $sms['name'];
        }

        $builder->add('smss', 'choice', [
            'choices' => $choices,
            'expanded'    => false,
            'multiple'    => true,
            'label'       => 'sms.campaign.action.smss',
            'label_attr'  => ['class' => 'control-label'],
            'empty_value' => false,
            'required'    => false,
            'attr'        => [
                'class'   => 'form-control',
            ],
        ]);

        $events = [
            self::RECEIVE => '收到短信',
            self::DNC => '取订短信',
            self::CLICK => '点击短信内链接',
        ];
        $builder->add('type', 'choice', [
            'choices' => $events,
            'expanded'    => false,
            'multiple'    => false,
            'label'       => 'sms.campaign.action.event_type',
            'label_attr'  => ['class' => 'control-label'],
            'empty_value' => false,
            'required'    => false,
            'attr'        => [
                'class'   => 'form-control',
            ],
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'campaignevent_sms';
    }
}
