<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\WeixinBundle\Command;

use Mautic\CampaignBundle\CampaignEvents;
use Mautic\CampaignBundle\Entity\Campaign;
use Mautic\CampaignBundle\Event\CampaignTriggerEvent;
use Mautic\CoreBundle\Command\ModeratedCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

class SendArticleCommand extends ModeratedCommand
{
    /**
     * @var EventDispatcher
     */
    protected $dispatcher;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('weixin:send:article');

        parent::configure();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();

        $sends = $container->get('doctrine')->getManager()->getRepository('MauticPlugin\WeixinBundle\Entity\NewsSend')->findArticlesToSend();

        $output->writeln(count($sends));
        foreach ($sends as $send){
            $container->get('weixin.api')->sendArticleDirect($send);
        }

    }

}
