<?php

namespace Freewind\Router;

use Freewind\Core\Result;
use Freewind\Core\FreewindHelper;
use Freewind\Core\HttpHelper;
use Typecho\Exception;
use Typecho\Widget\Request;

/**
 * Author: Mr丶冷文
 * Date: 2021-11-25 23:42
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description:
 */
class UpdateRouter implements IRouter
{

    /**
     * @throws Exception
     */
    function action(Request $request)
    {
        $version = FreewindHelper::instance()->getVersion();
        $lastVersion = FreewindHelper::lastVersion();
        $msg = '当前版本为【' . $version . '】，最新版本为【' . $lastVersion . '】';
        Result::success($msg, [
            'islast' => $lastVersion == $version,
            'url' => 'https://kevinlu98.cn/archives/27.html'
        ]);
    }
}