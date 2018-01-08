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
use Symfony\Component\Validator\Constraints\File;

/**
 * Class RoleType.
 */
class QrcodeLogoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'file',
            'file',
            [
                'label'      => 'mautic.weixin.qrcode.logo',
                'label_attr' => ['class' => 'control-label'],
                'required'   => false,
                'attr'       => [
                    'class' => 'form-control',
                ],
                'mapped' => false,
                'constraints' => [
                    new File(
                        [
                            'mimeTypes' => [
                                'image/png',
                            ],
                            'maxSize' => '1M',
                            'mimeTypesMessage' => 'mautic.qrcode.logo.png',
                        ]
                    ),
                ],
            ]
        );

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
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'weixin_qrcode_logo';
    }
}
