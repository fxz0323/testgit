<?php
/**
 * Created by PhpStorm.
 * User: hackc
 * Date: 2017-08-08
 * Time: 11:01
 */

namespace Mautic\SmsBundle\Command;


use Mautic\CoreBundle\Command\ModeratedCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Routing\RequestContext;

class SmsQueueCommand extends ModeratedCommand
{
    protected function configure()
    {
        $this
            ->setName('mautic:smses:send')
            ->setDescription('Processes Sms\'s queue')
            ->addOption('--message-id','-m',InputOption::VALUE_OPTIONAL,'The message id.')
            ->addOption('--segment-id','-se',InputOption::VALUE_OPTIONAL,'The segment id.');

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $processed  =   0;
        $container = $this->getContainer();

        $translator = $container->get('translator');
        $segmentId = $input->getoption('segment-id');
        $messageId = $input->getOption('message-id');
        $em         = $container->get('doctrine')->getManager();

        if (!$this->checkRunStatus($input, $output, 1)) {
            return 0;
        }



        /** @var \Mautic\SmsBundle\Model\SmsModel $model */
        $model = $container->get('mautic.sms.model.sms');

        $events = $model->getEventRepository()->getEntities(
            [
                'iterator_mode' => true,
                'filter' => [
                    'force' => [
                        [
                            'column' => 'e.status',
                            'expr'   => 'eq',
                            'value'  => 0,
                        ],
                    ],
                ],
            ]
        );

        while (($e = $events->next()) !== false) {
            $e = reset($e);

            $properties = $e->getProperties();

            //Sending
            $now = new \DateTime();

            $output->writeln('<info>'.$translator->trans('mautic.sms.schedule.sending', ['%id%' => $e->getId()]).'</info>');


            if($e->getTriggerDate() < $now)
            {
                //Sending
                /** @var \Mautic\SmsBundle\Entity\Sms $sms */
                $sms = $e->getSms();
                $listModel = $container->get('mautic.lead.model.list');
                $lead_list = $listModel->getEntity($properties['segment_id']);
                $leads = $listModel->getLeadsByList($lead_list);
                /** @var \Mautic\LeadBundle\Model\LeadModel $leadModel */
                $leadModel = $container->get('mautic.lead.model.lead');
                $sendCount = 0;

                $e->setStatus(1);
                $e->setSendCount($sendCount);
                $model->getEventRepository()->saveEntity($e);

                foreach ($leads as $group){
                    foreach($group as $lead){
                        $lead = $leadModel->getEntity($lead['id']);
                        $ret = $model->sendSms($sms,$lead);
                        $sendCount++;
                    }
                }
                $e->setStatus(2);
                $e->setSendCount($sendCount);
                $model->getEventRepository()->saveEntity($e);
            }

            $em->detach($e);
            unset($e);
        }

        unset($events);



        $this->completeRun();

        return 0;
    }
}