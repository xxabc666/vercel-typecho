<?php
/**
 * Author: Mr丶冷文
 * Date: 2022/10/14 11:51
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description: Cookie工具
 */

namespace Freewind\Core;

use Freewind\Config\Constants;

class CookieHelper
{

    const COOKIE_ADVISE = "freewind_advise__";
    const COOKIE_POST_TIPS = "freewind_post_tips__";

    private static function encrypt($data): string
    {
        return urlencode(base64_encode($data));
    }

    private static function decrypt($data)
    {
        return base64_decode(urldecode($data));
    }


    public static function get($key)
    {
        $k = md5($key);
        $value = $_COOKIE[$k];
        if ($value) {
            return self::decrypt($value);
        }
        return false;
    }


    public static function set($key, $value, $expires = 0)
    {
        $k = md5($key);
        $v = self::encrypt($value);
        $time = time() + ($expires > 0 ? $expires : Constants::LOCAL_COOKIE_EXPIRE);
        setcookie($k, $v, $time, '/');
    }
}