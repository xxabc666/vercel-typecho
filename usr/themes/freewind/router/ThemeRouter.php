<?php
/**
 * Author: Mr丶冷文
 * Date: 2022/10/18 14:36
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description:
 */

namespace Freewind\Router;


use Freewind\Core\FreewindHelper;
use Freewind\Core\HttpHelper;
use Utils\Helper;

class ThemeRouter
{
    function page()
    {
        ob_clean();
        ?>
        <!doctype html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport"
                  content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <title>Document</title>
            <style>
                #fw-content a {
                    color: #FE445C;
                    text-decoration: none;
                    border-bottom: 1px solid #FE445C;
                }

                #fw-content ul {
                    list-style: none;
                    padding-left: 20px;
                }

                #fw-content ul li:before {
                    content: '- ';
                    height: 30px;
                    line-height: 30px;
                }
            </style>
            <link href="<?php Helper::options()->themeUrl('static/plugin/layui/css/layui.min.css') ?>" rel="stylesheet">
        </head>
        <body>
        <div id="fw-content">
            <?php $infoPage = json_decode(HttpHelper::sendHttp("http://api.freewind.kevinlu98.cn/api/versions")); ?>
            <h1 style="font-weight: 100; font-size: 24px; text-align: center; padding-bottom: 5px; border-bottom: 1px solid #cccccc;">
                Freewind 自由之风 By <a href="https://kevinlu98.cn">@冷文学习者</a>
            </h1>
            <p style="padding: 10px;background-color: #F5F5F5; color: #797979; font-size: 12px; border-radius: 5px;margin-top: 10px;">
                <button type="button" id="check-update"
                        class="layui-btn layui-btn-sm layui-btn-normal">
                    <i class="layui-icon layui-icon-refresh"></i> 检查更新
                </button>
                <a href="http://doc.kevinlu98.cn/"
                   style="color:#fff;border:none;" class="layui-btn layui-btn-sm layui-btn-danger">
                    <i class="layui-icon layui-icon-read"></i>使用说明
                </a>
                <a href="tencent://message/?uin=1628048198&Site=&menu=yes"
                   class="layui-btn layui-btn-sm" style="color:#fff;border:none;">
                    <i class=" layui-icon layui-icon-login-qq"></i>联系作者
                </a>
            </p>
            <?php foreach ($infoPage->data->content as $info) : ?>
                <h3 style="margin-top: 20px;font-size: 16px;margin-right: 10px;font-weight: 100;"> <?php echo $info->title ?>
                    <span class="date" style="background-color: transparent;font-size: 14px;">
                        时间：<?php echo substr($info->created, 0, 10) ?>
                    </span>
                </h3>
                <?php echo $info->html ?>
            <?php endforeach; ?>
            <h3 style="margin-top: 20px;"> 历史版本：<a href="https://kevinlu98.cn/archives/27.html"
                                                       target="_blank">传送门</a></h3>
        </div>
        <script src="<?php Helper::options()->themeUrl('static/plugin/jquery/jquery-3.5.1.min.js') ?>"></script>
        <script src="<?php Helper::options()->themeUrl('static/plugin/layui/layui.min.js') ?>"></script>
        <script>
            $(function () {
                $('#check-update').on('click', function () {
                    let loadIdx = layer.load(1, {
                        shade: [0.1, '#fff'] //0.1透明度的白色背景
                    });
                    $.ajax({
                        url: '/update/check',
                        dataType: 'json',
                        success: res => {
                            layer.close(loadIdx)
                            if (res.data.islast === true) {
                                layer.msg(res.msg, {icon: 1})
                            } else {
                                layer.msg(res.msg, {icon: 2}, function () {
                                    layer.confirm('是否前往冷文学习者下载最新版本的主题?', {
                                        btn: ['前往', '下次吧'] //按钮
                                    }, function (idx) {
                                        layer.close(idx)
                                        window.open(res.data.url)
                                    });
                                })
                            }
                        }, error: res => {
                            layer.close(loadIdx)
                        }
                    })
                })
            })
        </script>
        </body>
        </html>
        <?php
        exit();
    }
}