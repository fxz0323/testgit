<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
if ($tmpl == 'index') {
    $view->extend('MauticCoreBundle:Default:content.html.php');
}
$view['slots']->set('mauticContent', 'config');
$view['slots']->set('headerTitle', $view['translator']->trans('mautic.config.header.index'));

$configKeys = array_keys($form->children);
?>

<!-- start: box layout -->
<div class="box-layout">
    <!-- step container -->
    <div class="col-md-3 bg-white height-auto">
        <div class="pr-lg pl-lg pt-md pb-md">
            <?php if (!$isWritable): ?>
                <div class="alert alert-danger"><?php echo $view['translator']->trans('mautic.config.notwritable'); ?></div>
            <?php endif; ?>
            <!-- Nav tabs -->
            <ul class="list-group list-group-tabs" role="tablist">
                <?php foreach ($configKeys as $i => $key) : ?>
                    <?php if (!isset($formConfigs[$key]) || !count($form[$key]->children)) {
                        continue;
                    } ?>
                    <li role="presentation" class="list-group-item <?php echo $i === 0 and $tag != 'weixin' ? 'in active' : ''; ?>">
                        <?php $containsErrors = ($view['form']->containsErrors($form[$key])) ? ' text-danger' : ''; ?>
                        <a href="#<?php echo $key; ?>" aria-controls="<?php echo $key; ?>" role="tab" data-toggle="tab"
                           class="steps<?php echo $containsErrors; ?>">
                            <?php echo $view['translator']->trans('mautic.config.tab.' . $key); ?>
                            <?php if ($containsErrors): ?>
                                <i class="fa fa-warning"></i>
                            <?php endif; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
                <li role="presentation" class="list-group-item <?php echo $tag == 'weixin' ? 'in active' : ''; ?>">
                    <a href="#social_config" aria-controls="social_config" role="tab" data-toggle="tab"
                       class="steps">
                        社交媒体设置
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- container -->
    <div class="col-md-9 bg-auto height-auto bdr-l">
        <?php echo $view['form']->start($form); ?>
        <!-- Tab panes -->
        <div class="tab-content">
            <?php foreach ($configKeys as $i => $key) : ?>
                <?php
                if (!isset($formConfigs[$key])) {
                    continue;
                }
                if (!count($form[$key]->children)):
                    $form[$key]->setRendered();
                    continue;
                endif;
                ?>
                <div role="tabpanel" class="tab-pane fade <?php echo $i === 0 and $tag != 'weixin' ? 'in active' : ''; ?> bdr-w-0"
                     id="<?php echo $key; ?>">
                    <div class="pt-md pr-md pl-md pb-md">
                        <?php echo $view['form']->widget($form[$key], ['formConfig' => $formConfigs[$key]]); ?>
                    </div>
                </div>
            <?php endforeach; ?>
            <div role="tabpanel" class="tab-pane fade <?php echo $tag == 'weixin' ? 'in active' : ''; ?> bdr-w-0" id="social_config">
                <div class="pt-md pr-md pl-md pb-md">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">社交媒体设置</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-10">
                                    <span>微信公众号</span>
                                </div>
                                <div class="col-md-2">
                                    <a class="btn btn-success" href="<?php echo $view['router']->path('mautic_weixin_open_oauth') ?>">Add</a>
                                </div>
                                <div class="col-md-12">
                                    <table class="table table-condensed">
                                        <tr>
                                            <th>公众号名称</th>
                                            <th>类型</th>
                                            <th>认证</th>
                                            <th>创建时间</th>
                                            <th>解绑</th>
                                        </tr>
                                        <?php foreach ($weixins as $weixin) : ?>
                                        <tr>
                                            <td>
                                                <img style="max-height:80px;" src="<?php echo $weixin->getIcon(); ?>">
                                                <?php echo $weixin->getAccountName(); ?>
                                            </td>
                                            <td><?php echo $weixin->getTypeText(); ?></td>
                                            <td><?php echo $weixin->getVerifiedText(); ?></td>
                                            <td><?php echo $weixin->getCreateTime() ? $weixin->getCreateTime()->format('Y-m-d H:i:s'):''; ?></td>
                                            <td><a href="<?php echo $view['router']->path('mautic_weixin_open_unlink', ['id' => $weixin->getId()] ) ?>"><i class="fa fa-chain-broken"></i></a></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php echo $view['form']->end($form); ?>
    </div>
</div>