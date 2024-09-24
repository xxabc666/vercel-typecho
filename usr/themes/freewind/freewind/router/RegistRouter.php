<?php
/**
 * Author: Mr丶冷文
 * Date: 2022/10/14 12:03
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description:
 */

namespace Freewind\Router;

use Freewind\Core\Result;
use Freewind\Core\VerifyCode;
use Typecho\Cookie;
use Typecho\Db;
use Typecho\Db\Exception;
use Typecho\Validate;
use Typecho\Widget;
use Typecho\Widget\Request;
use Utils\Helper;
use Utils\PasswordHash;

class RegistRouter implements IRouter
{

    /**
     * @throws Exception
     */
    function action(Request $request)
    {
        if (!$request->isPost()) {
            Result::error('不被允许的请求方式');
        }
        $register = Widget::widget('Widget_Register');
        $options = Helper::options();
        $user = Widget::widget('Widget_User');
        $db = Db::get();
        if ($user->hasLogin()) {
            Result::error(_t('已经有用户登录啦'));
        }
        if (!$options->allowRegister) {
            Result::error(_t('站点已经关闭了注册功能'));
        }
        if (!VerifyCode::checkCode($request->get('imgcode'))) {
            Result::error(_t('验证码错误'));
        }

        if (!preg_match('/^[a-zA-Z][0-9a-zA-Z_]{5,31}/', $request->get('name'))) {
            Result::error('用户名长度必须为6-32之间只包含字母数字下划线且必须字母开头');
        }
        if (!preg_match('/^(?![0-9]+$)(?![a-z]+$)(?![A-Z]+$)(?!([^(0-9a-zA-Z)])+$).{6,18}$/', $request->get('password'))) {
            Result::error('密码包含 数字,英文,字符中的两种以上，长度6-20');
        }

        $validator = new Validate();
        $validator->addRule('name', 'required', _t('必须填写用户名称'));
        $validator->addRule('name', 'minLength', _t('用户名至少包含5个字符'), 5);
        $validator->addRule('name', 'maxLength', _t('用户名最多包含32个字符'), 32);
        $validator->addRule('name', 'xssCheck', _t('请不要在用户名中使用特殊字符'));
        $validator->addRule('name', array($register, 'nameExists'), _t('用户名已经存在'));
        $validator->addRule('screenName', 'required', _t('用户昵称不能为空'));
        $validator->addRule('mail', 'required', _t('必须填写电子邮箱'));
        $validator->addRule('mail', array($register, 'mailExists'), _t('电子邮箱地址已经存在'));
        $validator->addRule('mail', 'email', _t('电子邮箱格式错误'));
        $validator->addRule('mail', 'maxLength', _t('电子邮箱最多包含200个字符'), 200);
        $validator->addRule('password', 'required', _t('必须填写密码'));
        $validator->addRule('password', 'minLength', _t('为了保证账户安全, 请输入至少六位的密码'), 6);
        $validator->addRule('password', 'maxLength', _t('为了便于记忆, 密码长度请不要超过十八位'), 18);
        $validator->addRule('confirm', 'confirm', _t('两次输入的密码不一致'), 'password');
        $regist_user = $request->from('name', 'screenName', 'password', 'mail', 'confirm');
        if ($error = $validator->run($regist_user)) {
            Result::validator_error($error);
        }
        $hasher = new PasswordHash(8, true);

        $regist_user['password'] = $hasher->HashPassword($regist_user['password']);
        $regist_user['created'] = $options->time;
        $regist_user['group'] = 'subscriber';
        unset($regist_user['confirm']);

        $regist_user = $register->pluginHandle()->register($regist_user);
        $insertId = $db->query($db->insert('table.users')->rows($regist_user));
        if (!$insertId) {
            Result::error(_t('注册失败'));
        }
        $result = $db->fetchRow($db->select()->from('table.users')->where('uid = ?', $insertId)
            ->limit(1));
        if (!$result) {
            Result::error(_t('注册失败'));
        }
        $register->pluginHandle()->finishRegister($register);
        $user->login($regist_user['name'], $regist_user['password'], false,
            1 == $request->remember ? $options->time + $options->timezone + 30 * 24 * 3600 : 0);
        Cookie::delete('__typecho_first_run');
        Cookie::delete('__typecho_remember_name');
        Cookie::delete('__typecho_remember_mail');


        //登录
        $login_info = [
            'name' => $request->get('name'),
            'password' => $request->get('password'),
        ];
        $login = Widget::widget('Widget_Login');
        $user = Widget::widget('Widget_User');
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


        Result::success(_t('恭喜你，' . $regist_user['screenName'] . '注册成功'));
    }
}