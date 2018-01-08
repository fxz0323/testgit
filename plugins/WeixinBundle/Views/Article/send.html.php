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
$view['slots']->set('headerTitle', $view['translator']->trans('weixin.article'));

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
                    <p id="count"></p>
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
        mQuery(document).ready(function () {
            mQuery('#weixin_news_send_sendType').on('change', function () {
                if(mQuery(this).val() == 'group') {
                    mQuery('#weixin_news_send_list').closest('.row').show();
                }else{
                    mQuery('#weixin_news_send_list').closest('.row').hide();
                }
                querySend(mQuery(this).val());
            }).trigger('change');

            mQuery('#weixin_news_send_list').on('change', function () {
                querySend(mQuery('#weixin_news_send_sendType').val(), mQuery(this).val());
            });
        });

        function querySend(type, list = 0) {
            var url = "<?php echo $view['router']->path('mautic_weixin_query_send') ?>";
            mQuery.ajax({
                url: url,
                data: {
                    type: type,
                    list: list
                },
                success: function(data) {
                    mQuery('#count').text(data.count + '人');
                }
            });
        }
    })();

</script>