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

use Mautic\CoreBundle\Factory\MauticFactory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class LeadImportFieldType.
 */
class OrderImportFieldType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $fields = [
            'orderNo' => '订单号',
            'orderTime' => '下单时间',
            'totalFee' => '订单金额',
            'contact' => '联系人手机号码',
            'productName' => '商品名',
            'productType' => '商品品类',
            'productSize' => '型号',
            'productColor' => '颜色',
            'unitPrice' => '单价',
            'quantity' => '数量',
            'totalPrice' => '金额',
            'origin' => '订单来源',
            'recipient' => '收件人',
            'recipientMobile' => '收件人电话',
            'address' => '收件人地址',
        ];
        foreach ($fields as $field => $label) {
            $builder->add(
                $field,
                'choice',
                [
                    'choices'    => $options['import_fields'],
                    'label'      => $label,
                    'required'   => false,
                    'label_attr' => ['class' => 'control-label'],
                    'attr'       => ['class' => 'form-control'],
                    'data'       => $this->getDefaultValue($field, $options['import_fields']),
                ]
            );
        }

        $builder->add(
            'buttons',
            'form_buttons',
            [
                'apply_text'  => false,
                'save_text'   => 'mautic.lead.import.start',
                'save_class'  => 'btn btn-primary',
                'save_icon'   => 'fa fa-user-plus',
                'cancel_icon' => 'fa fa-times',
            ]
        );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(['import_fields']);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'order_field_import';
    }

    /**
     * @param string $fieldName
     * @param array  $importFields
     *
     * @return string
     */
    public function getDefaultValue($fieldName, array $importFields)
    {
        if (isset($importFields[$fieldName])) {
            return $importFields[$fieldName];
        }

        return null;
    }
}
