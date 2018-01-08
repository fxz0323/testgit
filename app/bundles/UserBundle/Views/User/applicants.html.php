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
$view['slots']->set('mauticContent', 'user');
$view['slots']->set('headerTitle', $view['translator']->trans('mautic.user.users'));

$view['slots']->set(
    'actions',
    $view->render(
        'MauticCoreBundle:Helper:page_actions.html.php',
        [
            'templateButtons' => [
                'new' => $permissions['create'],
            ],
            'routeBase' => 'user',
            'langVar' => 'user.user',
            'extraHtml' => '<a class="btn btn-default" href="'.$view['router']->path('mautic_user_applicants').'"><span>申请信息列表</span></a>',
        ]
    )
);
?>

<?php echo $view->render(
    'MauticCoreBundle:Helper:list_toolbar.html.php',
    [
        'searchValue' => $searchValue,
        'searchHelp' => 'mautic.user.user.help.searchcommands',
        'action' => $currentRoute,
    ]
); ?>

<div class="page-list">
    <div class="table-responsive">
        <table class="table table-hover table-striped table-bordered user-list" id="userTable">
            <thead>
            <tr>
                <?php
                echo $view->render(
                    'MauticCoreBundle:Helper:tableheader.html.php',
                    [
                        'checkall'        => 'true',
                        'target'          => '#userTable',
                        'langVar'         => 'user.user',
                        'routeBase'       => 'user',
                        'templateButtons' => [
                            'delete' => $permissions['delete'],
                        ],
                    ]
                );
                ?>
                <th class="visible-md visible-lg col-user-avatar"></th>
                <?php
                echo $view->render(
                    'MauticCoreBundle:Helper:tableheader.html.php',
                    [
                        'sessionVar' => 'user',
                        'orderBy'    => 'u.lastName, u.firstName, u.username',
                        'text'       => 'mautic.core.name',
                        'class'      => 'col-user-name',
                        'default'    => true,
                    ]
                );

                echo $view->render(
                    'MauticCoreBundle:Helper:tableheader.html.php',
                    [
                        'sessionVar' => 'user',
                        'orderBy'    => 'u.username',
                        'text'       => 'mautic.core.username',
                        'class'      => 'col-user-username',
                    ]
                );

                echo $view->render(
                    'MauticCoreBundle:Helper:tableheader.html.php',
                    [
                        'sessionVar' => 'user',
                        'orderBy'    => 'u.email',
                        'text'       => 'mautic.core.type.email',
                        'class'      => 'visible-md visible-lg col-user-email',
                    ]
                );

                echo $view->render(
                    'MauticCoreBundle:Helper:tableheader.html.php',
                    [
                        'sessionVar' => 'user',
                        'orderBy'    => 'r.name',
                        'text'       => 'mautic.user.role',
                        'class'      => 'visible-md visible-lg col-user-role',
                    ]
                );

                echo $view->render(
                    'MauticCoreBundle:Helper:tableheader.html.php',
                    [
                        'sessionVar' => 'user',
                        'orderBy'    => 'u.id',
                        'text'       => 'mautic.core.id',
                        'class'      => 'visible-md visible-lg col-user-id',
                    ]
                );
                ?>
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
                                'href' => $view['router']->path('mautic_user_action', ['objectAction' => 'active', 'objectId' => $item->getId()]),
                                'data-toggle' => 'ajax',
                                'data-method' => 'GET',
                            ],
                            'btnText' => $view['translator']->trans('mautic.user.active'),
                            'iconClass' => 'fa fa-user-circle-o'
                        ];

                        echo $view->render(
                            'MauticCoreBundle:Helper:list_actions.html.php',
                            [
                                'item'            => $item,
                                'templateButtons' => [
                                    'edit'   => $permissions['edit'],
                                    'delete' => $permissions['delete'],
                                ],
                                'customButtons' => $custom,
                                'routeBase' => 'user',
                                'langVar'   => 'user.user',
                                'pull'      => 'left',
                            ]
                        );
                        ?>
                    </td>
                    <td class="visible-md visible-lg">
                        <img class="img img-responsive img-thumbnail w-44" src="<?php echo $view['gravatar']->getImage($item->getEmail(), '50'); ?>"/>
                    </td>
                    <td>
                        <div>
                            <?php if ($permissions['edit']) : ?>
                                <a href="<?php echo $view['router']->path(
                                    'mautic_user_action',
                                    ['objectAction' => 'edit', 'objectId' => $item->getId()]
                                ); ?>" data-toggle="ajax">
                                    <?php echo $item->getName(true); ?>
                                </a>
                            <?php else : ?>
                                <?php echo $item->getName(true); ?>
                            <?php endif; ?>
                        </div>
                        <div class="small"><em><?php echo $item->getPosition(); ?></em></div>
                    </td>
                    <td><?php echo $item->getUsername(); ?></td>
                    <td class="visible-md visible-lg">
                        <a href="mailto: <?php echo $item->getEmail(); ?>"><?php echo $item->getEmail(); ?></a>
                    </td>
                    <td class="visible-md visible-lg"><?php echo $item->getRole() ? $item->getRole()->getName() : ''; ?></td>
                    <td class="visible-md visible-lg"><?php echo $item->getId(); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <div class="panel-footer">
            <?php echo $view->render(
                'MauticCoreBundle:Helper:pagination.html.php',
                [
                    'totalItems' => count($items),
                    'page'       => $page,
                    'limit'      => $limit,
                    'baseUrl'    => $view['router']->path('mautic_user_index'),
                    'sessionVar' => 'user',
                ]
            ); ?>
        </div>
    </div>

</div>
