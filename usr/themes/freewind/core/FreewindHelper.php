<?php
/**
 * Author: Mr丶冷文
 * Date: 2021-11-24 19:12
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description: 主题相关工具
 */

namespace Freewind\Core;

use Freewind\Config\Constants;
use Typecho\Exception;
use Typecho\Widget;
use Utils\Helper;


class FreewindHelper
{

    private static $helper;

    private $options;

    public $version = "1.0";

    const DEFAULT_CDN_PREFIX = 'https://cdn.jsdelivr.net/gh/kevinlu98/freecdn@';


    /**
     * 构造方法
     */
    private function __construct()
    {
        $this->options = Helper::options();

        $theme = $this->options->theme;
        $themes = Widget::widget('\Widget\Themes\Rows');
        while ($themes->next()) {
            if ($theme == $themes->name) {
                $this->version = $themes->version;
                break;
            }
        }

    }


    /**
     * 获取freewind版本
     * @return mixed|null
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * 设置本地配色
     * @param string $name 目标配色
     */
    public static function userLocalColor(string $name = "default.css")
    {
        CookieHelper::set(Constants::COLOR_LOCAL_COOKIE, $name, Constants::LOCAL_COOKIE_EXPIRE);
    }

    /**
     * 获取主题配色
     * @return mixed|string
     */
    public static function themeColor()
    {
        if (Site::get(Site::NAME_SITE_STYLE_ALLOW) == Site::ENABLE) {
            $color = CookieHelper::get(Constants::COLOR_LOCAL_COOKIE);
            if ($color) return $color;
        }
        return Site::get(Site::NAME_SITE_STYLE) ?: 'default.css';
    }

    /**
     * 获取最新版本
     * @return bool|string
     * @throws Exception
     * @throws \Exception
     */
    public static function lastVersion()
    {
        $result = json_decode(HttpHelper::sendHttp('http://api.freewind.kevinlu98.cn/api/last'));
        return $result->data;
    }

    /**
     * 获取本地所有配色
     * @return array|false
     */
    public static function colorList()
    {
        $colors = scandir(__FREEWIND_ROOT__ . '/static/css/color');
        $colors = array_filter($colors, function ($filename) {
            return strpos($filename, ".css");
        });
        $colors = array_map(function ($name) {
            $filename = __FREEWIND_ROOT__ . '/static/css/color/' . $name;
            $fp = fopen($filename, 'r');
            $content = fread($fp, 1024);
            $is_color = preg_match('/@color-name: (.*)/', $content, $matche_name);
            $is_top = preg_match('/@top-color: (.*)/', $content, $matche_top);
            $is_left = preg_match('/@left-color: (.*)/', $content, $matche_left);
            return [
                'name' => $is_color ? $matche_name[1] : '',
                'filename' => $name,
                'top' => $is_top ? $matche_top[1] : '',
                'left' => $is_left ? $matche_left[1] : ''
            ];
        }, $colors);
        return array_filter($colors, function ($color) {
            return strlen($color['name']) > 0 && strlen($color['top']) > 0 && strlen($color['left']) > 0;
        });
    }

    public static function freeCdn($path = ""): string
    {
        if (defined("__FREEWIND_DEBUG__") && __FREEWIND_DEBUG__) {
            return '/usr/themes/freewind/static/' . $path . ($path ? '?v=' . self::instance()->version : '');
        }
        if (Site::get(Site::NAME_DEP_STATIC_ENABLE) == Site::ENABLE) {
            if (Site::get(Site::NAME_DEP_STATIC_PATH)) {
                return Site::get(Site::NAME_DEP_STATIC_PATH) . $path . ($path ? '?v=' . self::instance()->version : '');
            }
        }
        return self::DEFAULT_CDN_PREFIX . self::instance()->version . '/' . $path . ($path ? '?v=' . self::instance()->version : '');

    }

    public static function instance(): FreewindHelper
    {
        if (!self::$helper instanceof self) {
            self::$helper = new self();
        }
        return self::$helper;
    }
}