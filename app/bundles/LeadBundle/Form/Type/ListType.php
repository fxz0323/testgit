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

use Mautic\CategoryBundle\Model\CategoryModel;
use Mautic\CoreBundle\Form\EventListener\CleanFormSubscriber;
use Mautic\CoreBundle\Form\EventListener\FormExitSubscriber;
use Mautic\CoreBundle\Helper\UserHelper;
use Mautic\CoreBundle\Security\Permissions\CorePermissions;
use Mautic\EmailBundle\Model\EmailModel;
use Mautic\LeadBundle\Form\DataTransformer\FieldFilterTransformer;
use Mautic\LeadBundle\Helper\FormFieldHelper;
use Mautic\LeadBundle\Model\LeadModel;
use Mautic\LeadBundle\Model\ListModel;
use Mautic\StageBundle\Model\StageModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class ListType.
 */
class ListType extends AbstractType
{
    private $translator;
    private $em;
    private $tokenStorage;
    private $fieldChoices = [];
    private $timezoneChoices = [];
    private $countryChoices = [];
    private $regionChoices = [];
    private $listChoices = [];
    private $emailChoices = [];
    private $tagChoices = [];
    private $stageChoices = [];
    private $localeChoices = [];
    private $categoriesChoices = [];
    private $weixinChoices = [];
    private $smsChoices = [];
    private $pageChoices = [];
    private $formChoices = [];

    /**
     * ListType constructor.
     *
     * @param TranslatorInterface $translator
     * @param ListModel $listModel
     * @param EmailModel $emailModel
     * @param CorePermissions $security
     * @param LeadModel $leadModel
     * @param StageModel $stageModel
     * @param CategoryModel $categoryModel
     * @param UserHelper $userHelper
     */
    public function __construct(TranslatorInterface $translator, ListModel $listModel, EmailModel $emailModel, CorePermissions $security, LeadModel $leadModel, StageModel $stageModel, CategoryModel $categoryModel, UserHelper $userHelper, $doctrine, $tokenStorage)
    {
        $this->translator = $translator;
        $this->em = $doctrine->getManager();
        $this->tokenStorage = $tokenStorage;
        $this->fieldChoices = $listModel->getChoiceFields();

        // Locales
        $this->timezoneChoices = FormFieldHelper::getTimezonesChoices();
        $this->countryChoices = FormFieldHelper::getCountryChoices();
        $this->regionChoices = FormFieldHelper::getRegionChoices();
        $this->localeChoices = FormFieldHelper::getLocaleChoices();

        // Segments
        $lists = $listModel->getUserLists();
        foreach ($lists as $list) {
            $this->listChoices[$list['id']] = $list['name'];
        }

        $viewOther = $security->isGranted('email:emails:viewother');
        $currentUser = $userHelper->getUser();
        $emailRepo = $emailModel->getRepository();

        $emailRepo->setCurrentUser($currentUser);

        $emails = $emailRepo->getEmailList('', 0, 0, $viewOther, true);

        foreach ($emails as $email) {
            $this->emailChoices[$email['language']][$email['id']] = $email['name'];
        }
        ksort($this->emailChoices);

        $tags = $leadModel->getTagList();
        foreach ($tags as $tag) {
            $this->tagChoices[$tag['value']] = $tag['label'];
        }

        $stages = $stageModel->getRepository()->getSimpleList();
        foreach ($stages as $stage) {
            $this->stageChoices[$stage['value']] = $stage['label'];
        }

        $categories = $categoryModel->getLookupResults('global');

        foreach ($categories as $category) {
            $this->categoriesChoices[$category['id']] = $category['title'];
        }

        $user = $this->tokenStorage->getToken()->getUser();
        $weixins = $this->em->getRepository('MauticPlugin\WeixinBundle\Entity\Weixin')
            ->createQueryBuilder('w')
            ->where('w.owner = :owner')
            ->setParameter('owner', $user)
            ->getQuery()
            ->getResult();

        foreach($weixins as $weixin){
            $this->weixinChoices[$weixin->getId()] = (string) $weixin;
        }

        $smses = $this->em->getRepository('Mautic\SmsBundle\Entity\Sms')
            ->createQueryBuilder('w')
//            ->where('w.createdBy = :owner')
//            ->setParameter('owner', $user)
            ->getQuery()
            ->getResult();

        foreach($smses as $sms){
            $this->smsChoices[$sms->getId()] = (string) $sms->getName();
        }


        $pages = $this->em->getRepository('Mautic\PageBundle\Entity\Page')
            ->createQueryBuilder('w')
            ->where('w.createdBy = :owner')
            ->setParameter('owner', $user)
            ->getQuery()
            ->getResult();

        foreach($pages as $page){
            $this->pageChoices[$page->getId()] = (string) $page->getName();
        }

        $forms = $this->em->getRepository('Mautic\FormBundle\Entity\Form')
            ->createQueryBuilder('w')
            ->where('w.createdBy = :owner')
            ->setParameter('owner', $user)
            ->getQuery()
            ->getResult();

        foreach($forms as $form){
            $this->formChoices[$form->getId()] = $form->getName();
        }
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventSubscriber(new CleanFormSubscriber(['description' => 'html']));
        $builder->addEventSubscriber(new FormExitSubscriber('lead.list', $options));

        $builder->add(
            'name',
            'text',
            [
                'label' => 'mautic.core.name',
                'label_attr' => ['class' => 'control-label'],
                'attr' => ['class' => 'form-control'],
            ]
        );

        $builder->add(
            'alias',
            'text',
            [
                'label' => 'mautic.core.alias',
                'label_attr' => ['class' => 'control-label'],
                'attr' => [
                    'class' => 'form-control',
                    'length' => 25,
                    'tooltip' => 'mautic.lead.list.help.alias',
                ],
                'required' => false,
            ]
        );

        $builder->add(
            'description',
            'textarea',
            [
                'label' => 'mautic.core.description',
                'label_attr' => ['class' => 'control-label'],
                'attr' => ['class' => 'form-control editor'],
                'required' => false,
            ]
        );

        $builder->add(
            'isGlobal',
            'yesno_button_group',
            [
                'label' => 'mautic.lead.list.form.isglobal',
            ]
        );

        $builder->add('isPublished', 'yesno_button_group');

        if (!$builder->getData()->isStatic()) {

            $filterModalTransformer = new FieldFilterTransformer($this->translator);
            $builder->add(
                $builder->create(
                    'filters',
                    'collection',
                    [
                        'type' => 'leadlist_filter',
                        'options' => [
                            'label' => false,
                            'timezones' => $this->timezoneChoices,
                            'countries' => $this->countryChoices,
                            'regions' => $this->regionChoices,
                            'fields' => $this->fieldChoices,
                            'lists' => $this->listChoices,
                            'emails' => $this->emailChoices,
                            'tags' => $this->tagChoices,
                            'stage' => $this->stageChoices,
                            'locales' => $this->localeChoices,
                            'globalcategory' => $this->categoriesChoices,
                            'weixin' => $this->weixinChoices,
                            'sms' => $this->smsChoices,
                            'page' => $this->pageChoices,
                            'forms' => $this->formChoices,
                        ],
                        'error_bubbling' => false,
                        'mapped' => true,
                        'allow_add' => true,
                        'allow_delete' => true,
                        'label' => false,
                    ]
                )->addModelTransformer($filterModalTransformer)
            );
        }
        $builder->add('buttons', 'form_buttons');

        if (!empty($options['action'])) {
            $builder->setAction($options['action']);
        }
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'Mautic\LeadBundle\Entity\LeadList',
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['fields'] = $this->fieldChoices;
        $view->vars['countries'] = $this->countryChoices;
        $view->vars['regions'] = $this->regionChoices;
        $view->vars['timezones'] = $this->timezoneChoices;
        $view->vars['lists'] = $this->listChoices;
        $view->vars['emails'] = $this->emailChoices;
        $view->vars['tags'] = $this->tagChoices;
        $view->vars['stage'] = $this->stageChoices;
        $view->vars['locales'] = $this->localeChoices;
        $view->vars['globalcategory'] = $this->categoriesChoices;
        $view->vars['weixin'] = $this->weixinChoices;
        $view->vars['sms'] = $this->smsChoices;
        $view->vars['page'] = $this->pageChoices;
        $view->vars['forms'] = $this->formChoices;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'leadlist';
    }
}
