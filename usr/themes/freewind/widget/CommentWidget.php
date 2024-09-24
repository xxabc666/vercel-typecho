<?php
/**
 * Author: Mr丶冷文
 * Date: 2022/10/15 10:55
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description: 评论组件
 */

namespace Freewind\Widget;

use Freewind\Core\HttpHelper;
use Freewind\Core\MailHelper;
use Freewind\Core\Result;
use Freewind\Core\CookieHelper;
use Freewind\Core\Site;
use Typecho\Cookie;
use Typecho\Db;
use Typecho\Db\Exception;
use Typecho\Validate;
use Typecho\Widget;
use Utils\Helper;
use Widget\Archive;

class CommentWidget
{
    /**
     * @throws Exception
     * @throws \PHPMailer\PHPMailer\Exception
     * @throws \Exception
     */
    function action(Archive $archive)
    {
        $options = Helper::options();
        $user = Widget::widget('Widget\User');
        $db = Db::get();
        if (!$archive->allow('comment')) {
            Result::error('评论功能已关闭');
        }
        if (!$user->pass('editor', true) && $archive->authorId != $user->uid &&
            $options->commentsPostIntervalEnable) {
            $last_comment = $db->fetchRow($db->select('created')->from('table.comments')
                ->where('cid = ?', $archive->cid)
                ->where('ip = ?', $archive->request->getIp())
                ->order('created', Db::SORT_DESC)
                ->limit(1));
            if ($last_comment && ($options->gmtTime - $last_comment['created'] > 0 &&
                    $options->gmtTime - $last_comment['created'] < $options->commentsPostInterval)) {
                Result::error('对不起, 您的发言过于频繁, 请稍侯再次发布');
            }
        }
        $comment = array(
            'cid' => $archive->cid,
            'created' => $options->gmtTime,
            'agent' => $archive->request->getAgent(),
            'ip' => $archive->request->getIp(),
            'ownerId' => $archive->author->uid,
            'type' => 'comment',
            'status' => !$archive->allow('edit') && $options->commentsRequireModeration ? 'waiting' : 'approved'
        );
        /** 判断父节点 */
        if ($parentId = $archive->request->filter('int')->get('parent')) {
            if ($options->commentsThreaded && ($parent = $db->fetchRow($db->select('coid', 'cid')->from('table.comments')
                    ->where('coid = ?', $parentId))) && $archive->cid == $parent['cid']) {
                $comment['parent'] = $parentId;
            } else {
                Result::error('父级评论不存在');
            }
        }

        $feedback = Widget::widget('Widget_Feedback');
        $validator = new Validate();

        $validator->addRule('author', 'required', _t('必须填写用户名'));
        $validator->addRule('author', 'xssCheck', _t('请不要在用户名中使用特殊字符'));
        $validator->addRule('author', 'maxLength', _t('用户名最多包含200个字符'), 200);

        if ($options->commentsRequireMail && !$user->hasLogin()) {
            $validator->addRule('mail', 'required', _t('必须填写电子邮箱地址'));
        }

        $validator->addRule('mail', 'email', _t('邮箱地址不合法'));
        $validator->addRule('mail', 'maxLength', _t('电子邮箱最多包含200个字符'), 200);

        if ($options->commentsRequireUrl && !$user->hasLogin()) {
            $validator->addRule('url', 'required', _t('必须填写个人主页'));
        }

        $validator->addRule('url', 'url', _t('个人主页地址格式错误'));
        $validator->addRule('url', 'maxLength', _t('个人主页地址最多包含200个字符'), 200);

        $validator->addRule('text', 'required', _t('必须填写评论内容'));

        $comment['text'] = $archive->request->text;
        /** 对一般匿名访问者,将用户数据保存一个月 */
        if (!$user->hasLogin()) {
            /** Anti-XSS */
            $comment['author'] = $archive->request->filter('trim')->author;
            $comment['mail'] = $archive->request->filter('trim')->mail;
            $comment['url'] = $archive->request->filter('trim')->url;

            /** 修正用户提交的url */
            if (!empty($comment['url'])) {
                $urlParams = parse_url($comment['url']);
                if (!isset($urlParams['scheme'])) {
                    $comment['url'] = 'http://' . $comment['url'];
                }
            }

            $expire = $options->gmtTime + $options->timezone + 30 * 24 * 3600;
            Cookie::set('__typecho_remember_author', $comment['author'], $expire);
            Cookie::set('__typecho_remember_mail', $comment['mail'], $expire);
            Cookie::set('__typecho_remember_url', $comment['url'], $expire);
        } else {
            $comment['author'] = $user->screenName;
            $comment['mail'] = $user->mail;
            $comment['url'] = $user->url;

            /** 记录登录用户的id */
            $comment['authorId'] = $user->uid;
        }

        /** 评论者之前须有评论通过了审核 */
        if (!$options->commentsRequireModeration && $options->commentsWhitelist) {
            if ($feedback->size($feedback->select()->where('author = ? AND mail = ? AND status = ?', $comment['author'], $comment['mail'], 'approved'))) {
                $comment['status'] = 'approved';
            } else {
                $comment['status'] = 'waiting';
            }
        }

        if ($error = $validator->run($comment)) {
            //返回第一条错误信息
            Result::validator_error($error);
        }

        //挂载点
        try {
            $feedback->pluginHandle()->comment($comment, $archive);
        } catch (Exception $e) {
            Result::error($e->getMessage());
        }

        /** 添加评论 */
        $comment_id = $feedback->insert($comment);
        if (!$comment_id) {
            Result::error('评论失败,请稍后再试');
        }

        $comments = CookieHelper::get('extend_contents_comments');
        if (empty($comments)) {
            $comments = array();
        } else {
            $comments = explode(',', $comments);
        }
        array_push($comments, $archive->cid);
        $comments = implode(',', $comments);
        CookieHelper::set('extend_contents_comments', $comments);

        if (Site::get(Site::NAME_MAIL_ALLOW) > Site::DISABLE) {
            $subject = '文章《' . $archive->title . '》收到了新的评论';
            $content = '<p>文章《' . $archive->title . '》最新评论内容:</p><p>'
                . $comment['text'] . '</p><p>'
                . '评论作者:' . $comment['author'] . '</p>'
                . '<p>时间:' . date('Y-m-d H:m:s', $comment['created']) . '</p>'
                . '<p><a href="' . $archive->permalink . '" target="_blank">文章详情</a></p>'
                . '<p>本邮件为<a href="' . Helper::options()->siteUrl . '">' . Helper::options()->title . '</a>自动发送，请勿直接回复</p>';
            MailHelper::asyncMail(Site::get(Site::NAME_MAIL_RECEIVE), $subject, $content);
            if ($parentId != 0 && Site::get(Site::NAME_MAIL_ALLOW) == Site::ENABLE_AND_OTHER) {
                $parent = $db->fetchRow($db->select('mail')
                    ->from('table.comments')
                    ->where('coid = ?', $parentId));
                if ($parent) {
                    $mailto = $parent['mail'];
                    $subject = '文章《' . $archive->title . '》的评论收到回复';
                    $content = '<p>您在文章《' . $archive->title . '》收到回复:</p><p>'
                        . $comment['text'] . '</p><p>'
                        . '回复者:' . $comment['author'] . '</p>'
                        . '<p>时间:' . date('Y-m-d H:m:s', $comment['created']) . '</p>'
                        . '<p><a href="' . $archive->permalink . '" target="_blank">文章详情</a></p>'
                        . '<p>本邮件为<a href="' . Helper::options()->siteUrl . '">' . Helper::options()->title . '</a>自动发送，请勿直接回复</p>';
                    MailHelper::asyncMail($mailto, $subject, $content);
                }
            }
        }

        Cookie::delete('__typecho_remember_text');
        $db->fetchRow($feedback->select()->where('coid = ?', $comment_id)
            ->limit(1), array($feedback, 'push'));
        Result::success('评论成功');
    }
}