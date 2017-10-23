<?php

namespace MauticPlugin\WeixinBundle\Controller;

use Mautic\CoreBundle\Controller\AbstractFormController;
use MauticPlugin\WeixinBundle\Entity\Menu;
use MauticPlugin\WeixinBundle\Entity\MenuItem;
use MauticPlugin\WeixinBundle\Form\Type\FollowedMessageType;
use MauticPlugin\WeixinBundle\Form\Type\KeywordMessageType;
use MauticPlugin\WeixinBundle\Form\Type\MenuItemType;
use MauticPlugin\WeixinBundle\Form\Type\MenuType;
use Symfony\Component\HttpFoundation\Request;

class MenuController extends BaseController
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        $currentWeixin = $this->getCurrentWeixin();
        if ($currentWeixin->getMenus()->last()) {
            $id = $currentWeixin->getMenus()->last()->getId();
        } else {
            $id = 0;
        }

        return $this->redirectToRoute('mautic_weixin_menu_edit_menu', ['id' => $id]);
    }

    public function editMenuAction(Request $request, $id)
    {

        $currentWeixin = $this->getCurrentWeixin();

        $em = $this->getDoctrine()->getManager();
        if (0 == $id) {
            $menu = new Menu();
            $menu->setWeixin($currentWeixin);
            $currentWeixin->addMenu($menu);
        } else {
            $menu = $em->getRepository('MauticPlugin\WeixinBundle\Entity\Menu')->find($id);
        }

        $form = $this->createForm(MenuType::class, $menu, [
            'action' => $this->generateUrl('mautic_weixin_menu_edit_menu', ['id' => $id])
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($menu->getType() == Menu::MENU_TYPE_URL) {
                $menu->setMessage(null);
            } else {
                $this->get('weixin.helper.message')->handleMessageImage($menu->getMessage(), $form->get('message')->get('file')->getData());
                $em->persist($menu->getMessage());
            }

            $em->persist($menu);
            $em->flush();

            return $this->redirectToRoute('mautic_weixin_menu_edit_menu', ['id' => $menu->getId()]);
        }

        return $this->delegateView([
            'viewParameters' => [
                'currentWeixin' => $currentWeixin,
                'currentMenu' => $menu,
                'form' => $form->createView(),
            ],
            'contentTemplate' => 'WeixinBundle:Menu:index.html.php',
            'passthroughVars' => [

            ],
        ]);
    }

    public function editMenuItemAction(Request $request, $id, $menuId)
    {

        $currentWeixin = $this->getCurrentWeixin();

        $em = $this->getDoctrine()->getManager();
        $menu = $em->getRepository('MauticPlugin\WeixinBundle\Entity\Menu')->find($menuId);
        if (0 == $id) {
            $menuItem = new MenuItem();
            $menuItem->setMenu($menu);
            $menu->addItem($menuItem);
        } else {
            $menuItem = $em->getRepository('MauticPlugin\WeixinBundle\Entity\MenuItem')->find($id);
        }

        $form = $this->createForm(MenuItemType::class, $menuItem, [
            'action' => $this->generateUrl('mautic_weixin_menu_edit_menu_item', ['id' => $id, 'menuId' => $menuId])
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($menuItem->getType() == Menu::MENU_TYPE_URL) {
                $menuItem->setMessage(null);
            } else {
                $this->get('weixin.helper.message')->handleMessageImage($menuItem->getMessage(), $form->get('message')->get('file')->getData());
                $em->persist($menuItem->getMessage());
            }

            $em->persist($menuItem);
            $em->flush();

            return $this->redirectToRoute('mautic_weixin_menu_edit_menu_item', ['id' => $menuItem->getId(), 'menuId' => $menuId]);
        }

        return $this->delegateView([
            'viewParameters' => [
                'currentWeixin' => $currentWeixin,
                'currentMenu' => $menu,
                'currentItem' => $menuItem,
                'form' => $form->createView(),
            ],
            'contentTemplate' => 'WeixinBundle:Menu:index.html.php',
            'passthroughVars' => [

            ],
        ]);
    }

}
