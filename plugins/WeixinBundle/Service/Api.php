<?php
/**
 * Created by PhpStorm.
 * User: meng
 * Date: 17-10-23
 * Time: 下午10:11
 */

namespace MauticPlugin\WeixinBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use EasyWeChat\Message\Article;
use EasyWeChat\Message\Image;
use EasyWeChat\Message\News;
use EasyWeChat\Message\Text;
use EasyWeChat\OpenPlatform\Guard;
use Mautic\LeadBundle\Entity\Lead;
use MauticPlugin\WeixinBundle\Entity\Menu;
use MauticPlugin\WeixinBundle\Entity\MenuItem;
use MauticPlugin\WeixinBundle\Entity\Message;
use MauticPlugin\WeixinBundle\Entity\NewsHistory;
use MauticPlugin\WeixinBundle\Entity\NewsSend;
use MauticPlugin\WeixinBundle\Entity\Qrcode;
use MauticPlugin\WeixinBundle\Entity\QrcodeScan;
use MauticPlugin\WeixinBundle\Entity\Rule;
use MauticPlugin\WeixinBundle\Entity\Weixin;
use MauticPlugin\WeixinBundle\Event\Event;
use MauticPlugin\WeixinBundle\Event\Events;

class Api
{
    private $configs;
    private $app;
    private $openPlatform;
    private $em;
    private $dispatcher;

    public function __construct($doctrine, $dispatcher, $configs)
    {
        $this->configs = $configs;
        $this->em = $doctrine->getManager();
        $this->dispatcher = $dispatcher;
    }

    private function getOpenPlatform()
    {
        if (!$this->openPlatform) {
            $app = new \EasyWeChat\Foundation\Application($this->configs);
            $this->openPlatform = $app->open_platform;
        }

        return $this->openPlatform;
    }

    public function handleAuth()
    {
        $openPlatform = $this->getOpenPlatform();
        $server = $openPlatform->server;
        $server->setMessageHandler(function ($event) use ($openPlatform) {
            switch ($event->InfoType) {
                case Guard::EVENT_AUTHORIZED: // 授权成功

                case Guard::EVENT_UPDATE_AUTHORIZED: // 更新授权

                case Guard::EVENT_UNAUTHORIZED: // 授权取消
            }
        });
        $response = $server->serve();
        return $response;
    }

    public function handleMessage(Weixin $weixin)
    {
        $this->setWeixin($weixin);
        $server = $this->app->server;
        $server->setMessageHandler(function ($message) use ($weixin) {
            file_put_contents('/tmp/test.log', date('Y-m-d H:i:s') . ': ' . json_encode($message) . PHP_EOL, FILE_APPEND);
            $event = new Event($weixin, $message);
            if ($message->MsgType == 'event') {
                switch ($message->Event) {
                    case 'subscribe':
                        $this->dispatcher->dispatch(Events::WEIXIN_SUBSCRIBE, $event);
                        if (false !== strstr($message->EventKey, 'qrscene')) {
                            $qrnb = ltrim($message->EventKey, 'qrscene_');
                            $qrcode = $this->em->getRepository('MauticPlugin\WeixinBundle\Entity\Qrcode')->findOneBy([
                                'weixin' => $weixin,
                                'nb' => $qrnb,
                            ]);
                            $this->recordScan($message, $qrcode, true);
                            return $this->sendMessage($qrcode->getMessage());
                        }
                        return $this->sendMessage($weixin->getFollowedMessage());
                    case 'unsubscribe':
                        $this->dispatcher->dispatch(Events::WEIXIN_UNSUBSCRIBE, $event);
                        break;
                    case 'CLICK':
                        $this->dispatcher->dispatch(Events::WEIXIN_CLICK, $event);
                        $message = $this->em->getRepository('MauticPlugin\WeixinBundle\Entity\Message')->find($message->EventKey);
                        return $this->sendMessage($message);
                    case 'VIEW':
                        $this->dispatcher->dispatch(Events::WEIXIN_CLICK, $event);
                        break;
                    case 'SCAN':
                        $this->dispatcher->dispatch(Events::WEIXIN_SCAN, $event);
                        $qrcode = $this->em->getRepository('MauticPlugin\WeixinBundle\Entity\Qrcode')->findOneBy([
                            'weixin' => $weixin,
                            'nb' => $message->EventKey,
                        ]);
                        $this->recordScan($message, $qrcode);
                        return $this->sendMessage($qrcode->getMessage());
                    default:
                        # code...
                        break;
                }
            }
            if ($message->MsgType == 'text') {
                $this->dispatcher->dispatch(Events::WEIXIN_TEXT, $event);
                return $this->sendMessage($this->getMessageRes($weixin, $message->Content));
            }
        });

        $response = $server->serve();

        return $response;
    }

    private function getMessageRes(Weixin $weixin, $msg)
    {
        foreach ($weixin->getRules() as $rule) {
            foreach ($rule->getKeywords() as $keyword) {
                if ($keyword->getType() == Rule::RULE_TYPE_COMPLET && $keyword->getKeyword() == $msg) {
                    return $rule->getMessage();
                }
                if ($keyword->getType() == Rule::RULE_TYPE_LIKE && strpos($msg, $keyword->getKeyword()) !== false) {
                    return $rule->getMessage();
                }
            }
        }
    }

    public function getLoginUrl()
    {
        $this->getOpenPlatform()->pre_auth->getCode();
        $response = $this->getOpenPlatform()->pre_auth->redirect('http://m-demo.linkall.sh.cn/s/weixin/oauth-return');
        $response->getTargetUrl();
        return $response->getTargetUrl();
    }

    public function test($weixin)
    {
        $authorizer_info = $this->getOpenPlatform()->getAuthorizerInfo($auth_info->first()['authorizer_appid']);
        dump($authorizer_info);
        die();
    }

    public function createWeixin($code)
    {
        $auth_info = $this->getOpenPlatform()->getAuthorizationInfo($code);
        $authorizer_info = $this->getOpenPlatform()->getAuthorizerInfo($auth_info->first()['authorizer_appid']);

        $weixin = $this->em->getRepository('MauticPlugin\WeixinBundle\Entity\Weixin')->findOneBy(['authorizerAppId' => $auth_info['authorization_info']['authorizer_appid']]);
        if (!$weixin) {
            $weixin = new Weixin();
        }

        $weixin->setAuthorizerAppId($auth_info['authorization_info']['authorizer_appid']);
        $weixin->setAuthorizerAccessToken($auth_info['authorization_info']['authorizer_access_token']);
        $weixin->setAuthorizerRefreshToken($auth_info['authorization_info']['authorizer_refresh_token']);
        $weixin->setAccountName($authorizer_info['authorizer_info']['nick_name']);
        $weixin->setIcon($authorizer_info['authorizer_info']['head_img']);
        $weixin->setType($authorizer_info['authorizer_info']['service_type_info']['id']);
        $weixin->setVerified($authorizer_info['authorizer_info']['verify_type_info']['id']);

        return $weixin;
    }

    public function unlinkWeixin(Weixin $weixin)
    {
        $this->setWeixin($weixin);
        $this->getOpenPlatform()->parseJSON('post', ['https://api.weixin.qq.com/cgi-bin/open/unbind', [
            'appid' => $weixin->getAuthorizerAppId(),
            'open_appid' => 'wx8c1a03d2f7e747c8',
        ]]);
    }

    public function setWeixin(Weixin $weixin)
    {
        $this->app = $this->getOpenPlatform()->createAuthorizerApplication($weixin->getAuthorizerAppId(), $weixin->getAuthorizerRefreshToken());
    }

    public function initMenu(Weixin $weixin)
    {
        $this->setWeixin($weixin);
        $menus = $this->app->menu->current();

        foreach ($menus['selfmenu_info']['button'] as $menuBar) {
            $menu = new Menu();
            $weixin->addMenu($menu);
            $menu->setWeixin($weixin);
            $menu->setName($menuBar['name']);
            $menu->setType(isset($menuBar['type']) ? $menuBar['type'] : '');
            $menu->setUrl(isset($menuBar['url']) ? $menuBar['url'] : '');
            if (isset($menuBar['sub_button'])) {
                foreach ($menuBar['sub_button']['list'] as $button) {
                    if (!in_array($button['type'], [Menu::MENU_TYPE_URL, Menu::MENU_TYPE_MESSAGE])) {
                        continue;
                    }
                    $menuItem = new MenuItem();
                    $menuItem->setName($button['name']);
                    $menuItem->setType($button['type']);
                    $menuItem->setUrl(isset($button['url']) ? $button['url'] : '');
                    $menuItem->setMenu($menu);
                    $this->em->persist($menuItem);
                }
            }
            $this->em->persist($menu);
        }

        $this->em->flush();
    }

    public function updateMenu(Weixin $weixin)
    {
        $this->setWeixin($weixin);

        $buttons = [];
        foreach ($weixin->getMenus() as $menu) {
            /* @var $menu Menu */
            if (count($menu->getItems()) == 0) {
                $buttons[] = $this->buildButton($menu);
            } else {

                $button = [
                    'name' => $menu->getName(),
                    'sub_button' => [],
                ];
                foreach ($menu->getItems() as $item) {
                    $button['sub_button'][] = $this->buildButton($item);
                }
                $buttons[] = $button;
            }
        }

        try {
            $this->app->menu->add($buttons);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function buildButton($menu)
    {
        if ($menu->getType() == Menu::MENU_TYPE_MESSAGE) {
            $button = [
                'type' => 'click',
                'name' => $menu->getName(),
                'key' => $menu->getMessage()->getId(),
            ];
        }
        if ($menu->getType() == Menu::MENU_TYPE_URL) {
            $button = [
                'type' => 'view',
                'name' => $menu->getName(),
                'url' => $menu->getUrl(),
            ];
        }

        return $button;
    }

    private function sendMessage(Message $message = null)
    {
        if (null == $message) {
            return;
        }
        switch ($message->getMsgType()) {
            case Message::MSG_TYPE_TEXT:
                $msg = new Text();
                $msg->content = $message->getContent();
                break;
            case Message::MSG_TYPE_IMG:
                $msg = new Image();
                $msg->media_id = $message->getImageId();
                break;
            case Message::MSG_TYPE_IMGTEXT:
                $msg = new News();
                $msg->title = $message->getArticleTitle();
                $msg->description = $message->getArticleDesc();
                $msg->url = $message->getArticleUrl();
                $msg->image = $message->getImageUrl();
                break;
        }

        return $msg;
    }

    public function uploadImage(Weixin $weixin, $image)
    {
        $filePath = '/tmp/' . md5(uniqid()) . '.' . $image->guessExtension();
        copy($image->getRealPath(), $filePath);
        $this->setWeixin($weixin);
        $material = $this->app->material;
        $result = $material->uploadImage($filePath);
        unlink($filePath);
        return $result;
    }

    public function syncArticles(Weixin $weixin)
    {
        $this->setWeixin($weixin);
        $material = $this->app->material;
        $stats = $material->stats();
        $count = $stats['news_count'];

        for ($i = 0; $i < $count; $i += 20) {
            $lists = $material->lists('news', $i, 20);
            foreach ($lists['item'] as $weixinNew) {

                $news = $this->em->getRepository('MauticPlugin\WeixinBundle\Entity\News')->findOneBy([
                    'mediaId' => $weixinNew['media_id']
                ]);
                if (!$news) {
                    $news = new \MauticPlugin\WeixinBundle\Entity\News();
                    $news->setWeixin($weixin);
                    $news->setMediaId($weixinNew['media_id']);
                    $this->em->persist($news);
                } else {
                    foreach ($news->getItems() as $item) {
                        $this->em->remove($item);
                    }
                }

                $news->setUpdateTime((new \DateTime())->setTimestamp($weixinNew['update_time']));

                foreach ($weixinNew['content']['news_item'] as $weixinItem) {
                    $item = new \MauticPlugin\WeixinBundle\Entity\NewsItem();
                    $this->em->persist($item);
                    $item->setNews($news);
                    $item->setTitle($weixinItem['title']);
                    $item->setThumbMediaId($weixinItem['thumb_media_id']);
                    $item->setShowCoverPic($weixinItem['show_cover_pic']);
                    $item->setAuthor($weixinItem['author']);
                    $item->setDigest($weixinItem['digest']);
                    $item->setContent($weixinItem['content']);
                    $item->setUrl($weixinItem['url']);
                    $item->setContentSourceUrl($weixinItem['content_source_url']);

                    try {
                        $image = $material->get($weixinItem['thumb_media_id']);
                        $filename = md5(uniqid()) . '.jpg';
                        file_put_contents($this->configs['material_dir'] . $filename, $image);
                        $item->setThumbMedia('material/' . $filename);
                    } catch (\Exception $e) {

                    }

                }
            }
        }
        $this->em->flush();
    }

    public function syncArticle(\MauticPlugin\WeixinBundle\Entity\News $news)
    {

        $this->setWeixin($news->getWeixin());
        $material = $this->app->material;
        $resource = $material->get($news->getMediaId());
        $news->setUpdateTime((new \DateTime())->setTimestamp($resource['update_time']));

        foreach ($news->getItems() as $item) {
            $this->em->remove($item);
        }

        foreach ($resource['news_item'] as $weixinItem) {
            $item = new \MauticPlugin\WeixinBundle\Entity\NewsItem();
            $this->em->persist($item);
            $item->setNews($news);
            $item->setTitle($weixinItem['title']);
            $item->setThumbMediaId($weixinItem['thumb_media_id']);
            $item->setShowCoverPic($weixinItem['show_cover_pic']);
            $item->setAuthor($weixinItem['author']);
            $item->setDigest($weixinItem['digest']);
            $item->setContent($weixinItem['content']);
            $item->setUrl($weixinItem['url']);
            $item->setContentSourceUrl($weixinItem['content_source_url']);

            $image = $material->get($weixinItem['thumb_media_id']);
            $filename = md5(uniqid()) . '.jpg';
            file_put_contents($this->configs['material_dir'] . $filename, $image);
            $item->setThumbMedia('material/' . $filename);
        }
        $this->em->flush();
    }

    public function sendArticleDirect(\MauticPlugin\WeixinBundle\Entity\NewsSend $send)
    {
        $this->setWeixin($send->getNews()->getWeixin());
        if ($send->getSendType() == NewsSend::NEWS_SEND_ALL) {
            $this->app->broadcast->sendNews($send->getNews()->getMediaId());

        }
        if ($send->getSendType() == NewsSend::NEWS_SEND_GROUP) {
            $list = $send->getList();
            $openids = $this->getLeadsInListWeixin($list, $send->getNews()->getWeixin()->getId());
            if(count($openids) == 0) {
                return;
            }
            if(count($openids) == 1) {
                $openids[] = '';
            }
            $this->app->broadcast->sendNews($send->getNews()->getMediaId(), $openids);
        }

        $send->setHasSent(true);
        $this->em->persist($send);
        $this->createSendHistory($send->getNews(), NewsHistory::TYPE_DERICT, $send->getSendType());
    }

    private function createSendHistory(\MauticPlugin\WeixinBundle\Entity\News $news, $type, $sendType)
    {
        $history = new NewsHistory();
        $history->setNews($news);
        $history->setTime(new \DateTime());
        $history->setType($type);
        $history->setSendType($sendType);

        $this->em->persist($history);
        $this->em->flush();
    }

    public function uploadQrcode(Qrcode $qrcode)
    {
        $this->setWeixin($qrcode->getWeixin());

        $result = $this->app->qrcode->forever($qrcode->getNb());
        $url = $result->url;
        $qrcode->setUrl($url);
    }

    private function recordScan($message, $qrcode, $isSub = false)
    {
        $scan = new QrcodeScan();
        $scan->setQrcode($qrcode);
        $scan->setUser($message->FromUserName);

        $leads = $this->em->getRepository('Mautic\LeadBundle\Entity\Lead')->getLeadsByFieldValue('wechat_openid', $message['FromUserName']);
        if (empty(key($leads))) {
            return;
        }
        $lead = $this->em->getRepository('Mautic\LeadBundle\Entity\Lead')->find(key($leads));
        $scan->setLead($lead);
        $scan->setScanTime((new \DateTime())->setTimestamp($message->CreateTime));
        $userInfos = $this->app->user->get($message->FromUserName);
        $scan->setCity($userInfos['city']);
        $scan->setProvince($userInfos['province']);
        $scan->setCountry($userInfos['country']);
        $scan->setIsSubscribeEvent($isSub);

        $this->em->persist($scan);
        $this->em->flush();
    }

    public function getUserInfos($weixin, $openId)
    {
        $this->setWeixin($weixin);
        return $this->app->user->get($openId);
    }

    public function sychUsers($weixin)
    {
        file_put_contents('/tmp/test.log', date('Y-m-d H:i:s') . ':' . 'sychUsers: ' . $weixin->getId() . PHP_EOL, FILE_APPEND);
        $this->setWeixin($weixin);
        $lists = $this->app->user->lists($nextOpenId = null);
        file_put_contents('/tmp/test.log', date('Y-m-d H:i:s') . ':' . json_encode($lists) . PHP_EOL, FILE_APPEND);

        foreach ($lists['data']['openid'] as $openid) {
            $event = new Event($weixin, ['FromUserName' => $openid]);
            $this->dispatcher->dispatch(Events::WEIXIN_SUBSCRIBE, $event);
        }
    }

    public function getLeadsInListWeixin($listId, $weixinId)
    {
        return $this->em->getRepository('MauticPlugin\WeixinBundle\Entity\LeadWeixin')->findOpenidsInListWeixin($listId, $weixinId);
    }

    public function getLeadWeixin($weixinId)
    {
        return $this->em->getRepository('MauticPlugin\WeixinBundle\Entity\LeadWeixin')->findOpenidsInWeixin($weixinId);
    }

}