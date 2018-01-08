<?php

namespace Mautic\LeadBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Mautic\CoreBundle\Doctrine\Mapping\ClassMetadataBuilder;

class LeadOrder
{
    /**
     * @var int
     */
    private $id;

    private $origin;

    private $orderNo;

    private $orderTime;

    private $totalFee = 0;

    private $orderLines;

    private $contact;

    private $mobile;

    private $recipient;

    private $address;

    private $recipientMobile;

    public function __construct()
    {
        $this->orderLines = new ArrayCollection();
    }

    /**
     * @param ORM\ClassMetadata $metadata
     */
    public static function loadMetadata(ORM\ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder->setTable('lead_order');

        $builder->addId();

        $builder->createField('origin', 'string')
            ->nullable()
            ->columnName('origin')
            ->build();

        $builder->createField('orderNo', 'string')
            ->columnName('order_no')
            ->build();

        $builder->createField('mobile', 'string')
            ->columnName('mobile')
            ->build();

        $builder->createField('orderTime', 'datetime')
            ->columnName('order_time')
            ->nullable()
            ->build();

        $builder->createField('totalFee', 'decimal')
            ->columnName('total_fee')
            ->precision(10)
            ->scale(2)
            ->build();

        $builder->createField('recipient', 'string')
            ->nullable()
            ->columnName('recipient')
            ->build();

        $builder->createField('address', 'string')
            ->nullable()
            ->columnName('address')
            ->build();

        $builder->createField('recipientMobile', 'string')
            ->nullable()
            ->columnName('recipient_mobile')
            ->build();

        $builder->createOneToMany('orderLines', 'LeadOrderLine')
            ->setIndexBy('id')
            ->mappedBy('leadOrder')
            ->cascadePersist()
            ->fetchExtraLazy()
            ->build();

        $builder->addContact(false, 'CASCADE', false, 'leadOrders');
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
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * @param mixed $origin
     */
    public function setOrigin($origin)
    {
        $this->origin = $origin;
    }

    /**
     * @return mixed
     */
    public function getOrderNo()
    {
        return $this->orderNo;
    }

    /**
     * @param mixed $orderNo
     */
    public function setOrderNo($orderNo)
    {
        $this->orderNo = $orderNo;
    }

    /**
     * @return mixed
     */
    public function getOrderTime()
    {
        return $this->orderTime;
    }

    /**
     * @param mixed $orderTime
     */
    public function setOrderTime($orderTime)
    {
        $this->orderTime = $orderTime;
    }

    /**
     * @return mixed
     */
    public function getTotalFee()
    {
        return $this->totalFee;
    }

    /**
     * @param mixed $totalFee
     */
    public function setTotalFee($totalFee)
    {
        $this->totalFee = $totalFee;
    }

    /**
     * @return ArrayCollection
     */
    public function getOrderLines()
    {
        return $this->orderLines;
    }

    /**
     * @param ArrayCollection $orderLines
     */
    public function setOrderLines($orderLines)
    {
        $this->orderLines = $orderLines;
    }

    public function addOrderLine($orderLine)
    {
        $this->orderLines->add($orderLine);
    }

    /**
     * @return mixed
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * @param mixed $contact
     */
    public function setContact($contact)
    {
        $this->contact = $contact;
    }

    /**
     * @return mixed
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * @param mixed $mobile
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
    }

    public function calculatePrice()
    {
        $this->totalFee = 0;
        foreach ($this->orderLines as $orderLine) {
            $this->totalFee += $orderLine->getTotalPrice();
        }
    }

    /**
     * @return mixed
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @param mixed $recipient
     */
    public function setRecipient($recipient)
    {
        $this->recipient = $recipient;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getRecipientMobile()
    {
        return $this->recipientMobile;
    }

    /**
     * @param mixed $recipientMobile
     */
    public function setRecipientMobile($recipientMobile)
    {
        $this->recipientMobile = $recipientMobile;
    }

}
