<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
$view->extend('MauticCoreBundle:Default:content.html.php');
$view['slots']->set('mauticContent', 'weixin');
$view['slots']->set('headerTitle', $view['translator']->trans('weixin.menu'));

$view['slots']->set(
    'actions',
    $view->render(
        'WeixinBundle:Common:switcher.html.php',
        [
            'currentWeixin' => $currentWeixin,'weixins' => $weixins,
        ]
    )
);

$pageButtons = [];

echo $view['assets']->includeStylesheet('plugins/WeixinBundle/Assets/css/menu.css');

?>
<div class="col-md-12 bg-white height-auto">
    <div class="row">
        <div class="col-md-5">
            <div class="weixin-window">
                <a href="<?php echo $view['router']->path('mautic_weixin_menu_update'); ?>" class="btn btn-success">同步菜单</a>
                <div class="weixin-menu">
                    <?php
                    $menuCount = count($currentWeixin->getMenus());
                    $current = 0;
                    $count = 0;
                    ?>
                    <?php foreach ($currentWeixin->getMenus() as $menu): ?>
                        <div class="weixin-menu-bar col-md-4 <?php
                        $count++;
                        if ($currentMenu->getId() === $menu->getId()) {
                            if (!isset($currentItem)) {
                                echo 'weixin-menu-selected';
                            }
                            $current = $count;
                        }
                        ?>">
                            <span><a href="<?php echo $view['router']->path('mautic_weixin_menu_edit_menu', ['id' => $menu->getId() ? $menu->getId() : 0]); ?>">
                                    <?php echo $menu->getName(); ?>
                                </a></span>
                        </div>
                    <?php endforeach; ?>
                    <?php if ($menuCount < 3): ?>
                        <div class="weixin-menu-bar col-md-4">
                            <span><a href="<?php echo $view['router']->path('mautic_weixin_menu_edit_menu', ['id' => 0]) ?>"><i
                                            class="fa fa-plus"></i></a></span>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="weixin-menu-items weixin-menu-items-<?php echo $current ?>">
                    <?php foreach ($currentMenu->getItems() as $item): ?>
                        <div class="weixin-menu-item
                        <?php
                        if (isset($currentItem) && $currentItem->getId() === $item->getId()) {
                            echo 'weixin-menu-selected';
                        }
                        ?>">
                            <span><a href="<?php echo $view['router']->path('mautic_weixin_menu_edit_menu_item', ['id' => ($item->getId() ? $item->getId() : 0), 'menuId' => $currentMenu->getId()]) ?>">
                                    <?php echo $item->getName(); ?>
                                </a></span>
                        </div>
                    <?php endforeach; ?>
                    <?php if (count($currentMenu->getItems()) < 5 && $currentMenu->getId()): ?>
                        <div class="weixin-menu-item">
                            <span><a href="<?php echo $view['router']->path('mautic_weixin_menu_edit_menu_item', ['id' => 0, 'menuId' => $currentMenu->getId()]) ?>"><i
                                            class="fa fa-plus"></i></a></span>
                        </div>
                    <?php endif; ?>
                    <div class="arrow-down"></div>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="panel panel-success menu-editor">
                <div class="panel-heading">
                    <h4><?php
                        if(isset($currentItem)) {
                            echo $view['translator']->trans('weixin.menuitem');
                        }else {
                            echo $view['translator']->trans('weixin.menu');
                        }
                        ?></h4>
                </div>
                <div class="panel-body menu-editor-body">
                    <?php echo $view['form']->start($form); ?>
                    <div class="row">
                        <div class="col-md-12">
                            <?php echo $view['form']->row($form['name']); ?>

                            <?php if ($form->vars['value'] instanceof \MauticPlugin\WeixinBundle\Entity\MenuItem  || count($form->vars['value']->getItems()) == 0): ?>
                                <?php echo $view['form']->row($form['type']); ?>
                                <div class="url <?php if ($form->vars['value']->getType() == 'click') echo 'hidden'; ?>">
                                    <?php echo $view['form']->row($form['url']); ?>
                                </div>
                                <div class="message <?php if ($form->vars['value']->getType() == 'view') echo 'hidden'; ?>">
                                    <?php echo $view['form']->row($form['message']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-12">
                            <?php echo $view['form']->row($form['save']); ?>

                            <?php if (isset($currentItem) && $currentItem->getId()): ?>
                                <a class="btn btn-danger" href="<?php echo $view['router']->path('mautic_weixin_menu_delete_menu_item', ['id' => $currentItem->getId() ]); ?>"><?php echo $view['translator']->trans('mautic.core.form.delete'); ?></a>
                            <?php elseif(!isset($currentItem) && $currentMenu->getId()): ?>
                                <a class="btn btn-danger" href="<?php echo $view['router']->path('mautic_weixin_menu_delete_menu', ['id' => $currentMenu->getId() ]); ?>"><?php echo $view['translator']->trans('mautic.core.form.delete'); ?></a>

                            <?php endif; ?>
                        </div>
                    </div>
                    <?php echo $view['form']->end($form); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    mQuery(document).ready(function () {
        mQuery('.msg-type').on('change', function () {
            mQuery('[data-toggle="msg-type"]').closest('.row').hide();
            mQuery('[toggle-type~="' + mQuery(this).val() + '"]').closest('.row').show();

        }).trigger('change');

        mQuery('input[type=radio][name="weixin_menu[type]"],input[type=radio][name="weixin_menu_item[type]"]').on('change', function () {
            if (mQuery(this).val() == 'view') {
                mQuery('.url').removeClass('hidden');
                mQuery('.message').addClass('hidden');
            } else if (mQuery(this).val() == 'click') {
                mQuery('.url').addClass('hidden');
                mQuery('.message').removeClass('hidden');
            }
        });

        mQuery('#weixin_menu_name,#weixin_menu_item_name').on('input', function () {
            mQuery('.weixin-menu-selected span').text(mQuery(this).val());
        });
    })
    ;
</script>