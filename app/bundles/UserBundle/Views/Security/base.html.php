<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title><?php echo $view['slots']->get('pageTitle', 'Linkall'); ?></title>
    <meta name="robots" content="noindex, nofollow" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="icon" type="image/x-icon" href="<?php echo $view['assets']->getUrl('media/images/favicon.ico') ?>" />
    <link rel="apple-touch-icon" href="<?php echo $view['assets']->getUrl('media/images/apple-touch-icon.png') ?>" />
    <?php $view['assets']->outputSystemStylesheets(); ?>
    <?php echo $view->render('MauticCoreBundle:Default:script.html.php'); ?>
    <?php $view['assets']->outputHeadDeclarations(); ?>
</head>
<style type="text/css">
    .bg{
        overflow-y: hidden;
        position:fixed;
        top: 0;
        left: 0;
        width:100%;
        height:100%;
        min-width: 1000px;
        z-index:-10;
        zoom: 1;
        background-color: #fff;
        background-repeat: no-repeat;
        background-size: cover;
        -webkit-background-size: cover;
        -o-background-size: cover;
        background-position: center 0;

/*        background: url("*/<?php //echo $view['assets']->getUrl('app/bundles/UserBundle/Assets/images/bg_login.png'); ?>/* ") center no-repeat;*/
    }
/*    @media only screen and (min-width: 1024px){*/
/*        /*当分辨率width >= 1024px 时使用1.jpg作为背景图片*/*/
/*        .bg{*/
/*            background: url("*/<?php //echo $view['assets']->getUrl('app/bundles/UserBundle/Assets/images/bg_login1.png'); ?>/* ") center no-repeat;*/
/*        }*/
/*    }*/
/*    @media only screen and (min-width: 400px) and (max-width: 1024px)*/
/*    { /*当分辨率400px < width < 1024px 时使用2.jpg作为背景图片*/*/
/**/
/*        .bg{*/
/**/
/*            background: url("*/<?php //echo $view['assets']->getUrl('app/bundles/UserBundle/Assets/images/bg_login2.png'); ?>/* ") center no-repeat;*/
/*        }*/
/*    }*/
/*    @media only screen and (max-width: 400px)*/
/*    { /*当分辨率width =< 400px 时使用3.jpg作为背景图片*/*/
/*        .bg{*/
/*            background: url("*/<?php //echo $view['assets']->getUrl('app/bundles/UserBundle/Assets/images/bg_login3.png'); ?>/* ") center no-repeat;*/
/*        }*/
/*    }*/
</style>
<body class="bg" style='background: url("<?php echo $view['assets']->getUrl('app/bundles/UserBundle/Assets/images/bg_login.jpg'); ?> ");'>
<section id="main" role="main">
    <div class="container" style='margin-top:80px;'>
        <div class="row" style="margin-top: 50px">
            <div class="col-lg-4 col-lg-offset-4" style="margin-left: 61.333333%;width: 31.333333%;">
                <!--登陆界面 start-->
                <div class="panel" name="form-login" style="background: rgba(245,249,253,0.5) ;height: 420px;">
                    <div class="panel-body">
                        <div class="mautic-logo img-circle mb-md text-center" style="background: none">
                            <img class="mautic-logo-figure" width="140" src="<?php echo $view['assets']->getUrl('app/bundles/UserBundle/Assets/images/logo.png'); ?>">
                        </div>
                        <div id="main-panel-flash-msgs">
                            <?php echo $view->render('MauticCoreBundle:Notification:flashes.html.php'); ?>
                        </div>
                        <?php $view['slots']->output('_content'); ?>
                    </div>
                </div>
                <!--登陆框 start-->
                <div class="col-lg-4 col-lg-offset-4 text-center text-muted" style="width: 70.333333%;margin-left: 16.333333%;">
                    <?php echo $view['translator']->trans('mautic.core.copyright', ['%date%' => date('Y')]); ?>
                </div>
                <!--登陆框 end-->
            </div>

            <!--登陆界面 end-->
        </div>
<!--        <div class="row">-->
<!--            <div class="col-lg-4 col-lg-offset-4 text-center text-muted" style="margin-left: 35.333333%;">-->
<!--                --><?php //echo $view['translator']->trans('mautic.core.copyright', ['%date%' => date('Y')]); ?>
<!--            </div>-->
<!--        </div>-->
    </div>
</section>
<?php echo $view['security']->getAuthenticationContent(); ?>
</body>
</html>

<script type="text/javascript">
//   if (window.innerWidth)
//   winWidth = window.innerWidth;
//   else if ((document.body) && (document.body.clientWidth))
//       winWidth = document.body.clientWidth;
//   // 获取窗口高度
//   if (window.innerHeight)
//       winHeight = window.innerHeight;
//   else if ((document.body) && (document.body.clientHeight))
//       winHeight = document.body.clientHeight;
//   // 通过深入 Document 内部对 body 进行检测，获取窗口大小
//   if (document.documentElement && document.documentElement.clientHeight && document.documentElement.clientWidth)
//   {
//       winHeight = document.documentElement.clientHeight;
//       winWidth = document.documentElement.clientWidth;
//   }
//
//   alert('w:'+winWidth +'and h:'+winHeight);
</script>
