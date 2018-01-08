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
class QrcodeScan
{
    /**
     * @var int
     */
    private $id;

    private $qrcode;

    private $user;

    private $lead;

    private $scanTime;

    private $province;

    private $city;

    private $country;

    private $isSubscribeEvent;

    public function __construct()
    {
    }

    /**
     * @param ORM\ClassMetadata $metadata
     */
    public static function loadMetadata(ORM\ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder->setTable('weixin_qrcode_scan');

        $builder->addId();

        $builder->createField('user', 'string')
            ->columnName('user')
            ->build();

        $builder->createField('province', 'string')
            ->columnName('province')
            ->build();

        $builder->createField('city', 'string')
            ->columnName('city')
            ->build();
        $builder->createField('country', 'string')
            ->columnName('country')
            ->build();

        $builder->createField('scanTime', 'datetime')
            ->columnName('scan_time')
            ->build();

        $builder->createField('isSubscribeEvent', 'boolean')
            ->columnName('is_subscribe_event')
            ->build();

        $builder->createManyToOne('lead', 'Mautic\LeadBundle\Entity\Lead')
            ->addJoinColumn('lead_id', 'id', true, false, 'CASCADE')
            ->build();

        $builder->createManyToOne('qrcode', 'Qrcode')
            ->addJoinColumn('qrcode_id', 'id', false, false, 'CASCADE')
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
    public function getQrcode()
    {
        return $this->qrcode;
    }

    /**
     * @param mixed $qrcode
     */
    public function setQrcode($qrcode)
    {
        $this->qrcode = $qrcode;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getLead()
    {
        return $this->lead;
    }

    /**
     * @param mixed $lead
     */
    public function setLead($lead)
    {
        $this->lead = $lead;
    }

    /**
     * @return mixed
     */
    public function getScanTime()
    {
        return $this->scanTime;
    }

    /**
     * @param mixed $scanTime
     */
    public function setScanTime($scanTime)
    {
        $this->scanTime = $scanTime;
    }

    /**
     * @return mixed
     */
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * @param mixed $province
     */
    public function setProvince($province)
    {
        $this->province = $province;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getisSubscribeEvent()
    {
        return $this->isSubscribeEvent;
    }

    /**
     * @param mixed $isSubscribeEvent
     */
    public function setIsSubscribeEvent($isSubscribeEvent)
    {
        $this->isSubscribeEvent = $isSubscribeEvent;
    }


}
