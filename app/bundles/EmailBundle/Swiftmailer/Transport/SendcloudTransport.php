<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\EmailBundle\Swiftmailer\Transport;

use Joomla\Http\Http;
use Mautic\CoreBundle\Factory\MauticFactory;
use Mautic\LeadBundle\Entity\DoNotContact;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SendcloudTransport.
 */
class SendcloudTransport extends \Swift_SmtpTransport  implements InterfaceCallbackTransport
{
    /**
     * {@inheritdoc}
     */
    public function __construct($host = 'localhost', $port = 25, $security = null)
    {
        parent::__construct('smtp2525.sendcloud.net', 2525, null);

        $this->setAuthMode('login');
    }

    /**
     * Returns a "transport" string to match the URL path /mailer/{transport}/callback.
     *
     * @return mixed
     */
    public function getCallbackPath()
    {
        return 'sendcloudemail';
    }

    /**
     * Handle bounces & complaints from ElasticEmail.
     *
     * @param Request       $request
     * @param MauticFactory $factory
     *
     * @return mixed
     */
    public function handleCallbackResponse(Request $request, MauticFactory $factory)
    {
        $translator = $factory->getTranslator();
        $logger     = $factory->getLogger();
        $logger->debug('Receiving webhook from SendCloudEmail');

        $data = $request->request->all();
//
//        $file_name = time() . ".txt";
//        file_put_contents($file_name,json_encode($data));
        if (isset($data['recipientArray'])) {
            $to = $data['recipientArray'];
            $match = [];
            preg_match('/^\[\'(.*)\'\]/', $to, $match);
            $to = $match[1];
            $rows     = [];
            $email    = $to;
            $status   = $data['event'];
            // https://elasticemail.com/support/delivery/http-web-notification
            if (in_array($status, ['report_spam', 'unsubscribe'])) {
                $rows[DoNotContact::UNSUBSCRIBED]['emails'][$email] = $status;
            } elseif (in_array($status, ['invalid', 'bounce'])) {
                // just hard bounces https://elasticemail.com/support/user-interface/activity/bounced-category-filters
                $rows[DoNotContact::BOUNCED]['emails'][$email] = $status;
            } elseif ($status == 'error') {
                $rows[DoNotContact::BOUNCED]['emails'][$email] = $translator->trans('mautic.email.complaint.reason.unknown');
            }


            return $rows;
        }
        //$file_name = time() . ".txt";
        //file_put_contents($file_name,json_encode($data));

    }
}
