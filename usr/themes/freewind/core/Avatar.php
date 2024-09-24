<?php

namespace Freewind\Core;

use Freewind\Config\Constants;
use Typecho\Common;

/**
 * Author: Mr丶冷文
 * Date: 2021-11-25 14:54
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description: 头像工具类
 */
class Avatar
{
    /**
     * 检验gravatar头像是否存在
     * @param string $email 邮箱
     * @return bool
     */
    private static function existGravatar(string $email): bool
    {
        $hash = md5(strtolower(trim($email)));
        if (defined('__TYPECHO_GRAVATAR_PREFIX__')) {
            $uri = __TYPECHO_GRAVATAR_PREFIX__ . $hash . '?d=404';
        } else {
            $uri = 'http://www.gravatar.com' . $hash . '?d=404';
        }

        $headers = @get_headers($uri);
        if (!preg_match("|200|", $headers[0])) {
            $has_valid_avatar = FALSE;
        } else {
            $has_valid_avatar = TRUE;
        }
        return $has_valid_avatar;
    }

    /**
     * 邮箱获取头像，有7天缓存
     * @param string $mail 邮箱
     * @return string
     */
    public static function get(string $mail): string
    {
        if (empty($mail)) return FreewindHelper::freeCdn('avatar/' . mt_rand(1, 40) . '.png');
        $avatar = Cache::getCache(Constants::CACHE_AVATAR_PREFIX . $mail);
        if ($avatar) return $avatar;
        $avatar = FreewindHelper::freeCdn('avatar/' . mt_rand(1, 40) . '.png');
        $qq = str_replace("@qq.com", "", $mail);
        if (is_numeric(trim($qq))) {
            $avatar = 'https://q1.qlogo.cn/g?b=qq&nk=' . $qq . '&s=100';
        } elseif (self::existGravatar($mail)) {
            $avatar = Common::gravatarUrl($mail, 220, 'X', 'mm');
        }
        if ($avatar) {
            Cache::putCache(Constants::CACHE_AVATAR_PREFIX . $mail, $avatar, Constants::CACHE_AVATAR_EXPIRE);
        }
        return $avatar;
    }

}