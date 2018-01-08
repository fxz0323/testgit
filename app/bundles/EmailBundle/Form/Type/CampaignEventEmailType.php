<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\EmailBundle\Form\Type;

use MauticPlugin\WeixinBundle\Event\Events;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


class CampaignEventEmailType extends AbstractType
{

    const RECEIVE = 'campaign.email.recieve';
    const OPEN = 'campaign.email.open';
    const DNC = 'campaign.email.dnc';
    const CLICK = 'campaign.email.click';

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

        $emails = $this->em->getRepository('Mautic\EmailBundle\Entity\Email')->getEmailList('', 0, 0, true, true);

        $choices = [];
        foreach ($emails as $email) {
            $choices[$email['id']] = $email['name'];
        }

        $builder->add('emails', 'choice', [
            'choices' => $choices,
            'expanded'    => false,
            'multiple'    => true,
            'label'       => 'email.campaign.action.emails',
            'label_attr'  => ['class' => 'control-label'],
            'empty_value' => false,
            'required'    => false,
            'attr'        => [
                'class'   => 'form-control',
            ],
        ]);

        $events = [
            self::RECEIVE => '收到邮件',
            self::OPEN => '打开邮件',
            self::DNC => '取订邮件',
            self::CLICK => '点击邮件内链接',
        ];
        $builder->add('type', 'choice', [
            'choices' => $events,
            'expanded'    => false,
            'multiple'    => false,
            'label'       => 'email.campaign.action.event_type',
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
        return 'campaignevent_email';
    }
}
