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

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class PointActionFormSubmitType.
 */
class PointActionWeixinType extends AbstractType
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
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pointaction_weixin';
    }
}
