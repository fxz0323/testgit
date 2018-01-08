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
            'currentWeixin' => $currentWeixin, 'weixins' => $weixins,
        ]
    )
);

$pageButtons = [];

?>

    <div class="row">
        <div class="col-md-2 col-md-offset-10">
            <a href="<?php echo $view['router']->path('mautic_weixin_article_sync_all'); ?>" class="btn btn-default">同步公众号已有图文</a>
        </div>
    </div>

<?php if (count($items)): ?>
    <div class="table-responsive">
        <table class="table table-hover table-striped table-bordered">
            <thead>
            <tr>
                <th></th>
                <th>图</th>
                <th>文</th>
                <th>更新时间</th>
                <th>群发记录</th>
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
                                'href' => $view['router']->path('mautic_weixin_article_sync', ['id' => $item->getId()]),
                                'data-toggle' => 'ajax',
                                'data-method' => 'GET',
                            ],
                            'btnText' => '从微信同步更新',
                            'iconClass' => 'fa fa-refresh',
                        ];

                        $custom[] = [
                            'attr' => [
                                'href' => $view['router']->path('mautic_weixin_article_send_schedule', ['id' => $item->getId()]),
                                'data-toggle' => 'ajax',
                                'data-method' => 'GET',
                            ],
                            'btnText' => '定时群发',
                            'iconClass' => 'fa fa-send',
                        ];

                        $custom[] = [
                            'attr' => [
                                'href' => $view['router']->path('mautic_weixin_article_send', ['id' => $item->getId()]),
                                'data-toggle' => 'ajax',
                                'data-method' => 'GET',
                            ],
                            'btnText' => '群发',
                            'iconClass' => 'fa fa-send',
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
                    <td><?php echo '<img style="max-height:80px;" src="' . $view['assets']->getUrl($item->getItems()->first()->getThumbMedia()) . '">' ?></td>
                    <td><?php echo $item->getItems()->first()->getTitle() ?></td>
                    <td><?php echo $item->getUpdateTime()->format('Y-m-d H:i:s') ?></td>
                    <td>
                        <ul>
                            <?php
                            foreach ($item->getHistories() as $history) {
                                echo '<li>' . (string)$history . '</li>';
                            }
                            ?>
                        </ul>
                    </td>
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
            'baseUrl' => $view['router']->path('mautic_weixin_article'),
            'target' => '',
            'sessionVar' => 'article',
        ]); ?>
    </div>
<?php else: ?>
    <?php echo $view->render('MauticCoreBundle:Helper:noresults.html.php'); ?>
<?php endif; ?>