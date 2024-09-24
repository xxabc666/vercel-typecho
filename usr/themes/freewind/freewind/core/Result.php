<?php
/**
 * Author: Mr丶冷文
 * Date: 2022/10/14 11:23
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description: Ajax返回
 */

namespace Freewind\Core;

class Result
{
    /**
     * Ajax返回
     * @param false $success 是否成功
     * @param string $msg 消息
     * @param array $data 数据
     */
    function normail(bool $success = false, string $msg = '', array $data = [])
    {
        $result = [
            'success' => $success,
            'msg' => $msg,
            'data' => $data
        ];
        self::returnMsg($result);
    }

    /**
     * Ajax返回 错误
     * @param string $msg 错误信息
     */
    public static function error(string $msg = '', $data = [])
    {
        $result = [
            'success' => false,
            'msg' => $msg,
            'data' => $data
        ];
        self::returnMsg($result);
    }

    /**
     * Ajax返回 成功
     * @param string $msg 成功信息
     * @param array $data 数据
     */
    public static function success(string $msg = '', $data = [])
    {
        $result = [
            'success' => true,
            'msg' => $msg,
            'data' => $data
        ];
        self::returnMsg($result);
    }


    /**
     * Ajax返回  自定义
     * @param false[] $data
     */
    public static function returnMsg(array $data = ['success' => false])
    {
        ob_clean();
        echo json_encode($data);
        exit();
    }


    public static function validator_error($error)
    {
        foreach ($error as $v) {
            self::error($v);
        }
    }
}