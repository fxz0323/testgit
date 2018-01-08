<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

/** @var \Mautic\PageBundle\Entity\Page $activePage */
//@todo - add landing page stats/analytics
$view->extend('MauticCoreBundle:Default:content.html.php');
$view['slots']->set('mauticContent', 'page');
$view['slots']->set('headerTitle', $activePage->getTitle());

?>

<!-- start: box layout -->
<div class="box-layout">

    <!-- right section -->
    <div class="col-md-3 bg-white bdr-l height-auto">
        <!-- preview URL -->
        <div class="panel bg-transparent shd-none bdr-rds-0 bdr-w-0 mt-sm mb-0">
            <div class="panel-heading">
                <div class="panel-title"><?php echo $view['translator']->trans('mautic.page.url'); ?></div>
            </div>
            <div class="panel-body pt-xs">
                <div class="input-group">
                    <input onclick="this.setSelectionRange(0, this.value.length);" type="text" class="form-control"
                           readonly
                           value="<?php echo $pageUrl; ?>"/>
                    <span class="input-group-btn">
                        <button class="btn btn-default btn-nospin" onclick="window.open('<?php echo $pageUrl; ?>', '_blank');">
                            <i class="fa fa-external-link"></i>
                        </button>
                    </span>
                </div>
            </div>
        </div>

        <div class="panel bg-transparent shd-none bdr-rds-0 bdr-w-0 mt-sm mb-0">
            <div class="panel-heading">
                <div class="panel-title"><?php echo $view['translator']->trans('mautic.page.qrcode'); ?></div>
            </div>
            <div class="panel-body pt-xs">
                <?php $qrCode = new \Endroid\QrCode\QrCode($pageUrl); echo '<img style="max-height:300px;" src="'.$qrCode->writeDataUri().'">'?>
            </div>
        </div>

        <div class="panel bg-transparent shd-none bdr-rds-0 bdr-w-0 mt-sm mb-0">
            <div class="panel-heading">
            </div>
            <div class="panel-body pt-xs">
                <a class="btn btn-default" href="<?php echo $view['router']->path('mautic_page_index'); ?>">返回</a>
            </div>
        </div>


    </div>
    <!--/ right section -->
</div>

<!--/ end: box layout -->
