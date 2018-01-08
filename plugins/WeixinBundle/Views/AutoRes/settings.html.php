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

?>

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
                                <td><?php echo $weixin->getCreateTime() ? $weixin->getCreateTime()->format('Y-m-d H:s:i'):''; ?></td>
                                <td><a href="<?php echo $view['router']->path('mautic_weixin_open_unlink', ['id' => $weixin->getId()] ) ?>"><i class="fa fa-chain-broken"></i></a></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
