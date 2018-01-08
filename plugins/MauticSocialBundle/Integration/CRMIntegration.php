<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\MauticSocialBundle\Integration;

/**
 * Class FacebookIntegration.
 */
class CRMIntegration extends SocialIntegration
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return '自有CRM';
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentifierFields()
    {
        return [
            'crm',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getSupportedFeatures()
    {
        return [

        ];
    }
}
