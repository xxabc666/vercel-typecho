<?php
/**
 * Author: Mr丶冷文
 * Date: 2022/10/18 22:27
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description:
 */

namespace Freewind\Router;

use Freewind\Config\Constants;
use Freewind\Core\FreewindHelper;
use Freewind\Core\Result;
use Freewind\Core\Site;
use Freewind\Extend\LinkedExtend;
use Typecho\Widget\Request;
use Utils\Helper;

class LinkedRouter extends IExtendRouter
{
    protected $table = LinkedExtend::TABLE_NAME;
    protected $fieldList = LinkedExtend::FIELD_LIST;

    function lstCache(): array
    {
        return [LinkedExtend::CACHE_PREFIX];
    }

    function verification($item)
    {
        if (!$item['name']) {
            Result::error("站点名称不能为空");
        }
        if (!$item['link']) {
            Result::error("站点链接不能为空");
        }
    }

    function condation(Request $request): array
    {
        return [];
    }

    function page(): void
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
            <link href="<?php Helper::options()->themeUrl('static/plugin/layui/css/layui.min.css') ?>" rel="stylesheet">
            <title>Document</title>
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
        <div id="fw-content">
            <form class="layui-form fw-setting-form" id="data-form" lay-filter="data-form">
                <div class="layui-form-item">
                    <label class="layui-form-label">友链展示数量</label>
                    <div class="layui-input-inline">
                        <input type="text" name="<?php echo Site::NAME_LINKED_NUM ?>"
                               placeholder="友链展示数量"
                               lay-verify="number"
                               autocomplete="off"
                               class="layui-input">
                        <div class="layui-form-mid layui-word-aux">
                            除了友情链接独立页面外的其它地方最大显示多少条友链,0或不填表示所有
                        </div>
                    </div>
                    <label class="layui-form-label">友链默认描述</label>
                    <div class="layui-input-inline">
                        <input type="text" name="<?php echo Site::NAME_LINKED_DESC ?>"
                               placeholder="友链默认描述"
                               autocomplete="off"
                               class="layui-input">
                        <div class="layui-form-mid layui-word-aux">
                            当友链描述没有填写时展示的描述信息
                        </div>
                    </div>
                    <label class="layui-form-label">展示友情链接</label>
                    <div class="layui-input-inline">
                        <select name="<?php echo Site::NAME_LINKED_ALLOW ?>" lay-verify="required">
                            <option value="<?php echo Site::ENABLE ?>">展示</option>
                            <option value="<?php echo Site::DISABLE ?>">不展示</option>
                        </select>
                        <div class="layui-form-mid layui-word-aux">
                            除了友情链接独立页面外的其它地方是否展示友情链接
                        </div>
                    </div>
                    <div class="layui-input-inline" style="width: 200px;">
                        <button class="layui-btn" lay-submit lay-filter="data-form">提交保存</button>
                        <button type="button" class="layui-btn layui-btn-normal" id="fw-add-btn">
                            <i class="layui-icon layui-icon-addition"></i>新增
                        </button>
                    </div>
                </div>
            </form>
            <table id="nav-table" lay-filter="nav-table" lay-filter="nav-table"></table>
        </div>
        <script type="text/html" id="fw-save-form-content">
            <form class="layui-form" id="fw-save-form" lay-filter="fw-save-form">
                <input type="hidden" name="id">
                <div class="layui-form-item" style="margin-top: 10px;">
                    <label class="layui-form-label">站点名称</label>
                    <div class="layui-input-inline">
                        <input type="text" name="name" required lay-verify="required" placeholder="请输入站点名称"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item" style="margin-top: 10px;">
                    <label class="layui-form-label">描述信息</label>
                    <div class="layui-input-inline">
                        <textarea name="desc" placeholder="请输入描述信息" class="layui-textarea"></textarea>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">站点地址</label>
                    <div class="layui-input-inline">
                        <input type="text" name="link" placeholder="请输入站点地址" required lay-verify="required"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">站点图标</label>
                    <div class="layui-input-inline">
                        <input type="text" name="icon"
                               placeholder="请输入站点图标"
                               autocomplete="off"
                               class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn" lay-submit lay-filter="fw-save-form">立即提交</button>
                    </div>
                </div>
            </form>
        </script>
        <script src="<?php Helper::options()->themeUrl('static/plugin/jquery/jquery-3.5.1.min.js') ?>"></script>
        <script src="<?php Helper::options()->themeUrl('static/plugin/layui/layui.min.js') ?>"></script>
        <script>
            $(function () {
                layui.use(['form', 'table'], function () {
                    const form = layui.form;
                    const table = layui.table;
                    let saveIdx = 0;
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
                    $.ajax({
                        url: '/website/info?<?php echo Constants::PARAMS_OPTIONS ?>=<?php echo (Site::NAME_LINKED_NUM . '||' . Site::NAME_LINKED_DESC) . '||' . Site::NAME_LINKED_ALLOW ?>',
                        dataType: "json",
                        success: res => {
                            form.val('data-form', res.data)
                        }
                    })
                    $('#fw-add-btn').on('click', function () {
                        saveIdx = layer.open({
                            type: 1,
                            shadeClose: true,
                            skin: 'layui-layer-rim', //加上边框
                            area: ['400px', '400px'], //宽高
                            content: $('#fw-save-form-content').html(),
                        });
                    })
                    $('#fw-content').on('click', '.fw-nav-edit-btn', function () {
                        let idx = $(this).data('index');
                        let data = table.cache['nav-table'][idx]
                        saveIdx = layer.open({
                            type: 1,
                            shadeClose: true,
                            skin: 'layui-layer-rim', //加上边框
                            area: ['400px', '400px'], //宽高
                            content: $('#fw-save-form-content').html(),
                        });
                        form.val('fw-save-form', data)
                    }).on('click', '.fw-nav-del-btn', function () {
                        let id = $(this).data('id')
                        layer.confirm("你确认要删除当前行数据？", {btn: ['确认', '取消']}, function (idx) {
                            layer.close(idx)
                            $.ajax({
                                url: '/linked/del',
                                method: 'post',
                                data: {id: id},
                                dataType: 'json',
                                success: res => {
                                    if (res.success) {
                                        layer.msg(res.msg, {icon: 1})
                                        navTable.reload()
                                    } else {
                                        layer.msg(res.msg, {icon: 2})
                                    }
                                }
                            })
                        })
                    })
                    form.on('submit(fw-save-form)', function (data) {
                        console.log(data.field);
                        $.ajax({
                            url: '/linked/save',
                            method: 'post',
                            data: data.field,
                            dataType: 'json',
                            success: res => {
                                if (res.success) {
                                    layer.msg('保存成功', {icon: 1})
                                    layer.close(saveIdx)
                                    navTable.reload()
                                } else {
                                    layer.msg(res.msg, {icon: 2})
                                }

                            }
                        })
                        return false;
                    })
                    $('#fw-type-btn-group button').on('click', function () {
                        $('#fw-type-btn-group .layui-btn-normal').removeClass('layui-btn-normal').addClass('layui-btn-primary')
                        $(this).removeClass('layui-btn-primary').addClass('layui-btn-normal')
                        navTable.reload({
                            where: {
                                'type': $(this).data('type')
                            },
                        })
                    })
                    let navTable = table.render({
                        elem: '#nav-table',
                        height: 600,
                        url: '/linked/list',
                        page: true,
                        request: {
                            pageName: 'pageNum',
                            limitName: 'pageSize',
                        },
                        parseData: function (res) {
                            return {
                                "code": res.success ? 0 : 1,
                                "msg": res.msg,
                                "count": res.data.count, //解析数据长度
                                "data": res.data.rows //解析数据列表
                            };
                        },
                        cols: [[
                            {
                                field: 'id',
                                title: '序号',
                                width: 80,
                                templet: function (data) {
                                    return data.LAY_TABLE_INDEX + 1
                                }
                            },
                            {
                                field: 'name',
                                title: '站点名称',
                            },
                            {
                                field: 'desc',
                                title: '站点描述',
                            },
                            {
                                field: 'icon',
                                title: '站点图标',
                                templet: function (data) {
                                    let icon = data['icon'] ? data['icon'] : data['link'] + '/favicon.ico';
                                    return `<img style="max-width: 28px" src="${icon}">`
                                }
                            },

                            {
                                field: 'url',
                                title: '站点地址',
                                templet: function (data) {
                                    return `<a href="${data['link']}" target="_blank">${data['link']}</a>`
                                }
                            },
                            {
                                title: '操作',
                                width: 200,
                                templet: function (data) {
                                    return `<a data-index="${data.LAY_TABLE_INDEX}" class="fw-nav-edit-btn layui-btn layui-btn-xs">编辑</a> <a data-id="${data['id']}" class="fw-nav-del-btn layui-btn layui-btn-danger layui-btn-xs">删除</a>`;
                                }
                            },
                        ]]
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