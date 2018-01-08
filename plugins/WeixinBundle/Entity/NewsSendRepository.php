<?php

/*
 * @copyright   2016 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\WeixinBundle\Entity;

use Mautic\CoreBundle\Entity\CommonRepository;


class NewsSendRepository extends CommonRepository
{

    public function findArticlesToSend()
    {
        $q = $this->createQueryBuilder('a')
            ->where('a.hasSent = 0')
            ->andWhere('a.sendTime <= :now')
            ->andWhere('a.sendTime > :five')
            ->setParameter('now', (new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s'))
            ->setParameter('five', (new \DateTime('now', new \DateTimeZone('UTC')))->modify('-1 minutes')->format('Y-m-d H:i:s'));

        return $q->getQuery()->getResult();
    }

}
