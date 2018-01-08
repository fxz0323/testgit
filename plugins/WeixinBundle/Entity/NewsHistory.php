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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Mautic\ApiBundle\Serializer\Driver\ApiMetadataDriver;
use Mautic\CoreBundle\Doctrine\Mapping\ClassMetadataBuilder;
use Mautic\CoreBundle\Entity\FormEntity;
use Mautic\LeadBundle\Entity\LeadList;
use Mautic\LeadBundle\Form\Validator\Constraints\LeadListAccess;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * Class Weixin.
 */
class NewsHistory
{

    const TYPE_DERICT = 'direct';
    const TYPE_SCHEDULE = 'schedule';

    /**
     * @var int
     */
    private $id;

    private $news;

    private $time;

    private $type;

    private $sendType;

    private $list;

    public function __construct()
    {

    }

    public static function loadMetadata(ORM\ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder->setTable('weixin_news_history');

        $builder->addId();

        $builder->createField('time', 'datetime')
            ->columnName('time')
            ->nullable()
            ->build();

        $builder->createField('type', 'string')
            ->columnName('type')
            ->build();

        $builder->createField('sendType', 'string')
            ->columnName('send_type')
            ->build();

        $builder->createManyToOne('news', 'News')
            ->addJoinColumn('news_id', 'id', false, false, 'CASCADE')
            ->build();

        $builder->createManyToOne('list', 'Mautic\LeadBundle\Entity\LeadList')
            ->addJoinColumn('list_id', 'id', true, false, 'CASCADE')
            ->build();
    }

    public function __toString()
    {
        if ($this->getSendType() == 'all') {
            return $this->getTime()->format('Y-m-d H:i:s') . ' (全部)';
        }
        if ($this->getSendType() == 'group') {
            return $this->getTime()->format('Y-m-d H:i:s') . ' (' . ($this->list ? $this->list->getName() : '组群') . ')';
        }

        return $this->getTime()->format('Y-m-d H:i:s');
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getNews()
    {
        return $this->news;
    }

    /**
     * @param mixed $news
     */
    public function setNews($news)
    {
        $this->news = $news;
    }

    /**
     * @return mixed
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param mixed $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getSendType()
    {
        return $this->sendType;
    }

    /**
     * @param mixed $sendType
     */
    public function setSendType($sendType)
    {
        $this->sendType = $sendType;
    }

    /**
     * @return mixed
     */
    public function getList()
    {
        return $this->list;
    }

    /**
     * @param mixed $list
     */
    public function setList($list)
    {
        $this->list = $list;
    }

}

