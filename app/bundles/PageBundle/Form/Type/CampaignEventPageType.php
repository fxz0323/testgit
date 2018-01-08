<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\PageBundle\Form\Type;

use MauticPlugin\WeixinBundle\Event\Events;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


class CampaignEventPageType extends AbstractType
{

    const VISIT = 'campaign.page.visit';
    const CLICK = 'campaign.page.click';

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

        $pages = $this->em->getRepository('Mautic\PageBundle\Entity\Page')->getPageList('', 0, 0, true);

        $choices = [];
        foreach ($pages as $page) {
            $choices[$page['id']] = $page['title'];
        }

        $builder->add('pages', 'choice', [
            'choices' => $choices,
            'expanded'    => false,
            'multiple'    => true,
            'label'       => 'page.campaign.action.pages',
            'label_attr'  => ['class' => 'control-label'],
            'empty_value' => false,
            'required'    => false,
            'attr'        => [
                'class'   => 'form-control',
            ],
        ]);

        $events = [
            self::VISIT => '访问页面',
            self::CLICK => '点击页面内链接',
        ];
        $builder->add('type', 'choice', [
            'choices' => $events,
            'expanded'    => false,
            'multiple'    => false,
            'label'       => 'page.campaign.action.event_type',
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
        return 'campaignevent_page';
    }
}
