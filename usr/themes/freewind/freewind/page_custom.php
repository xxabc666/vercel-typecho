<?php
/**
 * 自定义页面
 *
 * @package custom
 */

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
        <a href="<?php $this->options->siteUrl(); ?>"><i class="fa fa-home"></i> 首页</a> <i class="split">/</i>
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
        <?php echo $this->content?>
    </div>
    <?php $this->need('include/comments.php'); ?>
</div>
<?php $this->need('include/footer.php'); ?>
