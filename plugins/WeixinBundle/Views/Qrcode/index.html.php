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

<div class="row">
    <div class="col-md-2 col-md-offset-10">
        <a href="<?php echo $view['router']->path('mautic_weixin_qrcode_new'); ?>" class="btn btn-default">新建二维码</a>
    </div>
</div>

<?php if (count($items)): ?>
    <div class="table-responsive">
        <table class="table table-hover table-striped table-bordered">
            <thead>
            <tr>
                <th></th>
                <th>二维码名称</th>
                <th>公众号</th>
                <th>标签</th>
                <th>二维码</th>
                <th>回复内容</th>
                <th>创建时间</th>

            </tr>
            </thead>
            <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td>
                        <?php
                        $custom = [];

                        $custom[] = [
                            'attr' => [
                                'href' => $view['router']->path('mautic_weixin_qrcode_delete', ['id' => $item->getId()]),
                                'data-toggle' => 'ajax',
                                'data-method' => 'GET',
                            ],
                            'btnText' => $view['translator']->trans('mautic.core.form.delete'),
                        ];

                        $custom[] = [
                            'attr' => [
                                'href' => $view['router']->path('mautic_weixin_qrcode_edit', ['id' => $item->getId()]),
                                'data-toggle' => 'ajax',
                                'data-method' => 'GET',
                            ],
                            'btnText' => $view['translator']->trans('mautic.core.form.edit'),
                            'iconClass' => 'fa fa-pencil-square-o',

                        ];
                        echo $view->render('MauticCoreBundle:Helper:list_actions.html.php', [
                            'item' => $item,
                            'templateButtons' => [

                            ],
                            'routeBase' => 'contact',
                            'langVar' => 'lead.lead',
                            'customButtons' => $custom,
                            'nameGetter' => 'getId'
                        ]);
                        ?>
                    </td>
                    <td><a href="<?php echo $view['router']->path('mautic_weixin_qrcode_show', ['id' => $item->getId()])?>"><?php echo $item->getName() ?></a></td>
                    <td><?php echo $item->getWeixin() ?></td>
                    <td><?php echo $item->getTag() ?></td>
                    <td><?php $qrCode = new \Endroid\QrCode\QrCode($item->getUrl()); echo '<img style="max-height:80px;" src="'.$qrCode->writeDataUri().'">'?></td>
                    <td><?php echo $item->getMessage() ?></td>
                    <td><?php echo $item->getCreateTime()->format('Y-m-d') ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="panel-footer">
        <?php echo $view->render('MauticCoreBundle:Helper:pagination.html.php', [
            'totalItems' => $totalItems,
            'page' => $page,
            'limit' => 10,
            'menuLinkId' => 'mautic_weixin_qrcode',
            'baseUrl' => $view['router']->path('mautic_weixin_qrcode'),
            'tmpl' => 'list',
            'sessionVar' => 'qrcode',
        ]); ?>
    </div>
<?php else: ?>
    <?php echo $view->render('MauticCoreBundle:Helper:noresults.html.php'); ?>
<?php endif; ?>