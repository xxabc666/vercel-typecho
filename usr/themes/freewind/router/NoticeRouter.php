<?php

/**
 * Author: Mr丶冷文
 * Date: 2021-11-28 16:49
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description:
 */

namespace Freewind\Router;

use Freewind\Core\CookieHelper;
use Freewind\Core\Result;
use Typecho\Widget\Request;

class NoticeRouter implements IRouter
{

    function action(Request $request)
    {
        CookieHelper::set(CookieHelper::COOKIE_ADVISE, 1);
        Result::success();
    }

    function closeTips()
    {
        CookieHelper::set(CookieHelper::COOKIE_POST_TIPS, 1, 3600 * 24 * 7);
        Result::success();
    }
}