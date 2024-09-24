<?php
/**
 * 相册
 *
 * @package custom
 */

use Freewind\Core\Article;
use Freewind\Widget\ArticleWidget;
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
            <a href="<?php $this->options->siteUrl(); ?>"><i class="iconfont icon-home"></i> 首页</a> <i
                    class="split">/</i>
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
        <div class="blog-list" style="padding: 0 20px;">
            <?php $photoWidget = new ArticleWidget($this, 3); ?>
            <?php try {
                $picture = $photoWidget->articles();
            } catch (\Typecho\Db\Exception $e) {
                $picture = [];
            } ?>
            <?php while ($picture->next()): ?>
                <div class="blog-item bottom-shadow">
                    <div class="shuo-title pos-rlt">
                        <div class="shuo-avatar pos-abs">
                            <?php echo $picture->author->gravatar(50); ?>
                        </div>
                        <div class="shuo-info">
                            <p class="author-name"><?php echo $picture->author(); ?>
                                发布了相册《<?php echo $picture->title(); ?>
                                》</p>
                            <p class="time"><?php $picture->date('Y-m-d H:i:s'); ?></p>
                        </div>
                    </div>
                    <?php $photo_infos = Article::photosIndex($picture, 4) ?>
                    <?php $index = 0 ?>
                    <div class="photo-list">
                        <?php foreach ($photo_infos['photos'] as $photo): ?>
                            <?php if ($index++ < 3): ?>
                                <img class="lw-shuo-img lazy" src="<?php echo $photo['src'] ?>"
                                     style="margin-bottom: 5px;<?php echo $photo['style'] ?: '' ?>"
                                     data-caption="<?php echo $photo['caption'] ?>" alt="">
                            <?php else: ?>
                                <a class="img-more" href="<?php echo $picture->permalink ?>"
                                   style="margin-bottom: 5px;vertical-align: top">
                                    <div class="bg-more"><span>+<?php echo $photo_infos['count'] - 4 ?></span></div>
                                    <img class="lazy" src="<?php echo $photo['src'] ?>"
                                         data-caption="<?php echo $photo['caption'] ?>" alt="">
                                </a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <?php if ($this->password) : ?>
                            <p><i class="iconfont icon-mimasuolock"></i>该相册为私密相册，<a
                                        href="<?php echo $picture->permalink ?>">输入密码查看<i
                                            style="margin-left: 5px;" class="fa fa-angle-double-right"></i></a></p>
                        <?php else: ?>
                            <p>共<?php echo $photo_infos['count'] ?>张照片，<a href="<?php echo $picture->permalink ?>">查看全部<i
                                            style="margin-left: 5px;" class="fa fa-angle-double-right"></i></a></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        <?php try {
            $photoWidget->pageNav();
        } catch (\Typecho\Db\Exception $e) {
        } ?>
    </div>
<?php $this->need('include/footer.php'); ?>