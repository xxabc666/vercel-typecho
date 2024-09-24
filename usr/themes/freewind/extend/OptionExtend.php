<?php
/**
 * Author: Mr丶冷文
 * Date: 2022/10/17 14:13
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description:
 */

namespace Freewind\Extend;

use Freewind\Core\FreewindHelper;
use Freewind\Core\Site;
use Typecho\Db;
use Typecho\Db\Exception;

class OptionExtend extends IExtend
{

    /**
     * @throws Exception
     */
    static function init()
    {
        $db = Db::get();
        $tname = Site::TABLE_NAME;
        $sql = "CREATE TABLE `{$db->getPrefix()}$tname`  (
                    `id` int NOT NULL,
                    `name` varchar(255) not null ,
                    `value_str` varchar(255),
                    `value_int` int(11),
                    `value_text` text,
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
        $result = $db->fetchRow($db->select("count(1) as count")->from("information_schema.TABLES")->where("TABLE_NAME = ?", $db->getPrefix() . Site::TABLE_NAME));
        return $result['count'] > 0;
    }


}