<?php

use Freewind\Core\Article;
use Freewind\Core\Avatar;
use Freewind\Core\FreewindHelper;
use Freewind\Core\FreewindUtils;
use Freewind\Core\Site;
use Freewind\Extend\IExtend;
use Freewind\Extend\LinkedExtend;
use Freewind\Extend\RightExtend;
use Typecho\Plugin;
use Typecho\Widget;
use Utils\Helper;

if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<footer id="app-footer">
    <?php if (Site::get(Site::NAME_LINKED_ALLOW) == Site::ENABLE): ?>
        友情链接:
        <?php
        $links = IExtend::search(LinkedExtend::TABLE_NAME, 1, Site::get(Site::NAME_LINKED_NUM));
        shuffle($links);
        ?>
        <?php foreach ($links as $link): ?>
            <a href="<?php echo $link['link'] ?>" target="_blank"><?php echo $link['name'] ?></a>
        <?php endforeach; ?>

    <?php endif; ?>
    <br>
    <?php echo Site::get(Site::NAME_SITE_FOOTER) ?: '© 2022-2023 <a href="/">' . $this->options->title . '</a>' ?>
</footer>
</div>
<div class="right-bar bg-white hide-md">
    <div class="right-tab">
        <ul class="pos-rlt bottom-shadow">
            <li data-select="hot-selector" class="active"><a href="javascript:void (0);"><i
                            class="fa fa-fire"></i></a></li>
            <li data-select="new-selector"><a href="javascript:void (0);"><i class="fa fa-comment"></i></a>
            </li>
            <span class="pos-abs"></span>
        </ul>
        <div id="hot-selector" class="right-item select-item bottom-shadow-no-opacity current">
            <h3>热门文章</h3>
            <div class="right-item-body">
                <ul class="item-blog-list">
                    <?php $this->widget('Freewind\Widget\HotWidght@hot')->to($hot); ?>
                    <?php while ($hot->next()): ?>
                        <li>
                            <a href="<?php $hot->permalink() ?>"><?php $hot->title(); ?></a>
                            <p><i class="fa fa-eye"><?php echo Article::views($hot) ?> </i><i
                                        class="fa fa-clock-o"><?php $hot->date('Y-m-d'); ?> </i></p>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>
        <div id="new-selector" class="right-item select-item bottom-shadow-no-opacity">
            <h3>最新评论</h3>
            <div class="right-item-body">
                <ul class="item-comment-list">
                    <?php $this->widget('Widget_Comments_Recent')->to($comments); ?>
                    <?php while ($comments->next()): ?>
                        <li class="pos-rlt">
                            <div class="pos-abs avatar shadow">
                                <img src="<?php echo Avatar::get($comments->mail) ?>" alt="">
                            </div>
                            <strong><?php $comments->author(false); ?></strong>
                            <p class="comm"><?php echo preg_replace("/<br>|<p>|<\/p>|<li>|<\/li>/", ' ', $comments->text) ?>
                        </li>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="right-item">
        <h3>日历</h3>
        <div class="right-item-body">
            <p class="calendar-title">
                <?php echo date('Y年m月') ?>
                <?php echo FreewindUtils::printCalendar() ?>
            </p>
        </div>
    </div>
    <div class="right-item">
        <h3>标签云</h3>
        <div class="right-item-body tag-cloud">
            <?php Widget::widget('Widget\Metas\Tag\Cloud')->to($tags); ?>
            <?php if ($tags->have()): ?>
                <?php $index = 0 ?>
                <?php while ($tags->next()):
                    if ($index++ >= 30) break;
                    ?>
                    <a href="<?php $tags->permalink(); ?>" title="<?php $tags->name(); ?>">
                        <?php $tags->name(); ?></a>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
    <?php if (Site::get(Site::NAME_LINKED_ALLOW) == Site::ENABLE): ?>
        <div class="right-item">
            <h3>友情链接</h3>
            <div class="right-item-body friend-random">
                <?php
                $links = IExtend::search(LinkedExtend::TABLE_NAME, 1, Site::get(Site::NAME_LINKED_NUM));
                shuffle($links);
                foreach ($links as $link):?>
                    <a href="<?php echo $link['link'] ?>" target="_blank"><?php echo $link['name'] ?></a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
    <?php if ($this->is('post')): ?>
        <div class="right-item">
            <h3>文章目录</h3>
            <div id="blog-tree" class="right-item-body"></div>
        </div>
    <?php elseif ($this->is('page')): ?>
        <div class="right-item">
            <div id="blog-tree" style="opacity: 0;height: 0;width: 0;" class="right-item-body"></div>
        </div>
    <?php endif; ?>
</div>
</div>
</div>
<div id="bg-cover" class="pos-fix">
    <div class="search-sm-form pos-rlt shadow">
        <button class="pos-abs cover-close">
            <svg t="1637150636999" class="icon" viewBox="0 0 1024 1024" version="1.1"
                 xmlns="http://www.w3.org/2000/svg"
                 p-id="13528" width="18" height="18">
                <path d="M753.365333 843.861333a64 64 0 0 0 90.496-90.496L602.496 512l241.365333-241.365333a64 64 0 0 0-90.496-90.496L512 421.504 270.634667 180.138667a64 64 0 1 0-90.496 90.496L421.504 512l-241.365333 241.365333a64 64 0 0 0 90.496 90.496L512 602.496l241.365333 241.365333z"
                      p-id="13529"></path>
            </svg>
        </button>
        <div class="input-item">
            <form method="post" id="search-form" action="">
                <input type="text" name="s" size="32" id="search-content" placeholder="请输入搜索关键字...">
                <button class="pos-abs">
                    <svg t="1637150525623" class="icon" viewBox="0 0 1024 1024" version="1.1"
                         xmlns="http://www.w3.org/2000/svg" p-id="12707" width="12" height="12">
                        <path d="M1016.08414 917.875656l-182.330654-180.63718a451.592951 451.592951 0 0 0 89.754099-271.520262 461.753793 461.753793 0 1 0-461.753792 457.802354 462.318284 462.318284 0 0 0 263.617385-81.851222L909.395305 1024z m-554.330347-143.945253a308.212189 308.212189 0 1 1 311.034645-308.212189 309.905663 309.905663 0 0 1-311.034645 308.212189z"
                              p-id="12708" fill="#ffffff"></path>
                    </svg>
                    搜索
                </button>
            </form>
        </div>
        <p class="keywords-list">
            <span>推荐关键字：</span>
            <?php Widget::widget('Widget_Metas_Tag_Cloud')->to($tags); ?>
            <?php if ($tags->have()): ?>
                <?php $index = 0 ?>
                <?php while ($tags->next()):
                    if ($index++ >= 10) break;
                    ?>
                    <a href="javascript:void (0)" data-key="<?php $tags->name(); ?>"><?php $tags->name(); ?></a>
                <?php endwhile; ?>
            <?php endif; ?>
        </p>
    </div>
</div>
</div>

<div id="bottom-btn-list">
    <a id="top-btn" href="javascript:void (0);"><i class="fa fa-arrow-up"></i></a>
    <?php if (Site::get(Site::NAME_MASTER_QQ_ALLOW) == Site::ENABLE): ?>
        <a class="qq-btn" data-qq="<?php echo Site::get(Site::NAME_MASTER_QQ); ?>"
           href="tencent://message/?uin=<?php echo Site::get(Site::NAME_MASTER_QQ); ?>&Site=&menu=yes"
           target="_blank"><i class="fa fa-qq"></i></a>
    <?php endif; ?>
</div>
<div id="right-tool-bar">
    <?php Plugin::factory('freewind')->rightToolBar($this); ?>
    <?php if (Site::get(Site::NAME_SITE_STYLE_ALLOW) == Site::ENABLE) : ?>
        <div id="right-color" class="right-tool-item" style="width: 300px;">
            <a class="right-btn" data-target="right-color" href="javascript:void(0)">
                <i style="font-size: 20px" class="fa fa-cog"></i></a>
            <div class="right-title">配色方案</div>
            <div class="right-content" style="border-radius: 0 0 10px 10px">
                <?php $colors = FreewindHelper::colorList() ?>
                <?php foreach ($colors as $color): ?>
                    <a href="javascript:void(0)" class="set-color-btn"
                       data-color="<?php echo $color['filename'] ?>">
                        <div style="border-left: 1px solid #EEEEEE;border-right: 1px solid #EEEEEE;background-color:#EEEEEE;">
                            <div style="background-color:<?php echo $color['top'] ?>;height: 8px;">
                                <div style="background-color:<?php echo $color['left'] ?>;width: 15px;height: 8px;"></div>
                            </div>
                            <div style="background-color:<?php echo $color['left'] ?>;width: 15px;height: 40px;"></div>
                        </div>
                        <?php echo $color['name'] ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
<script src="<?php Helper::options()->themeUrl('static/js/freewind.function.js') ?>"></script>
<script src="<?php Helper::options()->themeUrl('static/js/freewind.core.js') ?>"></script>

<?php if ($this->is('index')) : ?>
    <script>
        $(function () {
            let recommends = $("#recommend-box .recommend.item");
            if (recommends) {
                $(recommends[0]).addClass("active")
            }
        })
    </script>
<?php endif; ?>
<?php echo Site::get(Site::NAME_DEP_GLOBAL_JS) ?>
<?php $this->footer(); ?>
<script>
    $.fn.serializeJson = function () {
        let serializeObj = {};
        $(this.serializeArray()).each(function () {
            serializeObj[this.name] = this.value;
        });
        return serializeObj;
    };
    <?php if (Site::get(Site::NAME_RIGHT_ALLOW) == Site::ENABLE): ?>
    // 右键
    $('body').contextMenu({
        width: 110,
        itemHeight: 30,// 菜单项height
        bgColor: "#fff",// 背景颜色
        color: "#777",// 字体颜色
        fontSize: 12,// 字体大小
        hoverBgColor: "#99CC66",// hover背景颜色
        menu: [
            {// 菜单项
                text: "<i class='fa fa-home'></i>首页",
                callback: function () {
                    location.href = '<?php $this->options->siteUrl(); ?>'
                }
            },
            {
                text: "<i class='fa fa-copy'></i>复制",
                callback: function () {
                    let select = window.getSelection ? window.getSelection() : document.selection.createRange().text;
                    console.log(select)
                    if (select.anchorOffset === select.focusOffset) {
                        cocoMessage.warning("没还没选择文字哦...", 2000);
                    } else {
                        document.execCommand("Copy")
                    }
                }
            }, {
                text: "<i class='fa fa-arrow-right'></i>前进",
                callback: function () {
                    history.go(1)
                }
            }, {
                text: "<i class='fa fa-arrow-left'></i>后退",
                callback: function () {
                    history.go(-1)
                }
            }, {
                text: "<i class='fa fa-refresh'></i>刷新",
                callback: function () {
                    location.reload()
                }
            }
            <?php foreach (IExtend::lst(RightExtend::TABLE_NAME, RightExtend::CACHE_PREFIX, ['status' => RightExtend::STATUS_ENABLE], 'ordered') as $menu):?>
            , {
                text: "<i class='fa <?php echo $menu['icon'] ?>'></i><?php echo $menu['name'] ?>",
                callback: function () {
                    <?php if ($menu['type'] == RightExtend::TYPE_LINK): ?>
                    location.href = '<?php echo $menu['link']; ?>'
                    <?php else: ?>
                    <?php echo $menu['script']?>
                    <?php endif; ?>
                }
            }
            <?php endforeach; ?>
        ]
    })
    <?php endif;?>


    <?php if (Site::get(Site::NAME_SITE_COPY_ALLOW) == Site::ENABLE):?>
    //复制回调
    document.body.oncopy = function () {
        cocoMessage.warning("<?php echo Site::get(Site::NAME_SITE_COPY_TEXT) ?>", 2000);
    }
    <?php endif; ?>

    function reload() {
        window.freewind = new Freewind($("#is-page").data('page').trim() === 'page')
        if (window.freewind.page) {
            window.freewind.registerPage()
        }
        <?php Plugin::factory('freewind')->pjaxload(); ?>
        <?php echo Site::get(Site::NAME_DEP_PJAX_LOAD)?>
        window.freewind.topInit()
        window.freewind.registerHandler()
    }

    reload();
    $(document).pjax(
        'a[href^="<?php Helper::options()->siteUrl()?>"]:not(a[target="_blank"],a[no-pjax]), a[href^="?"]',
        {
            container: '#app-main',
            fragment: '#app-main',
            timeout: 8000
        }
    ).on('pjax:send', function () {
        NProgress.start();//加载动画效果开始
    }).on('pjax:complete', function () {
        reload();
        NProgress.done();//加载动画效果结束
    });
</script>
</body>
</html>