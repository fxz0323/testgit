<?php

namespace Mautic\LeadBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mautic\CoreBundle\Doctrine\Mapping\ClassMetadataBuilder;


class LeadOrderLine
{
    /**
     * @var int
     */
    private $id;

    private $leadOrder;

    private $productName;

    private $productType;

    private $productSize;

    private $productColor;

    private $unitPrice = 0;

    private $quantity = 0;

    private $totalPrice = 0;


    public function __construct()
    {
    }


    /**
     * @param ORM\ClassMetadata $metadata
     */
    public static function loadMetadata(ORM\ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder->setTable('lead_order_line');

        $builder->addId();

        $builder->createField('productName', 'string')
            ->columnName('product_name')
            ->build();

        $builder->createField('productType', 'string')
            ->columnName('product_type')
            ->nullable()
            ->build();

        $builder->createField('productSize', 'string')
            ->columnName('product_size')
            ->nullable()
            ->build();

        $builder->createField('productColor', 'string')
            ->columnName('product_color')
            ->nullable()
            ->build();

        $builder->createField('unitPrice', 'decimal')
            ->columnName('unit_price')
            ->precision(10)
            ->scale(2)
            ->build();

        $builder->createField('quantity', 'decimal')
            ->columnName('quantity')
            ->precision(10)
            ->scale(2)
            ->build();

        $builder->createField('totalPrice', 'decimal')
            ->columnName('total_price')
            ->precision(10)
            ->scale(2)
            ->build();

        $builder->createManyToOne('leadOrder','LeadOrder')
            ->addJoinColumn('lead_order_id', 'id', false, false, 'CASCADE')
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
    public function getLeadOrder()
    {
        return $this->leadOrder;
    }

    /**
     * @param mixed $leadOrder
     */
    public function setLeadOrder($leadOrder)
    {
        $this->leadOrder = $leadOrder;
    }


    /**
     * @return mixed
     */
    public function getProductName()
    {
        return $this->productName;
    }

    /**
     * @param mixed $productName
     */
    public function setProductName($productName)
    {
        $this->productName = $productName;
    }

    /**
     * @return mixed
     */
    public function getProductType()
    {
        return $this->productType;
    }

    /**
     * @param mixed $productType
     */
    public function setProductType($productType)
    {
        $this->productType = $productType;
    }

    /**
     * @return mixed
     */
    public function getProductSize()
    {
        return $this->productSize;
    }

    /**
     * @param mixed $productSize
     */
    public function setProductSize($productSize)
    {
        $this->productSize = $productSize;
    }

    /**
     * @return mixed
     */
    public function getProductColor()
    {
        return $this->productColor;
    }

    /**
     * @param mixed $productColor
     */
    public function setProductColor($productColor)
    {
        $this->productColor = $productColor;
    }

    /**
     * @return int
     */
    public function getUnitPrice()
    {
        return $this->unitPrice;
    }

    /**
     * @param int $unitPrice
     */
    public function setUnitPrice($unitPrice)
    {
        $this->unitPrice = $unitPrice;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * @return int
     */
    public function getTotalPrice()
    {
        return $this->totalPrice;
    }

    /**
     * @param int $totalPrice
     */
    public function setTotalPrice($totalPrice)
    {
        $this->totalPrice = $totalPrice;
    }



}
