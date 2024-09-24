<?php
/**
 * Author: Mr丶冷文
 * Date: 2022/10/18 01:18
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description:
 */

namespace Freewind\Extend;

use Typecho\Db;
use Typecho\Db\Exception;

class NavigationExtend extends IExtend
{
    const CACHE_PREFIX = "cache:freewind_navigation_";

    const TYPE_PAGE = 1;
    const TYPE_NAVIGATION = 2;

    const STATUS_ENABLE = 1;
    const STATUS_DISABLE = 2;
    const TARGET_CURRENT = 1;
    const TARGET_NEW = 2;

    const FIELD_LIST = ['id', 'name', 'ordered', 'status', 'target', 'icon', 'type', 'url'];

    const TABLE_NAME = "freewind_navigation";


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
                    `ordered` int(11) not null default 1,
                    `status` int(11) not null default 1,
                    `target` int(11) not null default 1,
                    `type` int(11) not null default 1,
                    `icon` varchar(255)  NULL ,
                    `url` varchar(512) NULL,
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