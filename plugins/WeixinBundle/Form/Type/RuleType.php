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
class RuleType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('name', 'text', [
            'label' => 'weixin.rule.name',
            'label_attr' => ['class' => 'control-label'],
            'attr'       => ['class' => 'form-control'],
            'required'   => true,
        ]);
        $builder->add('type', 'choice', [
            'label' => 'weixin.rule.type',
            'choices' => Rule::$ruleTypes,
            'label_attr' => ['class' => 'control-label'],
            'required'   => true,
            'expanded' => true,
        ]);
        $builder->add('keywords', 'text', [
            'label' => 'weixin.rule.keywords',
            'label_attr' => ['class' => 'control-label'],
            'attr'       => ['class' => 'form-control'],
            'required'   => true,
        ]);
        $builder->add('message', MessageType::class, [
            'label' => false,
            'required'   => false,
        ]);

        $builder->add('save', 'submit', [
            'label' => 'mautic.core.form.save',
            'attr' => ['class' => 'btn btn-success']
        ]);


        $builder->addEventListener(FormEvents::SUBMIT, function(FormEvent $event) {

            $rule = $event->getData();

            $keywordsArray = explode(' ', $rule->getKeywords());
            $keywords = new ArrayCollection();
            foreach ($keywordsArray as $keywordValue) {

                $keyword = new Keyword();
                $keyword->setKeyword($keywordValue);
                $keyword->setRule($rule);
                $keyword->setType($rule->getType());
                $keywords->add($keyword);
            }

            $rule->setKeywords($keywords);
        });


    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'MauticPlugin\WeixinBundle\Entity\Rule',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'weixin_rule_new';
    }
}
