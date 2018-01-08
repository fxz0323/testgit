<?php
/**
 * Created by PhpStorm.
 * User: hackc
 * Date: 2017-08-09
 * Time: 11:02
 */

namespace Mautic\SmsBundle\Form\Type;


use Mautic\CoreBundle\Factory\MauticFactory;
use Mautic\CoreBundle\Form\DataTransformer\IdToEntityModelTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class SmsPatchSendType extends AbstractType
{
    private $translator;
    private $em;
    private $request;

    public function __construct(MauticFactory $factory)
    {
        $this->translator   = $factory->getTranslator();
        $this->em           = $factory->getEntityManager();
        $this->request      = $factory->getRequest();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            $builder->create(
                'lists',
                'leadlist_choices',
                [
                    'label'         =>  'mautic.sms.form.list',
                    'label_attr'    =>  ['class' => 'control-label'],
                    'attr'  =>  [
                        'class'         =>  'form-control',
                    ],
                    'multiple'    => false,
                    'required'    => true,
                    'constraints' => [
                        new NotBlank(
                            ['message' => 'mautic.company.choosecompany.notblank']
                        ),
                    ],
                    'static_only' => false,
                ]
            )
        );

        $builder->add(
            'sendTime',
            'datetime',
            [
                'widget'     => 'single_text',
                'label'      => 'mautic.sms.form.sendtime',
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'class'       => 'form-control',
                    'data-toggle' => 'datetime',
                ],
                'format'   => 'yyyy-MM-dd HH:mm',
                'required' => false,
            ]
        );

        $builder->add(
            'buttons',
            'form_buttons',
            [
                'apply_text'    =>  false,
                'save_text'     =>  'mautic.sms.send',
                'save_icon'     =>  'fa fa-send',
            ]
        );
    }
}