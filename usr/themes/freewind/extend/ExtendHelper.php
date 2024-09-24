<?php
/**
 * Author: Mr丶冷文
 * Date: 2022/10/17 13:22
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description:
 */

namespace Freewind\Extend;

class ExtendHelper
{
    static $extends = [
        'Freewind\Extend\LinkedExtend',
        'Freewind\Extend\OptionExtend',
        'Freewind\Extend\NavigationExtend',
        'Freewind\Extend\BannerExtend',
        'Freewind\Extend\RightExtend',
        'Freewind\Extend\FileExtend',
    ];

    public static function initExtend(): void
    {
        foreach (self::$extends as $extend) {
            if (!$extend::isInit()) {
                $extend::init();
            }
        }
    }
}