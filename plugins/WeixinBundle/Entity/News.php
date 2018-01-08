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
class News
{

    /**
     * @var int
     */
    private $id;

    private $weixin;

    private $mediaId;

    private $updateTime;

    private $items;

    private $histories;

    public function __construct()
    {

    }

    public static function loadMetadata(ORM\ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder->setTable('weixin_news')
            ->setCustomRepositoryClass('MauticPlugin\WeixinBundle\Entity\NewsRepository');;

        $builder->addId();

        $builder->createField('updateTime', 'datetime')
            ->columnName('update_time')
            ->nullable()
            ->build();

        $builder->createField('mediaId', 'string')
            ->columnName('media_id')
            ->build();

        $builder->createManyToOne('weixin', 'Weixin')
            ->addJoinColumn('weixin_id', 'id', false, false, 'CASCADE')
            ->build();

        $builder->createOneToMany('items', 'NewsItem')
            ->setIndexBy('id')
            ->mappedBy('news')
            ->cascadePersist()
            ->fetchExtraLazy()
            ->build();

        $builder->createOneToMany('histories', 'NewsHistory')
            ->setIndexBy('id')
            ->mappedBy('news')
            ->cascadePersist()
            ->fetchExtraLazy()
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
    public function getWeixin()
    {
        return $this->weixin;
    }

    /**
     * @param mixed $weixin
     */
    public function setWeixin($weixin)
    {
        $this->weixin = $weixin;
    }

    /**
     * @return mixed
     */
    public function getMediaId()
    {
        return $this->mediaId;
    }

    /**
     * @param mixed $mediaId
     */
    public function setMediaId($mediaId)
    {
        $this->mediaId = $mediaId;
    }

    /**
     * @return mixed
     */
    public function getUpdateTime()
    {
        return $this->updateTime;
    }

    /**
     * @param mixed $updateTime
     */
    public function setUpdateTime($updateTime)
    {
        $this->updateTime = $updateTime;
    }

    /**
     * @return mixed
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param mixed $items
     */
    public function setItems($items)
    {
        $this->items = $items;
    }

    /**
     * @return mixed
     */
    public function getHistories()
    {
        return $this->histories;
    }

    /**
     * @param mixed $histories
     */
    public function setHistories($histories)
    {
        $this->histories = $histories;
    }

}
