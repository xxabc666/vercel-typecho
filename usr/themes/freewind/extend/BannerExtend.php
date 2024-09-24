<?php
/**
 * Author: Mr丶冷文
 * Date: 2022/10/18 16:59
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description:
 */

namespace Freewind\Extend;

use Typecho\Db;
use Typecho\Db\Exception;

class BannerExtend extends IExtend
{

    const TABLE_NAME = "freewind_banner";

    const STATUS_ENABLE = 1;
    const STATUS_DISABLE = 2;

    const FIELD_LIST = ['id', 'title', 'desc', 'url', 'cover', 'status', 'ordered'];
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
                    `title` varchar(255) not null ,
                    `desc` varchar(255)  null ,
                    `url` varchar(255) NULL,
                    `cover` varchar(255) not null,
                    `status` int(11) not null default  1,
                    `ordered` int(11) not null default  1,
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