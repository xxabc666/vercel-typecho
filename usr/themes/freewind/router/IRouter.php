<?php
/**
 * Author: Mr丶冷文
 * Date: 2022/10/14 11:37
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description: 统一路由接口
 */

namespace Freewind\Router;

use Typecho\Widget\Request;

interface IRouter
{
    function action(Request $request);
}