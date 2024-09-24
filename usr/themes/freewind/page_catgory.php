<?php
/**
 * 分类大全
 *
 * @package custom
 */

use Freewind\Core\Article;
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
    <div class="page-bg">
        <ul class="category-list bottom-shadow">
            <?php $this->widget('Widget_Metas_Category_List')->to($category); ?>
            <?php while ($category->next()) : ?>
                <?php if ($category->levels === 0) :
                    ?>
                    <li>
                        <p class="category-head">
                            <i class="iconfont icon-folder"></i>
                            <a href="<?php $category->permalink(); ?>"><?php $category->name(); ?></a>
                            <span>(Total <?php $category->count(); ?> articles)</span>
                        </p>
                        <p class="category-update">
                            最后更新: <?php try {
                                echo Article::meta_last_modify($category->mid);
                            } catch (\Typecho\Db\Exception $e) {
                                echo '1970-01-01';
                            } ?>
                        </p>
                    </li>
                <?php endif ?>
            <?php endwhile; ?>
        </ul>
    </div>
</div>

<?php $this->need('include/footer.php'); ?>
