<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\WeixinBundle\Security\Permissions;

use Mautic\CoreBundle\Security\Permissions\AbstractPermissions;
use Symfony\Component\Form\FormBuilderInterface;

class WeixinPermissions extends AbstractPermissions
{
    /**
     * {@inheritdoc}
     */
    public function __construct($params)
    {
        parent::__construct($params);

        $this->addExtendedPermissions('weixins');


        $this->permissions['access'] = [
            'view'   => 4,
            'edit'   => 16,
            'create' => 32,
            'delete' => 128,
            'weixin_view' => 256,
            'full'   => 1024,
        ];
        $this->permissions['access']['publish'] = 512;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'weixin';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface &$builder, array $options, array $data)
    {

        $choices = [
            'weixin_view' => '查看公众号及图文',
            'view'   => 'mautic.core.permissions.view',
            'edit'   => 'mautic.core.permissions.edit',
            'create' => 'mautic.core.permissions.create',
            'delete' => 'mautic.core.permissions.delete',
        ];
        $choices['publish'] = 'mautic.core.permissions.publish';
        $choices['full'] = 'mautic.core.permissions.full';
        $bundle = 'weixin';
        $level = 'access';
        $label = ($level == 'categories') ? 'mautic.category.permissions.categories' : "mautic.$bundle.permissions.$level";
        $builder->add("$bundle:$level", 'permissionlist', [
            'choices' => $choices,
            'label'   => $label,
            'bundle'  => $bundle,
            'level'   => $level,
            'data'    => (!empty($data[$level]) ? $data[$level] : []),
        ]);

        $this->addExtendedFormFields('weixin', 'weixins', $builder, $data);
    }

}
