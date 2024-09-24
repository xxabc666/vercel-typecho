<?php
/**
 * Author: Mr丶冷文
 * Date: 2022/10/17 13:13
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description:
 */

namespace Freewind\Extend;

use Typecho\Db;
use Typecho\Db\Exception;

class LinkedExtend extends IExtend
{
    const TABLE_NAME = "freewind_friendly";

    const FIELD_LIST = ['id', 'name', 'link', 'icon', 'desc'];
    const CACHE_PREFIX = "freewind_banner_";

    /**
     * @throws Exception
     */
    static function init()
    {
        $db = Db::get();
        $tname = self::TABLE_NAME;
        $sql = "CREATE TABLE `{$db->getPrefix()}$tname`  (
                    `id` int NOT NULL AUTO_INCREMENT,
                    `name` varchar(255) not null ,
                    `link` varchar(255) not null ,
                    `icon` varchar(255) NULL,
                    `desc` varchar(512) NULL,
                    PRIMARY KEY (`id`)
                );";
        $db->query($sql);
    }

    /**
     * @throws Exception
     */
    static function isInit(): bool
    {
        $db = Db::get();
        $result = $db->fetchRow($db->select("count(1) as count")->from("information_schema.TABLES")->where("TABLE_NAME = ?", $db->getPrefix() . self::TABLE_NAME));
        var_dump($result);
        return $result['count'] > 0;
    }
}