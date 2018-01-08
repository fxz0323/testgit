<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\PageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mautic\ApiBundle\Serializer\Driver\ApiMetadataDriver;
use Mautic\CoreBundle\Doctrine\Mapping\ClassMetadataBuilder;
use Mautic\EmailBundle\Entity\Email;
use Mautic\LeadBundle\Entity\Lead;

/**
 * Class Submit.
 */
class Submit
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var Page
     */
    private $page;

    /**
     * @var \Mautic\LeadBundle\Entity\Lead
     */
    private $lead;

    /**
     * @var \Mautic\CoreBundle\Entity\IpAddress
     */
    private $ipAddress;


    /**
     * @param ORM\ClassMetadata $metadata
     */
    public static function loadMetadata(ORM\ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder->setTable('page_submits')
            ->setCustomRepositoryClass('Mautic\PageBundle\Entity\SubmitRepository')
        ;

        $builder->addId();

        $builder->createField('date', 'datetime')
            ->columnName('date')
            ->build();

        $builder->createManyToOne('page', 'Page')
            ->addJoinColumn('page_id', 'id', true, false, 'SET NULL')
            ->build();

        $builder->addLead(true, 'SET NULL');

        $builder->addIpAddress();

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
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return Page
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param Page $page
     */
    public function setPage($page)
    {
        $this->page = $page;
    }

    /**
     * @return Lead
     */
    public function getLead()
    {
        return $this->lead;
    }

    /**
     * @param Lead $lead
     */
    public function setLead($lead)
    {
        $this->lead = $lead;
    }

    /**
     * @return \Mautic\CoreBundle\Entity\IpAddress
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * @param \Mautic\CoreBundle\Entity\IpAddress $ipAddress
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;
    }

}
