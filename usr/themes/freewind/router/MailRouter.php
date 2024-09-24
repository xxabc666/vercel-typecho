<?php

namespace Freewind\Router;

use Freewind\Core\MailHelper;
use Freewind\Core\Result;
use Freewind\Core\Site;
use PHPMailer\PHPMailer\Exception;
use Typecho\Widget\Request;
use Utils\Helper;


/**
 * Author: Mr丶冷文
 * Date: 2021-11-25 15:33
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description:
 */
class MailRouter implements IRouter
{

    /**
     * @throws Exception
     */
    function action(Request $request)
    {
        $mailto = Site::get(Site::NAME_MAIL_RECEIVE);
        $subject = '测试邮件';
        $content = '这是由' . Helper::options()->title . '发送的一封测试邮件';
        $result = MailHelper::sendMail($mailto, $subject, $content);
        Result::returnMsg($result);
    }

    function sendMail(Request $request)
    {
        if ($request->isPost()) {
            if ($request->get('code') != Site::get(Site::NAME_MAIL_AUTHOR_CODE)) {
                Result::error('邮箱校验码错误');
            }
            $to = $request->get('to');
            $subject = $request->get('subject');
            $content = $request->get('content');
            if (!$to) {
                Result::error('收件人不能为空');
            }
            if (!$subject) {
                Result::error('标题不能为空');
            }
            if (!$content) {
                Result::error('内容不能为空');
            }
            try {
                $result = MailHelper::sendMail($to, $subject, $content);
                Result::returnMsg($result);
            } catch (Exception $e) {
                Result::error('发送失败', $e);
            }

        } else {
            Result::error('不被允许的请求方式');
        }
    }
}