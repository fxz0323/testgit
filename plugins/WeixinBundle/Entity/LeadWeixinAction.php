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
class LeadWeixinAction
{
    /**
     * @var int
     */
    private $id;

    private $contact;

    private $weixin;

    private $event;

    private $message;

    private $time;

    public function __construct()
    {
    }

    /**
     * @param ORM\ClassMetadata $metadata
     */
    public static function loadMetadata(ORM\ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder->setTable('weixin_lead_action');

        $builder->addId();

        $builder->createField('event', 'string')
            ->columnName('event')
            ->build();

        $builder->createField('message', 'array')
            ->columnName('message')
            ->build();

        $builder->createField('time', 'datetime')
            ->columnName('time')
            ->build();

        $builder->createManyToOne('weixin', 'Weixin')
            ->addJoinColumn('weixin_id', 'id', false, false, 'CASCADE')
            ->build();

        $builder->addContact(false, 'CASCADE', false, 'weixinActions');
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
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param mixed $event
     */
    public function setEvent($event)
    {
        $this->event = $event;
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


}
