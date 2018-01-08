<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\WeixinBundle\Form\Type;

use MauticPlugin\WeixinBundle\Event\Events;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


class CampaignEventWeixinType extends AbstractType
{
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

        $weixins = $this->em->getRepository('MauticPlugin\WeixinBundle\Entity\Weixin')
            ->createQueryBuilder('w')
            ->where('w.owner = :owner')
            ->setParameter('owner', $user)
            ->getQuery()
            ->getResult();

        $choices = [];
        foreach($weixins as $weixin){
            $choices[$weixin->getId()] = (string) $weixin;
        }

        $builder->add('weixins', 'choice', [
            'choices' => $choices,
            'expanded'    => false,
            'multiple'    => true,
            'label'       => 'weixin.point.action.weixins',
            'label_attr'  => ['class' => 'control-label'],
            'empty_value' => false,
            'required'    => false,
            'attr'        => [
                'class'   => 'form-control',
            ],
        ]);

        $events = [
            Events::WEIXIN_SUBSCRIBE => '关注微信公众号',
            Events::WEIXIN_UNSUBSCRIBE => '取关微信公众号',
            Events::WEIXIN_CLICK => '点击菜单',
            Events::WEIXIN_SCAN => '扫码二维码',
            Events::WEIXIN_TEXT => '发送信息',
        ];
        $builder->add('type', 'choice', [
            'choices' => $events,
            'expanded'    => false,
            'multiple'    => true,
            'label'       => 'weixin.campaign.action.event_type',
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
        return 'campaignevent_weixin';
    }
}
