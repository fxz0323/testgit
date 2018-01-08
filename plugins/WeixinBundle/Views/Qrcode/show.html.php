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
$view['slots']->set('headerTitle', $view['translator']->trans('weixin.qrcode'));

$view['slots']->set(
    'actions',
    $view->render(
        'WeixinBundle:Common:switcher.html.php',
        [
            'currentWeixin' => $currentWeixin, 'weixins' => $weixins,
        ]
    )
);

$pageButtons = [];

?>


<div class="col-md-12 bg-white height-auto">
    <div class="row">
        <div class="col-md-12">
            <table class="table table-condensed">
                <tr>
                    <th>二维码名称</th>
                    <td><?php echo $qrcode->getName() ?></td>
                </tr>
                <tr>
                    <th>公众号</th>
                    <td><?php echo $qrcode->getWeixin() ?></td>
                </tr>
                <tr>
                    <th>标签</th>
                    <td><?php echo $qrcode->getTag() ?></td>
                </tr>
                <tr>
                    <th>关联联系人字段</th>
                    <td><?php echo $qrcode->getLeadField1() ? $fields[$qrcode->getLeadField1()] : '' ?></td>
                </tr>
                <tr>
                    <th>值</th>
                    <td><?php echo $qrcode->getLeadField1Value() ?></td>
                </tr>
                <tr>
                    <th>关联联系人字段</th>
                    <td><?php echo $qrcode->getLeadField2() ? $fields[$qrcode->getLeadField2()] : '' ?></td>
                </tr>
                <tr>
                    <th>值</th>
                    <td><?php echo $qrcode->getLeadField2Value() ?></td>
                </tr>
                <tr>
                    <th>二维码</th>
                    <td><img style="max-height:150px;" src="<?php echo $view['assets']->getUrl($qrcode->getImage()); ?>"></td>
                <tr>
                <tr>
                    <th>Logo</th>
                    <td><img style="max-height:150px;" src="<?php echo $view['assets']->getUrl($qrcode->getLogo()); ?>"></td>
                <tr>
                <tr>
                    <th>二维码</th>
                    <td><img style="max-height:150px;" src="<?php echo $view['assets']->getUrl($qrcode->getFinalImage()); ?>"></td>
                <tr>
                <tr>
                    <th>访问人数</th>
                    <td><?php echo count($qrcode->getScans()) ?></td>
                <tr>
                <tr>
                    <th>关注人数</th>
                    <td><?php echo $qrcode->getSubNb() ?></td>
                <tr>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-condensed">
                <tr>
                    <th>联系人</th>
                    <th>扫码地址</th>
                    <th>扫码时间</th>
                </tr>
                <?php foreach ($qrcode->getScans() as $scan): ?>
                    <tr>
                        <td><?php echo $scan->getLead() ? $model->getLeadDetails($scan->getLead())['core']['wechat_nickname']['value'] : '未知联系人'; ?></td>
                        <td><?php echo empty($scan->getCity()) ? '未知地点' : $scan->getProvince() . $scan->getCity() ?></td>
                        <td><?php echo $scan->getScanTime()->format('Y-m-d H:i:s') ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <a class="btn btn-success" href="<?php echo $view['router']->path('mautic_weixin_qrcode_logo', ['id' => $qrcode->getId()]); ?>">Logo</a>
            <a class="btn btn-default" href="<?php echo $view['router']->path('mautic_weixin_qrcode_download', ['id' => $qrcode->getId()]); ?>">下载二维码</a>
        </div>
    </div>
</div>