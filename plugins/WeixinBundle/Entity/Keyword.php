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
class Keyword
{
    /**
     * @var int
     */
    private $id;

    private $keyword;

    private $type;

    private $rule;

    public function __construct()
    {
    }

    public function getTypeText()
    {
        return Rule::$ruleTypes[$this->getType()];
    }


    /**
     * @param ORM\ClassMetadata $metadata
     */
    public static function loadMetadata(ORM\ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder->setTable('weixin_keywords');

        $builder->addId();

        $builder->createField('keyword', 'string')
            ->columnName('keyword')
            ->build();

        $builder->createField('type', 'string')
            ->columnName('type')
            ->build();

        $builder->createManyToOne('rule', 'Rule')
            ->addJoinColumn('rule_id', 'id', false, false, 'CASCADE')
            ->build();
    }

    /**
     * @param ClassMetadata $metadata
     */
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
//        $metadata->addPropertyConstraint(
//            'name',
//            new NotBlank(
//                [
//                    'message' => 'mautic.core.name.required',
//                ]
//            )
//        );
//
//        $metadata->addConstraint(new Callback([
//            'callback' => function (Weixin $sms, ExecutionContextInterface $context) {
//                $type = $sms->getSmsType();
//                if ($type == 'list') {
//                    $validator = $context->getValidator();
//                    $violations = $validator->validate(
//                        $sms->getLists(),
//                        [
//                            new LeadListAccess(
//                                [
//                                    'message' => 'mautic.lead.lists.required',
//                                ]
//                            ),
//                            new NotBlank(
//                                [
//                                    'message' => 'mautic.lead.lists.required',
//                                ]
//                            ),
//                        ]
//                    );
//
//                    if (count($violations) > 0) {
//                        $string = (string) $violations;
//                        $context->buildViolation($string)
//                            ->atPath('lists')
//                            ->addViolation();
//                    }
//                }
//            },
//        ]));
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
    public function getKeyword()
    {
        return $this->keyword;
    }

    /**
     * @param mixed $keyword
     */
    public function setKeyword($keyword)
    {
        $this->keyword = $keyword;
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
    public function getRule()
    {
        return $this->rule;
    }

    /**
     * @param mixed $rule
     */
    public function setRule($rule)
    {
        $this->rule = $rule;
    }


}
