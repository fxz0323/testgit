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

use Doctrine\Common\Collections\ArrayCollection;
use MauticPlugin\WeixinBundle\Entity\Keyword;
use MauticPlugin\WeixinBundle\Entity\News;
use MauticPlugin\WeixinBundle\Entity\NewsSend;
use MauticPlugin\WeixinBundle\Entity\Rule;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

/**
 * Class RoleType.
 */
class NewsSendScheduleType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('sendType', 'choice', [
            'label' => 'weixin.news.type',
            'choices' => NewsSend::$types,
            'label_attr' => ['class' => 'control-label'],
            'required'   => true,
        ]);

        $builder->add('list',
            'leadlist_choices',
            [
                'global_only' => true,
                'static_only' => false,
                'label'       => 'mautic.lead.form.list',
                'label_attr'  => ['class' => 'control-label'],
                'multiple'    => false,
                'required'    => false,
            ]
        );
        $attr['data-toggle'] = 'datetime';
        $builder->add('sendTime', 'datetime', [
            'label' => 'weixin.news.time',
            'label_attr' => ['class' => 'control-label'],
            'widget' => 'single_text',
            'required' => true,
            'attr' => $attr,
            'format' => 'yyyy-MM-dd HH:mm',
            'with_seconds' => false,
        ]);

        $builder->add('save', 'submit', [
            'label' => 'mautic.core.form.save',
            'attr' => ['class' => 'btn btn-success']
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'MauticPlugin\WeixinBundle\Entity\NewsSend',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'weixin_news_send';
    }
}
