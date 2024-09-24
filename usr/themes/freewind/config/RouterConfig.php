<?php
/**
 * Author: Mr丶冷文
 * Date: 2022/10/14 11:17
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description: 路由
 */

namespace Freewind\Config;

class RouterConfig
{

    const ROUTER = [
        '/mail/check' => ['\Freewind\Router\MailRouter', true],
        '/mail/send' => ['\Freewind\Router\MailRouter', false, 'sendMail'],
        '/update/check' => ['\Freewind\Router\UpdateRouter', true],
        '/local/color' => ['\Freewind\Router\ColorRouter', false],
        '/login' => ['\Freewind\Router\LoginRouter', false],
        '/verify/code' => ['\Freewind\Router\VerifyCodeRouter', false],
        '/register' => ['\Freewind\Router\RegistRouter', false],
        '/freewind/upload' => ['\Freewind\Router\UploadRouter', true],
        '/support' => ['\Freewind\Router\SupportRouter', false],
        '/close/notice' => ['Freewind\Router\NoticeRouter', false],
        '/close/postips' => ['Freewind\Router\NoticeRouter', true, 'closeTips'],
        '/report' => ['\Freewind\Router\ReportRouter', false],

        '/website/info' => ['\Freewind\Router\WebsiteRouter', true, "info"],
        '/website/save' => ['\Freewind\Router\WebsiteRouter', true, "save"],
        '/website/page/site' => ['\Freewind\Router\WebsiteRouter', true, "pageSite"],
        '/website/page/bg' => ['\Freewind\Router\WebsiteRouter', true, "pageBg"],
        '/website/page/master' => ['\Freewind\Router\WebsiteRouter', true, "pageMaster"],
        '/website/page/comment' => ['\Freewind\Router\WebsiteRouter', true, "pageComment"],
        '/website/page/developer' => ['\Freewind\Router\WebsiteRouter', true, "pageDeveloper"],

        '/freewind/info' => ['\Freewind\Router\ThemeRouter', true, "page"],

        '/navigation/save' => ['\Freewind\Router\NavigationRouter', true, "save"],
        '/navigation/list' => ['\Freewind\Router\NavigationRouter', true, "search"],
        '/navigation/order' => ['\Freewind\Router\NavigationRouter', true, "order"],
        '/navigation/del' => ['\Freewind\Router\NavigationRouter', true, "delete"],
        '/navigation/page' => ['\Freewind\Router\NavigationRouter', true, "page"],

        '/banner/page' => ['\Freewind\Router\BannerRouter', true, 'page'],
        '/banner/save' => ['\Freewind\Router\BannerRouter', true, 'save'],
        '/banner/list' => ['\Freewind\Router\BannerRouter', true, 'search'],
        '/banner/del' => ['\Freewind\Router\BannerRouter', true, 'delete'],
        '/banner/order' => ['\Freewind\Router\BannerRouter', true, 'order'],

        '/linked/page' => ['\Freewind\Router\LinkedRouter', true, 'page'],
        '/linked/save' => ['\Freewind\Router\LinkedRouter', true, 'save'],
        '/linked/list' => ['\Freewind\Router\LinkedRouter', true, 'search'],
        '/linked/del' => ['\Freewind\Router\LinkedRouter', true, 'delete'],

        '/right/page' => ['\Freewind\Router\RightRouter', true, 'page'],
        '/right/save' => ['\Freewind\Router\RightRouter', true, 'save'],
        '/right/list' => ['\Freewind\Router\RightRouter', true, 'search'],
        '/right/del' => ['\Freewind\Router\RightRouter', true, 'delete'],
        '/right/order' => ['\Freewind\Router\RightRouter', true, 'order'],

        '/fwfile/page' => ['\Freewind\Router\FileRouter', true, 'page'],
        '/fwfile/save' => ['\Freewind\Router\FileRouter', true, 'save'],
        '/fwfile/list' => ['\Freewind\Router\FileRouter', true, 'search'],
        '/fwfile/del' => ['\Freewind\Router\FileRouter', true, 'delete'],
        '/fwfile/order' => ['\Freewind\Router\FileRouter', true, 'order'],


    ];
    const PARAMS = [
        Constants::COMMENT_PARAMS => ['\Freewind\Widget\CommentWidget', false],
    ];
}