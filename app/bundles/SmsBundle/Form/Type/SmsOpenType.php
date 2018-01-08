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

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class EmailOpenType.
 */
class SmsOpenType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $defaultOptions = [
            'label'      => 'mautic.sms.open.limittosmss',
            'label_attr' => ['class' => 'control-label'],
            'attr'       => [
                'class'   => 'form-control',
                'tooltip' => 'mautic.sms.open.limittosmss_descr',
            ],
            'required' => false,
        ];

        $builder->add('smss', 'sms_list', $defaultOptions);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'smsopen_list';
    }
}
