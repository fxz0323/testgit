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
            'currentWeixin' => $currentWeixin,'weixins' => $weixins,
        ]
    )
);

$pageButtons = [];

?>
<div class="box-layout">
    <div class="col-md-12 bg-white height-auto">
        <div class="row">
            <div class="col-xs-12">
                <ul class="bg-auto nav nav-tabs pr-md pl-md">
                    <li class="<?php if ($module == 'followed') echo 'active in'; ?>">
                        <a href="#followed" role="tab" data-toggle="tab">
                            被添加自动回复 </a>
                    </li>
                    <li class="<?php if ($module == 'keyword') echo 'active in'; ?>">
                        <a href="#keyword" role="tab" data-toggle="tab">
                            关键词自动回复 </a>
                    </li>
                </ul>

                <!-- start: tab-content -->
                <div class="tab-content pa-md">
                    <div class="tab-pane fade bdr-w-0 <?php if ($module == 'followed') echo 'active in'; ?>"
                         id="followed">
                        <div class="row">
                            <div class="col-md-12">
                                <?php echo $view['form']->start($followedMessageForm); ?>
                                <div class="row">
                                    <div class="col-md-6">
                                        <?php echo $view['form']->row($followedMessageForm['followedMessage']); ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <?php echo $view['form']->row($followedMessageForm['save']); ?>
                                    </div>
                                </div>
                                <?php echo $view['form']->end($followedMessageForm); ?>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade bdr-w-0 <?php if ($module == 'keyword') echo 'active in'; ?>"
                         id="keyword">
                        <div class="row">
                            <div class="col-md-12">
                                <?php foreach ($currentWeixin->getRules() as $rule): ?>
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4><a href="<?php echo $view['router']->path('mautic_weixin_auto_res_edit_rule', ['id' => $rule->getId()]); ?>"><?php echo $rule->getName(); ?></a></h4>
                                        </div>
                                        <div class="panel-body">
                                            <p><?php echo $view['translator']->trans('weixin.rule_keyword') ?>
                                                <?php foreach ($rule->getKeywords() as $keyword): ?>
                                                    <span><?php echo $keyword->getKeyword(); ?></span>
                                                <?php endforeach; ?>
                                            </p>
                                            <p><?php echo $view['translator']->trans('mautic.weixin.message.msg_type') ?>: <?php echo $rule->getMessage()->getMsgType(); ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                                <a class="btn btn-success"
                                   href="<?php echo $view['router']->path('mautic_weixin_auto_res_new_rule'); ?>"><i
                                            class="fa fa-plus"></i><?php echo $view['translator']->trans('weixin.new_rule'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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