<?php
/**
 * Author: Mr丶冷文
 * Date: 2022/10/14 12:59
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description:
 */

namespace Freewind\Router;

use Exception;
use Freewind\Core\Result;
use Freewind\Core\FreewindHelper;
use Typecho\Widget\Request;

class ColorRouter implements IRouter
{

    function action(Request $request)
    {
        $name = $request->get('name');
        if ($name) {
            try {
                if ($name == FreewindHelper::themeColor()) {
                    Result::error('目标配色与当前配色一样');
                }
                FreewindHelper::userLocalColor($name);
                Result::success('配色更换成功');
            } catch (Exception $e) {
                Result::error($e->getMessage());
            }
        }
        Result::error('配色不能为空');
    }
}