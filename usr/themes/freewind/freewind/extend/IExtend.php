<?php
/**
 * Author: Mr丶冷文
 * Date: 2022/10/15 12:06
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description:
 */

namespace Freewind\Extend;


use Freewind\Core\Cache;
use Typecho\Db;
use Typecho\Db\Exception;
use Typecho\Db\Query;

abstract class IExtend
{
    const ORDER_PARAM = "params";

    abstract static function init();

    abstract static function isInit(): bool;

    /**
     * @throws Exception
     */
    static function save($item, $table, $cacheName = [])
    {
        $db = Db::get();
        $id = $item['id'];
        unset($item['id']);
        if ($id) {
            $db->query($db->update($db->getPrefix() . $table)->rows($item)->where('id=?', $id));
        } else {
            $db->query($db->insert($db->getPrefix() . $table)->rows($item));
        }
        if ($cacheName) {
            foreach ($cacheName as $cname) {
                Cache::deleteCache($cname);
            }
        }
    }


    /**
     * @throws Exception
     */
    public static function search($table, $pageNum, $pageSize, $condition = [], $order = ''): array
    {
        $db = Db::get();
        $sql = $db->select()->from($db->getPrefix() . $table);
        $sql = self::condition2where($sql, $condition);
        if ($order) {
            $sql = $sql->order("ordered");
        }
        return $db->fetchAll($sql->page($pageNum, $pageSize));
    }

    /**
     * @throws Exception
     */
    public static function count($table, $condition = [])
    {
        $db = Db::get();
        $sql = $db->select('count(1) as count')->from($db->getPrefix() . $table);
        $sql = self::condition2where($sql, $condition);
        $result = $db->fetchRow($sql);
        return $result['count'];
    }


    public static function arr2str($arr): string
    {
        $result = "";
        foreach ($arr as $k => $v) {
            $result = $result . $k . '_' . $v;
        }
        return $result;
    }

    public static function lst($table, $cacheName = '', $condition = [], $order = '')
    {
        if ($cacheName) {
            $cdata = Cache::getCache($cacheName . self::arr2str($condition) . $order);
            if ($cdata) {
                return $cdata;
            }
        }
        try {
            $db = Db::get();
            $sql = $db->select()->from($db->getPrefix() . $table);
            $sql = self::condition2where($sql, $condition);
            if ($order) {
                $sql = $sql->order($order);
            }
            $result = $db->fetchAll($sql);
            if ($cacheName) {
                Cache::putCache($cacheName . self::arr2str($condition) . $order, $result);
            }
            return $result;
        } catch (Exception $e) {
            return [];
        }
    }


    /**
     * @throws Exception
     */
    public static function delete($table, $id, $cacheName = [])
    {
        $db = Db::get();
        $db->query($db->delete($db->getPrefix() . $table)->where('id=?', $id));
        if ($cacheName) {
            foreach ($cacheName as $cname) {
                Cache::deleteCache($cname);
            }
        }
    }

    /**
     * @throws Exception
     */
    public static function order($table, $info, $order = 'ordered', $cacheName = []): void
    {
        $id = $info[0];
        $od = $info[1];
        if ($id && $od) {
            $db = Db::get();
            $db->query($db->update($db->getPrefix() . $table)->rows([$order => $od])->where('id=?', $id));
        }
        if ($cacheName) {
            foreach ($cacheName as $cname) {
                Cache::deleteCache($cname);
            }
        }
    }


    static function condition2where(Query $sql, $condition = []): Query
    {
        foreach ($condition as $k => $v) {
            if ($v)
                $sql = $sql->where("$k=?", $v);
        }
        return $sql;
    }

}