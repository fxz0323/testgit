<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

return [

    'name'        => 'Weixin',
    'description' => 'Weixin bundle',
    'author'      => 'Meng Xiao',
    'version'     => '0.1.0',
    'routes' => [
        'main' => [
            'mautic_weixin_auto_res' => [
                'path'         => '/weixin/auto-res',
                'controller'   => 'WeixinBundle:AutoRes:index',
            ],
            'mautic_weixin_settings' => [
                'path'         => '/weixin/settings',
                'controller'   => 'WeixinBundle:AutoRes:settings',
            ],
            'mautic_weixin_auto_res_followed_message' => [
                'path'         => '/weixin/auto-res/followed-message',
                'controller'   => 'WeixinBundle:AutoRes:followedMessage',
            ],
            'mautic_weixin_auto_res_new_rule' => [
                'path'         => '/weixin/auto-res/rule/new',
                'controller'   => 'WeixinBundle:AutoRes:newRule',
            ],
            'mautic_weixin_auto_res_edit_rule' => [
                'path'         => '/weixin/auto-res/rule/{id}/edit',
                'controller'   => 'WeixinBundle:AutoRes:editRule',
            ],
            'mautic_weixin_auto_res_edit_keyword' => [
                'path'         => '/weixin/auto-res/keyword/{id}/edit',
                'controller'   => 'WeixinBundle:AutoRes:editKeyword',
            ],
            'mautic_weixin_auto_res_delete_keyword' => [
                'path'         => '/weixin/auto-res/keyword/{id}/delete',
                'controller'   => 'WeixinBundle:AutoRes:deleteKeyword',
            ],
            'mautic_weixin_auto_res_delete_rule' => [
                'path'         => '/weixin/auto-res/rule/{id}/delete',
                'controller'   => 'WeixinBundle:AutoRes:deleteRule',
            ],
            'mautic_weixin_menu' => [
                'path'         => '/weixin/menu',
                'controller'   => 'WeixinBundle:Menu:index',
            ],
            'mautic_weixin_menu_edit_menu' => [
                'path'         => '/weixin/menu/edit/{id}',
                'controller'   => 'WeixinBundle:Menu:editMenu',
            ],
            'mautic_weixin_menu_edit_menu_item' => [
                'path'         => '/weixin/menu/edit/{menuId}/item/{id}',
                'controller'   => 'WeixinBundle:Menu:editMenuItem',
            ],
            'mautic_weixin_menu_delete_menu' => [
                'path'         => '/weixin/menu/delete/{id}',
                'controller'   => 'WeixinBundle:Menu:deleteMenu',
            ],
            'mautic_weixin_menu_delete_menu_item' => [
                'path'         => '/weixin/menu/delete/item/{id}',
                'controller'   => 'WeixinBundle:Menu:deleteMenuItem',
            ],
            'mautic_weixin_menu_update' => [
                'path'         => '/weixin/menu/update',
                'controller'   => 'WeixinBundle:Menu:updateMenu',
            ],
            'mautic_weixin_qrcode' => [
                'path'         => '/weixin/qrcode/{page}',
                'controller'   => 'WeixinBundle:Qrcode:index',
                'defaults' => ['page' => 1],
            ],
            'mautic_weixin_qrcode_new' => [
                'path'         => '/weixin/qrcode/new',
                'controller'   => 'WeixinBundle:Qrcode:new',
            ],
            'mautic_weixin_qrcode_show' => [
                'path'         => '/weixin/qrcode/show/{id}',
                'controller'   => 'WeixinBundle:Qrcode:show',
            ],
            'mautic_weixin_qrcode_download' => [
                'path'         => '/weixin/qrcode/download/{id}',
                'controller'   => 'WeixinBundle:Qrcode:download',
            ],
            'mautic_weixin_qrcode_delete' => [
                'path'         => '/weixin/qrcode/delete/{id}',
                'controller'   => 'WeixinBundle:Qrcode:delete',
            ],
            'mautic_weixin_qrcode_edit' => [
                'path'         => '/weixin/qrcode/edit/{id}',
                'controller'   => 'WeixinBundle:Qrcode:edit',
            ],
            'mautic_weixin_qrcode_logo' => [
                'path'         => '/weixin/qrcode/logo/{id}',
                'controller'   => 'WeixinBundle:Qrcode:logo',
            ],
            'mautic_weixin_article' => [
                'path'         => '/weixin/article/{page}',
                'controller'   => 'WeixinBundle:Article:index',
                'defaults' => ['page' => 1],
            ],
            'mautic_weixin_article_sync_all' => [
                'path'         => '/weixin/article/sync-all',
                'controller'   => 'WeixinBundle:Article:syncAll',
            ],
            'mautic_weixin_article_sync' => [
                'path'         => '/weixin/article/sync/{id}',
                'controller'   => 'WeixinBundle:Article:sync',
            ],
            'mautic_weixin_article_send' => [
                'path'         => '/weixin/article/send/{id}',
                'controller'   => 'WeixinBundle:Article:send',
            ],
            'mautic_weixin_article_send_schedule' => [
                'path'         => '/weixin/article/send-schedule/{id}',
                'controller'   => 'WeixinBundle:Article:sendSchedule',
            ],
            'mautic_weixin_choose_weixin' => [
                'path'         => '/weixin/choose-weixin',
                'controller'   => 'WeixinBundle:AutoRes:chooseWeixin',
            ],
            'mautic_weixin_open_oauth' => [
                'path'         => '/weixin-oauth',
                'controller'   => 'WeixinBundle:Open:oauthLogin',
            ],
            'mautic_weixin_open_auth_return' => [
                'path'         => '/weixin/oauth-return',
                'controller'   => 'WeixinBundle:Open:authReturn',
            ],
            'mautic_weixin_open_unlink' => [
                'path'         => '/weixin/unlink/{id}',
                'controller'   => 'WeixinBundle:Open:unlink',
            ],
            'mautic_weixin_sych_users' => [
                'path'         => '/weixin/sych/{id}/users',
                'controller'   => 'WeixinBundle:Open:sychUsers',
            ],
            'mautic_weixin_query_send' => [
                'path'         => '/weixin/query/send',
                'controller'   => 'WeixinBundle:Article:getCountLeadToSend',
            ],

        ],
        'public' => [
            'mautic_weixin_open_auth' => [
                'path'         => '/weixin/auth',
                'controller'   => 'WeixinBundle:Open:auth',
            ],
            'mautic_weixin_open_message' => [
                'path'         => '/weixin/{appId}/callback',
                'controller'   => 'WeixinBundle:Open:handleMessage',
            ],

        ]
    ],
    'menu' => [
        'main' => [
            'items' => [
                'mautic.channels.weixin' => [
                    'route'  => 'mautic_weixin_settings',
                    'access' => ['weixin:weixins:viewown', 'weixin:weixins:viewother'],
                    'parent' => 'mautic.core.channels',
//                    'checks' => [
//                        'integration' => [
//                            'Twilio' => [
//                                'enabled' => true,
//                            ],
//                        ],
//                    ],
                    'priority' => 70,
                ],
            ],
        ],
    ],
    'services' => [
        'others' => [
            'weixin.helper.message' => [
                'class'     => 'MauticPlugin\WeixinBundle\Service\MessageHelper',
                'arguments' => ['@weixin.api', '%kernel.root_dir%']
            ],
//            'weixin.open_application' => [
//                'class'     => 'MauticPlugin\WeixinBundle\Service\Application',
//                'arguments' => ['%mautic.weixin.open_configs%']
//            ],
            'weixin.api' => [
                'class'     => 'MauticPlugin\WeixinBundle\Service\Api',
                'arguments' => ['@doctrine', '@event_dispatcher', '%mautic.weixin.configs%']
            ],
        ],
        'forms' => [
            'weixin.form.type.pointaction_weixin' => [
                'class' => 'MauticPlugin\WeixinBundle\Form\Type\PointActionWeixinType',
                'arguments' => [
                    '@security.token_storage',
                    '@doctrine'
                ],
                'alias' => 'pointaction_weixin',
            ],
            'weixin.form.type.campaignevent_weixin' => [
                'class' => 'MauticPlugin\WeixinBundle\Form\Type\CampaignEventWeixinType',
                'arguments' => [
                    '@security.token_storage',
                    '@doctrine'
                ],
                'alias' => 'campaignevent_weixin',
            ],
        ],
        'events' => [
            'weixin.subscriber' => [
                'class'     => 'MauticPlugin\WeixinBundle\Subscriber\WeixinSubscriber',
                'arguments' => ['@doctrine', '@weixin.api', '@mautic.lead.model.lead']
            ],
            'weixin.point.subscriber' => [
                'class'     => 'MauticPlugin\WeixinBundle\Subscriber\PointSubscriber',
                'arguments' => [
                    'mautic.point.model.point',
                    '@doctrine'
                ],

            ],
            'weixin.campaign.subscriber' => [
                'class'     => 'MauticPlugin\WeixinBundle\Subscriber\CampaignSubscriber',
            ],
        ],
    ],
    'parameters' => [
//        'weixin.open_configs' => [
//            'debug'                => false,                        //是否调试模式
//            'component_app_id'     => 'wx8c1a03d2f7e747c8',           //第三方公众平台app id
//            'component_app_secret' => 'd12409c5c671eb649d9da1187b0e39db',       //第三方公众平台app secret
//            'token'                => '123123',                      //公众号消息校验Token
//            'aes_key'              => '1234567891234567891234567891234567891234567',                    //公众号消息加解密Key
//
//            'redirect_uri' => 'http://m-demo.linkall.sh.cn/s/weixin/oauth-return',                  //授权回调页面URI
//            'log' => [                                              //日志
//                'level' => 'debug',
//                'file'  => '/tmp/easyopenwechat.log',
//            ],
//        ],
        'weixin.configs' => [
            'debug'  => true,
            'log' => [
                'level'      => 'debug',
                'permission' => 0777,
                'file'       => '/tmp/easywechat.log',
            ],
            'open_platform' => [
                'app_id'   => 'wx8c1a03d2f7e747c8',
                'secret'   => 'd12409c5c671eb649d9da1187b0e39db',
                'token'    => '123123',
                'aes_key'  => '1234567891234567891234567891234567891234567',
            ],
            'material_dir' => '%kernel.root_dir%/../material/',
            'qrcode_dir' => '%kernel.root_dir%/../qrcode/',
        ]
    ]
];
