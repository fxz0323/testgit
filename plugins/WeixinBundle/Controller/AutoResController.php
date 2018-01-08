<?php

namespace MauticPlugin\WeixinBundle\Controller;

use Mautic\CoreBundle\Controller\AbstractFormController;
use MauticPlugin\WeixinBundle\Entity\Keyword;
use MauticPlugin\WeixinBundle\Entity\Message;
use MauticPlugin\WeixinBundle\Entity\Rule;
use MauticPlugin\WeixinBundle\Form\Type\FollowedMessageType;
use MauticPlugin\WeixinBundle\Form\Type\KeywordMessageType;
use MauticPlugin\WeixinBundle\Form\Type\KeywordType;
use MauticPlugin\WeixinBundle\Form\Type\RuleEditType;
use MauticPlugin\WeixinBundle\Form\Type\RuleType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AutoResController extends BaseController
{

    public function settingsAction()
    {
        $currentWeixin = $this->getCurrentWeixin();

        return $this->delegateView([
            'viewParameters' => [
                'currentWeixin' => $currentWeixin,
            ],
            'contentTemplate' => 'WeixinBundle:AutoRes:settings.html.php',
            'passthroughVars' => [

            ],
        ]);
    }

    /**
     * @Route("/")
     */
    public function indexAction(Request $request)
    {

        $module = $request->query->get('m', 'followed');

        $currentWeixin = $this->getCurrentWeixin();

        if(null == $currentWeixin) {
            return $this->redirectToRoute('mautic_weixin_settings');
        }

        $followedMessageForm = $this->createForm(FollowedMessageType::class, $currentWeixin, [
            'action' => $this->generateUrl('mautic_weixin_auto_res_followed_message'),
        ]);

        return $this->delegateView([
            'viewParameters' => [
                'currentWeixin' => $currentWeixin,
                'module' => $module,
                'followedMessageForm' => $this->setFormTheme($followedMessageForm, 'WeixinBundle:AutoRes:index.html.php', 'WeixinBundle:FormTheme\Rule'),
            ],
            'contentTemplate' => 'WeixinBundle:AutoRes:index.html.php',
            'passthroughVars' => [

            ],
        ]);
    }

    public function followedMessageAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $currentWeixin = $this->getCurrentWeixin();
        if (!$currentWeixin->getFollowedMessage()) {
            $message = new Message();
            $currentWeixin->setFollowedMessage($message);
        }

        $followedMessageForm = $this->createForm(FollowedMessageType::class, $currentWeixin, [
            'action' => $this->generateUrl('mautic_weixin_auto_res_followed_message'),
        ]);
        $followedMessageForm->handleRequest($request);

        if ($followedMessageForm->isSubmitted() && $followedMessageForm->isValid()) {

            $this->get('weixin.helper.message')->handleMessageImage($currentWeixin, $currentWeixin->getFollowedMessage(), $followedMessageForm->get('followedMessage')->get('file')->getData());
            $em->persist($currentWeixin->getFollowedMessage());
            $em->persist($currentWeixin);
            $em->flush();

            return $this->redirectToRoute('mautic_weixin_auto_res', ['m' => 'followed']);
        }

        return $this->delegateView([
            'viewParameters' => [
                'currentWeixin' => $currentWeixin,
                'module' => 'followed',
                'followedMessageForm' => $this->setFormTheme($followedMessageForm, 'WeixinBundle:AutoRes:index.html.php', 'WeixinBundle:FormTheme\Rule'),
            ],
            'contentTemplate' => 'WeixinBundle:AutoRes:index.html.php',
            'passthroughVars' => [

            ],
        ]);
    }

    public function newRuleAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $currentWeixin = $this->getCurrentWeixin();

        $rule = new Rule();

        $form = $this->createForm(RuleType::class, $rule);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $currentWeixin->addRule($rule);
            $rule->setWeixin($currentWeixin);
            $this->get('weixin.helper.message')->handleMessageImage($currentWeixin, $rule->getMessage(), $form->get('message')->get('file')->getData());

            $em->persist($rule);
            $em->persist($rule->getMessage());
            $em->flush();

            return $this->redirectToRoute('mautic_weixin_auto_res', ['m' => 'keyword']);
        }

        return $this->delegateView([
            'viewParameters' => [
                'currentWeixin' => $currentWeixin,
                'form' => $form->createView(),
            ],
            'contentTemplate' => 'WeixinBundle:AutoRes:newRule.html.php',
            'passthroughVars' => [

            ],
        ]);
    }

    public function editRuleAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $currentWeixin = $this->getCurrentWeixin();

        $rule = $em->getRepository('MauticPlugin\WeixinBundle\Entity\Rule')->find($id);

        $form = $this->createForm(RuleEditType::class, $rule, ['action' => $this->generateUrl('mautic_weixin_auto_res_edit_rule', ['id' => $id])]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->get('weixin.helper.message')->handleMessageImage($currentWeixin, $rule->getMessage(), $form->get('message')->get('file')->getData());
            $em->persist($rule->getMessage());
            $em->flush();

            return $this->redirectToRoute('mautic_weixin_auto_res', ['m' => 'keyword']);
        }

        return $this->delegateView([
            'viewParameters' => [
                'currentWeixin' => $currentWeixin,
                'form' => $form->createView(),
            ],
            'contentTemplate' => 'WeixinBundle:AutoRes:editRule.html.php',
            'passthroughVars' => [

            ],
        ]);
    }

    public function editKeyWordAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $currentWeixin = $this->getCurrentWeixin();

        if (0 == $id) {
            $keyword = new Keyword();
            $em->persist($keyword);
            $ruleId = $request->query->get('ruleId');
            $rule = $em->getRepository('MauticPlugin\WeixinBundle\Entity\Rule')->find($ruleId);
            $keyword->setRule($rule);
        } else {
            $keyword = $em->getRepository('MauticPlugin\WeixinBundle\Entity\Keyword')->find($id);
        }

        $form = $this->createForm(KeywordType::class, $keyword);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->flush();
            return $this->redirectToRoute('mautic_weixin_auto_res_edit_rule', ['id' => $keyword->getRule()->getId()]);
        }

        return $this->delegateView([
            'viewParameters' => [
                'currentWeixin' => $currentWeixin,
                'form' => $form->createView(),
            ],
            'contentTemplate' => 'WeixinBundle:AutoRes:editKeyword.html.php',
            'passthroughVars' => [

            ],
        ]);

    }

    public function deleteKeyWordAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $keyword = $em->getRepository('MauticPlugin\WeixinBundle\Entity\Keyword')->find($id);
        $ruleId = $keyword->getRule()->getId();
        $em->remove($keyword);
        $em->flush();

        return $this->redirectToRoute('mautic_weixin_auto_res_edit_rule', ['id' => $ruleId]);
    }

    public function deleteRuleAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $rule = $em->getRepository('MauticPlugin\WeixinBundle\Entity\Rule')->find($id);
        $em->remove($rule);
        $em->flush();

        return $this->redirectToRoute('mautic_weixin_auto_res', ['m' => 'keyword']);
    }

    public function chooseWeixinAction(Request $request)
    {
        $id = $request->query->get('id');
        $this->get('session')->set('current_weixin_id', $id);

        return new JsonResponse();
    }

}
