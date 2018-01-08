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


class LeadWeixinRepository extends CommonRepository
{

    public function findOpenidsInListWeixin($listId, $weixinId)
    {
        $sql = 'SELECT openid FROM lead_weixin w LEFT JOIN lead_lists_leads l ON w.contact_id = l.lead_id WHERE w.weixin_id = '.$weixinId.' AND l.leadlist_id = '. $listId;
        $q = $this->_em->getConnection()->fetchAll($sql);

        $data = [];
        foreach ($q as $item) {
            $data[] = $item['openid'];
        }

        return $data;
    }

    public function findOpenidsInWeixin($weixinId)
    {
        $sql = 'SELECT openid FROM lead_weixin w WHERE w.weixin_id = '.$weixinId;
        $q = $this->_em->getConnection()->fetchAll($sql);

        foreach ($q as $item) {
            $data[] = $item['openid'];
        }

        return $data;
    }

}
