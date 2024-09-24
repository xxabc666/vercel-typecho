<?php

/**
 * Author: Mr丶冷文
 * Date: 2021-11-24 20:49
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description: http请求工具类
 */

namespace Freewind\Core;


use Exception;

class HttpHelper
{
    /**
     * @param string $url url
     * @param array $params 参数
     * @param string $method 请求方式
     * @param array $header 请求头
     * @param int $timeout 超时
     * @return bool|string 请求结果
     * @throws Exception
     */
    public static function sendHttp(string $url, array $params = [], string $method = 'GET', array $header = array(), int $timeout = 5)
    {
        if (!$header['Content-Type'])
            $header['Content-Type'] = 'application/json;charset=UTF-8';
        $opts = array(
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER => $header
        );
        switch (strtoupper($method)) {
            case 'GET':
                $opts[CURLOPT_URL] = $url . '?' . http_build_query($params);
                break;
            case 'POST':
                $params = http_build_query($params);
                $opts[CURLOPT_URL] = $url;
                $opts[CURLOPT_POST] = 1;
                $opts[CURLOPT_POSTFIELDS] = $params;
                break;
            case 'DELETE':
                $opts[CURLOPT_URL] = $url;
                $opts[CURLOPT_HTTPHEADER] = array("X-HTTP-Method-Override: DELETE");
                $opts[CURLOPT_CUSTOMREQUEST] = 'DELETE';
                $opts[CURLOPT_POSTFIELDS] = $params;
                break;
            case 'PUT':
                $opts[CURLOPT_URL] = $url;
                $opts[CURLOPT_POST] = 0;
                $opts[CURLOPT_CUSTOMREQUEST] = 'PUT';
                $opts[CURLOPT_POSTFIELDS] = $params;
                break;
            default:
                throw new Exception('不支持的请求方式！');
        }
        $ch = curl_init();
        curl_setopt_array($ch, $opts);
        $data = curl_exec($ch);
        curl_error($ch);
        return $data;
    }
}