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

use Mautic\CoreBundle\Form\EventListener\CleanFormSubscriber;
use Mautic\CoreBundle\Form\EventListener\FormExitSubscriber;
use MauticPlugin\WeixinBundle\Entity\Message;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\File;

/**
 * Class RoleType.
 */
class MessageType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('msgType', 'choice', [
            'label'      => 'mautic.weixin.message.msg_type',
            'label_attr' => ['class' => 'control-label'],
            'attr'       => ['class' => 'form-control msg-type'],
            'choices'    => $options['msg_type']
        ]);

        $builder->add('content', 'textarea', [
            'label'      => 'mautic.weixin.message.content',
            'label_attr' => ['class' => 'control-label'],
            'attr'       => ['class' => 'form-control', 'data-toggle' => 'msg-type', 'toggle-type' => 'text'],
            'required'   => false,
        ]);

        $builder->add('articleTitle', 'text', [
            'label'      => 'mautic.weixin.message.article_title',
            'label_attr' => ['class' => 'control-label'],
            'attr'       => ['class' => 'form-control', 'data-toggle' => 'msg-type', 'toggle-type' => 'imgtext'],
            'required'   => false,
        ]);

        $builder->add('articleDesc', 'text', [
            'label'      => 'mautic.weixin.message.article_desc',
            'label_attr' => ['class' => 'control-label'],
            'attr'       => ['class' => 'form-control', 'data-toggle' => 'msg-type', 'toggle-type' => 'imgtext'],
            'required'   => false,
        ]);

        $builder->add('articleUrl', 'text', [
            'label'      => 'mautic.weixin.message.article_url',
            'label_attr' => ['class' => 'control-label'],
            'attr'       => ['class' => 'form-control', 'data-toggle' => 'msg-type', 'toggle-type' => 'imgtext'],
            'required'   => false,
        ]);

        $builder->add(
            'image',
            'image',
            [
                'label' => false,
                'attr'       => [
                    'data-toggle' => 'msg-type',
                    'toggle-type' => 'img imgtext',
                ],
            ]
        );

        $builder->add(
            'file',
            'file',
            [
                'label'      => 'mautic.weixin.message.img',
                'label_attr' => ['class' => 'control-label'],
                'required'   => false,
                'attr'       => [
                    'class' => 'form-control',
                    'data-toggle' => 'msg-type',
                    'toggle-type' => 'img imgtext',
                ],
                'mapped' => false,
                'constraints' => [
                    new File(
                        [
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/png',
                            ],
                            'maxSize' => '1M',
                            'mimeTypesMessage' => 'mautic.lead.avatar.types_invalid',
                        ]
                    ),
                ],
            ]
        );

    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class'         => 'MauticPlugin\WeixinBundle\Entity\Message',
            'msg_type' => Message::$msgTypes
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'weixin_message';
    }
}
