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
$view['slots']->set('headerTitle', $view['translator']->trans('mautic.weixin.auto_res'));

$view['slots']->set(
    'actions',
    $view->render(
        'WeixinBundle:Common:switcher.html.php',
        [
            'currentWeixin' => $currentWeixin,
            'weixins' => $weixins,
        ]
    )
);

$pageButtons = [];

?>

<div class="col-md-12 bg-white height-auto">
    <div class="row">
        <div class="col-md-12">


            <?php echo $view['form']->start($form); ?>
            <div class="row">
                <div class="col-md-6">
                    <?php echo $view['form']->row($form['name']); ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4>
                                <?php echo $view['translator']->trans('weixin.rule_keyword') ?>
                                <a href="<?php echo $view['router']->path('mautic_weixin_auto_res_edit_keyword', ['id' => 0, 'ruleId' => $form->vars['value']->getId()]) ?>"><i
                                            class="fa fa-plus"></i></i></a>
                            </h4>
                        </div>
                        <div class="panel-body">
                            <table class="table table-condensed">
                                <?php foreach ($form->vars['value']->getKeywords() as $keyword): ?>
                                    <tr>
                                        <td><?php echo $keyword->getKeyword(); ?></td>
                                        <td><?php echo $view['translator']->trans($keyword->getTypeText()); ?></td>
                                        <td>
                                            <a href="<?php echo $view['router']->path('mautic_weixin_auto_res_edit_keyword', ['id' => $keyword->getId()]) ?>"><i
                                                        class="fa fa-pencil"></i></a></td>
                                        <td>
                                            <a href="<?php echo $view['router']->path('mautic_weixin_auto_res_delete_keyword', ['id' => $keyword->getId()]) ?>"><i
                                                        class="fa fa-trash"></i></a></td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>
                    </div>
                    <?php echo $view['form']->row($form['message']); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <?php echo $view['form']->row($form['save']); ?>
                    <a href="<?php echo $view['router']->path('mautic_weixin_auto_res_delete_rule', ['id' => $form->vars['value']->getId()] ); ?>" class="btn btn-danger"><?php echo $view['translator']->trans('mautic.core.form.delete') ?></a>
                </div>
            </div>
            <?php echo $view['form']->end($form); ?>
        </div>
    </div>
</div>

<script>

    (function () {
        mQuery(document).ready(function () {
            mQuery('.msg-type').on('change', function () {
                mQuery('[data-toggle="msg-type"]').closest('.row').hide();
                mQuery('[toggle-type~="' + mQuery(this).val() + '"]').closest('.row').show();

            }).trigger('change');
        });
    })();

</script>