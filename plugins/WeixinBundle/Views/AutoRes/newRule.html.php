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
                    <?php echo $view['form']->form($form); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <?php echo $view['form']->row($form['save']); ?>
                </div>
            </div>
            <?php echo $view['form']->end($form); ?>

        </div>
    </div>
</div>

<script>

    (function () {
        mQuery(document).ready(function() {
            mQuery('.msg-type').on('change', function() {
                mQuery('[data-toggle="msg-type"]').closest('.row').hide();
                mQuery('[toggle-type~="'+mQuery(this).val()+'"]').closest('.row').show();

            }).trigger('change');
        });
    })();

</script>