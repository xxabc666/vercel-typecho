<?php use Freewind\Core\Article;
use Freewind\Core\Site;
use Typecho\Plugin;

if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('include/header.php'); ?>
<?php if ($this->password && !($_POST['passwd'] && $_POST['passwd'] == $this->password)) : ?>
    <?php $msg = '访问密码错误' ?>
    <div style="width: 100vw;height: 100vh;position:fixed;top: 0;left: 0;z-index: 9999999;background-color:#292E32;">
        <div style="text-align: center;padding: 5px;background-color: #FE445C;color: #fff;"><?php echo $msg ?></div>
        <div style="margin-top: 200px;text-align: center">
            <img style="border-radius: 50%;width: 128px;height: 128px;"
                 src="<?php echo Site::get(Site::NAME_MASTER_AVATAR) ?>"
                 alt="">
            <h1 style="color: #fff;font-size: 18px"><?php $this->title() ?></h1>
            <form action="" method="post">
                <label for="">请输入访问密码</label>
                <br>
                <input name="passwd" style="outline: none;padding-left: 20px;" type="password">
                <br>
                <input type="submit"
                       style="outline: none;background-color: #3BB349;border: none;margin-top: 5px;color: #fff;padding: 5px 10px;width: 100px;border-radius: 5px"
                       value="提交">
            </form>
        </div>
    </div>
<?php else: ?>
    <div class="bg-white main-header">
        <h1 class="no-marge"><?php $this->title() ?></h1>
        <p class="blog-info no-marge">
            <i class="fa fa-user"> <?php $this->author(); ?></i>
            <i class="fa fa-clock-o"> <?php $this->date('Y-m-d'); ?></i>
            <i class="fa fa-eye"> <?php try {
                    echo Article::views($this);
                } catch (\Typecho\Db\Exception $e) {
                    echo 0;
                } ?></i>
            <i class="fa fa-comment"> <?php $this->commentsNum('%d条评论'); ?></i>
            <i class="fa fa-folder"> <?php $this->category(''); ?></i>
            <i class="fa fa-tags"><?php $this->tags(""); ?></i>
        </p>
    </div>
    <div class="blog-content">
        <div class="crumbs bottom-shadow">
            <a href="<?php $this->options->siteUrl(); ?>"><i class="iconfont icon-home"></i> 首页</a> <i
                    class="split">/</i>
            <strong>正文</strong>
        </div>
        <div style="margin:10px 20px;">
            <?php Plugin::factory('freewind')->contentTop($this); ?>
        </div>
        <?php if ($this->fields->postType == 2): ?>
            <div id="shuo-content" class="bottom-shadow">
                <?php echo $this->content ?>
                <?php foreach (Article::shuo_pic_list($this) as $picture): ?>
                    <img class="lw-shuo-img lazy" data-original="<?php echo $picture ?>" alt="" src="">
                <?php endforeach; ?>
                <?php if ($this->fields->postShuoMusic): ?>
                    <meting-js
                            list-folded="true"
                            server="<?php echo $this->fields->postShuoMP ?>"
                            type="<?php echo $this->fields->postShuoMT ?>"
                            id="<?php echo $this->fields->postShuoMusic ?>">
                    </meting-js>
                <?php endif; ?>
                <?php if ($this->fields->postShuoBvid): ?>
                    <iframe class="fwbili"
                            src="//player.bilibili.com/player.html?bvid=<?php echo $this->fields->postShuoBvid ?>&page=<?php echo $this->fields->postShuoPage ?>"></iframe>
                <?php endif; ?>
            </div>
        <?php elseif ($this->fields->postType == 3): ?>
            <div id="shuo-content" class="bottom-shadow">
                <?php $photos = Article::photoList($this) ?>
                <?php foreach ($photos as $photo): ?>
                    <img class="lw-shuo-img lazy" data-original="<?php echo $photo['src'] ?>"
                         style="margin-bottom: 10px;margin-right: 0;"
                         data-caption="<?php echo $photo['caption'] ?>" alt="" src="">
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div id="write" class="bottom-shadow">
                <?php echo $this->content ?>
            </div>
            <?php $this->need('include/file.php'); ?>
            <div class="blog-copyright bottom-shadow">
                <div class="copy-right-item">
                    <svg t="1639819851355" class="icon" viewBox="0 0 1024 1024" version="1.1"
                         xmlns="http://www.w3.org/2000/svg" p-id="36651" width="48" height="48">
                        <path d="M512 512m-448 0a448 448 0 1 0 896 0 448 448 0 1 0-896 0Z" fill="#FB3A83"
                              p-id="36652"></path>
                        <path d="M676.5 701c-9 0-16.6-6.3-18.1-15-1.1-6-2.5-11.6-4.2-16.7l-0.1-0.2-0.1-0.2c-9.1-31.5-29.1-59-56.6-78-26.2 13.2-55.6 20.1-85.3 20.1-29.8 0-59.2-7-85.5-20.2-27.6 19-47.7 46.5-56.8 78.1-0.2 0.7-2.1 7.9-4.8 18.5-2 8.1-9.3 13.7-17.8 13.7h-10.7c-4.8 0-9.4-2-12.5-5.6-3.1-3.5-4.6-8.2-4-12.8 8.1-60.2 46.2-113.1 101.7-141.5l5.4-2.8 5.1 3.4c23.5 15.9 51.1 24.3 79.8 24.3 28.7 0 56.2-8.4 79.7-24.2l5.1-3.4 5.4 2.8c55.4 28.4 93.4 81.3 101.5 141.4 0.6 4.6-0.8 9.3-4 12.8s-7.7 5.6-12.5 5.6h-10.7zM512.2 526.3c-74.5 0-135-60-135-133.6S437.7 259 512.2 259s135 60 135 133.6c0 73.7-60.5 133.7-135 133.7z m0-222.4c-49.6 0-89.9 39.8-89.9 88.8s40.3 88.8 89.9 88.8c49.6 0 89.9-39.8 89.9-88.8s-40.3-88.8-89.9-88.8z"
                              fill="#FFFFFF" p-id="36653"></path>
                    </svg>
                    版权所属: <?php echo $this->author() ?>
                </div>
                <div class="copy-right-item">
                    <svg t="1639819895076" class="icon" viewBox="0 0 1024 1024" version="1.1"
                         xmlns="http://www.w3.org/2000/svg" p-id="40341" width="48" height="48">
                        <path d="M277.9 742s167.9-294.1 465.3-465.3C575.7 577.8 277.9 742 277.9 742z" fill="#FFFFFF"
                              p-id="40342"></path>
                        <path d="M743.1 742S575.2 447.9 277.8 276.7C445.3 577.8 743.1 742 743.1 742z" fill="#FFFFFF"
                              p-id="40343"></path>
                        <path d="M284.1 343m-49 0a49 49 0 1 0 98 0 49 49 0 1 0-98 0Z" fill="#FFFFFF"
                              p-id="40344"></path>
                        <path d="M284.1 515.5m-49 0a49 49 0 1 0 98 0 49 49 0 1 0-98 0Z" fill="#FFFFFF"
                              p-id="40345"></path>
                        <path d="M284.1 683.5m-49 0a49 49 0 1 0 98 0 49 49 0 1 0-98 0Z" fill="#FFFFFF"
                              p-id="40346"></path>
                        <path d="M808.4 341.6s-208.5-61.2-420 0c211.5 64.8 420 0 420 0zM808.4 516.6s-208.5-61.2-420 0c211.5 64.8 420 0 420 0zM808.4 684.6s-208.5-61.2-420 0c211.5 64.8 420 0 420 0z"
                              fill="#FFFFFF" p-id="40347"></path>
                        <path d="M512 512m-448 0a448 448 0 1 0 896 0 448 448 0 1 0-896 0Z" fill="#1881EA"
                              p-id="40348"></path>
                        <path d="M734.8 756.5s-221.3-60.5-445.7 0c224.5 64 445.7 0 445.7 0z" fill="#FFFFFF"
                              p-id="40349"></path>
                        <path d="M517.1 209.6C627 285.7 780.5 399.4 780.5 399.4S626.8 513.1 516.9 589.2c167.5-189.8 167.7-189.8 0.2-379.6z"
                              fill="#F2F2F2" p-id="40350"></path>
                        <path d="M444.2 702.7s-33.1-252.3 88.6-252.3c43.2 0 63.1-20.4 63.1-51 0-31.3-23.1-50.2-63.1-50.2-71.7 0-191.9 28.8-88.6 353.5z"
                              fill="#F2F2F2" p-id="40351"></path>
                    </svg>
                    本文链接: <a href="<?php $this->permalink() ?>"><?php $this->permalink() ?></a>
                </div>
                <div class="copy-right-item">
                    <svg t="1639819923661" class="icon" viewBox="0 0 1024 1024" version="1.1"
                         xmlns="http://www.w3.org/2000/svg" p-id="40936" width="48" height="48">
                        <path d="M512 96c229.76 0 416 186.24 416 416S741.76 928 512 928 96 741.76 96 512 282.24 96 512 96z m0 64C317.589333 160 160 317.589333 160 512S317.589333 864 512 864 864 706.410667 864 512 706.410667 160 512 160z m10.666667 170.666667c51.882667 0 100.522667 20.736 136.234666 56.704l4.586667 4.778666-46.933333 43.52a128 128 0 1 0-4.096 178.24l4.096-4.245333 46.933333 43.52A192 192 0 1 1 522.666667 330.666667z"
                              fill="#3AB549" p-id="40937"></path>
                    </svg>
                    协议授权: <a href="https://creativecommons.org/licenses/by-nc-sa/4.0/deed.zh">《署名-非商业性使用-相同方式共享
                        4.0 国际
                        (CC BY-NC-SA 4.0)》</a>
                </div>
            </div>
            <div class="neighbor">
                <?php $this->thePrev('%s', '<a class="fr" href="javascript:void(0)">没有了...</a>', ['title' => "下一篇", 'tagClass' => "fr"]); ?>
                <?php $this->theNext('%s', '<a class="fl" href="javascript:void(0)">没有了...</a>', ['title' => "上一篇", 'tagClass' => "fl"]); ?>
            </div>
        <?php endif; ?>
        <?php $this->need('include/comments.php'); ?>
    </div>
<?php endif; ?>
<?php $this->need('include/footer.php'); ?>