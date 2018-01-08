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
class NewsSend
{

    const NEWS_SEND_ALL = 'all';
    const NEWS_SEND_GROUP = 'group';

    static $types = [
        self::NEWS_SEND_ALL => 'weixin.news.type.all',
        self::NEWS_SEND_GROUP => 'weixin.news.type.group'
    ];

    /**
     * @var int
     */
    private $id;

    private $news;

    private $sendTime;

    private $sendType = self::NEWS_SEND_ALL;

    private $list;

    private $hasSent = false;

    public function __construct()
    {

    }

    public static function loadMetadata(ORM\ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder->setTable('weixin_news_send')
            ->setCustomRepositoryClass('MauticPlugin\WeixinBundle\Entity\NewsSendRepository');

        $builder->addId();

        $builder->createField('sendTime', 'datetime')
            ->columnName('send_time')
            ->nullable()
            ->build();

        $builder->createField('sendType', 'string')
            ->columnName('send_type')
            ->build();

        $builder->createField('list', 'integer')
            ->columnName('list_id')
            ->build();

        $builder->createField('hasSent', 'boolean')
            ->columnName('has_sent')
            ->build();

        $builder->createManyToOne('news', 'News')
            ->addJoinColumn('news_id', 'id', false, false, 'CASCADE')
            ->build();

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
    public function getSendTime()
    {
        return $this->sendTime;
    }

    /**
     * @param mixed $sendTime
     */
    public function setSendTime($sendTime)
    {
        $this->sendTime = $sendTime;
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

    /**
     * @return bool
     */
    public function isHasSent()
    {
        return $this->hasSent;
    }

    /**
     * @param bool $hasSent
     */
    public function setHasSent($hasSent)
    {
        $this->hasSent = $hasSent;
    }

}
