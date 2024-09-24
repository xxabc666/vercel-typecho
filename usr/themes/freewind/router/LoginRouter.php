<?php
/**
 * Author: Mr丶冷文
 * Date: 2022/10/14 11:38
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description:
 */

namespace Freewind\Router;

use Freewind\Core\Result;
use Typecho\Cookie;
use Typecho\Validate;
use Typecho\Widget;
use Typecho\Widget\Request;
use Utils\Helper;

class LoginRouter implements IRouter
{

    function action(Request $request)
    {
        if (!$request->isPost()) {
            Result::error('不被允许的请求方式');
        }
        $login = Widget::widget('Widget_Login');
        $options = Helper::options();
        $user = Widget::widget('Widget_User');
        if ($user->hasLogin()) {
            Result::error('已经有用户登录啦');
        }
        $validator = new Validate();
        $validator->addRule('name', 'required', _t('请输入用户名'));
        $validator->addRule('password', 'required', _t('请输入密码'));
        $login_info = [
            'name' => $request->get('name'),
            'password' => $request->get('password'),
        ];
        if ($error = $validator->run($login_info)) {
            //返回第一条错误信息
            Result::validator_error($error);
        }
        $valid = $user->login($login_info['name'], $login_info['password'], false,
            1 == $request->remember ? $options->time + $options->timezone + 30 * 24 * 3600 : 0);
        if (!$valid) {
            /** 防止穷举,休眠3秒 */
            sleep(3);
            $login->pluginHandle()->loginFail($user, $login_info['name'],
                $login_info['password'], 1 == $request->remember);
            Cookie::set('__typecho_remember_name', $login_info['name']);
            Result::error(_t('用户名或密码无效'));
        }
        $login->pluginHandle()->loginSucceed($user, $login_info['name'],
            $login_info['password'], 1 == $request->remember);
        Result::success(_t(' 登录成功'));
    }
}