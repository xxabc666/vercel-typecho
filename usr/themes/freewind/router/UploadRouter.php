<?php

/**
 * Author: Mr丶冷文
 * Date: 2021-11-28 14:09
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description:
 */

namespace Freewind\Router;

use Freewind\Core\Result;
use Typecho\Widget\Request;

class UploadRouter implements IRouter
{

    function action(Request $request)
    {
        $allowedExts = array("gif", "jpeg", "jpg", "png");
        $temp = explode(".", $_FILES["file"]["name"]);
        $extension = end($temp);     // 获取文件后缀名
        if ((($_FILES["file"]["type"] == "image/gif")
                || ($_FILES["file"]["type"] == "image/jpeg")
                || ($_FILES["file"]["type"] == "image/jpg")
                || ($_FILES["file"]["type"] == "image/pjpeg")
                || ($_FILES["file"]["type"] == "image/x-png")
                || ($_FILES["file"]["type"] == "image/png"))
            && ($_FILES["file"]["size"] < 5 * 1024 * 1024)
            && in_array($extension, $allowedExts)) {
            if ($_FILES["file"]["error"] > 0) {
                Result::error('文件上传异常');
            } else {
                $arr = explode(".", $_FILES["file"]["name"]);
                $hz = $arr[count($arr) - 1];
                $name = gmmktime() . '.' . $hz;
                $filename = __TYPECHO_ROOT_DIR__ . '/upload/' . date('Ymd') . '/' . $name;
                $dir = dirname($filename);
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                }
                move_uploaded_file($_FILES["file"]["tmp_name"], $filename);
                Result::success("文件上传成功", '/upload/' . date('Ymd') . '/' . $name);
            }
        } else {
            Result::error('文件格式有误   ');
        }
    }
}