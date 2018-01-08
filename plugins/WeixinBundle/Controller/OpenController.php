<?php

namespace MauticPlugin\WeixinBundle\Controller;

use Chunhei2008\EasyOpenWechat\Support\Log;
use Mautic\CoreBundle\Controller\AbstractFormController;
use MauticPlugin\WeixinBundle\Entity\Weixin;
use MauticPlugin\WeixinBundle\Form\Type\FollowedMessageType;
use MauticPlugin\WeixinBundle\Form\Type\KeywordMessageType;
use Symfony\Component\HttpFoundation\Request;

class OpenController extends AbstractFormController
{

    public function authAction(Request $request)
    {
        return $this->get('weixin.api')->handleAuth();
    }

    public function handleMessageAction(Request $request, $appId)
    {
        $em = $this->getDoctrine()->getManager();
        $weixin = $em->getRepository('MauticPlugin\WeixinBundle\Entity\Weixin')->findOneBy(
            ['authorizerAppId' => $appId]
        );

        return $this->get('weixin.api')->handleMessage($weixin);
    }


    public function unlinkAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $weixin = $em->getRepository('MauticPlugin\WeixinBundle\Entity\Weixin')->find($id);

        try {
            $this->get('weixin.api')->unlinkWeixin($weixin);
        } catch (\Exception $e){

        }

        $weixin->setOwner(null);
        $em->persist($weixin);
        $em->flush();

        return $this->redirectToRoute('mautic_weixin_settings');
    }

    public function authReturnAction(Request $request)
    {
        $auth_code = $request->query->get('auth_code');

        $weixin = $this->get('weixin.api')->createWeixin($auth_code);

        $weixin->setOwner($this->getUser());
        $weixin->setCreateTime(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $em->persist($weixin);
        $em->flush();

        return $this->redirectToRoute('mautic_weixin_settings');

    }

    public function oauthLoginAction()
    {
        $url = $this->get('weixin.api')->getLoginUrl();
        return $this->redirect($url);
    }



    public function sychUsersAction($id)
    {
        return $this->redirectToRoute('mautic_weixin_auto_res');
    }
}
