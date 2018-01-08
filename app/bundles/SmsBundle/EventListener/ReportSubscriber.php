<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\SmsBundle\EventListener;

use Doctrine\DBAL\Connection;
use Mautic\CoreBundle\EventListener\CommonSubscriber;
use Mautic\CoreBundle\Helper\Chart\LineChart;
use Mautic\CoreBundle\Helper\Chart\PieChart;
use Mautic\LeadBundle\Entity\DoNotContact;
use Mautic\ReportBundle\Event\ReportBuilderEvent;
use Mautic\ReportBundle\Event\ReportGeneratorEvent;
use Mautic\ReportBundle\Event\ReportGraphEvent;
use Mautic\ReportBundle\ReportEvents;

/**
 * Class ReportSubscriber.
 */
class ReportSubscriber extends CommonSubscriber
{
    /**
     * @var Connection
     */
    protected $db;

    /**
     * ReportSubscriber constructor.
     *
     * @param Connection $db
     */
    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            ReportEvents::REPORT_ON_BUILD          => ['onReportBuilder', 0],
            ReportEvents::REPORT_ON_GENERATE       => ['onReportGenerate', 0],
            ReportEvents::REPORT_ON_GRAPH_GENERATE => ['onReportGraphGenerate', 0],
        ];
    }

    /**
     * Add available tables and columns to the report builder lookup.
     *
     * @param ReportBuilderEvent $event
     */
    public function onReportBuilder(ReportBuilderEvent $event)
    {
        if ($event->checkContext(['smss', 'sms.stats'])) {
            $prefix               = 'e.';
            $variantParent        = 'vp.';
            $channelUrlTrackables = 'cut.';
            $doNotContact         = 'dnc.';
            $columns              = [
                $prefix.'name' => [
                    'label' => 'mautic.email.subject',
                    'type'  => 'string',
                ],
                $prefix.'lang' => [
                    'label' => 'mautic.core.language',
                    'type'  => 'string',
                ],

                $prefix.'sent_count' => [
                    'label' => 'mautic.email.report.sent_count',
                    'type'  => 'int',
                ],
                'hits' => [
                    'alias'   => 'hits',
                    'label'   => 'mautic.email.report.hits_count',
                    'type'    => 'string',
                    'formula' => $channelUrlTrackables.'hits',
                ],
                'unique_hits' => [
                    'alias'   => 'unique_hits',
                    'label'   => 'mautic.email.report.unique_hits_count',
                    'type'    => 'string',
                    'formula' => $channelUrlTrackables.'unique_hits',
                ],
                'hits_ratio' => [
                    'alias'   => 'hits_ratio',
                    'label'   => 'mautic.email.report.hits_ratio',
                    'type'    => 'string',
                    'formula' => 'CONCAT(ROUND('.$channelUrlTrackables.'hits/('.$prefix.'sent_count * '.$channelUrlTrackables
                        .'trackable_count)*100),\'%\')',
                ],
                'unique_ratio' => [
                    'alias'   => 'unique_ratio',
                    'label'   => 'mautic.email.report.unique_ratio',
                    'type'    => 'string',
                    'formula' => 'CONCAT(ROUND('.$channelUrlTrackables.'unique_hits/('.$prefix.'sent_count * '.$channelUrlTrackables
                        .'trackable_count)*100),\'%\')',
                ],
                'unsubscribed' => [
                    'alias'   => 'unsubscribed',
                    'label'   => 'mautic.email.report.unsubscribed',
                    'type'    => 'string',
                    'formula' => 'COUNT(DISTINCT '.$doNotContact.'id)',
                ],
                'unsubscribed_ratio' => [
                    'alias'   => 'unsubscribed_ratio',
                    'label'   => 'mautic.email.report.unsubscribed_ratio',
                    'type'    => 'string',
                    'formula' => 'CONCAT(ROUND((COUNT(DISTINCT '.$doNotContact.'id)/'.$prefix.'sent_count)*100),\'%\')',
                ],
            ];
            $columns = array_merge(
                $columns,
                $event->getStandardColumns($prefix, [], 'mautic_sms_action'),
                $event->getCategoryColumns(),
                $event->getCampaignByChannelColumns()
            );
            $data = [
                'display_name' => 'mautic.sms.smss',
                'columns'      => $columns,
            ];
            $event->addTable('smss', $data);

            if ($event->checkContext('sms.stats')) {
                // Ratios are not applicable for individual stats
                unset($columns['read_ratio'], $columns['unsubscribed_ratio'], $columns['hits_ratio'], $columns['unique_ratio']);

                // sms counts are not applicable for individual stats
                unset($columns[$prefix.'read_count'], $columns[$prefix.'variant_sent_count'], $columns[$prefix.'variant_read_count']);

                // Prevent null DNC records from filtering the results
                $columns['unsubscribed']['formula'] = 'dnc.reason';

                $statPrefix  = 'es.';
                $statColumns = [
                    $statPrefix.'date_sent' => [
                        'label' => 'mautic.sms.report.stat.date_sent',
                        'type'  => 'datetime',
                    ],
                    $statPrefix.'source' => [
                        'label' => 'mautic.report.field.source',
                        'type'  => 'string',
                    ],
                    $statPrefix.'source_id' => [
                        'label' => 'mautic.report.field.source_id',
                        'type'  => 'int',
                    ],
                ];

                $data = [
                    'display_name' => 'mautic.sms.stats.report.table',
                    'columns'      => array_merge(
                        $columns,
                        $statColumns,
                        $event->getLeadColumns(),
                        $event->getIpColumn()
                    ),
                ];
                $event->addTable('sms.stats', $data, 'smss');

                // Register Graphs
//                $context = 'sms.stats';
//                $event->addGraph($context, 'line', 'mautic.sms.graph.line.stats');
//                $event->addGraph($context, 'pie', 'mautic.sms.graph.pie.ignored.read.failed');
//                $event->addGraph($context, 'table', 'mautic.sms.table.most.smss.sent');
//                $event->addGraph($context, 'table', 'mautic.sms.table.most.smss.read');
//                $event->addGraph($context, 'table', 'mautic.sms.table.most.smss.failed');
//                $event->addGraph($context, 'table', 'mautic.sms.table.most.smss.read.percent');
            }
        }
    }

    /**
     * Initialize the QueryBuilder object to generate reports from.
     *
     * @param ReportGeneratorEvent $event
     */
    public function onReportGenerate(ReportGeneratorEvent $event)
    {
        $context    = $event->getContext();
        $qb         = $event->getQueryBuilder();
        $hasGroupBy = $event->hasGroupBy();

        // channel_url_trackables subquery
        $qbcut        = $this->db->createQueryBuilder();
        $clickColumns = ['hits', 'unique_hits', 'hits_ratio', 'unique_ratio'];
        $dncColumns   = ['unsubscribed', 'unsubscribed_ratio'];

        switch ($context) {
            case 'smss':
                $qb->from(MAUTIC_TABLE_PREFIX.'sms_messages', 'e');
//                   ->leftJoin('e', MAUTIC_TABLE_PREFIX.'smss', 'vp', 'vp.id = e.variant_parent_id');

                $event->addCategoryLeftJoin($qb, 'e');

                if (!$hasGroupBy) {
                    $qb->groupBy('e.id');
                }
                if ($event->hasColumn($clickColumns) || $event->hasFilter($clickColumns)) {
                    $qbcut->select(
                        'COUNT(cut2.channel_id) AS trackable_count, SUM(r.hits) AS hits',
                        'SUM(r.unique_hits) AS unique_hits',
                        'cut2.channel_id'
                    )
                            ->from(MAUTIC_TABLE_PREFIX.'page_redirects', 'r')
                            ->innerJoin('r', MAUTIC_TABLE_PREFIX.'channel_url_trackables', 'cut2',

                                    $qbcut->expr()->eq('r.id', 'cut2.redirect_id')

                            )
                          ->where('cut2.channel = \'sms\'')
                          ->groupBy('cut2.channel_id');
                    $qb->leftJoin('e', sprintf('(%s)', $qbcut->getSQL()), 'cut', 'e.id = cut.channel_id');
                }

                if ($event->hasColumn($dncColumns) || $event->hasFilter($dncColumns)) {
                    $qb->leftJoin(
                        'e',
                        MAUTIC_TABLE_PREFIX.'lead_donotcontact',
                        'dnc',
                        'e.id = dnc.channel_id and dnc.channel=\'sms\' and dnc.reason='.DoNotContact::UNSUBSCRIBED
                    );
                }

                $event->addCampaignByChannelJoin($qb, 'e', 'sms');

                break;
            case 'sms.stats':
                $qb->from(MAUTIC_TABLE_PREFIX.'sms_message_stats', 'es')
                   ->leftJoin('es', MAUTIC_TABLE_PREFIX.'sms_messages', 'e', 'e.id = es.sms_id');
//                   ->leftJoin('e', MAUTIC_TABLE_PREFIX.'smss', 'vp', 'vp.id = e.variant_parent_id');

                $event->addCategoryLeftJoin($qb, 'e')
                      ->addLeadLeftJoin($qb, 'es')
                      ->addIpAddressLeftJoin($qb, 'es')
                      ->applyDateFilters($qb, 'date_sent', 'es');

                if ($event->hasColumn($clickColumns) || $event->hasFilter($clickColumns)) {
                    $qbcut->select('COUNT(ph.id) AS hits', 'COUNT(DISTINCT(ph.redirect_id)) AS unique_hits', 'cut2.channel_id', 'ph.lead_id')
                          ->from(MAUTIC_TABLE_PREFIX.'channel_url_trackables', 'cut2')
                          ->join(
                              'cut2',
                              MAUTIC_TABLE_PREFIX.'page_hits',
                              'ph',
                              'cut2.redirect_id = ph.redirect_id AND cut2.channel_id = ph.source_id'
                          )
                          ->where('cut2.channel = \'sms\' AND ph.source = \'sms\'')
                          ->groupBy('cut2.channel_id, ph.lead_id');
                    $qb->leftJoin('e', sprintf('(%s)', $qbcut->getSQL()), 'cut', 'e.id = cut.channel_id AND es.lead_id = cut.lead_id');
                }

                if ($event->hasColumn($dncColumns) || $event->hasFilter($dncColumns)) {
                    $qb->leftJoin(
                        'e',
                        MAUTIC_TABLE_PREFIX.'lead_donotcontact',
                        'dnc',
                        'e.id = dnc.channel_id AND dnc.channel=\'sms\' AND dnc.reason='.DoNotContact::UNSUBSCRIBED.' AND es.lead_id = dnc.lead_id'
                    );
                }

                $event->addCampaignByChannelJoin($qb, 'e', 'sms');

                break;
        }

        $event->setQueryBuilder($qb);
    }

    /**
     * Initialize the QueryBuilder object to generate reports from.
     *
     * @param ReportGraphEvent $event
     */
    public function onReportGraphGenerate(ReportGraphEvent $event)
    {
        // Context check, we only want to fire for Lead reports
        if (!$event->checkContext('sms.stats')) {
            return;
        }

        $graphs   = $event->getRequestedGraphs();
        $qb       = $event->getQueryBuilder();
        $statRepo = $this->em->getRepository('MauticsmsBundle:Stat');
        foreach ($graphs as $g) {
            $options      = $event->getOptions($g);
            $queryBuilder = clone $qb;
            $chartQuery   = clone $options['chartQuery'];
            $origQuery    = clone $queryBuilder;
            $chartQuery->applyDateFilters($queryBuilder, 'date_sent', 'es');

            switch ($g) {
                case 'mautic.sms.graph.line.stats':
                    $chart     = new LineChart(null, $options['dateFrom'], $options['dateTo']);
                    $sendQuery = clone $queryBuilder;
                    $readQuery = clone $origQuery;
                    $readQuery->andWhere($qb->expr()->isNotNull('date_read'));
                    $failedQuery = clone $queryBuilder;
                    $failedQuery->andWhere($qb->expr()->eq('es.is_failed', ':true'));
                    $failedQuery->setParameter('true', true, 'boolean');
                    $chartQuery->applyDateFilters($readQuery, 'date_read', 'es');
                    $chartQuery->modifyTimeDataQuery($sendQuery, 'date_sent', 'es');
                    $chartQuery->modifyTimeDataQuery($readQuery, 'date_read', 'es');
                    $chartQuery->modifyTimeDataQuery($failedQuery, 'date_sent', 'es');
                    $sends  = $chartQuery->loadAndBuildTimeData($sendQuery);
                    $reads  = $chartQuery->loadAndBuildTimeData($readQuery);
                    $failes = $chartQuery->loadAndBuildTimeData($failedQuery);
                    $chart->setDataset($options['translator']->trans('mautic.sms.sent.smss'), $sends);
                    $chart->setDataset($options['translator']->trans('mautic.sms.read.smss'), $reads);
                    $chart->setDataset($options['translator']->trans('mautic.sms.failed.smss'), $failes);
                    $data         = $chart->render();
                    $data['name'] = $g;

                    $event->setGraph($g, $data);
                    break;

                case 'mautic.sms.graph.pie.ignored.read.failed':
                    $counts = $statRepo->getIgnoredReadFailed($queryBuilder);
                    $chart  = new PieChart();
                    $chart->setDataset($options['translator']->trans('mautic.sms.read.smss'), $counts['read']);
                    $chart->setDataset($options['translator']->trans('mautic.sms.failed.smss'), $counts['failed']);
                    $chart->setDataset($options['translator']->trans('mautic.sms.ignored.smss'), $counts['ignored']);
                    $event->setGraph(
                        $g,
                        [
                            'data'      => $chart->render(),
                            'name'      => $g,
                            'iconClass' => 'fa-flag-checkered',
                        ]
                    );
                    break;

                case 'mautic.sms.table.most.smss.sent':
                    $queryBuilder->select('e.id, e.subject as title, count(es.id) as sent')
                                 ->groupBy('e.id, e.subject')
                                 ->orderBy('sent', 'DESC');
                    $limit                  = 10;
                    $offset                 = 0;
                    $items                  = $statRepo->getMostsmss($queryBuilder, $limit, $offset);
                    $graphData              = [];
                    $graphData['data']      = $items;
                    $graphData['name']      = $g;
                    $graphData['iconClass'] = 'fa-paper-plane-o';
                    $graphData['link']      = 'mautic_sms_action';
                    $event->setGraph($g, $graphData);
                    break;

                case 'mautic.sms.table.most.smss.read':
                    $queryBuilder->select('e.id, e.subject as title, count(CASE WHEN es.is_read THEN 1 ELSE null END) as "read"')
                                 ->groupBy('e.id, e.subject')
                                 ->orderBy('"read"', 'DESC');
                    $limit                  = 10;
                    $offset                 = 0;
                    $items                  = $statRepo->getMostsmss($queryBuilder, $limit, $offset);
                    $graphData              = [];
                    $graphData['data']      = $items;
                    $graphData['name']      = $g;
                    $graphData['iconClass'] = 'fa-eye';
                    $graphData['link']      = 'mautic_sms_action';
                    $event->setGraph($g, $graphData);
                    break;

                case 'mautic.sms.table.most.smss.failed':
                    $queryBuilder->select('e.id, e.subject as title, count(CASE WHEN es.is_failed THEN 1 ELSE null END) as failed')
                                 ->having('count(CASE WHEN es.is_failed THEN 1 ELSE null END) > 0')
                                 ->groupBy('e.id, e.subject')
                                 ->orderBy('failed', 'DESC');
                    $limit                  = 10;
                    $offset                 = 0;
                    $items                  = $statRepo->getMostsmss($queryBuilder, $limit, $offset);
                    $graphData              = [];
                    $graphData['data']      = $items;
                    $graphData['name']      = $g;
                    $graphData['iconClass'] = 'fa-exclamation-triangle';
                    $graphData['link']      = 'mautic_sms_action';
                    $event->setGraph($g, $graphData);
                    break;

                case 'mautic.sms.table.most.smss.read.percent':
                    $queryBuilder->select('e.id, e.subject as title, round(e.read_count / e.sent_count * 100) as ratio')
                                 ->groupBy('e.id, e.subject')
                                 ->orderBy('ratio', 'DESC');
                    $limit                  = 10;
                    $offset                 = 0;
                    $items                  = $statRepo->getMostsmss($queryBuilder, $limit, $offset);
                    $graphData              = [];
                    $graphData['data']      = $items;
                    $graphData['name']      = $g;
                    $graphData['iconClass'] = 'fa-tachometer';
                    $graphData['link']      = 'mautic_sms_action';
                    $event->setGraph($g, $graphData);
                    break;
            }
            unset($queryBuilder);
        }
    }
}
