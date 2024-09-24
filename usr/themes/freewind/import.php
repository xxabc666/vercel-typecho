<?php
/**
 * Author: Mr丶冷文
 * Date: 2022/10/14 11:14
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description:
 */
function import(string $package, $before = [], $after = [])
{
    foreach ($before as $filename) {
        if (strpos($filename, '.php') != strlen($filename) - 4) {
            $filename = $filename . '.php';
        }
        include_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . $package . DIRECTORY_SEPARATOR . $filename);
    }
    $handler = opendir(dirname(__FILE__) . DIRECTORY_SEPARATOR . $package);
    $filenames = [];
    while (($filename = readdir($handler)) !== false) {
        if ($filename != '.' && $filename != '..' && strpos($filename, '.php') == strlen($filename) - 4) {
            if (!(in_array($filename, $before) || in_array(str_replace('.php', '', $filename), $before)
                || in_array($filename, $after) || in_array(str_replace('.php', '', $filename), $after))) {
                $filenames[] = $filename;
            }
        }
    }
    sort($filenames);
    foreach ($filenames as $filename)
        include_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . $package . DIRECTORY_SEPARATOR . $filename);
    foreach ($after as $filename) {
        if (strpos($filename, '.php') != strlen($filename) - 4) {
            $filename = $filename . '.php';
        }
        include_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . $package . DIRECTORY_SEPARATOR . $filename);
    }
}