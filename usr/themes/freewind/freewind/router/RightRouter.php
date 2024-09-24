<?php
/**
 * Author: Mr丶冷文
 * Date: 2022/10/19 01:39
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description:
 */

namespace Freewind\Router;

use Freewind\Config\Constants;
use Freewind\Core\FreewindHelper;
use Freewind\Core\Result;
use Freewind\Core\Site;
use Freewind\Extend\IExtend;
use Freewind\Extend\RightExtend;
use Typecho\Widget\Request;
use Utils\Helper;

class RightRouter extends IExtendRouter
{
    protected $table = RightExtend::TABLE_NAME;

    protected $fieldList = RightExtend::FIELD_LIST;

    protected $ordered = 'ordered';

    function lstCache(): array
    {
        return [RightExtend::CACHE_PREFIX . IExtend::arr2str(['status' => RightExtend::STATUS_ENABLE]) . $this->ordered];
    }

    function verification($item)
    {
        if (!$item['name']) {
            Result::error("自定义右键名称不能为空");
        }
    }

    function condation(Request $request): array
    {
        return [];
    }

    function page()
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
            <link rel="stylesheet" href="<?php echo FreewindHelper::freeCdn('plugin/font-awesome/css/font-awesome.min.css')?>">
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
            <form class="layui-form" id="data-form" lay-filter="data-form">
                <div class="layui-form-item">
                    <label class="layui-form-label">开启右键</label>
                    <div class="layui-input-inline">
                        <select name="<?php echo Site::NAME_RIGHT_ALLOW ?>" lay-verify="required">
                            <option value="<?php echo Site::ENABLE ?>">开启</option>
                            <option value="<?php echo Site::DISABLE ?>">关闭</option>
                        </select>
                    </div>
                    <div class="layui-input-inline" style="width: 100px;">
                        <button class="layui-btn" lay-submit lay-filter="data-form">立即提交</button>
                    </div>
                    <div class="layui-form-mid layui-word-aux">
                        开启自定义右键功能，开启后可以自定义配置一些右键菜单，支持 <code>javascript</code> 脚本及跳转链接两种方式
                    </div>
                </div>
            </form>
            <div style="margin-top: 20px;">
                <div class="layui-btn-group">
                    <button class="layui-btn layui-btn-primary layui-btn-sm" id="fw-nav-add-btn">
                        <i class="layui-icon layui-icon-addition"></i>新增
                    </button>
                    <button class="layui-btn layui-btn-primary layui-btn-sm" id="fw-nav-order-btn">
                        <i class="layui-icon layui-icon-refresh-3"></i>保存排序
                    </button>
                </div>
            </div>
            <table id="nav-table" lay-filter="nav-table" lay-filter="nav-table"></table>
        </div>
        <script type="text/html" id="fw-nav-save">
            <form class="layui-form" id="nav-save-form" lay-filter="nav-save-form">
                <input type="hidden" name="id">
                <div class="layui-form-item" style="margin-top: 10px;">
                    <label class="layui-form-label">名称</label>
                    <div class="layui-input-inline">
                        <input type="text" name="name" required lay-verify="required" placeholder="请输入名称"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">图标</label>
                    <div class="layui-input-inline">
                        <input type="text" name="icon" placeholder="请输入图标"
                               autocomplete="off" class="layui-input">
                    </div>
                    <div class="layui-form-mid layui-word-aux">
                        <a href="https://www.thinkcmf.com/font_awesome.html" target="_blank">
                            Font Awesome 4.7
                        </a>图标，仅在类型为导航时生效
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">类型</label>
                    <div class="layui-input-inline">
                        <select name="type" lay-verify="required">
                            <option value="<?php echo RightExtend::TYPE_LINK ?>">链接跳转</option>
                            <option value="<?php echo RightExtend::TYPE_SCRIPT ?>">自定义js</option>
                        </select>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">跳转地址</label>
                    <div class="layui-input-inline">
                        <input type="text" name="link" placeholder="请输入跳转地址"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">排序</label>
                    <div class="layui-input-inline">
                        <input type="number" name="ordered" placeholder="请输入排序"
                               required lay-verify="required|number"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">是否启用</label>
                    <div class="layui-input-inline">
                        <select name="status" lay-verify="required">
                            <option value="<?php echo RightExtend::STATUS_ENABLE ?>">启用</option>
                            <option value="<?php echo RightExtend::STATUS_DISABLE ?>">禁用</option>
                        </select>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">Js脚本</label>
                    <div class="layui-input-block">
                        <textarea name="script" class="layui-textarea"></textarea>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn" lay-submit lay-filter="nav-save-form">立即提交</button>
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
                    let navIdx = 0;
                    $.ajax({
                        url: '/website/info?<?php echo Constants::PARAMS_OPTIONS ?>=<?php echo Site::NAME_RIGHT_ALLOW ?>',
                        dataType: "json",
                        success: res => {
                            form.val('data-form', res.data)
                        }
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
                    $('#fw-nav-order-btn').on('click', function () {
                        let data = ""
                        table.cache['nav-table'].forEach(item => data += (item['id'] + '----' + item['ordered'] + ';'))
                        $.ajax({
                            url: '/right/order',
                            data: {'params': data},
                            dataType: 'json',
                            success: res => {
                                if (res.success) {
                                    layer.msg('保存成功', {icon: 1})
                                    navTable.reload()
                                } else {
                                    layer.msg(res.msg, {icon: 2})
                                }
                            }

                        })
                    })
                    $('#fw-nav-add-btn').on('click', function () {
                        navIdx = layer.open({
                            type: 1,
                            shadeClose: true,
                            skin: 'layui-layer-rim', //加上边框
                            area: ['600px', '550px'], //宽高
                            content: $('#fw-nav-save').html(),
                        });
                        form.render('select');
                    })
                    $('#fw-content').on('click', '.fw-nav-status-btn', function () {
                        let idx = $(this).data('index');
                        table.cache['nav-table'][idx]['status'] = $(this).data('value');
                        $.ajax({
                            url: '/right/save',
                            method: 'post',
                            data: table.cache['nav-table'][idx],
                            dataType: 'json',
                            success: res => {
                                if (res.success) {
                                    layer.msg('保存成功', {icon: 1})
                                    navTable.reload()
                                } else {
                                    layer.msg(res.msg, {icon: 2})
                                }
                            }
                        })
                    }).on('click', '.fw-nav-edit-btn', function () {
                        let idx = $(this).data('index');
                        let data = table.cache['nav-table'][idx]
                        navIdx = layer.open({
                            type: 1,
                            shadeClose: true,
                            skin: 'layui-layer-rim', //加上边框
                            area: ['600px', '550px'], //宽高
                            content: $('#fw-nav-save').html(),
                        });
                        form.render('select');
                        form.val('nav-save-form', data)
                    }).on('click', '.fw-nav-del-btn', function () {
                        let id = $(this).data('id')
                        layer.confirm("你确认要删除当前行数据？", {btn: ['确认', '取消']}, function (idx) {
                            layer.close(idx)
                            $.ajax({
                                url: '/right/del',
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
                    form.on('submit(nav-save-form)', function (data) {
                        $.ajax({
                            url: '/right/save',
                            method: 'post',
                            data: data.field,
                            dataType: 'json',
                            success: res => {
                                if (res.success) {
                                    layer.msg('保存成功', {icon: 1})
                                    layer.close(navIdx)
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
                        url: '/right/list',
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
                                title: '名称',
                            },
                            {
                                field: 'icon',
                                title: '图标',
                                templet: function (data) {
                                    return data['icon'] ? `<i class="fa ${data['icon']}"></i>` : ''
                                }
                            },
                            {
                                field: 'type',
                                title: '类型',
                                templet: function (data) {
                                    return data['type'] == <?php echo RightExtend::TYPE_LINK?> ? '链接' : 'js脚本'
                                }
                            },
                            {
                                field: 'link',
                                title: '跳转地址',
                                templet: function (data) {
                                    return `<a href="${data['link']}" target="_blank">${data['link']}</a>`
                                }
                            },
                            {
                                field: 'script',
                                title: 'js脚本',
                            },
                            {
                                field: 'ordered',
                                title: '排序',
                                edit: 'text'
                            },
                            {
                                field: 'status',
                                title: '状态',
                                templet: function (data) {
                                    return data['status'] == <?php echo RightExtend::STATUS_ENABLE?> ? '启用' : '禁用'
                                }
                            },
                            {
                                title: '操作',
                                width: 200,
                                templet: function (data) {
                                    let btn = `<a data-index="${data.LAY_TABLE_INDEX}" class="fw-nav-edit-btn layui-btn layui-btn-xs">编辑</a> <a data-id="${data['id']}" class="fw-nav-del-btn layui-btn layui-btn-danger layui-btn-xs">删除</a>`
                                    if (data['status'] == <?php echo RightExtend::STATUS_ENABLE?> ) {
                                        btn = `<a class="fw-nav-status-btn layui-btn layui-btn-warm layui-btn-xs" data-index="${data.LAY_TABLE_INDEX}" data-value="<?php echo RightExtend::STATUS_DISABLE?>" lay-event="detail">禁用</a>` + btn;
                                    } else {
                                        btn = `<a class="fw-nav-status-btn layui-btn layui-btn-normal layui-btn-xs" data-index="${data.LAY_TABLE_INDEX}" data-value="<?php echo RightExtend::STATUS_ENABLE?>" lay-event="detail">启用</a>` + btn;
                                    }
                                    return btn;
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