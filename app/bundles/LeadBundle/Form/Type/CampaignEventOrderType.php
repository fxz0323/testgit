<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\LeadBundle\Form\Type;

use MauticPlugin\WeixinBundle\Event\Events;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


class CampaignEventOrderType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $events = [
            'total_count' => '总订单数',
            'total_price' => '总订单金额',
            'origin' => '订单来源',
            'order_no' => '订单号',
            'product_type' => '商品品类',
        ];
        $builder->add('type', 'choice', [
            'choices' => $events,
            'expanded'    => false,
            'multiple'    => true,
            'label'       => 'weixin.order.action.event_type',
            'label_attr'  => ['class' => 'control-label'],
            'empty_value' => false,
            'required'    => false,
            'attr'        => [
                'class'   => 'form-control',
            ],
        ]);

        $builder->add('value', 'text', [
            'label'       => 'weixin.order.action.value',
            'label_attr'  => ['class' => 'control-label'],
            'required'    => true,
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
        return 'campaignevent_order';
    }
}
