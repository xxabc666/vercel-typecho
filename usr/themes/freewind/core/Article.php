<?php
/**
 * Author: Mr丶冷文
 * Date: 2021-11-28 16:20
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description:
 */

namespace Freewind\Core;

use Typecho\Db;
use Typecho\Db\Exception;
use Typecho\Widget;

class Article
{
    /**
     * @throws Exception
     */
    public static function support($cid): array
    {
        $db = Db::get();
        $prefix = $db->getPrefix();
        if (!array_key_exists('support', $db->fetchRow($db->select()->from('table.contents')))) {
            $db->query('ALTER TABLE `' . $prefix . 'contents` ADD `support` INT(10) DEFAULT 0;');
            return [
                'icon' => ' fa-thumbs-o-up',
                'count' => 0,
                'text' => '点赞'
            ];
        }
        $row = $db->fetchRow($db->select('support')->from('table.contents')->where('cid = ?', $cid));
        $support = CookieHelper::get('extend_contents_support');
        if (empty($support)) {
            $support = array();
        } else {
            $support = explode(',', $support);
        }
        if (!in_array($cid, $support)) {
            return [
                'icon' => ' fa-thumbs-o-up',
                'count' => $row['support'],
                'text' => '点赞'
            ];
        } else {
            return [
                'icon' => 'fa-thumbs-up',
                'count' => $row['support'],
                'text' => '已赞'
            ];
        }
    }

    public static function shuo_pic_list($article)
    {
        if ($article->fields->postShuoPic) {
            $lines = explode("\n", $article->fields->postShuoPic);
            return array_filter($lines, function ($line) {
                return trim($line);
            });
        }
        return [];
    }

    public static function keywords($article)
    {
        if ($article->fields->postKeywords) return $article->fields->postKeywords;
        $tag = array_map(function ($tag) {
            return $tag['name'];
        }, $article->tags);
        return implode(',', $tag);
    }

    public static function summery($post, $strlen = 70)
    {
        if ($post->fields->postDesc) return $post->fields->postDesc;
        if ($post->fields->postType == 3) {
            return '发布了相册《' . $post->title . '》';
        } else {
            return mb_substr(preg_replace("/[{<](.|\n)+?[>}]/", '', $post->content), 0, $strlen) . "...";
        }
    }

    /**
     * @throws Exception
     */
    public static function meta_last_modify($mid)
    {
        $db = Db::get();
        $prefix = $db->getPrefix();
        $sql = "SELECT FROM_UNIXTIME(`modified`,'%Y-%m-%d') as time FROM `" . $prefix . "contents` WHERE `cid` IN (SELECT `cid` FROM `" . $prefix . "relationships` WHERE mid = " . $mid . ") ORDER BY time DESC LIMIT 1";
        $res = $db->fetchRow($db->query($sql));
        return $res ? $res['time'] : '暂无更新';
    }


    /**
     * @throws Exception
     */
    public static function views($archive)
    {
        $cid = $archive->cid;
        $db = Db::get();
        $prefix = $db->getPrefix();
        if (!array_key_exists('views', $db->fetchRow($db->select()->from('table.contents')))) {
            $db->query('ALTER TABLE `' . $prefix . 'contents` ADD `views` INT(10) DEFAULT 0;');
            return 0;
        }
        $row = $db->fetchRow($db->select('views')->from('table.contents')->where('cid = ?', $cid));
        if ($archive->is('single')) {
            $views = CookieHelper::get('extend_contents_views');
            if (empty($views)) {
                $views = array();
            } else {
                $views = explode(',', $views);
            }
            if (!in_array($cid, $views)) {
                $db->query($db->update('table.contents')->rows(array('views' => (int)$row['views'] + 1))->where('cid = ?', $cid));
                $views[] = $cid;
                $views = implode(',', $views);
                CookieHelper::set('extend_contents_views', $views); //记录查看cookie
            }
        }
        return $row['views'];
    }

    public static function enclosure($post, $fileType)
    {
        //"1" => "关闭",
        //"2" => "开启",
        //"3" => "开启回复可见",
        //"4" => "开启登录可见",
        //"5" => "开启登录回复可见",
        $user = Widget::widget('Widget_User');
        if ($user->group == 'administrator') {
            return true;
        }

        if ($fileType == 2) {
            return true;
        } else if ($fileType == 3) {
            $comments = CookieHelper::get('extend_contents_comments');
            if (!empty($comments)) {
                $comments = explode(',', $comments);
                if (in_array($post->cid, $comments)) {
                    return true;
                }
            }
        } else if ($fileType == 4) {
            if ($user->hasLogin()) {
                return true;
            }
        } else if ($fileType == 5) {
            $comments = CookieHelper::get('extend_contents_comments');
            if (empty($comments)) {
                $comments = [];
            } else {
                $comments = explode(',', $comments);
            }
            if ($user->hasLogin() && in_array($post->cid, $comments)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @throws Exception
     */
    public static function metas_count($type = 'category', $limit = 6): array
    {
        $db = Db::get();
        $select = $db->select("name,count")
            ->from('table.metas')
            ->where('type = ?', $type)
            ->where('parent = ?', 0)
            ->order('count', Db::SORT_DESC)
            ->limit($limit);


        $rows = $db->fetchAll($select);

        $category_rows = [];
        foreach ($rows as $row) {
            $category_rows[] = [
                'name' => $row['name'],
                'count' => (int)$row['count']
            ];
        }
        return $category_rows;
    }


    /**
     * @throws Exception
     */
    public static function article_count($dateArray): array
    {
        $db = Db::get();
        $select = $db->select("DATE_FORMAT(FROM_UNIXTIME(created),'%Y-%m') as time,count(1) as number")
            ->from('table.contents')
            ->where('type = ?', 'post')
            ->group('time')
            ->order('time', Db::SORT_DESC)
            ->limit(6);
        $rows = $db->fetchAll($select);

        $article_rows = [];
        foreach ($rows as $row) {
            $article_rows[$row['time']] = $row['number'];
        }
        $article_count = [];
        foreach ($dateArray as $date) {
            $article_count[] = [
                'time' => $date,
                'count' => $article_rows[$date] ? (int)$article_rows[$date] : 0
            ];
        }
        return $article_count;
    }

    public static function photoList($post): array
    {
        $lines = explode("\n", $post->text);
        $lines = array_filter($lines, function ($line) {
            return $line;
        });
        return array_map(function ($line) {
            $line = trim($line);
            $items = explode(";", $line);
            if (count($items) > 1) {
                return [
                    'caption' => $items[1],
                    'src' => $items[0]
                ];
            }
            return [
                "src" => trim($line)
            ];
        }, $lines);
    }

    public static function photosIndex($post, $len = 0): array
    {
        if ($post->password)
            return [
                'photos' => [
                    [
                        'src' => FreewindHelper::freeCdn('image/lazyload.jpg'),
                        'style'=> 'border:1px solid #eee'
                    ]
                ],
                'count' => ' ? '
            ];
        $lines = self::photoList($post);
        $count = count($lines);
        $len = $len < 0 ? count($lines) : $len;
        $len = count($lines) > $len ? $len : count($lines);
        return
            [
                'photos' => array_splice($lines, 0, $len),
                'count' => $count
            ];
    }

    /**
     * @throws Exception
     */
    public static function get_statistics(): array
    {
        $db = Db::get();
        $contents = $db->fetchRow($db->select('COUNT(1) as count')->from('table.contents')->where('type = ?', 'post'));
        $category = $db->fetchRow($db->select('COUNT(1) as count')->from('table.metas')->where('type = ?', 'category'));
        $comment = $db->fetchRow($db->select('COUNT(1) as count')->from('table.comments')->where('type = ?', 'comment'));
        return [
            'contents' => $contents['count'],
            'category' => $category['count'],
            'comment' => $comment['count'],
        ];
    }

    /**
     * @throws Exception
     */
    public static function getCommentByCid($cid, $len = 4): array
    {
        $db = Db::get();
        $select = $db->select('author,authorId,ownerId,mail,text,created')
            ->from('table.comments')
            ->where('cid = ?', $cid)
            ->order('created', Db::SORT_DESC)
            ->limit($len);
        return $db->fetchAll($select);
    }

    public static function parseFile($data)
    {
        $lines = explode("||", $data);
        $lines = array_filter($lines, function ($line) {
            return trim($line);
        });
        if (count($lines) == 0) {
            return false;
        }
        return count($lines) > 1 ? ['url' => $lines[0], 'pwd' => $lines[1]] : ['url' => $lines[0], 'pwd' => ''];

    }

}