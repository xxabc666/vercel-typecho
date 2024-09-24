<?php
/**
 * Author: Mr丶冷文
 * Date: 2022/10/19 17:27
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description:
 */

namespace Freewind\Extend;

use Typecho\Db;
use Typecho\Db\Exception;

class FileExtend extends IExtend
{

    const TABLE_NAME = "freewind_file";


    const FIELD_LIST = ['id', 'name', 'picture', 'ordered', 'passwd', 'status'];
    const CACHE_PREFIX = "freewind_freewind_file_";

    const STATUS_ENABLE = 1;
    const STATUS_DISABLE = 2;

    const PASSWD_NONE = 1;
    const PASSWD_SUPPORT = 2;

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
                    `picture` text NULL,
                    `ordered` int default 1,
                    `passwd` int default 1,
                    `status` int default 1,
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