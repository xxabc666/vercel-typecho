<?php
/**
 * Author: Mr丶冷文
 * Date: 2021-11-24 20:22
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description: 本地缓存工具类
 */

namespace Freewind\Core;

class Cache
{
    private static $insance;


    private static function instance(): Cache
    {
        if (!self::$insance instanceof self) {
            self::$insance = new self(__TYPECHO_ROOT_DIR__ . '/cache');
        }
        return self::$insance;
    }


    /**
     * 写本地缓存
     * @param string $key 键
     * @param mixed $value 值
     * @param int|null $expire 过期时间
     */
    public static function putCache(string $key, $value, int $expire = null)
    {
        self::instance()->set($key, $value, $expire);
    }

    /**
     * 删除本地缓存
     * @param string $key
     * @return void
     */
    public static function deleteCache(string $key)
    {
        self::instance()->del($key);
    }

    /**
     * 读本地绊缓存
     * @param string $key
     * @return false|string|null
     */
    public static function getCache($key)
    {
        return self::instance()->get($key);
    }

    /**
     * 缓存目录
     * @var ?string
     */
    private static $msCachePath = null;
    /**
     * 默认缓存失效时间(24小时)
     * @var int
     */
    const miEXPIRE = 86400;

    /**
     * 构造
     * self::$msCachePath 缓存目录为共享目录
     * @param string $sCachePath
     */
    function __construct(string $sCachePath = './cache/')
    {
        if (is_null(self::$msCachePath))
            self::$msCachePath = $sCachePath;
    }

    /**
     * 读取缓存
     * 返回: 缓存内容,字符串或数组；缓存为空或过期返回null
     * @param string $sKey 缓存键值(无需做md5())
     * @return string | null
     * @access public
     */
    public function get(string $sKey)
    {
        if (empty($sKey))
            return false;

        $sFile = self::getFileName($sKey);
        if (!file_exists($sFile))
            return null;
        else {
            $handle = fopen($sFile, 'rb');
            if (intval(fgets($handle)) > time())//检查时间戳
            {    //未失效期，取出数据
                $sData = fread($handle, filesize($sFile));
                fclose($handle);
                return unserialize($sData);
            } else {    //已经失效期
                fclose($handle);
                return null;
            }
        }
    }

    /**
     * 写入缓存
     *
     * @param string $sKey 缓存键值
     * @param mixed $mVal 需要保存的对象
     * @param int|null $iExpire 失效时间
     * @return bool
     * @access public
     */
    public function set(string $sKey, $mVal, int $iExpire = null): bool
    {
        if (empty($sKey))
            return false;

        $sFile = self::getFileName($sKey);
        if (!file_exists(dirname($sFile)))
            if (!self::isMkdir(dirname($sFile)))
                return false;

        $aBuf = array();
        $aBuf[] = time() + ((empty($iExpire)) ? self::miEXPIRE : intval($iExpire));
        $aBuf[] = serialize($mVal);
        /*写入文件操作*/
        $handle = fopen($sFile, 'wb');
        fwrite($handle, implode("\n", $aBuf));
        fclose($handle);
        return true;
    }

    /**
     * 删除指定的缓存键值
     *
     * @param string $sKey 缓存键值
     * @return bool 是否删除成功
     */
    public function del(string $sKey): bool
    {
        if (empty($sKey))
            return false;
        else {
            @unlink(self::getFileName($sKey));
            return true;
        }
    }

    /**
     * 获取缓存文件全路径
     * 返回: 缓存文件全路径
     * $sKey的值会被转换成md5(),并分解为3级目录进行访问
     * @param string $sKey 缓存键
     * @return string
     * @access protected
     */
    private static function getFileName(string $sKey)
    {
        if (empty($sKey))
            return false;

        $key_md5 = md5($sKey);
        $aFileName = array();
        $aFileName[] = rtrim(self::$msCachePath, '/');
        $aFileName[] = $key_md5[0] . $key_md5[1];
        $aFileName[] = $key_md5[2] . $key_md5[3];
        $aFileName[] = $key_md5[4] . $key_md5[5];
        $aFileName[] = $key_md5;
        return implode('/', $aFileName);
    }

    /**
     * 创建目录
     *
     * @param string $sDir
     * @return bool
     */
    private static function isMkdir(string $sDir = ''): bool
    {
        if (empty($sDir))
            return false;

        /*如果无法创建缓存目录，让系统直接抛出错误提示*/
        if (!mkdir($sDir, 0755, true))
            return false;
        else
            return true;
    }
}