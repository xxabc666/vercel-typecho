<?php

use Freewind\Core\Avatar;
use Freewind\Core\FreewindHelper;
use Freewind\Core\Router;
use Freewind\Extend\ArticleExtend;
use Freewind\Extend\ExtendHelper;
use Typecho\Plugin;
use Typecho\Widget\Helper\Form;
use Typecho\Widget\Helper\Form\Element\Hidden;
use Typecho\Widget\Helper\Layout;
use Widget\Archive;

if (!defined('__TYPECHO_ROOT_DIR__')) exit;
define('__FREEWIND_ROOT__', dirname(__FILE__));


include_once 'import.php';
//第三方
import('lib' . DIRECTORY_SEPARATOR . 'PHPMailer', ['OAuthTokenProvider']);
// 配置
import('config');
//freewind 核心包
import('core');
//路由包
import('router', ['IRouter', 'IExtendRouter']);
//组件
import('widget');
// freewind对后台的扩展
import('extend', ['IExtend'], ['ExtendHelper']);

/**
 * 初始化
 * @param Archive $archive
 */
function themeInit(Archive $archive)
{
    Router::register_params($archive);
    Plugin::factory('Widget\Archive')->beforeRender = array('Freewind\Core\Router', 'action');
}


function themeConfigHandle(array $settings, bool $isInit)
{
    if ($isInit) {
        Router::register();
        ExtendHelper::initExtend();
    }
}

/**
 * 后台外观设置
 * @param Form $form
 */
function themeConfig(Form $form)
{
    $form->addInput(new Hidden("freewind", null, "true", "", ""));
    include_once('include' . DIRECTORY_SEPARATOR . 'setting.php');
}


/**
 * 独立页面与文章设置
 * @param Layout $layout
 */
function themeFields(Layout $layout)
{
    ?>
    <link rel="stylesheet" href="<?php echo FreewindHelper::freeCdn('css/post.write.css') ?>">
    <?php
    $uri = $_SERVER['REQUEST_URI'];
    if (strstr($uri, "write-page")) {
        ?>
        <style>
            #custom-field textarea {
                width: 100%;
                height: 200px;
                resize: none;
            }


        </style>
        <?php
        $layout->addItem(new Form\Element\Textarea(
            "css",
            null,
            null,
            "自定义页面css",
            null
        ));

    } elseif (strstr($uri, "write-post")) {
        Plugin::factory('admin/write-post.php')->bottom = '\Freewind\Extend\ArticleExtend::script';
        ArticleExtend::fieldList($layout);
    }

}

/**
 * 评论设置
 * @param $comments
 * @param $options
 */
function threadedComments($comments, $options)
{
    ?>
    <li>
        <?php $comments->parentAuthor(); ?>

        <div class="pos-abs avatar">
            <img class="lazy" data-original="<?php echo Avatar::get($comments->mail) ?>"
                 alt="" src="">
        </div>
        <div class="commen-body">
            <p class="comm-title">
                <strong><?php echo $comments->author; ?></strong>
                <?php if ($comments->authorId === $comments->ownerId): ?>
                    <i class="identity admin">管理员</i>
                <?php elseif ($comments->authorId != 0): ?>
                    <i class="identity mumber">会员</i>
                <?php else: ?>
                    <i class="identity vistor">游客</i>
                <?php endif; ?>
                <span><?php $comments->date('Y-m-d H:i'); ?></span>
                <a class="replay-btn" href="javascript:void (0);"
                   data-parent="<?php echo $comments->coid ?>"
                   data-pname="<?php echo $comments->author; ?>"
                >回复</a>
            </p>
            <div class="comm-content bottom-shadow">
                <?php $comments->content(); ?>
            </div>
        </div>
    </li>
    <?php
    if ($comments->children) {
        $comments->threadedComments($options);
    } ?>
    <?php


}





