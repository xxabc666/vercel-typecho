<?php
/**
 * Author: Mr丶冷文
 * Date: 2022/10/18 00:21
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description:
 */

namespace Freewind\Core;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Utils\Helper;

class MailHelper
{
    /**
     * @throws Exception
     */
    public static function sendMail($to, $title, $content): array
    {
        $mail = new PHPMailer;
        $mail->isSMTP();
        if (Site::get(Site::NAME_MAIL_SSL) == Site::ENABLE) {
            $mail->SMTPSecure = 'ssl';
        }
        $mail->CharSet = PHPMailer::CHARSET_UTF8;
        $mail->Timeout = 20;
        $mail->SMTPAuth = true;
        $mail->isHTML(true);
        $mail->Host = Site::get(Site::NAME_MAIL_SERVER);
        $mail->Port = Site::get(Site::NAME_MAIL_PORT);
        $mail->Username = Site::get(Site::NAME_MAIL_USERNAME);
        $mail->Password = Site::get(Site::NAME_MAIL_PASSWORD);
        $mail->setFrom(Site::get(Site::NAME_MAIL_USERNAME), Helper::options()->title);
        $mail->addAddress($to);

        $mail->Subject = $title;
        $mail->Body = $content;
        if ($mail->send()) {
            return [
                'success' => true,
                'msg' => '发送成功'
            ];
        } else {
            return [
                'success' => false,
                'msg' => $mail->ErrorInfo
            ];
        }
    }


    /**
     * 异步邮件
     * @param $to
     * @param $subject
     * @param $content
     * @return void
     */
    public static function asyncMail($to, $subject, $content)
    {
        try {
            HttpHelper::sendHttp(
                Helper::options()->siteUrl . 'mail/send',
                [
                    'code' => Site::get(Site::NAME_MAIL_AUTHOR_CODE),
                    'to' => $to,
                    'subject' => $subject,
                    'content' => $content
                ],
                'POST',
                ['Content-Type' => 'multipart/form-data'],
                1);
        } catch (\Exception $e) {
        }
    }
}