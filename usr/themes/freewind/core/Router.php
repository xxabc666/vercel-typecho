<?php
/**
 * Author: Mr丶冷文
 * Date: 2022/10/14 11:21
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description:
 */

namespace Freewind\Core;

use Freewind\Config\Constants;
use Freewind\Config\RouterConfig;
use Typecho\Plugin;
use Typecho\Widget;
use Utils\Helper;
use Widget\Archive;

class Router
{
    public static function action(Archive $archive)
    {
        foreach (RouterConfig::ROUTER as $path => $router_weight) {
            $pathInfo = $archive->request->getPathInfo();
            $widget_name = $router_weight[0];
            $admin = $router_weight[1];
            if ($path == $pathInfo) {
                if ($admin && !Widget::widget('Widget_User')->pass('administrator', true)) {
                    Result::error('权限不允许执行操作');
                }
                $widget = new $widget_name();
                if ($router_weight[2]) {
                    $fun = $router_weight[2];
                    $widget->$fun($archive->request);
                } else {
                    $widget->action($archive->request);
                }
                break;
            }
        }
    }


    /**
     * 注册路由
     * @return void
     */
    public static function register()
    {
        foreach (RouterConfig::ROUTER as $path => $widget) {
            Helper::addRoute($widget[0] . ($widget[2] ?: ''), $path, 'Widget\Archive', 'render');
        }
    }

    public static function register_params(Archive $archive)
    {
        foreach (RouterConfig::PARAMS as $param => $param_widget) {
            if ($archive->request->is(Constants::FREEWIND_PARAM . '=' . $param)) {
                $admin = $param_widget[1];
                $widget_name = $param_widget[0];
                if ($admin && !Widget::widget('Widget_User')->pass('administrator', true)) {
                    Result::error('权限不允许执行操作');
                }
                $widget = new $widget_name();
                if ($param_widget[2]) {
                    $fun = $param_widget[2];
                    $widget->$fun($archive);
                } else {
                    $widget->action($archive);
                }
                break;
            }
        }

    }
}