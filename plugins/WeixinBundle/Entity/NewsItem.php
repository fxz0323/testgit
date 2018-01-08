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
class NewsItem
{

    /**
     * @var int
     */
    private $id;

    private $news;

    private $title;

    private $thumbMediaId;

    private $thumbMedia;

    private $showCoverPic;

    private $author;

    private $digest;

    private $content;

    private $url;

    private $contentSourceUrl;

    public function __construct()
    {

    }

    /**
     * @param ORM\ClassMetadata $metadata
     */
    public static function loadMetadata(ORM\ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder->setTable('weixin_news_item');

        $builder->addId();

        $builder->createField('title', 'string')
            ->columnName('type')
            ->build();

        $builder->createField('thumbMediaId', 'integer')
            ->columnName('thumb_media_id')
            ->nullable()
            ->build();

        $builder->createField('thumbMedia', 'string')
            ->columnName('thumb_media')
            ->nullable()
            ->build();

        $builder->createField('showCoverPic', 'boolean')
            ->columnName('show_cover_pic')
            ->nullable()
            ->build();

        $builder->createField('author', 'string')
            ->columnName('author')
            ->nullable()
            ->build();

        $builder->createField('digest', 'string')
            ->columnName('digest')
            ->nullable()
            ->build();

        $builder->createField('content', 'text')
            ->columnName('content')
            ->nullable()
            ->build();

        $builder->createField('url', 'string')
            ->columnName('url')
            ->nullable()
            ->build();

        $builder->createField('contentSourceUrl', 'string')
            ->columnName('content_source_url')
            ->nullable()
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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getThumbMediaId()
    {
        return $this->thumbMediaId;
    }

    /**
     * @param mixed $thumbMediaId
     */
    public function setThumbMediaId($thumbMediaId)
    {
        $this->thumbMediaId = $thumbMediaId;
    }

    /**
     * @return mixed
     */
    public function getThumbMedia()
    {
        return $this->thumbMedia;
    }

    /**
     * @param mixed $thumbMedia
     */
    public function setThumbMedia($thumbMedia)
    {
        $this->thumbMedia = $thumbMedia;
    }

    /**
     * @return mixed
     */
    public function getShowCoverPic()
    {
        return $this->showCoverPic;
    }

    /**
     * @param mixed $showCoverPic
     */
    public function setShowCoverPic($showCoverPic)
    {
        $this->showCoverPic = $showCoverPic;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @return mixed
     */
    public function getDigest()
    {
        return $this->digest;
    }

    /**
     * @param mixed $digest
     */
    public function setDigest($digest)
    {
        $this->digest = $digest;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
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
    public function getContentSourceUrl()
    {
        return $this->contentSourceUrl;
    }

    /**
     * @param mixed $contentSourceUrl
     */
    public function setContentSourceUrl($contentSourceUrl)
    {
        $this->contentSourceUrl = $contentSourceUrl;
    }



}
