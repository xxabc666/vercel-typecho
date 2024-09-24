<?php use Freewind\Core\Article;
use Freewind\Core\FreewindHelper;
use Freewind\Core\FreewindUtils;
use Freewind\Core\Site;
use Freewind\Extend\IExtend;
use Freewind\Extend\NavigationExtend;
use Utils\Helper;

if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <!--支持媒体查询-->
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <!--IE 8浏览器的页面渲染方式-->
    <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
    <!--针对国内浏览器厂商使用极速内核-->
    <meta name="renderer" content="webkit">
    <title><?php $this->archiveTitle(array(
            'category' => _t('分类 %s 下的文章'),
            'search' => _t('包含关键字 %s 的文章'),
            'tag' => _t('标签 %s 下的文章'),
            'author' => _t('%s 发布的文章')
        ), '', ' - '); ?><?php $this->options->title(); ?></title>
    <?php if ($this->is('single')) : ?>
        <meta name="keywords" content="<?php echo Article::keywords($this) ?>"/>
        <meta name="description" content="<?php echo Article::summery($this, 200) ?>"/>
        <?php $this->header('keywords=&description='); ?>
    <?php else : ?>
        <?php $this->header(); ?>
    <?php endif; ?>
    <link rel="icon" href="<?php echo Site::get(Site::NAME_SITE_FAVICON) ?>" type="image/x-icon">
    <!-- jquery相关 -->
    <!-- jquery的时代已经过去了，freewind现在还在jquery的余晖下残存 -->
    <script src="<?php echo FreewindHelper::freeCdn('plugin/jquery/jquery-3.5.1.min.js') ?>"></script>
    <!-- jquery 页面pjax -->
    <script src="<?php echo FreewindHelper::freeCdn('plugin/jquery/jquery.pjax.min.js') ?>"></script>
    <!-- jquery 图片延迟加载 -->
    <script src="<?php echo FreewindHelper::freeCdn('plugin/jquery/jquery.lazyload.min.js') ?>"></script>
    <!-- font-awesome -->
    <link href="<?php echo FreewindHelper::freeCdn('plugin/font-awesome/css/font-awesome.min.css') ?>" rel="stylesheet">
    <!-- bootstrap -->
    <link href="<?php echo FreewindHelper::freeCdn('plugin/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <script src="<?php echo FreewindHelper::freeCdn('plugin/bootstrap/js/bootstrap.min.js') ?>"></script>
    <!-- tocbot 文章目录生成 -->
    <link href="<?php echo FreewindHelper::freeCdn('plugin/tocbot/tocbot.min.css') ?>" rel="stylesheet">
    <script src="<?php echo FreewindHelper::freeCdn('plugin/tocbot/tocbot.min.js') ?>"></script>
    <!-- 图片预览 -->
    <link rel="stylesheet" href="<?php echo FreewindHelper::freeCdn('plugin/previewjs/preview.min.css') ?>">
    <script src="<?php echo FreewindHelper::freeCdn('plugin/previewjs/preview.min.js') ?>"></script>
    <!-- 自定义右键 -->
    <link rel="stylesheet" href="<?php echo FreewindHelper::freeCdn('plugin/menu/contextMenu.min.css') ?>">
    <script src="<?php echo FreewindHelper::freeCdn('plugin/menu/jquery.contextMenu.min.js') ?>"></script>
    <!-- 代码高亮 -->
    <link href="<?php echo FreewindHelper::freeCdn('plugin/highlight/styles/' . Site::get(Site::NAME_SITE_CODE_STYLE)) ?>"
          rel="stylesheet">
    <script src="<?php echo FreewindHelper::freeCdn('plugin/highlight/highlight.min.js') ?>"></script>
    <!-- 轮播图 -->
    <link rel="stylesheet" href="<?php echo FreewindHelper::freeCdn('plugin/slider/swiper-bundle.min.css') ?>">
    <script src="<?php echo FreewindHelper::freeCdn('plugin/slider/swiper-bundle.min.js') ?>"></script>
    <!-- echart报表 -->
    <script src="<?php echo FreewindHelper::freeCdn('plugin/echarts/echarts.min.js') ?>"></script>
    <!-- 加载条 -->
    <link href="<?php echo FreewindHelper::freeCdn('plugin/nprogress/nprogress.min.css') ?>" rel="stylesheet"
          type="text/css">
    <script src="<?php echo FreewindHelper::freeCdn('plugin/nprogress/nprogress.min.js') ?>"></script>
    <!-- wangEditor编辑器 -->
    <script src="<?php echo FreewindHelper::freeCdn('plugin/wangEdit/wangEditor.min.js') ?>"></script>
    <!-- 一个好看的弹窗组件 -->
    <script src="<?php echo FreewindHelper::freeCdn('plugin/message/coco-message.min.js') ?>"></script>
    <!-- 内容到剪切板 -->
    <script src="<?php echo FreewindHelper::freeCdn('plugin/clipboard/clipboard.js') ?>"></script>
    <!-- 播放器 -->
    <link rel="stylesheet" href="<?php echo FreewindHelper::freeCdn('plugin/aplayer/APlayer.css') ?>">
    <script src="<?php echo FreewindHelper::freeCdn('plugin/aplayer/APlayer.js') ?>"></script>
    <script src="<?php echo FreewindHelper::freeCdn('plugin/aplayer/Meting.min.js') ?>"></script>
    <!-- freewind静态资源 -->
    <link rel="stylesheet" href="<?php Helper::options()->themeUrl('static/css/style.css') ?>">
    <link rel="stylesheet" href="<?php Helper::options()->themeUrl('static/css/right.css') ?>">
    <link rel="stylesheet"
          href="<?php Helper::options()->themeUrl('static/css/color/' . FreewindHelper::themeColor()) ?>">
    <style>
        <?php if (Site::get(Site::NAME_PIC_BG_IMG_ALLOW)==Site::ENABLE): ?>
        #swiper-slider,
        #app-header,
        #app-aside,
        #shuo-content,
        #statistic-pain,
        #whisper-pain,
        #login-pain,
        #app-footer,
        #write,
        #comment-box,
        .blog-item,
        .file-down,
        .category-list li,
        .file-not-view,
        .opacity-item,
        .blog-copyright,
        .neighbor,
        .comment-list li,
        .custom-html,
        .time-list-box,
        .page-link,
        .comment-box,
        .index-comments,
        .page-navigator,
        .crumbs,
        .right-bar,
        .main-header {
            opacity: <?php echo (doubleval(Site::get(Site::NAME_PIC_BG_OPCITY))/100)<0.3?0.3:(doubleval(Site::get(Site::NAME_PIC_BG_OPCITY))/100) ?>;
        }

        #body {
            background-image: url('<?php echo Site::get(Site::NAME_PIC_BG_IMG)?>');
            background-position: center center;
            background-repeat: <?php echo Site::get(Site::NAME_PIC_BG_REPEAT)?>;
            background-attachment: fixed;
        <?php if (Site::get(Site::NAME_PIC_BG_REPEAT) == Site::REPEAT_NO):?> background-size: cover;
        <?php endif; ?> background-color: #464646;
        }

        <?php else: ?>
        #body {
            background-color: <?php echo Site::get(Site::NAME_PIC_BG_COLOR) ?>;
        }

        <?php endif; ?>

        .swiper {
            --swiper-theme-color: #fff;
            --swiper-navigation-size: 20px; /* 设置按钮大小 */
        }

        @font-face {
            font-family: LogoFont;
            src: url("<?php echo FreewindHelper::freeCdn('font/bunch-bonarie.ttf')?>");
        }


    </style>
    <?php echo Site::get(Site::NAME_DEP_GLOBAL_CSS) ?>
    <?php $this->fields->css() ?>
</head>
<body id="body">
<div id="theme-url" data-theme="<?php Helper::options()->themeUrl('') ?>" style="display: none"></div>
<div id="cdn-url" data-cdn="<?php echo FreewindHelper::freeCdn() ?>" style="display: none"></div>
<div id="site-url" data-url="<?php $this->options->siteUrl(); ?>" style="display: none"></div>
<div id="app" class="container zoomImgBox">
    <header id="app-header" class="bg-top">
        <!--左边栏和顶栏交集区域-->
        <div class="sidebar-header bg-left pos-rlt">
            <a href="javascript:void(0);" id="show-left-bar" class="pos-abs hide-lg show-left"><i
                        class="fa fa-bars"></i></a>
            <a class="header-logo" href="<?php $this->options->siteUrl(); ?>">
                <span class="free-logo"><?php echo Site::get(Site::NAME_SITE_TEXT_LOGO) ?: 'freewind' ?></span>
            </a>
            <a href="javascript:void(0);" id="show-search-sm" class="pos-abs hide-lg show-search"><i
                        class="fa fa-search"></i></a>
        </div>
        <!--顶部导航-->
        <div class="top-bar pos-rlt">
            <!--时间统计-->
            <ul class="top-list fl hide-md pos-rlt">
                <li>
                    <a href="javascript:void (0);" id="app-statistic">
                        <i class="fa fa-bar-chart"></i>
                        <i class="fa fa-caret-down"></i>
                    </a>
                </li>
            </ul>
            <!--搜索框-->
            <div class="search-header">
                <div class="float-content">
                    <a id="show-search-btn" class="fl" href="javascript:void(0)">请输入关键字进行搜索...</a>
                    <button class="search-btn fl"><i class="fa fa-search"></i></button>
                </div>
            </div>
            <!--介绍登录-->
            <ul class="top-list fr hide-sm">
                <li>
                    <a id="whisper-btn" href="javascript:void (0);">
                        <i class="fa fa-bookmark-o"></i>
                    </a>
                </li>
                <li>
                    <?php if ($this->user->hasLogin()): ?>
                        <a id="login-bar-btn" href="javascript:void (0);">
                            <?php echo $this->user->screenName ?: $this->user->name ?>
                        </a>
                    <?php else: ?>
                        <a id="login-bar-btn" href="javascript:void (0);">
                            <i class="fa fa-sign-in"></i>
                        </a>
                    <?php endif; ?>
                </li>
            </ul>
        </div>
    </header>
    <div id="app-aside" class="bg-left">
        <div class="website-info">
            <div class="website-avatar pos-rlt">
                <div class="avatar-border">
                    <img src="<?php echo Site::get(Site::NAME_MASTER_AVATAR) ?>"
                         alt="">
                </div>
                <svg t="1615984869803" class="icon" viewBox="0 0 1024 1024" version="1.1"
                     xmlns="http://www.w3.org/2000/svg" p-id="27684" width="200" height="200">
                    <path d="M663.864 147.333H362.136c-34.421 0-67.432 13.998-91.773 38.916l-207.86 212.78c-19.338 19.798-19.338 51.893 0 71.691l404.102 413.673c25.623 26.229 67.165 26.229 92.788 0L963.495 470.72c19.338-19.798 19.338-51.893 0-71.691l-207.862-212.78c-24.337-24.918-57.348-38.916-91.769-38.916z"
                          fill="#FFA820" p-id="27685"></path>
                    <path d="M62.504 399.028c-19.338 19.798-19.338 51.893 0 71.691l8.667 8.873c2.085-38.628 9.355-75.876 21.127-111.063l-29.794 30.499zM963.496 399.028L861.313 294.427c34.885 61.489 54.811 132.578 54.811 208.323 0 5.627-0.113 11.228-0.33 16.802l47.704-48.833c19.337-19.798 19.337-51.893-0.002-71.691z"
                          fill="#FEAC33" p-id="27686"></path>
                    <path d="M160.006 299.218l-67.708 69.311c-11.772 35.187-19.041 72.435-21.127 111.063l41.377 42.357a368.9 368.9 0 0 1-2.075-39.073c0-66.966 18.046-129.715 49.533-183.658zM861.313 294.427L755.635 186.248c-24.339-24.917-57.35-38.916-91.771-38.916h-45.019C749 203.074 840.189 332.323 840.189 482.875c0 51.345-10.611 100.209-29.752 144.528l105.356-107.851c0.218-5.574 0.33-11.175 0.33-16.802 0-75.746-19.925-146.835-54.81-208.323zM427.792 844.659l38.814 39.733c25.623 26.229 67.165 26.229 92.788 0l68.278-69.895c-46.362 21.333-97.961 33.236-152.341 33.236a368.23 368.23 0 0 1-47.539-3.074z"
                          fill="#FEB133" p-id="27687"></path>
                    <path d="M618.845 147.333H362.136c-34.421 0-67.432 13.998-91.773 38.916l-110.358 112.97c-31.487 53.943-49.533 116.692-49.533 183.657 0 13.2 0.708 26.235 2.075 39.073l64.293 65.816c-16.982-38.122-26.436-80.336-26.436-124.763 0-169.51 137.415-306.925 306.925-306.925s306.925 137.415 306.925 306.925S626.839 769.926 457.33 769.926c-46.985 0-91.495-10.573-131.306-29.445L427.792 844.66a368.23 368.23 0 0 0 47.539 3.074c54.38 0 105.979-11.903 152.341-33.236l182.765-187.094c19.14-44.319 29.752-93.184 29.752-144.528 0-150.553-91.189-279.802-221.344-335.543z"
                          fill="#FEB633" p-id="27688"></path>
                    <path d="M764.254 463.001c0-169.51-137.415-306.925-306.925-306.925S150.405 293.491 150.405 463.001c0 44.427 9.453 86.642 26.436 124.763L326.024 740.48c39.81 18.872 84.321 29.445 131.306 29.445 169.509 0.001 306.924-137.414 306.924-306.924z m-573.917-19.875c0-137.514 111.477-248.991 248.991-248.991S688.32 305.612 688.32 443.126 576.842 692.118 439.328 692.118 190.337 580.641 190.337 443.126z"
                          fill="#FFBC34" p-id="27689"></path>
                    <path d="M688.32 443.126c0-137.514-111.477-248.991-248.991-248.991S190.337 305.612 190.337 443.126s111.477 248.991 248.991 248.991S688.32 580.641 688.32 443.126zM421.327 614.31c-105.518 0-191.058-85.54-191.058-191.058s85.54-191.058 191.058-191.058 191.058 85.54 191.058 191.058S526.846 614.31 421.327 614.31z"
                          fill="#FFC134" p-id="27690"></path>
                    <path d="M421.327 232.194c-105.518 0-191.058 85.54-191.058 191.058s85.54 191.058 191.058 191.058 191.058-85.54 191.058-191.058-85.539-191.058-191.058-191.058z m-18.001 304.308c-73.523 0-133.125-59.602-133.125-133.125s59.602-133.125 133.125-133.125 133.125 59.602 133.125 133.125-59.602 133.125-133.125 133.125z"
                          fill="#FFC634" p-id="27691"></path>
                    <path d="M403.326 403.378m-133.125 0a133.125 133.125 0 1 0 266.25 0 133.125 133.125 0 1 0-266.25 0Z"
                          fill="#FFCB34" p-id="27692"></path>
                    <path d="M663.864 165.333c14.702 0 29.048 2.922 42.639 8.686 13.62 5.775 25.818 14.122 36.256 24.808L950.62 411.606c12.532 12.83 12.532 33.706 0.001 46.535L546.518 871.814c-8.977 9.189-20.881 14.25-33.518 14.25-12.638 0-24.542-5.061-33.518-14.25L75.38 458.141c-12.532-12.83-12.532-33.706-0.001-46.535l207.86-212.78c10.439-10.686 22.637-19.033 36.257-24.808 13.591-5.763 27.937-8.686 42.639-8.686h301.729m0-17.999H362.136c-34.421 0-67.432 13.998-91.772 38.915l-207.86 212.78c-19.338 19.798-19.338 51.893 0 71.691l404.102 413.673c12.811 13.115 29.603 19.672 46.394 19.672s33.583-6.557 46.394-19.672l404.102-413.673c19.338-19.798 19.338-51.893 0-71.691l-207.862-212.78c-24.338-24.917-57.349-38.915-91.77-38.915z"
                          fill="#FFA820" p-id="27693"></path>
                    <path d="M585.506 299.37H440.494c-16.543 0-32.407 6.686-44.106 18.584L296.49 419.583c-9.294 9.454-9.294 24.783 0 34.237l194.213 197.576a31.154 31.154 0 0 0 44.593 0L729.509 453.82c9.294-9.454 9.294-24.783 0-34.237l-99.896-101.629c-11.698-11.898-27.564-18.584-44.107-18.584z"
                          fill="#FFE3B4" p-id="27694"></path>
                    <path d="M222.012 346.805a17.94 17.94 0 0 1-12.677-5.222c-7.057-7.001-7.102-18.398-0.101-25.456l87.419-88.112c7.002-7.057 18.398-7.102 25.456-0.1 7.057 7.001 7.102 18.398 0.101 25.456l-87.419 88.112a17.945 17.945 0 0 1-12.779 5.322zM172.371 396.84a17.94 17.94 0 0 1-12.677-5.222c-7.058-7.001-7.103-18.398-0.101-25.456l7.428-7.487c7.002-7.058 18.399-7.103 25.456-0.101 7.058 7.001 7.103 18.398 0.101 25.456l-7.428 7.487a17.946 17.946 0 0 1-12.779 5.323z"
                          fill="#FFFFFF" p-id="27695"></path>
                </svg>
            </div>
            <div class="website-title">
                <h1><?php echo Site::get(Site::NAME_MASTER_NICKNAME) ?></h1>
            </div>
            <div class="website-count">
                <table>
                    <thead>
                    <tr>
                        <th>文章</th>
                        <th>分类</th>
                        <th>评论</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $statistics = Article::get_statistics() ?>
                    <tr>
                        <td><?php echo $statistics['contents'] ?></td>
                        <td><?php echo $statistics['category'] ?></td>
                        <td><?php echo $statistics['comment'] ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="nav-list">
            <ul>
                <li class="nav-title">导航</li>
                <li class="fw-nav-item">
                    <a href="<?php $this->options->siteUrl(); ?>" <?php echo $this->is('index') ? ' class="nav-active"' : '' ?>><i
                                class="fa fa-home"></i> 首页</a>
                </li>
                <?php $navs = IExtend::lst(NavigationExtend::TABLE_NAME, NavigationExtend::CACHE_PREFIX, ['status' => NavigationExtend::STATUS_ENABLE, 'type' => NavigationExtend::TYPE_NAVIGATION], 'ordered') ?>
                <?php foreach ($navs as $nav): ?>
                    <li class="fw-nav-item">
                        <a <?php echo (!$this->is('post') && strpos($_SERVER['REQUEST_URI'], $nav['url']) > -1) ? ' class="nav-active"' : '' ?>
                                href="<?php echo strpos($nav['url'], 'http') == 0 ? $this->options->siteUrl() . (strpos('/', $nav['url']) == 0 ? substr($nav['url'], 1) : $nav['url']) : $nav['url'] ?>"
                            <?php echo $nav['target'] == NavigationExtend::TARGET_NEW ? ' target="_blank"' : '' ?>>
                            <i class="fa <?php echo $nav['icon'] ?>"></i>
                            <?php echo $nav['name'] ?>
                        </a>
                    </li>
                <?php endforeach; ?>
                <li class="nav-title">组成</li>
                <li>
                    <a href="javascript:void (0)" data-target="nav-1"><i
                                class="fa fa-folder"></i>分类</a>
                    <i class="fa fa-angle-right pos-abs nav-right"></i>
                    <ul id="nav-1" class="child-nav-list">
                        <?php $this->widget('Widget_Metas_Category_List')->to($category); ?>
                        <?php while ($category->next()) : ?>
                            <?php if ($category->levels === 0) : ?>
                                <li>
                                    <?php $children = $category->getAllChildren($category->mid); ?>
                                    <?php if (empty($children)) : ?>
                                        <a href="<?php $category->permalink(); ?>" title="<?php $category->name(); ?>">
                                            <?php $category->name(); ?>
                                        </a>
                                        <i class="badge-circular "><?php $category->count(); ?></i>
                                    <?php else: ?>
                                        <?php $ulid = FreewindUtils::getRandstr(16) ?>
                                        <a href="javascript:void (0)" data-target="child-<?php echo $ulid ?>">
                                            <?php $category->name(); ?>
                                        </a>
                                        <i class="nav-right fa pos-abs fa-angle-down"></i>
                                        <ul id="child-<?php echo $ulid ?>" class="child-nav-list">
                                            <?php foreach ($children as $mid) : ?>
                                                <?php $child = $category->getCategory($mid); ?>
                                                <li>
                                                    <a href="<?php echo $child['permalink'] ?>"><?php echo $child['name']; ?></a>
                                                    <i class="badge-circular "><?php echo $child['count']; ?></i></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </li>
                            <?php endif; ?>
                        <?php endwhile; ?>
                    </ul>
                </li>
                <li>
                    <a href="javascript:void (0)" data-target="nav-page"><i
                                class="fa fa-file-text-o"></i>页面</a>
                    <i class="fa fa-angle-right pos-abs nav-right"></i>
                    <ul id="nav-page" class="child-nav-list">
                        <?php $pages = IExtend::lst(NavigationExtend::TABLE_NAME, NavigationExtend::CACHE_PREFIX, ['status' => NavigationExtend::STATUS_ENABLE, 'type' => NavigationExtend::TYPE_PAGE], 'ordered') ?>
                        <?php foreach ($pages as $page): ?>
                            <li>
                                <a href="<?php echo strpos($page['url'], 'http') == 0 ? $this->options->siteUrl() . (strpos('/', $page['url']) == 0 ? substr($page['url'], 1) : $page['url']) : $page['url'] ?>"
                                    <?php echo $page['target'] == NavigationExtend::TARGET_NEW ? ' target="_blank"' : '' ?>>
                                    <?php echo $page['name'] ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="user-footer">
            <div class="link-me">
                <b>联系我</b>
                <a target="_blank" href="<?php echo Site::get(Site::NAME_MASTER_ICON_VALUE1) ?>">
                    <i class="fa <?php echo Site::get(Site::NAME_MASTER_ICON1) ?>"></i>
                </a>
                <a href="<?php echo Site::get(Site::NAME_MASTER_ICON_VALUE2) ?>" target="_blank">
                    <i class="fa <?php echo Site::get(Site::NAME_MASTER_ICON2) ?>"></i>
                </a>
                <a target="_blank" href="<?php echo Site::get(Site::NAME_MASTER_ICON_VALUE3) ?>">
                    <i class="fa <?php echo Site::get(Site::NAME_MASTER_ICON3) ?>"></i>
                </a>
                <a target="_blank" href="<?php echo Site::get(Site::NAME_MASTER_ICON_VALUE4) ?>">
                    <i class="fa <?php echo Site::get(Site::NAME_MASTER_ICON4) ?>"></i>
                </a>
            </div>
            <div class="copy-right">
                <div>
                    <?php if (Site::get(Site::NAME_SITE_INFO_ALLOW) == Site::ENABLE): ?>
                        the theme is by <a href="https://www.kevinlu98.cn" target="_blank">@冷文学习者</a>
                    <?php else: ?>
                        this blog is by <a
                                href="<?php echo $this->options->siteUrl; ?>">@<?php $this->options->title() ?></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div id="app-content">
        <div id="app-main">
            <div id="is-page" style="display: none"
                 data-page=" <?php echo $this->is('post') || $this->is('page') ? 'page' : 'index' ?>"></div>
            <div class="main-center pos-rlt">
                <div id="statistic-pain" class="pos-fix bg-white shadow row no-marge fade-toggle">
                    <div id="time-statistic" class="col-md-8"></div>
                    <div id="category" class="col-md-4"></div>
                    <div id="labels" class="col-md-4"></div>
                </div>
                <div id="whisper-pain" class="pos-fix bg-white shadow row no-marge fade-toggle">
                    <h3>站点介绍</h3>
                    <div class="whisper-content">
                        <?php echo Site::get(Site::NAME_SITE_NOTICE) ?: '此处是站点介绍' ?>
                    </div>
                </div>
                <div id="login-pain" class="pos-fix bg-white shadow row no-marge fade-toggle">
                    <?php if ($this->user->hasLogin()): ?>
                        <ul class="user-center">
                            <li><a target="_blank" href="<?php echo $this->options->siteUrl . 'admin/' ?>">个人中心</a>
                            </li>
                            <li><a href="<?php $this->options->logoutUrl(); ?>" no-pjax="">退出登录</a></li>
                        </ul>
                    <?php else: ?>
                        <div id="login-div">
                            <form id="login-form"
                                  action="<?php echo $this->options->siteUrl . 'login' ?>"
                                  method="post"
                                  name="login" role="form">
                                <div class="input-item pos-rlt">
                                    <input type="text" name="name" placeholder="请输入用户名">
                                    <i class="fa fa-user pos-abs"></i>
                                </div>
                                <div class="input-item pos-rlt">
                                    <input type="password" name="password" placeholder="请输入密码">
                                    <i class="fa fa-lock pos-abs"></i>
                                </div>
                                <div class="login-btn-list">
                                    <button class="fr right-btn" type="submit">登录</button>
                                    <button id="to-register" class="fl" type="button">注册</button>
                                </div>
                            </form>
                        </div>
                        <div id="register-div" style="display: none">
                            <form id="register-form"
                                  action="<?php echo $this->options->siteUrl . 'register' ?>"
                                  method="post"
                                  name="register" role="form">
                                <?php if ($this->options->allowRegister): ?>
                                    <div class="input-item pos-rlt">
                                        <input type="text" name="name" placeholder="请输入用户名">
                                        <i class="fa fa-user pos-abs"></i>
                                    </div>
                                    <div class="input-item pos-rlt">
                                        <input type="text" name="screenName" placeholder="请输入昵称">
                                        <i class="fa fa-id-card pos-abs"></i>
                                    </div>
                                    <div class="input-item pos-rlt">
                                        <input type="text" name="mail" placeholder="请输入邮箱">
                                        <i class="fa fa-envelope pos-abs"></i>
                                    </div>
                                    <div class="input-item pos-rlt">
                                        <input type="password" id="password" name="password" placeholder="请输入密码">
                                        <i class="fa fa-lock pos-abs"></i>
                                    </div>
                                    <div class="input-item pos-rlt">
                                        <input type="password" name="confirm" placeholder="确认密码">
                                        <i class="fa fa-lock pos-abs"></i>
                                    </div>
                                    <div class="input-item pos-rlt">
                                        <input type="text" name="imgcode" placeholder="验证码">
                                        <i class="fa fa-code pos-abs"></i>
                                        <img class="code pos-abs" id="code-img"
                                             src="<?php echo $this->options->siteUrl . 'verify/code'; ?>"
                                             alt="">
                                    </div>
                                <?php else: ?>
                                    <h3 style="font-weight: 100;font-size: 18px;text-align: center">
                                        该站点关闭了注册功能</h3>
                                <?php endif; ?>
                                <div class="login-btn-list">
                                    <button class="fr right-btn" type="submit">注册</button>
                                    <button id="return-login" type="button" class="fl">返回登录</button>
                                </div>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>