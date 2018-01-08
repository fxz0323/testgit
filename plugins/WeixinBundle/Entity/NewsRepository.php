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


class NewsRepository extends CommonRepository
{

    public function getCount($weixin)
    {
        $q = $this->createQueryBuilder('a')
            ->select('count(a.id) AS nb')
            ->where('a.weixin = '.$weixin->getId());

        return $q->getQuery()->getSingleScalarResult();
    }

}
