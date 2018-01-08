<?php

namespace MauticPlugin\WeixinBundle\Controller;

use Mautic\CoreBundle\Controller\AbstractFormController;
use MauticPlugin\WeixinBundle\Entity\Qrcode;
use MauticPlugin\WeixinBundle\Form\Type\FollowedMessageType;
use MauticPlugin\WeixinBundle\Form\Type\KeywordMessageType;
use MauticPlugin\WeixinBundle\Form\Type\QrcodeLogoType;
use MauticPlugin\WeixinBundle\Form\Type\QrcodeType;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class QrcodeController extends BaseController
{
    /**
     * @Route("/")
     */
    public function indexAction($page = 1)
    {
        $currentWeixin = $this->getCurrentWeixin();
        if(null == $currentWeixin) {
            return $this->redirectToRoute('mautic_weixin_settings');
        }

        $em = $this->getDoctrine()->getManager();
        $count = $em->getRepository('MauticPlugin\WeixinBundle\Entity\Qrcode')->getCount($this->getUser());
        $items = $em->getRepository('MauticPlugin\WeixinBundle\Entity\Qrcode')->getAll($this->getUser(), 10, 10 * ($page - 1));

        return $this->delegateView([
            'viewParameters' => [
                'currentWeixin' => $currentWeixin,
                'page' => $page,
                'items' => $items,
                'totalItems' => $count,
            ],
            'contentTemplate' => 'WeixinBundle:Qrcode:index.html.php',
            'passthroughVars' => [

            ],
        ]);
    }

    public function newAction(Request $request)
    {
        $currentWeixin = $this->getCurrentWeixin();

        $em = $this->getDoctrine()->getManager();
        $weixins = $em->getRepository('MauticPlugin\WeixinBundle\Entity\Weixin')->findBy([
            'owner' => $this->getUser(),
            'type' => 2,
        ]);
        $qrcode = new Qrcode();
        $fields = $this->getModel('lead.field')->getFieldList();
        $form = $this->createForm(QrcodeType::class, $qrcode, [
            'action' => $this->generateUrl('mautic_weixin_qrcode_new'),
            'weixins' => $weixins,
            'fields' => current($fields)
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($qrcode->getMessage()->getMsgType() != 'none') {
                $this->get('weixin.helper.message')->handleMessageImage($currentWeixin, $qrcode->getMessage(), $form->get('message')->get('file')->getData());
                $em->persist($qrcode->getMessage());
            } else {
                $qrcode->setMessage(null);
            }
            $index = $em->getRepository('MauticPlugin\WeixinBundle\Entity\Qrcode')->getNextIndex($qrcode->getWeixin());
            $qrcode->setNb($index);
            $this->get('weixin.api')->uploadQrcode($qrcode);
            $qrcode->setCreateTime(new \DateTime());
            $em->persist($qrcode);
            $em->flush();

            return $this->redirectToRoute('mautic_weixin_qrcode');
        }

        return $this->delegateView([
            'viewParameters' => [
                'currentWeixin' => $currentWeixin,
                'form' => $form->createView(),
            ],
            'contentTemplate' => 'WeixinBundle:Qrcode:new.html.php',
            'passthroughVars' => [

            ],
        ]);
    }

    public function showAction($id)
    {
        $currentWeixin = $this->getCurrentWeixin();

        $em = $this->getDoctrine()->getManager();
        $qrcode = $em->getRepository('MauticPlugin\WeixinBundle\Entity\Qrcode')->find($id);

        if (!$qrcode->getImage()) {
            $filename = md5(uniqid()) . '.png';
            (new \Endroid\QrCode\QrCode($qrcode->getUrl()))->writeFile($this->getParameter('kernel.root_dir') . '/../qrcode/' . $filename);
            $qrcode->setImage('qrcode/' . $filename);
            $em->persist($qrcode);
            $em->flush();
        }
        $model = $this->getModel('lead.lead');
        $fields = $this->getModel('lead.field')->getFieldList();

        return $this->delegateView([
            'viewParameters' => [
                'currentWeixin' => $currentWeixin,
                'qrcode' => $qrcode,
                'model' => $model,
                'fields' => current($fields),
            ],
            'contentTemplate' => 'WeixinBundle:Qrcode:show.html.php',
            'passthroughVars' => [

            ],
        ]);
    }

    public function logoAction(Request $request, $id)
    {
        $currentWeixin = $this->getCurrentWeixin();

        $em = $this->getDoctrine()->getManager();
        $qrcode = $em->getRepository('MauticPlugin\WeixinBundle\Entity\Qrcode')->find($id);

        $form = $this->createForm(QrcodeLogoType::class, $qrcode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fileName = md5(uniqid()) . '.png';
            $file = $form->get('file')->getData();
            $file->move(
                $this->getParameter('kernel.root_dir') . '/../qrcode',
                $fileName
            );
            $qrcode->setLogo('qrcode/' . $fileName);
            $qr = imagecreatefrompng($this->getParameter('kernel.root_dir') . '/../' . $qrcode->getImage());
            $logo = imagecreatefrompng($this->getParameter('kernel.root_dir') . '/../' . $qrcode->getLogo());

            $qrWidth = imagesx($qr);
            $qrHeight = imagesy($qr);

            $logoWidth = imagesx($logo);
            $logoHeight = imagesy($logo);

            // Scale logo to fit in the QR Code
            $logoQrWidth = $qrWidth / 5;
            $scale = $logoWidth / $logoQrWidth;
            $logoQrHeight = $logoHeight / $scale;

            imagecopyresampled($qr, $logo, $qrWidth / 5 * 2, $qrHeight / 5 * 2, 0, 0, $logoQrWidth, $logoQrHeight, $logoWidth, $logoHeight);
            $finalImage = md5(uniqid()) . '.png';
            imagepng($qr, $this->getParameter('kernel.root_dir') . '/../qrcode/' . $finalImage);
            $qrcode->setFinalImage('qrcode/' . $finalImage);
            $em->persist($qrcode);
            $em->flush();

            return $this->redirectToRoute('mautic_weixin_qrcode_show', ['id' => $qrcode->getId()]);
        }

        return $this->delegateView([
            'viewParameters' => [
                'currentWeixin' => $currentWeixin,
                'form' => $form->createView(),
            ],
            'contentTemplate' => 'WeixinBundle:Qrcode:logo.html.php',
            'passthroughVars' => [

            ],
        ]);
    }

    public function downloadAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $qrcode = $em->getRepository('MauticPlugin\WeixinBundle\Entity\Qrcode')->find($id);

        $file = !empty($qrcode->getFinalImage()) ? $qrcode->getFinalImage() : $qrcode->getImage();

        $response = new BinaryFileResponse($this->getParameter('kernel.root_dir') . '/../' . $file);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);
        return $response;
    }

    public function editAction(Request $request, $id)
    {
        $currentWeixin = $this->getCurrentWeixin();
        $em = $this->getDoctrine()->getManager();
        $weixins = $em->getRepository('MauticPlugin\WeixinBundle\Entity\Weixin')->findBy([
            'owner' => $this->getUser(),
            'type' => 2,
        ]);

        $qrcode = $em->getRepository('MauticPlugin\WeixinBundle\Entity\Qrcode')->find($id);
        $fields = $this->getModel('lead.field')->getFieldList();
        $form = $this->createForm(QrcodeType::class, $qrcode, [
            'action' => $this->generateUrl('mautic_weixin_qrcode_edit', ['id' => $id]),
            'weixins' => $weixins,
            'fields' => current($fields),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($qrcode->getMessage()->getMsgType() != 'none') {
                $this->get('weixin.helper.message')->handleMessageImage($currentWeixin, $qrcode->getMessage(), $form->get('message')->get('file')->getData());
                $em->persist($qrcode->getMessage());
            } else {
                $qrcode->setMessage(null);
            }
            $index = $em->getRepository('MauticPlugin\WeixinBundle\Entity\Qrcode')->getNextIndex($qrcode->getWeixin());
            $qrcode->setNb($index);
            $this->get('weixin.api')->uploadQrcode($qrcode);
            $qrcode->setCreateTime(new \DateTime());
            $em->flush();

            return $this->redirectToRoute('mautic_weixin_qrcode');
        }

        return $this->delegateView([
            'viewParameters' => [
                'currentWeixin' => $currentWeixin,
                'form' => $form->createView(),
            ],
            'contentTemplate' => 'WeixinBundle:Qrcode:new.html.php',
            'passthroughVars' => [

            ],
        ]);
    }

    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $qrcode = $em->getRepository('MauticPlugin\WeixinBundle\Entity\Qrcode')->find($id);

        $em->remove($qrcode);
        $em->flush();

        return $this->redirectToRoute('mautic_weixin_qrcode');
    }

}
