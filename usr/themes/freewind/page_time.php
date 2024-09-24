<?php
/**
 * 时间轴
 *
 * @package custom
 */

use Freewind\Core\Article;
use Freewind\Core\FreewindUtils;
use Freewind\Widget\ArticleWidget;
use Typecho\Plugin;

if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('include/header.php');
?>
    <link rel="stylesheet" href="<?php $this->options->themeUrl('static/css/timeline.css'); ?>">
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
            <a href="<?php $this->options->siteUrl(); ?>"><i class="fa fa-home"></i> 首页</a> <i
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
        <div class="blog-list" style="padding: 0 20px">
            <?php
            $post = new ArticleWidget($this);
            try {
                $article = $post->articles();
            } catch (\Typecho\Db\Exception $e) {
                $article = [];
            }
            $year = 0;
            $mon = 0;
            $i = 0;
            $j = 0;
            $output = '';
            $color = FreewindUtils::linkColor();
            ?>
            <div class="bottom-shadow time-list-box"
                 style="padding: 10px 0;background-color:#fff;;margin-bottom: 20px;">
                <div class="time-list">
                    <?php while ($article->next()):
                        $year_tmp = date('Y', $article->created);
                        $mon_tmp = date('m', $article->created);
                        $y = $year;
                        $m = $mon;
                        if ($mon != $mon_tmp || $year != $year_tmp) {
                            $year = $year_tmp;
                            $mon = $mon_tmp;
                            if (!empty($output)) {
                                $color = FreewindUtils::linkColor();
                                $output .= '</ul></div>';
                            }
                            $output .= '<div class="time-item"><span class="time-line" style="background-color:' . $color . ';"></span><span class="time-circle" style="background-color:' . $color . ';">' . date('Y年m月', $article->created) . '</span><ul>';
                        }
                        $output .= '<li><div class="time-day" style="background-color:' . $color . ';"><span>' . date('d日', $article->created) . '</span></div>
<p class="time-author"><b>' . $article->author->screenName . '</b> 发布了<a href="' . $article->permalink . '">《' . $article->title . '》</a></p>
<p class="time-content" style="background-color:' . $color . ';"><i class="left-angle" style="border-color: ' . $color . ' transparent transparent transparent;"></i>' . Article::summery($article) . '</p></li>'
                        ?>

                    <?php endwhile;
                    $output .= '</ul></div>';
                    echo $output;
                    ?>
                </div>
            </div>

            <?php try {
                $post->pageNav();
            } catch (\Typecho\Db\Exception $e) {
            } ?>
        </div>
    </div>

    <script>
        $(function () {
            let timeLine = $('.time-item .time-line')
            $(timeLine[timeLine.length - 1]).css('top', 0)
        })
    </script>
<?php $this->need('include/footer.php'); ?>