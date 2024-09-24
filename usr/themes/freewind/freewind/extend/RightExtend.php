<?php
/**
 * Author: Mr丶冷文
 * Date: 2022/10/19 00:51
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description:
 */

namespace Freewind\Extend;

use Typecho\Db;
use Typecho\Db\Exception;

class RightExtend extends IExtend
{
    const TABLE_NAME = "freewind_right";


    const FIELD_LIST = ['id', 'name', 'link', 'icon', 'type', 'status', 'ordered', 'script'];
    const CACHE_PREFIX = "freewind_right_btn_";

    const TYPE_LINK = 1;
    const TYPE_SCRIPT = 2;
    const STATUS_ENABLE = 1;
    const STATUS_DISABLE = 2;

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
                    `icon` varchar(32) NULL,
                    `link` varchar(255) NULL,
                    `type` int default 1,
                    `status` int default 1,
                    `ordered` int default 1,
                    `script` varchar(1024) NULL,
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