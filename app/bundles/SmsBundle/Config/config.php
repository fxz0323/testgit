<?php

/*
 * @copyright   2016 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

return [
    'services' => [
        'events' => [
            'mautic.sms.campaignbundle.subscriber' => [
                'class' => 'Mautic\SmsBundle\EventListener\CampaignSubscriber',
                'arguments' => [
                    'mautic.helper.integration',
                    'mautic.sms.model.sms',
                ],
            ],
            'mautic.sms.reportbundle.subscriber' => [
                'class' => 'Mautic\SmsBundle\EventListener\ReportSubscriber',
                'arguments' => [
                    'doctrine.dbal.default_connection',
                ],
            ],
            'mautic.sms.smsbundle.subscriber' => [
                'class' => 'Mautic\SmsBundle\EventListener\SmsSubscriber',
                'arguments' => [
                    'mautic.core.model.auditlog',
                    'mautic.page.model.trackable',
                    'mautic.page.helper.token',
                    'mautic.asset.helper.token',
                ],
            ],
            'mautic.sms.channel.subscriber' => [
                'class' => \Mautic\SmsBundle\EventListener\ChannelSubscriber::class,
                'arguments' => [
                    'mautic.helper.integration',
                ],
            ],
            'mautic.sms.message_queue.subscriber' => [
                'class' => \Mautic\SmsBundle\EventListener\MessageQueueSubscriber::class,
                'arguments' => [
                    'mautic.sms.model.sms',
                ],
            ],
            'mautic.sms.stats.subscriber' => [
                'class' => \Mautic\SmsBundle\EventListener\StatsSubscriber::class,
                'arguments' => [
                    'doctrine.orm.entity_manager',
                ],
            ],
            'mautic.sms.points.subscriber' => [
                'class' => \Mautic\SmsBundle\EventListener\PointSubscriber::class,
                'arguments' => [
                    'mautic.point.model.point',
                ],
            ],


        ],
        'forms' => [
            'mautic.form.type.sms' => [
                'class' => 'Mautic\SmsBundle\Form\Type\SmsType',
                'arguments' => 'mautic.factory',
                'alias' => 'sms',
            ],
            'mautic.form.type.sign' => [
                'class' => 'Mautic\SmsBundle\Form\Type\SignType',
                'arguments' => 'mautic.factory',
                'alias' => 'sign',
            ],
            'mautic.form.type.smsconfig' => [
                'class' => 'Mautic\SmsBundle\Form\Type\ConfigType',
                'alias' => 'smsconfig',
            ],
            'mautic.form.type.smssend_list' => [
                'class' => 'Mautic\SmsBundle\Form\Type\SmsSendType',
                'arguments' => 'router',
                'alias' => 'smssend_list',
            ],
            'mautic.form.type.smssendinternal_list' => [
                'class' => 'Mautic\SmsBundle\Form\Type\SmsSendInternalType',
                'arguments' => 'router',
                'alias' => 'smssendinternal_list',
            ],
            'mautic.form.type.sms_list' => [
                'class' => 'Mautic\SmsBundle\Form\Type\SmsListType',
                'alias' => 'sms_list',
            ],
            'mautic.form.type.sign_list' => [
                'class' => 'Mautic\SmsBundle\Form\Type\SignListType',
                'arguments' => 'mautic.factory',
                'alias' => 'sign_list',
            ],
            'mautic.form.type.sms_patch_send' => [
                'class' => 'Mautic\SmsBundle\Form\Type\SmsPatchSendType',
                'arguments' => 'mautic.factory',
                'alias' => 'smspatch',
            ],
            'mautic.form.type.sms_test_send' => [
                'class' => 'Mautic\SmsBundle\Form\Type\SmsTestSendType',
                'arguments' => 'mautic.factory',
                'alias' => 'smstest',
            ],
            'mautic.form.type.sms_opem_list' => [
                'class' => 'Mautic\SmsBundle\Form\Type\SmsOpenType',
                'arguments' => 'mautic.factory',
                'alias' => 'smsopen_list',
            ],
            'mautic.form.type.campaignevent_sms' => [
                'class' => 'Mautic\SmsBundle\Form\Type\CampaignEventSmsType',
                'arguments' => [
                    '@security.token_storage',
                    '@doctrine'
                ],
                'alias' => 'campaignevent_sms',
            ],
        ],
        'helpers' => [
            'mautic.helper.sms' => [
                'class' => 'Mautic\SmsBundle\Helper\SmsHelper',
                'arguments' => [
                    'doctrine.orm.entity_manager',
                    'mautic.lead.model.lead',
                    'mautic.helper.phone_number',
                    'mautic.sms.model.sms',
                    'mautic.helper.integration',
                ],
                'alias' => 'sms_helper',
            ],
        ],
        'other' => [
            'mautic.sms.api' => [
//                'class'     => 'Mautic\SmsBundle\Api\TwilioApi',
                'class' => 'Mautic\SmsBundle\Api\EmayApi',
                'arguments' => [
                    'mautic.page.model.trackable',
                    'mautic.helper.phone_number',
                    'mautic.helper.integration',
                    'monolog.logger.mautic',
                ],
                'alias' => 'sms_api',
            ],
        ],
        'models' => [
            'mautic.sms.model.sms' => [
                'class' => 'Mautic\SmsBundle\Model\SmsModel',
                'arguments' => [
                    'mautic.page.model.trackable',
                    'mautic.lead.model.lead',
                    'mautic.channel.model.queue',
                    'mautic.sms.api',
                ],
            ],
            'mautic.sign.model.sign' => [
                'class' => 'Mautic\SmsBundle\Model\SignModel',
                'arguments' => [

                ],
            ],
        ],
        'integrations' => [
            'mautic.integration.twilio' => [
                'class' => \Mautic\SmsBundle\Integration\TwilioIntegration::class,
            ],
            'mautic.integration.emay' => [
                'class' => \Mautic\SmsBundle\Integration\EmayIntegration::class,
            ]
        ],
    ],
    'routes' => [
        'main' => [
            'mautic_sms_index' => [
                'path' => '/sms/{page}',
                'controller' => 'MauticSmsBundle:Sms:index',
            ],
            'mautic_sms_action' => [
                'path' => '/sms/{objectAction}/{objectId}',
                'controller' => 'MauticSmsBundle:Sms:execute',
            ],
            'mautic_sms_slist' => [
                'path' => '/sms/view/{objectId}/slist/{page}',
                'controller' => 'MauticSmsBundle:Sms:slist',
            ],
            'mautic_sms_contacts' => [
                'path' => '/sms/view/{objectId}/contact/{page}',
                'controller' => 'MauticSmsBundle:Sms:contacts',
            ],
            'mautic_sms_sign_index' => [
                'path' => '/sms_sign/{page}',
                'controller' => 'MauticSmsBundle:Sign:index',
            ],
            'mautic_sms_sign_action' => [
                'path' => '/sms_sign/{objectAction}/{objectId}',
                'controller' => 'MauticSmsBundle:Sign:execute',
            ],
        ],
        'public' => [
            'mautic_receive_sms' => [
                'path' => '/sms/receive',
                'controller' => 'MauticSmsBundle:Api\SmsApi:receive',
            ],
        ],
        'api' => [
            'mautic_api_smsesstandard' => [
                'standard_entity' => true,
                'name' => 'smses',
                'path' => '/smses',
                'controller' => 'MauticSmsBundle:Api\SmsApi',
            ],
        ],
    ],
    'menu' => [
        'main' => [
            'items' => [
                'mautic.sms.smses' => [
                    'route' => 'mautic_sms_index',
                    'access' => ['sms:smses:viewown', 'sms:smses:viewother'],
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
    'parameters' => [
        'sms_enabled' => false,
        'sms_username' => null,
        'sms_password' => null,
        'sms_sending_phone_number' => null,
        'sms_frequency_number' => null,
        'sms_frequency_time' => null,
    ],
];
