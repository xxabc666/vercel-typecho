<?php
/**
 * Author: Mr丶冷文
 * Date: 2022/10/17 16:10
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description:
 */

namespace Freewind\Router;

use Freewind\Config\Constants;
use Freewind\Core\FreewindHelper;
use Freewind\Core\Result;
use Freewind\Core\Site;
use Typecho\Db\Exception;
use Typecho\Widget\Request;
use Utils\Helper;

class WebsiteRouter
{
    function info(Request $request)
    {
        Result::success('', Site::sites(explode("||", $request->get(Constants::PARAMS_OPTIONS))));
    }

    function save()
    {
        foreach ($_POST as $key => $value) {
            try {
                Site::save($key, $value);
            } catch (Exception $e) {
                Result::error("保存失败", $e);
                break;
            }
        }
        Result::success("保存成功");
    }

    function pageSite()
    {
        ob_clean()
        ?>
        <!doctype html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport"
                  content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <title>Document</title>
            <link href="<?php Helper::options()->themeUrl('static/plugin/layui/css/layui.min.css') ?>" rel="stylesheet">
            <style>
                .layui-word-aux {
                    font-size: 12px;
                }

                .layui-form-label {
                    font-size: 12px;
                }

                code {
                    color: #FE445C;
                    padding: 2px;
                    background-color: #efefef;
                }

                .layui-input[type=text]:read-only {
                    background-color: #fff;
                }

                .red-tips {
                    color: #FE445C;
                }
            </style>
        </head>
        <body>
        <form class="layui-form fw-setting-form" id="data-form" lay-filter="data-form">
            <div class="layui-form-item">
                <label class="layui-form-label">Favicon设置</label>
                <div class="layui-input-inline">
                    <input type="text" name="<?php echo Site::NAME_SITE_FAVICON ?>"
                           placeholder="请输入背景图片URL"
                           autocomplete="off"
                           class="layui-input">
                    <div class="layui-form-mid layui-word-aux">
                        即浏览器标签上显示的图标，可上传可外链
                    </div>
                    <div class="layui-form-mid layui-word-aux">
                        <img style="max-width: 100%" src="" alt="" id="fw-favicon-show">
                    </div>

                </div>
                <div class="layui-input-inline">
                    <button type="button" class="layui-btn layui-btn-normal layui-btn-sm" id="fw-upload-favicon">
                        <i class="layui-icon">&#xe67c;</i>上传图片
                    </button>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">显示主题作者</label>
                <div class="layui-input-inline">
                    <select name="<?php echo Site::NAME_SITE_INFO_ALLOW ?>" lay-verify="required">
                        <option value="<?php echo Site::ENABLE ?>">显示</option>
                        <option value="<?php echo Site::DISABLE ?>">隐藏</option>
                    </select>
                </div>
                <div class="layui-form-mid layui-word-aux">
                    开启后站点左下角会显示 <b>the theme is by <a href="https://kevinlu98.cn">@冷文学习者</a></b>
                    <span class="red-tips">(* 开启该选项是您对该主题最大的支持)</span>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">站点Logo</label>
                <div class="layui-input-inline">
                    <input type="text" name="<?php echo Site::NAME_SITE_TEXT_LOGO ?>"
                           placeholder="请输入站点Logo(文字)"
                           lay-verify="required"
                           autocomplete="off"
                           class="layui-input">
                </div>
                <div class="layui-form-mid layui-word-aux">
                    该选项是站点左侧栏顶部文字logo，同时也是移动端的文字logo，目前仅支持文字
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">开启复制提示</label>
                <div class="layui-input-inline">
                    <select name="<?php echo Site::NAME_SITE_COPY_ALLOW ?>" lay-verify="required">
                        <option value="<?php echo Site::ENABLE ?>">开启</option>
                        <option value="<?php echo Site::DISABLE ?>">关闭</option>
                    </select>
                </div>
                <div class="layui-form-mid layui-word-aux">
                    开启后当别人复制站点文字时会提示版权
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">复制提示</label>
                <div class="layui-input-inline">
                    <input type="text" name="<?php echo Site::NAME_SITE_COPY_TEXT ?>" placeholder="请输入复制提示"
                           autocomplete="off"
                           class="layui-input">
                </div>
                <div class="layui-form-mid layui-word-aux">
                    在站点进行复制操作后的版权提示
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">配色</label>
                <div class="layui-input-inline">
                    <select name="<?php echo Site::NAME_SITE_STYLE ?>" lay-verify="required">
                        <?php $colors = FreewindHelper::colorList() ?>
                        <?php foreach ($colors as $color): ?>
                            <option value="<?php echo $color['filename'] ?>"><?php echo $color['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="layui-form-mid layui-word-aux">
                    即站点顶栏与左侧栏的配色方案，自定义配色方案请参考 <a
                            href="https://kevinlu98.cn/archives/76.html">Freewind自定义配色</a>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">允许更换配色</label>
                <div class="layui-input-inline">
                    <select name="<?php echo Site::NAME_SITE_STYLE_ALLOW ?>" lay-verify="required">
                        <option value="<?php echo Site::ENABLE ?>">允许</option>
                        <option value="<?php echo Site::DISABLE ?>">不允许</option>
                    </select>
                </div>
                <div class="layui-form-mid layui-word-aux">
                    是否允许用户通过站点右下角更换配色，该操作仅对操作用户生效且生效时长为1小时
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">文章代码风格</label>
                <div class="layui-input-inline">
                    <select name="<?php echo Site::NAME_SITE_CODE_STYLE ?>" lay-verify="required">
                        <?php foreach (Site::CODE_STYLE_LIST as $name => $value): ?>
                            <option value="<?php echo $value ?>"><?php echo $name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="layui-form-mid layui-word-aux">
                    是否允许用户通过站点右下角更换配色，该操作仅对操作用户生效且生效时长为1小时
                </div>
            </div>
            <div class="layui-form-item layui-form-text">
                <label class="layui-form-label">站点介绍</label>
                <div class="layui-input-block">
                        <textarea name="<?php echo Site::NAME_SITE_NOTICE ?>" placeholder="这里是站点介绍..."
                                  class="layui-textarea"></textarea>
                    <div class="layui-form-mid layui-word-aux">
                        点出右上角的文档图标出现站点介绍，支持HTML
                    </div>
                </div>
            </div>
            <div class="layui-form-item layui-form-text">
                <label class="layui-form-label">footer设置</label>
                <div class="layui-input-block">
                    <textarea name="<?php echo Site::NAME_SITE_FOOTER ?>" placeholder="这里是站点footer信息..."
                              class="layui-textarea"></textarea>
                    <div class="layui-form-mid layui-word-aux">
                        站点footer信息，通常为版权信息与备案号(支持HTML)
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button type="button" class="layui-btn layui-btn-primary fw-reload-btn">重载数据</button>
                    <button class="layui-btn" lay-submit lay-filter="data-form">提交保存</button>
                </div>
            </div>
        </form>
        <script src="<?php Helper::options()->themeUrl('static/plugin/jquery/jquery-3.5.1.min.js') ?>"></script>
        <script src="<?php Helper::options()->themeUrl('static/plugin/layui/layui.min.js') ?>"></script>
        <script>
            $(function () {
                layui.use(['form', 'upload'], function () {
                    const form = layui.form;
                    const upload = layui.upload;
                    upload.render({
                        elem: '#fw-upload-favicon',
                        url: '/freewind/upload',
                        done: res => {
                            if (res.success) {
                                layer.msg('上传成功', {icon: 1})
                                $('#fw-favicon-show').attr('src', res.data)
                                $('#data-form [name=<?php echo Site::NAME_SITE_FAVICON?>').val(res.data)
                            } else {
                                layer.msg(res.msg, {icon: 2})
                            }

                        },
                    })
                    $('#data-form .fw-reload-btn').on('click', function () {
                        let options = ""
                        $('#data-form [name]').each((index, element) => {
                            options += $(element).attr("name") + "||"
                        })
                        $.ajax({
                            url: '/website/info?<?php echo Constants::PARAMS_OPTIONS ?>=' + options,
                            dataType: 'json',
                            success: res => {
                                if (res.success) {
                                    form.val('data-form', res.data)
                                    $('#fw-favicon-show').attr('src', res.data['<?php echo Site::NAME_SITE_FAVICON?>'])
                                }
                            }
                        })
                    }).click()
                    form.on('submit(data-form)', function (data) {
                        $.ajax({
                            url: "/website/save",
                            method: 'post',
                            data: data.field,
                            dataType: "json",
                            success: res => {
                                if (res.success) {
                                    layer.msg('保存成功', {icon: 1})
                                } else {
                                    layer.msg(res.msg, {icon: 2})
                                }
                            }
                        })
                        return false;
                    })
                })
            })
        </script>
        </body>
        </html>
        <?php
        exit();
    }

    function pageBg()
    {
        ob_clean()
        ?>
        <!doctype html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport"
                  content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <title>Document</title>
            <link href="<?php Helper::options()->themeUrl('static/plugin/layui/css/layui.min.css') ?>" rel="stylesheet">
            <style>
                .layui-word-aux {
                    font-size: 12px;
                }

                .layui-form-label {
                    font-size: 12px;
                }

                code {
                    color: #FE445C;
                    padding: 2px;
                    background-color: #efefef;
                }

                .layui-input[type=text]:read-only {
                    background-color: #fff;
                }

                .red-tips {
                    color: #FE445C;
                }
            </style>
        </head>
        <body>
        <form class="layui-form fw-setting-form" id="data-form" lay-filter="data-form">
            <div class="layui-form-item">
                <label class="layui-form-label">背景色设置</label>
                <div class="layui-input-inline">
                    <input type="text" name="<?php echo Site::NAME_PIC_BG_COLOR ?>" placeholder="请输入背景色"
                           autocomplete="off"
                           lay-verify="required"
                           class="layui-input">
                </div>
                <div id="bg-color-picker" style="float:left;"></div>
                <div class="layui-form-mid layui-word-aux">
                    站点背景色设置
                </div>

            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">开启背景图</label>
                <div class="layui-input-inline">
                    <select name="<?php echo Site::NAME_PIC_BG_IMG_ALLOW ?>" lay-verify="required">
                        <option value="<?php echo Site::ENABLE ?>">开启</option>
                        <option value="<?php echo Site::DISABLE ?>">不开启</option>
                    </select>
                </div>
                <div class="layui-form-mid layui-word-aux">
                    是否开启站点背景图
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">背景图设置</label>
                <div class="layui-input-inline">
                    <input type="text" name="<?php echo Site::NAME_PIC_BG_IMG ?>" placeholder="请输入背景图片URL"
                           autocomplete="off"
                           class="layui-input">
                    <div class="layui-form-mid layui-word-aux">
                        站点背景图片，可上传可外链，仅在开启背景图后生效
                    </div>
                    <div class="layui-form-mid layui-word-aux">
                        <img style="max-width: 100%" src="" alt="" id="fw-bg-show">
                    </div>
                </div>
                <div class="layui-input-inline">
                    <button type="button" class="layui-btn layui-btn-normal layui-btn-sm" id="fw-upload-bgi">
                        <i class="layui-icon">&#xe67c;</i>上传图片
                    </button>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">平铺方式</label>
                <div class="layui-input-inline">
                    <select name="<?php echo Site::NAME_PIC_BG_REPEAT ?>" lay-verify="required">
                        <option value="<?php echo Site::REPEAT_NO ?>">不平铺</option>
                        <option value="<?php echo Site::REPEAT_REPEAT ?>">平铺</option>
                        <option value="<?php echo Site::REPEAT_REPEAT_X ?>">水平方向平铺</option>
                        <option value="<?php echo Site::REPEAT_REPEAT_Y ?>">垂直方向平铺</option>
                    </select>
                </div>
                <div class="layui-form-mid layui-word-aux">
                    背景图平铺方式，仅在开启背景图后生效
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">站点透明度</label>
                <div class="layui-input-inline">
                    <div id="fw-bg-opcity" style="margin-top: 16px;"></div>
                    <input type="hidden" value="100" name="<?php echo Site::NAME_PIC_BG_OPCITY ?>">
                </div>
                <div class="layui-form-mid layui-word-aux">
                    开启背景图后，可配合背影设置透明度
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button type="button" class="layui-btn layui-btn-primary fw-reload-btn">重载数据</button>
                    <button class="layui-btn" lay-submit lay-filter="data-form">提交保存</button>
                </div>
            </div>
        </form>
        <script src="<?php Helper::options()->themeUrl('static/plugin/jquery/jquery-3.5.1.min.js') ?>"></script>
        <script src="<?php Helper::options()->themeUrl('static/plugin/layui/layui.min.js') ?>"></script>
        <script>
            $(function () {
                layui.use(['form', 'upload', 'colorpicker', 'slider'], function () {
                    const form = layui.form;
                    const upload = layui.upload;
                    const colorpicker = layui.colorpicker;
                    const slider = layui.slider;

                    upload.render({
                        elem: '#fw-upload-bgi',
                        url: '/freewind/upload',
                        done: res => {
                            if (res.success) {
                                layer.msg('上传成功', {icon: 1})
                                $('#fw-bg-show').attr('src', res.data)
                                $('#data-form [name=<?php echo Site::NAME_PIC_BG_IMG?>').val(res.data)
                            } else {
                                layer.msg(res.msg, {icon: 2})
                            }

                        },
                    })


                    $('#data-form .fw-reload-btn').on('click', function () {
                        let options = ""
                        $('#data-form [name]').each((index, element) => {
                            options += $(element).attr("name") + "||"
                        })
                        $.ajax({
                            url: '/website/info?<?php echo Constants::PARAMS_OPTIONS ?>=' + options,
                            dataType: 'json',
                            success: res => {
                                if (res.success) {
                                    form.val('data-form', res.data)
                                    colorpicker.render({
                                        elem: '#bg-color-picker',  //绑定元素
                                        color: $("input[name=<?php echo Site::NAME_PIC_BG_COLOR ?>]").val(),
                                        predefine: true,
                                        colors: [$(".layui-input-inline input[name=<?php echo Site::NAME_PIC_BG_COLOR ?>]").val(), '#EEEEEF', '#BCD2DC', '#D4D2D4', '#4392B4', '#FDECD2'],
                                        done: function (color) {
                                            $("input[name=<?php echo Site::NAME_PIC_BG_COLOR ?>]").val(color)
                                        }
                                    });
                                    slider.render({
                                        elem: '#fw-bg-opcity',  //绑定元素
                                        value: $('input[name=<?php echo Site::NAME_PIC_BG_OPCITY?>]').val(),
                                        change: function (value) {
                                            $('input[name=<?php echo Site::NAME_PIC_BG_OPCITY?>]').val(value);
                                        }
                                    })
                                    $('#fw-bg-show').attr('src', res.data['<?php echo Site::NAME_PIC_BG_IMG?>'])
                                }
                            }
                        })
                    }).click()
                    form.on('submit(data-form)', function (data) {
                        $.ajax({
                            url: "/website/save",
                            method: 'post',
                            data: data.field,
                            dataType: "json",
                            success: res => {
                                if (res.success) {
                                    layer.msg('保存成功', {icon: 1})
                                } else {
                                    layer.msg(res.msg, {icon: 2})
                                }
                            }
                        })
                        return false;
                    })
                })
            })
        </script>
        </body>
        </html>
        <?php
        exit();
    }

    function pageMaster()
    {
        ob_clean()
        ?>
        <!doctype html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport"
                  content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <title>Document</title>
            <link href="<?php Helper::options()->themeUrl('static/plugin/layui/css/layui.min.css') ?>" rel="stylesheet">
            <style>
                .layui-word-aux {
                    font-size: 12px;
                }

                .layui-form-label {
                    font-size: 12px;
                }

                code {
                    color: #FE445C;
                    padding: 2px;
                    background-color: #efefef;
                }

                .layui-input[type=text]:read-only {
                    background-color: #fff;
                }

                .red-tips {
                    color: #FE445C;
                }
            </style>
        </head>
        <body>
        <form class="layui-form fw-setting-form" id="data-form" lay-filter="data-form">
            <div class="layui-form-item">
                <label class="layui-form-label">站长头像</label>
                <div class="layui-input-inline">
                    <input type="text" name="<?php echo Site::NAME_MASTER_AVATAR ?>" placeholder="请输入站长头像URL"
                           autocomplete="off"
                           lay-verify="required"
                           class="layui-input">
                    <div class="layui-form-mid layui-word-aux">
                        该选项是站点左侧栏显示的站长头像的URL地址
                    </div>
                    <div class="layui-form-mid layui-word-aux">
                        <img style="max-width: 100%" src="" alt="" id="fw-avatar-show">
                    </div>
                </div>
                <div class="layui-input-inline">
                    <button type="button" class="layui-btn layui-btn-normal layui-btn-sm" id="fw-upload-avatar">
                        <i class="layui-icon">&#xe67c;</i>上传图片
                    </button>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">站长昵称</label>
                <div class="layui-input-inline">
                    <input type="text" name="<?php echo Site::NAME_MASTER_NICKNAME ?>" placeholder="请输入站长昵称"
                           autocomplete="off"
                           lay-verify="required"
                           class="layui-input">
                </div>
                <div class="layui-form-mid layui-word-aux">
                    该选项是站点左侧栏站长头像下方的站长昵称
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">启用QQ联系我</label>
                <div class="layui-input-inline">
                    <select name="<?php echo Site::NAME_MASTER_QQ_ALLOW ?>" lay-verify="required">
                        <option value="<?php echo Site::ENABLE ?>">启用</option>
                        <option value="<?php echo Site::DISABLE ?>">禁止</option>
                    </select>
                </div>
                <div class="layui-form-mid layui-word-aux">
                    开启后站点右下角会了个QQ联系的按钮，点击之后会打开与你的QQ聊天窗口
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">站长QQ</label>
                <div class="layui-input-inline">
                    <input type="text" name="<?php echo Site::NAME_MASTER_QQ ?>" placeholder="请输入站长QQ"
                           autocomplete="off"
                           class="layui-input">
                </div>
                <div class="layui-form-mid layui-word-aux">
                    该选项是站长的QQ，也是右下角的QQ联系按钮的QQ
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">说明</label>
                <div class="layui-form-mid layui-word-aux">
                    下面配置左侧栏下方联系我的4个图标及跳转链接，图标采用的是<a
                            href="https://www.thinkcmf.com/font_awesome.html">Font Awesome
                        4.7</a>，格式为<code>fa-名称</code>,就直接过来拷贝就行
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">图标1</label>
                <div class="layui-input-inline">
                    <input type="text" name="<?php echo Site::NAME_MASTER_ICON1 ?>" placeholder="请输入图标1"
                           autocomplete="off"
                           lay-verify="required"
                           class="layui-input">
                </div>
                <label class="layui-form-label">跳转链接</label>
                <div class="layui-input-inline">
                    <input type="text" name="<?php echo Site::NAME_MASTER_ICON_VALUE1 ?>"
                           placeholder="请输入跳转链接1"
                           autocomplete="off"
                           class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">图标1</label>
                <div class="layui-input-inline">
                    <input type="text" name="<?php echo Site::NAME_MASTER_ICON2 ?>" placeholder="请输入图标2"
                           autocomplete="off"
                           lay-verify="required"
                           class="layui-input">
                </div>
                <label class="layui-form-label">跳转链接</label>
                <div class="layui-input-inline">
                    <input type="text" name="<?php echo Site::NAME_MASTER_ICON_VALUE2 ?>"
                           placeholder="请输入跳转链接2"
                           autocomplete="off"
                           class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">图标3</label>
                <div class="layui-input-inline">
                    <input type="text" name="<?php echo Site::NAME_MASTER_ICON3 ?>" placeholder="请输入图标3"
                           autocomplete="off"
                           lay-verify="required"
                           class="layui-input">
                </div>
                <label class="layui-form-label">跳转链接</label>
                <div class="layui-input-inline">
                    <input type="text" name="<?php echo Site::NAME_MASTER_ICON_VALUE3 ?>"
                           placeholder="请输入跳转链接3"
                           autocomplete="off"
                           class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">图标4</label>
                <div class="layui-input-inline">
                    <input type="text" name="<?php echo Site::NAME_MASTER_ICON4 ?>" placeholder="请输入图标4"
                           autocomplete="off"
                           lay-verify="required"
                           class="layui-input">
                </div>
                <label class="layui-form-label">跳转链接</label>
                <div class="layui-input-inline">
                    <input type="text" name="<?php echo Site::NAME_MASTER_ICON_VALUE4 ?>"
                           placeholder="请输入跳转链接4"
                           autocomplete="off"
                           class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button type="button" class="layui-btn layui-btn-primary fw-reload-btn">重载数据</button>
                    <button class="layui-btn" lay-submit lay-filter="data-form">提交保存</button>
                </div>
            </div>
        </form>
        <script src="<?php Helper::options()->themeUrl('static/plugin/jquery/jquery-3.5.1.min.js') ?>"></script>
        <script src="<?php Helper::options()->themeUrl('static/plugin/layui/layui.min.js') ?>"></script>
        <script>
            $(function () {
                layui.use(['form', 'upload'], function () {
                    const form = layui.form;
                    const upload = layui.upload;
                    upload.render({
                        elem: '#fw-upload-avatar',
                        url: '/freewind/upload',
                        done: res => {
                            if (res.success) {
                                layer.msg('上传成功', {icon: 1})
                                $('#fw-avatar-show').attr('src', res.data)
                                $('#data-form [name=<?php echo Site::NAME_MASTER_AVATAR?>').val(res.data)
                            } else {
                                layer.msg(res.msg, {icon: 2})
                            }

                        },
                    })


                    $('#data-form .fw-reload-btn').on('click', function () {
                        let options = ""
                        $('#data-form [name]').each((index, element) => {
                            options += $(element).attr("name") + "||"
                        })
                        $.ajax({
                            url: '/website/info?<?php echo Constants::PARAMS_OPTIONS ?>=' + options,
                            dataType: 'json',
                            success: res => {
                                if (res.success) {
                                    form.val('data-form', res.data)
                                    $('#fw-avatar-show').attr('src', res.data['<?php echo Site::NAME_MASTER_AVATAR?>'])
                                }
                            }
                        })
                    }).click()
                    form.on('submit(data-form)', function (data) {
                        $.ajax({
                            url: "/website/save",
                            method: 'post',
                            data: data.field,
                            dataType: "json",
                            success: res => {
                                if (res.success) {
                                    layer.msg('保存成功', {icon: 1})
                                } else {
                                    layer.msg(res.msg, {icon: 2})
                                }
                            }
                        })
                        return false;
                    })
                })
            })
        </script>
        </body>
        </html>
        <?php
        exit();
    }

    function pageComment()
    {
        ob_clean()
        ?>
        <!doctype html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport"
                  content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <title>Document</title>
            <link href="<?php Helper::options()->themeUrl('static/plugin/layui/css/layui.min.css') ?>" rel="stylesheet">
            <style>
                .layui-word-aux {
                    font-size: 12px;
                }

                .layui-form-label {
                    font-size: 12px;
                }

                code {
                    color: #FE445C;
                    padding: 2px;
                    background-color: #efefef;
                }

                .layui-input[type=text]:read-only {
                    background-color: #fff;
                }

                .red-tips {
                    color: #FE445C;
                }
            </style>
        </head>
        <body>
        <form class="layui-form fw-setting-form" id="data-form" lay-filter="data-form">
            <div class="layui-form-item">
                <label class="layui-form-label">开启邮件通知</label>
                <div class="layui-input-inline">
                    <select name="<?php echo Site::NAME_MAIL_ALLOW ?>" lay-verify="required">
                        <option value="<?php echo Site::DISABLE ?>">关闭</option>
                        <option value="<?php echo Site::ENABLE ?>">开启</option>
                        <option value="<?php echo Site::ENABLE_AND_OTHER ?>">开启并回复时通知</option>
                    </select>
                </div>
                <div class="layui-form-mid layui-word-aux">
                    收到评论或回复时，给被回复者及站长发送邮件通知
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">SMTP服务器</label>
                <div class="layui-input-inline">
                    <input type="text" name="<?php echo Site::NAME_MAIL_SERVER ?>" placeholder="请输入邮箱服务器"
                           autocomplete="off"
                           class="layui-input">
                </div>
                <div class="layui-form-mid layui-word-aux">
                    如:smtp.163.com
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">SMTP端口</label>
                <div class="layui-input-inline">
                    <input type="number" name="<?php echo Site::NAME_MAIL_PORT ?>" placeholder="请输入邮箱端口"
                           autocomplete="off"
                           class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">是否开启SSL</label>
                <div class="layui-input-inline">
                    <select name="<?php echo Site::NAME_MAIL_SSL ?>" lay-verify="required">
                        <option value="<?php echo Site::DISABLE ?>">关闭</option>
                        <option value="<?php echo Site::ENABLE ?>">开启</option>
                    </select>
                </div>
                <div class="layui-form-mid layui-word-aux">
                    如果不知道怎么设置的话就都试试，比如：你用的是腾讯企业邮箱就必须开启，qq邮箱就不用
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">发信邮箱</label>
                <div class="layui-input-inline">
                    <input type="text" name="<?php echo Site::NAME_MAIL_USERNAME ?>" placeholder="请输入发信邮箱"
                           autocomplete="off"
                           class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">发信密码</label>
                <div class="layui-input-inline">
                    <input type="password" name="<?php echo Site::NAME_MAIL_PASSWORD ?>"
                           placeholder="请输入发信密码"
                           autocomplete="off"
                           class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">发信校验标识</label>
                <div class="layui-input-inline">
                    <input type="text" name="<?php echo Site::NAME_MAIL_AUTHOR_CODE ?>"
                           placeholder="请输入发信校验标识"
                           autocomplete="off"
                           class="layui-input">
                </div>
                <div class="layui-form-mid layui-word-aux">
                    因为邮件是异步调接口发送的，怕被人恶意访问站点的邮件接口，这里需要设置个随机字符串来做校验,
                    <a id="fw-create-code" href="javascript:void(0);">点此随机生成</a>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">收信邮箱</label>
                <div class="layui-input-inline">
                    <input type="text" name="<?php echo Site::NAME_MAIL_RECEIVE ?>" placeholder="请输入收信邮箱"
                           autocomplete="off"
                           class="layui-input">
                    <div class="layui-form-mid layui-word-aux">
                        <b>*若有变动请先提交保存后再进行测试</b>
                    </div>
                </div>
                <button class="layui-btn layui-btn-sm layui-btn-normal" id="fw-mail-check" type="button">
                    发一封测试邮件
                </button>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button type="button" class="layui-btn layui-btn-primary fw-reload-btn">重载数据</button>
                    <button class="layui-btn" lay-submit lay-filter="data-form">提交保存</button>
                </div>
            </div>
        </form>
        <script src="<?php Helper::options()->themeUrl('static/plugin/jquery/jquery-3.5.1.min.js') ?>"></script>
        <script src="<?php Helper::options()->themeUrl('static/plugin/layui/layui.min.js') ?>"></script>

        <script>
            const _charStr = 'abacdefghjklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ0123456789';

            /**
             * 随机生成索引
             * @param min 最小值
             * @param max 最大值
             * @param i 当前获取位置
             */
            function RandomIndex(min, max, i) {
                let index = Math.floor(Math.random() * (max - min + 1) + min),
                    numStart = _charStr.length - 10;
                //如果字符串第一位是数字，则递归重新获取
                if (i === 0 && index >= numStart) {
                    index = RandomIndex(min, max, i);
                }
                //返回最终索引值
                return index;
            }

            /**
             * 随机生成字符串
             * @param len 指定生成字符串长度
             */
            function getRandomString(len) {
                let min = 0, max = _charStr.length - 1, _str = '';
                //判断是否指定长度，否则默认长度为15
                len = len || 15;
                //循环生成字符串
                let i = 0, index;
                for (; i < len; i++) {
                    index = RandomIndex(min, max, i);
                    _str += _charStr[index];
                }
                return _str;
            }

            $(function () {
                layui.use(['form'], function () {
                    const form = layui.form;
                    $('#data-form .fw-reload-btn').on('click', function () {
                        let options = ""
                        $('#data-form [name]').each((index, element) => {
                            options += $(element).attr("name") + "||"
                        })
                        $.ajax({
                            url: '/website/info?<?php echo Constants::PARAMS_OPTIONS ?>=' + options,
                            dataType: 'json',
                            success: res => {
                                if (res.success) {
                                    form.val('data-form', res.data)
                                }
                            }
                        })
                    }).click()
                    $('#fw-create-code').on('click', function () {
                        $('#data-form input[name=<?php echo Site::NAME_MAIL_AUTHOR_CODE?>]').val(getRandomString(64));
                    })
                    $('#fw-mail-check').on('click', function () {
                        let idx = layer.load(1, {
                            shade: [0.1, '#fff'] //0.1透明度的白色背景
                        });
                        $.ajax({
                            url: '/mail/check',
                            dataType: 'json',
                            success: res => {
                                layer.close(idx)
                                layer.msg(res.msg, {icon: res.success ? 1 : 2})
                            }, error: res => {
                                layer.close(idx)
                                layer.msg('邮件发送失败', {icon: 2})
                            }
                        })
                    })
                    form.on('submit(data-form)', function (data) {
                        $.ajax({
                            url: "/website/save",
                            method: 'post',
                            data: data.field,
                            dataType: "json",
                            success: res => {
                                if (res.success) {
                                    layer.msg('保存成功', {icon: 1})
                                } else {
                                    layer.msg(res.msg, {icon: 2})
                                }
                            }
                        })
                        return false;
                    })
                })
            })
        </script>
        </body>
        </html>
        <?php
        exit();
    }

    function pageDeveloper()
    {
        ob_clean()
        ?>
        <!doctype html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport"
                  content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <title>Document</title>
            <link href="<?php Helper::options()->themeUrl('static/plugin/layui/css/layui.min.css') ?>" rel="stylesheet">
            <style>
                .layui-word-aux {
                    font-size: 12px;
                }

                .layui-form-label {
                    font-size: 12px;
                }

                code {
                    color: #FE445C;
                    padding: 2px;
                    background-color: #efefef;
                }

                .layui-input[type=text]:read-only {
                    background-color: #fff;
                }

                .red-tips {
                    color: #FE445C;
                }
            </style>
        </head>
        <body>
        <form class="layui-form fw-setting-form" id="data-form" lay-filter="data-form">
            <div class="layui-form-item">
                <label class="layui-form-label">自定义静态资源路径</label>
                <div class="layui-input-inline">
                    <select name="<?php echo Site::NAME_DEP_STATIC_ENABLE ?>" lay-verify="required">
                        <option value="<?php echo Site::DISABLE ?>">关闭</option>
                        <option value="<?php echo Site::ENABLE ?>">开启</option>
                    </select>
                </div>
                <div class="layui-form-mid layui-word-aux">
                    允许自定义静态资源的路径，开启后站点将采用你自定义的路径来加载静态资源，可前往
                    <a href="https://github.com/kevinlu98/freecdn" target="_blank">传送门 <i
                                class="fa fa-send-o"></i></a>
                    下载对应主题版本的静态资源来避免cdn挂了出现一些功能不可用
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">静态资源路径</label>
                <div class="layui-input-inline">
                    <input type="text" name="<?php echo Site::NAME_DEP_STATIC_PATH ?>" placeholder="请输入静态资源路径"
                           autocomplete="off"
                           class="layui-input">
                </div>
                <div class="layui-form-mid layui-word-aux">
                    静态资源前缀, 请以"/"结尾，请确保已开启自定义静态资源路径
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">全局css</label>
                <div class="layui-input-block">
                    <textarea name="<?php echo Site::NAME_DEP_GLOBAL_CSS ?>" placeholder="这里是全局css..."
                              class="layui-textarea"></textarea>
                    <div class="layui-form-mid layui-word-aux">
                        控制主题的CSS样式，全局生效，请在外层加上 style标签，主题会将其放入head部分
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">全局JS</label>
                <div class="layui-input-block">
                    <textarea name="<?php echo Site::NAME_DEP_GLOBAL_JS ?>" placeholder="这里是全局JS..."
                              class="layui-textarea"></textarea>
                    <div class="layui-form-mid layui-word-aux">
                        控制主题的脚本，全局生效，请在外层加上 script 标签 如统计代码
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">PJAX回调</label>
                <div class="layui-input-block">
                    <textarea name="<?php echo Site::NAME_DEP_PJAX_LOAD ?>" placeholder="这里是PJAX回调..."
                              class="layui-textarea"></textarea>
                    <div class="layui-form-mid layui-word-aux">
                        如果你使用的插件有支持PJAX，请按照插件说明将PJAX回调的代码写在这里
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button type="button" class="layui-btn layui-btn-primary fw-reload-btn">重载数据</button>
                    <button class="layui-btn" lay-submit lay-filter="data-form">提交保存</button>
                </div>
            </div>
        </form>
        <script src="<?php Helper::options()->themeUrl('static/plugin/jquery/jquery-3.5.1.min.js') ?>"></script>
        <script src="<?php Helper::options()->themeUrl('static/plugin/layui/layui.min.js') ?>"></script>
        <script>
            $(function () {
                layui.use(['form'], function () {
                    const form = layui.form;
                    $('#data-form .fw-reload-btn').on('click', function () {
                        let options = ""
                        $('#data-form [name]').each((index, element) => {
                            options += $(element).attr("name") + "||"
                        })
                        $.ajax({
                            url: '/website/info?<?php echo Constants::PARAMS_OPTIONS ?>=' + options,
                            dataType: 'json',
                            success: res => {
                                if (res.success) {
                                    form.val('data-form', res.data)
                                }
                            }
                        })
                    }).click()
                    $('#fw-mail-check').on('click', function () {
                        let idx = layer.load(1, {
                            shade: [0.1, '#fff'] //0.1透明度的白色背景
                        });
                        $.ajax({
                            url: '/mail/check',
                            dataType: 'json',
                            success: res => {
                                layer.close(idx)
                                layer.msg(res.msg, {icon: res.success ? 1 : 2})
                            }, error: res => {
                                layer.close(idx)
                                layer.msg('邮件发送失败', {icon: 2})
                            }
                        })
                    })
                    form.on('submit(data-form)', function (data) {
                        $.ajax({
                            url: "/website/save",
                            method: 'post',
                            data: data.field,
                            dataType: "json",
                            success: res => {
                                if (res.success) {
                                    layer.msg('保存成功', {icon: 1})
                                } else {
                                    layer.msg(res.msg, {icon: 2})
                                }
                            }
                        })
                        return false;
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