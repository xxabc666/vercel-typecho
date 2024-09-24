<?php
/**
 * Author: Mr丶冷文
 * Date: 2022/10/17 16:16
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description:
 */

namespace Freewind\Core;


use Typecho\Db;
use Typecho\Db\Exception;

class Site
{
    const TABLE_NAME = "freewind_option";


    const CACHE_PREFIX = "cache:freewind_site_";


    const ENABLE = 2;
    const DISABLE = 1;
    const ENABLE_AND_OTHER = 3;

    const TYPE_INT = 'int';
    const TYPE_STR = 'str';
    const TYPE_TEXT = 'text';

    const NAME_SITE_INFO_ALLOW = "site_info_allow";
    const NAME_SITE_TEXT_LOGO = "site_text_logo";
    const NAME_SITE_COPY_ALLOW = "site_copy_allow";
    const NAME_SITE_COPY_TEXT = "site_copy_text";
    const NAME_SITE_STYLE = "site_style";
    const NAME_SITE_STYLE_ALLOW = "site_style_allow";
    const NAME_SITE_NOTICE = "site_notice";
    const NAME_SITE_FOOTER = "site_footer";
    const NAME_SITE_FAVICON = "site_favicon";
    const NAME_SITE_CODE_STYLE = "site_code_style";

    const NAME_PIC_BG_COLOR = "pic_bg_color";
    const NAME_PIC_BG_IMG_ALLOW = "pic_bg_img_allow";
    const NAME_PIC_BG_IMG = "pic_bg_img";
    const NAME_PIC_BG_REPEAT = "pic_bg_repeat";
    const NAME_PIC_BG_OPCITY = "pic_bg_opcity";


    const NAME_MASTER_AVATAR = "master_avatar";
    const NAME_MASTER_NICKNAME = "master_nickname";
    const NAME_MASTER_QQ = "master_qq";
    const NAME_MASTER_QQ_ALLOW = "master_qq_allow";
    const NAME_MASTER_ICON1 = "master_icon1";
    const NAME_MASTER_ICON_VALUE1 = "master_icon_value1";
    const NAME_MASTER_ICON2 = "master_icon2";
    const NAME_MASTER_ICON_VALUE2 = "master_icon_value2";
    const NAME_MASTER_ICON3 = "master_icon3";
    const NAME_MASTER_ICON_VALUE3 = "master_icon_value3";
    const NAME_MASTER_ICON4 = "master_icon4";
    const NAME_MASTER_ICON_VALUE4 = "master_icon_value4";

    const NAME_MAIL_ALLOW = "mail_allow";
    const NAME_MAIL_SERVER = "mail_server";
    const NAME_MAIL_PORT = "mail_port";
    const NAME_MAIL_USERNAME = "mail_username";
    const NAME_MAIL_PASSWORD = "mail_password";
    const NAME_MAIL_RECEIVE = "mail_receive";
    const NAME_MAIL_SSL = "mail_ssl";
    const NAME_MAIL_AUTHOR_CODE = "mail_author_code";


    const NAME_LINKED_NUM = "linked_num";
    const NAME_LINKED_DESC = "linked_desc";
    const NAME_LINKED_ALLOW = "linked_allow";


    const NAME_RIGHT_ALLOW = "right_allow";

    const NAME_DEP_STATIC_ENABLE = "dep_static_enable";
    const NAME_DEP_STATIC_PATH = "dep_static_path";
    const NAME_DEP_GLOBAL_CSS = "dep_global_css";
    const NAME_DEP_GLOBAL_JS = "dep_global_js";
    const NAME_DEP_PJAX_LOAD = "dep_pjax_load";

    const REPEAT_NO = "no-repeat";
    const REPEAT_REPEAT = "repeat";
    const REPEAT_REPEAT_X = "repeat-x";
    const REPEAT_REPEAT_Y = "repeat-y";

    const CODE_STYLE_LIST = [
        'Xcode风格' => 'xcode.min.css',
        'VS2015' => 'vs2015.min.css',
        'VS风格' => 'vs.min.css',
        'Stackoverflow-亮' => 'stackoverflow-light.min.css',
        'Stackoverflow-暗' => 'stackoverflow-dark.min.css',
        'QTCreater-亮' => 'qtcreator-light.min.css',
        'QTCreater-暗' => 'qtcreator-dark.min.css',
        'Panda-亮' => 'panda-syntax-light.min.css',
        'Panda-暗' => 'panda-syntax-dark.min.css',
        'Idea风格' => 'idea.min.css',
        'Intellij风格' => 'intellij-light.min.css',
        'GoogleCode' => 'googlecode.min.css',
        'Github-亮' => 'github.min.css',
        'Github-暗' => 'github-dark-dimmed.min.css',
        'Docco风格' => 'docco.min.css',
        'Atom-亮' => 'atom-one-light.min.css',
        'Atom-暗' => 'atom-one-dark.min.css',
        'Arduino风格' => 'arduino-light.min.css',
        'AndroidStudio风格' => 'androidstudio.min.css',
    ];


    //https://cdn.jsdelivr.net/npm/typecho_joe_theme@4.3.5/assets/img/lazyload.jpg

    const SITE = [
        // 站点设置
        self::NAME_SITE_INFO_ALLOW => [1, self::ENABLE, self::TYPE_INT],
        self::NAME_SITE_TEXT_LOGO => [2, "Freewind"],
        self::NAME_SITE_COPY_ALLOW => [3, self::DISABLE, self::TYPE_INT],
        self::NAME_SITE_COPY_TEXT => [4, "复制成功，若要转载请务必说明出处并保留原文链接"],
        self::NAME_SITE_STYLE => [5, "default.css"],
        self::NAME_SITE_STYLE_ALLOW => [6, self::DISABLE, self::TYPE_INT],
        self::NAME_SITE_NOTICE => [7, "这里是站点介绍...", self::TYPE_TEXT],
        self::NAME_SITE_FOOTER => [8, "", self::TYPE_TEXT],
        self::NAME_SITE_FAVICON => [14, "/favicon.ico"],
        self::NAME_SITE_CODE_STYLE => [43, self::CODE_STYLE_LIST['Xcode风格']],
        //背景设置
        self::NAME_PIC_BG_COLOR => [9, "#EEEEEF"],
        self::NAME_PIC_BG_IMG_ALLOW => [10, self::DISABLE, self::TYPE_INT],
        self::NAME_PIC_BG_IMG => [11, ""],
        self::NAME_PIC_BG_REPEAT => [12, self::REPEAT_NO],
        self::NAME_PIC_BG_OPCITY => [13, 100, self::TYPE_INT],
        //站长信息
        self::NAME_MASTER_AVATAR => [15, "https://imagebed-1252410096.cos.ap-nanjing.myqcloud.com/20210323/afd125bc8747404f9847b7b014fa0740.jpg"],
        self::NAME_MASTER_NICKNAME => [16, "Freewind主题"],
        self::NAME_MASTER_ICON1 => [17, "fa-qq"],
        self::NAME_MASTER_ICON2 => [18, "fa-weibo"],
        self::NAME_MASTER_ICON3 => [19, "fa-envelope"],
        self::NAME_MASTER_ICON4 => [20, "fa-github"],
        self::NAME_MASTER_ICON_VALUE1 => [21, ""],
        self::NAME_MASTER_ICON_VALUE2 => [22, ""],
        self::NAME_MASTER_ICON_VALUE3 => [23, ""],
        self::NAME_MASTER_ICON_VALUE4 => [24, ""],
        self::NAME_MASTER_QQ => [25, ""],
        self::NAME_MASTER_QQ_ALLOW => [26, self::DISABLE, self::TYPE_INT],
        // 评论回复
        self::NAME_MAIL_ALLOW => [27, self::DISABLE, self::TYPE_INT],
        self::NAME_MAIL_SERVER => [28, ""],
        self::NAME_MAIL_USERNAME => [29, ""],
        self::NAME_MAIL_PASSWORD => [30, ""],
        self::NAME_MAIL_RECEIVE => [31, ""],
        self::NAME_MAIL_PORT => [32, "", self::TYPE_INT],
        self::NAME_MAIL_SSL => [33, self::DISABLE, self::TYPE_INT],
        self::NAME_MAIL_AUTHOR_CODE => [44, ''],

        //友情链接
        self::NAME_LINKED_NUM => [34, 4, self::TYPE_INT],
        self::NAME_LINKED_DESC => [35, '这个站长很懒，什么也没留下'],
        self::NAME_LINKED_ALLOW => [36, self::ENABLE, self::TYPE_INT],

        //右键
        self::NAME_RIGHT_ALLOW => [37, self::DISABLE, self::TYPE_INT],

        //开发者设置
        self::NAME_DEP_STATIC_ENABLE => [38, self::DISABLE, self::TYPE_INT],
        self::NAME_DEP_STATIC_PATH => [39, ''],
        self::NAME_DEP_GLOBAL_CSS => [40, '', self::TYPE_TEXT],
        self::NAME_DEP_GLOBAL_JS => [41, '', self::TYPE_TEXT],
        self::NAME_DEP_PJAX_LOAD => [42, '', self::TYPE_TEXT]
    ];

    static function sites($names = []): array
    {
        $result = [];
        foreach ($names as $name) {
            if ($name)
                $result[$name] = self::get($name);
        }
        return $result;
    }

    static function get(string $name): string
    {
        $cValue = Cache::getCache(self::CACHE_PREFIX . $name);
        if ($cValue) {
            return $cValue;
        }
        $option = self::SITE[$name];
        if (!$option) {
            return "";
        }
        try {
            $db = Db::get();
            $result = $db->fetchRow($db->select()->from($db->getPrefix() . self::TABLE_NAME)->where('id = ?', $option[0]));
            if ($result) {
                $value = $result[self::fieldType($option)];
                Cache::putCache(self::CACHE_PREFIX . $name, $value);
                return $value;
            }
            return $option[1];
        } catch (Db\Exception $e) {
            echo $e;
            return $option[1];
        }
    }

    private static function exist($id): int
    {
        try {
            $db = Db::get();
            $result = $db->fetchRow($db->select('COUNT(1) as count')->from($db->getPrefix() . self::TABLE_NAME)->where('id = ?', $id));
            return $result['count'];
        } catch (Db\Exception $e) {
            return -1;
        }
    }

    private static function fieldType($option): string
    {
        $field = 'value_str';
        if ($option[2]) {
            if ($option[2] == self::TYPE_INT) {
                $field = 'value_int';
            } elseif ($option[2] == self::TYPE_TEXT) {
                $field = "value_text";
            }
        }
        return $field;
    }

    /**
     * @throws Exception
     */
    public static function save($name, $value)
    {
        $option = self::SITE[$name];
        if (!$option) {
            return;
        }
        $field = self::fieldType($option);
        $db = Db::get();
        if (self::exist($option[0]) > 0) {
            $db->query($db->update($db->getPrefix() . self::TABLE_NAME)->rows([$field => $value])->where('id=?', $option[0]));
        } else {
            $db->query($db->insert($db->getPrefix() . self::TABLE_NAME)->rows(['id' => $option[0], 'name' => $name, $field => $value]));
        }
        Cache::putCache(self::CACHE_PREFIX . $name, $value);
    }
}