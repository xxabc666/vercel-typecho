<?php
/**
 * Author: Mr丶冷文
 * Date: 2022/10/14 10:35
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description:
 */

namespace Freewind\Config;

class Constants
{
    //参数路由的参数名称
    const FREEWIND_PARAM = 'freewind_param';
    //默认Cookie过期时间
    const LOCAL_COOKIE_EXPIRE = 3600;
    //配色Cookie
    const COLOR_LOCAL_COOKIE = 'freewind_color_local_cookie';
    //文章状态为发布
    const POST_STATUS_PUBLISH = 'publish';
    //文章类型为文章
    const POST_TYPE_ARTICLE = 1;
    //参数路由为评论
    const COMMENT_PARAMS = 'comment';
    //头像缓存前缀
    const CACHE_AVATAR_PREFIX = 'avatar_cache:';
    //头像一周缓存
    const CACHE_AVATAR_EXPIRE = 604800;

    const PARAMS_OPTIONS = "options";
}