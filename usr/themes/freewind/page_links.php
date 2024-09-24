<?php
/**
 * 友情链接
 *
 * @package custom
 */

use Freewind\Core\Site;
use Freewind\Extend\LinkedExtend;
use Typecho\Plugin;

if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('include/header.php');
?>

<div class="bg-white main-header">
    <h1 class="no-marge">
        <?php if ($this->is('index')) : ?>
            <?php $this->options->title() ?>
        <?php else: ?>
            <?php $this->archiveTitle(array(
                'category' => _t('分类 %s 下的文章'),
                'search' => _t('包含关键字 %s 的文章'),
                'tag' => _t('标签 %s 下的文章'),
                'author' => _t('%s 发布的文章')
            ), '', ''); ?>
        <?php endif; ?>
    </h1>
    <p class="no-marge"><?php $this->options->description() ?></p>
</div>
<div class="blog-content">
    <div class="crumbs bottom-shadow">
        <a href="<?php $this->options->siteUrl(); ?>"><i class="iconfont icon-home"></i> 首页</a> <i class="split">/</i>
        <strong><?php $this->archiveTitle(array(
                'category' => _t('分类 %s 下的文章'),
                'search' => _t('包含关键字 %s 的文章'),
                'tag' => _t('标签 %s 下的文章'),
                'author' => _t('%s 发布的文章')
            ), '', ''); ?></strong>
    </div>
    <div style="margin:10px 20px;">
        <?php Plugin::factory('freewind')->contentTop($this); ?>
    </div>
    <div id="write" class="bottom-shadow">
        <?php echo $this->content ?>
    </div>
    <div class="bg-whitefff page-link bottom-shadow">
        <?php
        $links = LinkedExtend::lst(LinkedExtend::TABLE_NAME, LinkedExtend::CACHE_PREFIX, [], '');
        shuffle($links)
        ?>
        <?php if (count($links) > 0): ?>
            <div class="row" style="margin: 0;padding: 10px;">
                <?php foreach ($links as $link): ?>
                    <div class="col-md-4 col-sm-6 col-xs-12" style="padding: 5px;">
                        <div class="link-item bottom-shadow">
                            <div class="link-head">
                                <div class="link-bg-img"
                                     style="background-image:url(<?php echo $link['icon'] ?: $link['link'] . '/favicon.ico' ?>);"></div>
                            </div>
                            <div class="link-body pos-rlt">
                                <div class="link-img pos-abs" onclick="window.open('<?php echo $link['link'] ?>')">
                                    <img class="lazy"
                                         data-original="<?php echo $link['icon'] ?: $link['link'] . '/favicon.ico' ?>"
                                         alt="" src="">
                                </div>
                                <h3 onclick="window.open('<?php echo $link['link'] ?>')"><?php echo $link['name'] ?></h3>
                                <p><?php echo $link['desc'] ?: Site::get(Site::NAME_LINKED_DESC) ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div style="text-align: center">当前还没有添加友链哦</div>
        <?php endif; ?>
    </div>
    <?php $this->need('include/comments.php'); ?>
</div>

<?php $this->need('include/footer.php'); ?>

