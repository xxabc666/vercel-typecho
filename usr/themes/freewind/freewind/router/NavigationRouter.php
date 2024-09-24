<?php
/**
 * Author: Mr丶冷文
 * Date: 2022/10/18 01:30
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description:
 */

namespace Freewind\Router;

use Freewind\Core\FreewindHelper;
use Freewind\Core\Result;
use Freewind\Extend\IExtend;
use Freewind\Extend\NavigationExtend;
use Typecho\Widget\Request;
use Utils\Helper;

class NavigationRouter extends IExtendRouter
{
    protected $table = NavigationExtend::TABLE_NAME;

    protected $ordered = "ordered";

    protected $fieldList = NavigationExtend::FIELD_LIST;

    function lstCache(): array
    {
        $caches = [];
        $caches[] = NavigationExtend::CACHE_PREFIX . IExtend::arr2str(['status' => NavigationExtend::STATUS_ENABLE, 'type' => NavigationExtend::TYPE_NAVIGATION]) . $this->ordered;
        $caches[] = NavigationExtend::CACHE_PREFIX . IExtend::arr2str(['status' => NavigationExtend::STATUS_ENABLE, 'type' => NavigationExtend::TYPE_PAGE]) . $this->ordered;
        return $caches;
    }

    function verification($item)
    {
        if (!$item['name']) {
            Result::error("名称不能为空");
        }
    }

    function condation(Request $request): array
    {
        $type = $request->get('type');
        return ['type' => $type];
    }

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
            <link rel="stylesheet"
                  href="<?php echo FreewindHelper::freeCdn('plugin/font-awesome/css/font-awesome.min.css') ?>">
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
            <p style="padding: 10px;background-color: #F5F5F5; color: #797979; font-size: 12px; border-radius: 5px;margin-top: 10px;">
                <b>说明：</b> 导航就是左侧导航部分，页面是在组成里面，导航是支持图标的，而页面不支持，图标用的是
                <a href="https://www.thinkcmf.com/font_awesome.html" target="_blank">
                    Font Awesome 4.7
                </a>
                ，格式为<code>fa-名称</code>,直接过来拷贝就行
            </p>
            <div style="margin-top: 20px;">
                <div class="layui-btn-group">
                    <button class="layui-btn layui-btn-primary layui-btn-sm" id="fw-nav-add-btn">
                        <i class="layui-icon layui-icon-addition"></i>新增
                    </button>
                    <button class="layui-btn layui-btn-primary layui-btn-sm" id="fw-nav-order-btn">
                        <i class="layui-icon layui-icon-refresh-3"></i>保存排序
                    </button>
                </div>
                <div class="layui-btn-group" id="fw-type-btn-group" style="float:right;">
                    <button data-type="" class="layui-btn layui-btn-normal layui-btn-sm">
                        全部
                    </button>
                    <button data-type="<?php echo NavigationExtend::TYPE_NAVIGATION ?>"
                            class="layui-btn layui-btn-primary layui-btn-sm">
                        导航
                    </button>
                    <button data-type="<?php echo NavigationExtend::TYPE_PAGE ?>"
                            class="layui-btn layui-btn-primary layui-btn-sm">
                        页面
                    </button>
                </div>
            </div>
            <table id="nav-table" lay-filter="nav-table" lay-filter="nav-table"></table>
        </div>
        <script type="text/html" id="fw-nav-save">
            <form class="layui-form" id="nav-save-form" lay-filter="nav-save-form">
                <input type="hidden" name="id">
                <div class="layui-form-item" style="margin-top: 10px;">
                    <label class="layui-form-label">标题</label>
                    <div class="layui-input-inline">
                        <input type="text" name="name" required lay-verify="required" placeholder="请输入标题"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">图标</label>
                    <div class="layui-input-inline">
                        <input type="text" name="icon" placeholder="请输入图标"
                               autocomplete="off" class="layui-input">
                        <div class="layui-form-mid layui-word-aux">
                            <a href="https://www.thinkcmf.com/font_awesome.html" target="_blank">
                                Font Awesome 4.7
                            </a>图标，仅在类型为导航时生效
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">跳转地址</label>
                    <div class="layui-input-inline">
                        <input type="text" name="url" placeholder="请输入跳转地址"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">类型</label>
                    <div class="layui-input-inline">
                        <select name="type" lay-verify="required">
                            <option value="<?php echo NavigationExtend::TYPE_PAGE ?>">页面</option>
                            <option value="<?php echo NavigationExtend::TYPE_NAVIGATION ?>">导航</option>
                        </select>
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
                    <label class="layui-form-label">打开方式</label>
                    <div class="layui-input-inline">
                        <select name="target" lay-verify="required">
                            <option value="<?php echo NavigationExtend::TARGET_CURRENT ?>">当前页</option>
                            <option value="<?php echo NavigationExtend::TARGET_NEW ?>">新标签页</option>
                        </select>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">是否启用</label>
                    <div class="layui-input-inline">
                        <select name="status" lay-verify="required">
                            <option value="<?php echo NavigationExtend::STATUS_ENABLE ?>">启用</option>
                            <option value="<?php echo NavigationExtend::STATUS_DISABLE ?>">禁用</option>
                        </select>
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
                    $('#fw-nav-order-btn').on('click', function () {
                        let data = ""
                        table.cache['nav-table'].forEach(item => data += (item['id'] + '----' + item['ordered'] + ';'))
                        $.ajax({
                            url: '/navigation/order',
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
                            area: ['400px', '550px'], //宽高
                            content: $('#fw-nav-save').html(),
                        });
                        form.render('select');
                    })
                    $('#fw-content').on('click', '.fw-nav-status-btn', function () {
                        let idx = $(this).data('index');
                        table.cache['nav-table'][idx]['status'] = $(this).data('value');
                        $.ajax({
                            url: '/navigation/save',
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
                            area: ['400px', '550px'], //宽高
                            content: $('#fw-nav-save').html(),
                        });
                        form.render('select');
                        form.val('nav-save-form', data)
                    }).on('click', '.fw-nav-del-btn', function () {
                        let id = $(this).data('id')
                        layer.confirm("你确认要删除当前行数据？", {btn: ['确认', '取消']}, function (idx) {
                            layer.close(idx)
                            $.ajax({
                                url: '/navigation/del',
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
                            url: '/navigation/save',
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
                        url: '/navigation/list',
                        page: true,
                        request: {
                            pageName: 'pageNum',
                            limitName: 'pageSize',
                        },
                        where: {
                            'type': $('#fw-type-btn-group button.layui-btn-normal').data('type')
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
                                    return data['type'] == <?php echo NavigationExtend::TYPE_PAGE?> ? '页面' : '导航'
                                }
                            },
                            {
                                field: 'ordered',
                                title: '排序',
                                edit: 'text'
                            },
                            {
                                field: 'url',
                                title: '跳转地址',
                            },
                            {
                                field: 'target',
                                title: '打开方式',
                                templet: function (data) {
                                    return data['target'] == <?php echo NavigationExtend::TARGET_CURRENT?> ? '当前页' : '新页面'
                                }
                            },
                            {
                                field: 'status',
                                title: '状态',
                                templet: function (data) {
                                    return data['status'] == <?php echo NavigationExtend::STATUS_ENABLE?> ? '启用' : '禁用'
                                }
                            },
                            {
                                title: '操作',
                                width: 200,
                                templet: function (data) {
                                    let btn = `<a data-index="${data.LAY_TABLE_INDEX}" class="fw-nav-edit-btn layui-btn layui-btn-xs">编辑</a> <a data-id="${data['id']}" class="fw-nav-del-btn layui-btn layui-btn-danger layui-btn-xs">删除</a>`
                                    if (data['status'] == <?php echo NavigationExtend::STATUS_ENABLE?> ) {
                                        btn = `<a class="fw-nav-status-btn layui-btn layui-btn-warm layui-btn-xs" data-index="${data.LAY_TABLE_INDEX}" data-value="<?php echo NavigationExtend::STATUS_DISABLE?>" lay-event="detail">禁用</a>` + btn;
                                    } else {
                                        btn = `<a class="fw-nav-status-btn layui-btn layui-btn-normal layui-btn-xs" data-index="${data.LAY_TABLE_INDEX}" data-value="<?php echo NavigationExtend::STATUS_ENABLE?>" lay-event="detail">启用</a>` + btn;
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