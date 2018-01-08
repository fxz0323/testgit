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
use MauticPlugin\WeixinBundle\Entity\Message;
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
class QrcodeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', [
            'label' => 'weixin.qrcode.name',
            'label_attr' => ['class' => 'control-label'],
            'attr' => ['class' => 'form-control'],
            'required' => true,
        ]);
        $builder->add('weixin', 'entity', [
            'label' => 'weixin.qrcode.weixin',
            'class' => 'MauticPlugin\WeixinBundle\Entity\Weixin',
            'choices' => $options['weixins'],
            'label_attr' => ['class' => 'control-label'],
            'required' => true,
        ]);
        $builder->add('tag', 'text', [
            'label' => 'weixin.qrcode.tag',
            'label_attr' => ['class' => 'control-label'],
            'attr' => ['class' => 'form-control'],
            'required' => true,
        ]);
        $builder->add('leadField1', 'choice', [
            'label' => 'weixin.qrcode.leadField',
            'label_attr' => ['class' => 'control-label'],
            'choices' => $options['fields'],
            'attr' => ['class' => 'form-control'],
            'required' => false,
        ]);
        $builder->add('leadField1Value', 'text', [
            'label' => 'weixin.qrcode.leadField_value',
            'label_attr' => ['class' => 'control-label'],
            'attr' => ['class' => 'form-control'],
            'required' => false,
        ]);
        $builder->add('leadField2', 'choice', [
            'label' => 'weixin.qrcode.leadField',
            'choices' => $options['fields'],
            'label_attr' => ['class' => 'control-label'],
            'attr' => ['class' => 'form-control'],
            'required' => false,
        ]);

        $builder->add('leadField2Value', 'text', [
            'label' => 'weixin.qrcode.leadField_value',
            'label_attr' => ['class' => 'control-label'],
            'attr' => ['class' => 'form-control'],
            'required' => false,
        ]);

        $builder->add('message', MessageType::class, [
            'label' => false,
            'required' => false,
            'msg_type' => array_merge([
                'none' => 'weixin.message.type.none'
            ], Message::$msgTypes)
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
            'data_class' => 'MauticPlugin\WeixinBundle\Entity\Qrcode',
            'weixins' => [],
            'fields' => [],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'weixin_qrcode';
    }
}
