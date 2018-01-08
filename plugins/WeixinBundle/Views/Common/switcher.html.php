<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
?>
<!--
some stats: need more input on what type of form data to show.
delete if it is not require
-->
<div class="col-md-10">
    <a class="btn btn-default"
       href="<?php echo $view['router']->path('mautic_weixin_auto_res'); ?>"><?php echo $view['translator']->trans('weixin.auto_res'); ?></a>
    <a class="btn btn-default"
       href="<?php echo $view['router']->path('mautic_weixin_menu'); ?>"><?php echo $view['translator']->trans('weixin.menu'); ?></a>
    <a class="btn btn-default"
       href="<?php echo $view['router']->path('mautic_weixin_qrcode'); ?>"><?php echo $view['translator']->trans('weixin.qrcode'); ?></a>
    <a class="btn btn-default"
       href="<?php echo $view['router']->path('mautic_weixin_article'); ?>"><?php echo $view['translator']->trans('weixin.article'); ?></a>
    <?php if($currentWeixin): ?>
    <a class="btn btn-default"
       href="<?php echo $view['router']->path('mautic_weixin_sych_users', ['id' => $currentWeixin->getId()]); ?>"><?php echo $view['translator']->trans('weixin.sych.users'); ?></a>
    <?php endif; ?>
    <a class="btn btn-default"
       href="<?php echo $view['router']->path('mautic_weixin_settings'); ?>">设置</a>
</div>
<div class="col-md-2">
    <select id="weixin" class="form-control">
        <?php foreach ($weixins as $weixin): ?>
            <option value="<?php echo $weixin->getId() ?>" <?php if ($weixin == $currentWeixin) echo 'selected'; ?>><?php echo $weixin->getAccountName(); ?></option>
        <?php endforeach; ?>
    </select>
</div>

<script>
    mQuery(document).ready(function () {
        mQuery('#weixin').on('change', function () {
            var id = mQuery(this).val();
            mQuery.ajax({
                url: "<?php echo $view['router']->path('mautic_weixin_choose_weixin') ?>?id=" + id,
                success: function(){
                    location.reload();
                }
            })
        });
    });
</script>
