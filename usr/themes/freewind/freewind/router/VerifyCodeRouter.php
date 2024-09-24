<?php
/**
 * Author: Mr丶冷文
 * Date: 2022/10/14 12:23
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description:
 */

namespace Freewind\Router;

use Freewind\Core\VerifyCode;
use Typecho\Widget\Request;

class VerifyCodeRouter implements IRouter
{

    function action(Request $request)
    {
        ob_clean();
        VerifyCode::createCode();
        exit();
    }
}