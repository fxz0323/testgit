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
class Qrcode
{
    /**
     * @var int
     */
    private $id;

    private $weixin;

    private $name;

    private $url;

    private $image;

    private $logo;

    private $finalImage;

    private $tag;

    private $nb;

    private $message;

    private $createTime;

    private $scans;

    private $leadField1;

    private $leadField1Value;

    private $leadField2;

    private $leadField2Value;

    public function __construct()
    {
        $this->scans = new ArrayCollection();
    }

    /**
     * @param ORM\ClassMetadata $metadata
     */
    public static function loadMetadata(ORM\ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder->setTable('weixin_qrcode')
            ->setCustomRepositoryClass('MauticPlugin\WeixinBundle\Entity\QrcodeRepository');

        $builder->addId();

        $builder->createField('name', 'string')
            ->columnName('name')
            ->build();

        $builder->createField('url', 'string')
            ->columnName('url')
            ->nullable()
            ->build();

        $builder->createField('tag', 'string')
            ->columnName('tag')
            ->build();

        $builder->createField('image', 'string')
            ->columnName('image')
            ->nullable()
            ->build();

        $builder->createField('logo', 'string')
            ->columnName('logo')
            ->nullable()
            ->build();

        $builder->createField('finalImage', 'string')
            ->columnName('final_image')
            ->nullable()
            ->build();

        $builder->createField('nb', 'integer')
            ->columnName('nb')
            ->build();

        $builder->createField('createTime', 'datetime')
            ->columnName('create_time')
            ->build();

        $builder->createManyToOne('weixin', 'Weixin')
            ->addJoinColumn('weixin_id', 'id', false, false, 'CASCADE')
            ->build();

        $builder->createManyToOne('message', 'Message')
            ->addJoinColumn('message_id', 'id', true, false, 'CASCADE')
            ->build();

        $builder->createField('leadField1', 'string')
            ->columnName('lead_field1')
            ->nullable()
            ->build();

        $builder->createField('leadField1Value', 'string')
            ->columnName('lead_field1_value')
            ->nullable()
            ->build();

        $builder->createField('leadField2', 'string')
            ->columnName('lead_field2')
            ->nullable()
            ->build();

        $builder->createField('leadField2Value', 'string')
            ->columnName('lead_field2_value')
            ->nullable()
            ->build();

        $builder->createOneToMany('scans', 'QrcodeScan')
            ->setIndexBy('id')
            ->mappedBy('qrcode')
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return mixed
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param mixed $logo
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;
    }

    /**
     * @return mixed
     */
    public function getFinalImage()
    {
        return $this->finalImage;
    }

    /**
     * @param mixed $finalImage
     */
    public function setFinalImage($finalImage)
    {
        $this->finalImage = $finalImage;
    }

    /**
     * @return mixed
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @param mixed $tag
     */
    public function setTag($tag)
    {
        $this->tag = $tag;
    }

    /**
     * @return mixed
     */
    public function getNb()
    {
        return $this->nb;
    }

    /**
     * @param mixed $nb
     */
    public function setNb($nb)
    {
        $this->nb = $nb;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getCreateTime()
    {
        return $this->createTime;
    }

    /**
     * @param mixed $createTime
     */
    public function setCreateTime($createTime)
    {
        $this->createTime = $createTime;
    }

    /**
     * @return ArrayCollection
     */
    public function getScans()
    {
        return $this->scans;
    }

    /**
     * @param ArrayCollection $scans
     */
    public function setScans($scans)
    {
        $this->scans = $scans;
    }

    public function getSubNb()
    {
        $subScans = $this->scans->filter(function ($scan) {
            return $scan->getIsSubscribeEvent();
        });

        return count($subScans);
    }

    /**
     * @return mixed
     */
    public function getLeadField1()
    {
        return $this->leadField1;
    }

    /**
     * @param mixed $leadField1
     */
    public function setLeadField1($leadField1)
    {
        $this->leadField1 = $leadField1;
    }

    /**
     * @return mixed
     */
    public function getLeadField1Value()
    {
        return $this->leadField1Value;
    }

    /**
     * @param mixed $leadField1Value
     */
    public function setLeadField1Value($leadField1Value)
    {
        $this->leadField1Value = $leadField1Value;
    }

    /**
     * @return mixed
     */
    public function getLeadField2()
    {
        return $this->leadField2;
    }

    /**
     * @param mixed $leadField2
     */
    public function setLeadField2($leadField2)
    {
        $this->leadField2 = $leadField2;
    }

    /**
     * @return mixed
     */
    public function getLeadField2Value()
    {
        return $this->leadField2Value;
    }

    /**
     * @param mixed $leadField2Value
     */
    public function setLeadField2Value($leadField2Value)
    {
        $this->leadField2Value = $leadField2Value;
    }



}
