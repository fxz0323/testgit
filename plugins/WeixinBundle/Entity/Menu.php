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
class Menu
{

    const MENU_TYPE_URL = 'view';
    const MENU_TYPE_MESSAGE = 'click';

    static $types = [
        self::MENU_TYPE_URL => 'weixin.menu.type.url',
        self::MENU_TYPE_MESSAGE => 'weixin.menu.type.message',
    ];

    /**
     * @var int
     */
    private $id;

    private $name;

    private $items;

    private $weixin;

    private $type;

    private $url;

    private $message;

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->type = self::MENU_TYPE_URL;
    }

    /**
     * @param ORM\ClassMetadata $metadata
     */
    public static function loadMetadata(ORM\ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder->setTable('weixin_menu');

        $builder->addId();

        $builder->createField('name', 'string')
            ->columnName('name')
            ->build();

        $builder->createField('type', 'string')
            ->columnName('type')
            ->build();

        $builder->createField('url', 'string')
            ->columnName('url')
            ->nullable()
            ->build();

        $builder->createManyToOne('message', 'Message')
            ->addJoinColumn('message_id', 'id', true, false, 'CASCADE')
            ->build();
        $builder->createManyToOne('weixin', 'Weixin')
            ->addJoinColumn('weixin_id', 'id', false, false, 'CASCADE')
            ->build();

        $builder->createOneToMany('items', 'MenuItem')
            ->setIndexBy('id')
            ->mappedBy('menu')
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

    public function addItem($item)
    {
        $this->items->add($item);
    }

    public function removeItem($item)
    {
        $this->items->remove($item);
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


}
