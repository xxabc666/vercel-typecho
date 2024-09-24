<?php
/**
 * Author: Mr丶冷文
 * Date: 2022/10/18 17:06
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description:
 */

namespace Freewind\Router;

use Freewind\Core\FreewindHelper;
use Freewind\Core\Result;
use Freewind\Extend\BannerExtend;
use Freewind\Extend\IExtend;
use Typecho\Widget\Request;
use Utils\Helper;

class BannerRouter extends IExtendRouter
{

    protected $table = BannerExtend::TABLE_NAME;

    protected $ordered = "ordered";

    protected $fieldList = BannerExtend::FIELD_LIST;

    function lstCache(): array
    {
        $result = [];
        $result[] = BannerExtend::CACHE_PREFIX . IExtend::arr2str(['status' => BannerExtend::STATUS_ENABLE]) . $this->ordered;
        return $result;
    }

    public function verification($item)
    {
        if (!$item['title']) {
            Result::error("标题不能为空");
        }
        if (!$item['cover']) {
            Result::error("图片不能为空");
        }
    }


    public function condation(Request $request): array
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

                td .layui-table-cell {
                    height: 50px;
                }

                .red-tips {
                    color: #FE445C;
                }
            </style>
        </head>
        <body>
        <div id="fw-content">
            <div style="margin-top: 20px;">
                <div class="layui-btn-group">
                    <button class="layui-btn layui-btn-primary layui-btn-sm" id="fw-add-btn">
                        <i class="layui-icon layui-icon-addition"></i>新增
                    </button>
                    <button class="layui-btn layui-btn-primary layui-btn-sm" id="fw-nav-order-btn">
                        <i class="layui-icon layui-icon-refresh-3"></i>保存排序
                    </button>
                </div>
            </div>
            <table id="nav-table" lay-filter="nav-table" lay-filter="nav-table"></table>
        </div>
        <script type="text/html" id="fw-save-form-content">
            <form class="layui-form" id="fw-save-form" lay-filter="fw-save-form">
                <input type="hidden" name="id">
                <div class="layui-form-item" style="margin-top: 10px;">
                    <label class="layui-form-label">标题</label>
                    <div class="layui-input-inline">
                        <input type="text" name="title" required lay-verify="required" placeholder="请输入标题"
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
                    <label class="layui-form-label">跳转地址</label>
                    <div class="layui-input-inline">
                        <input type="text" name="url" placeholder="请输入跳转地址" required lay-verify="required"
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
                    <label class="layui-form-label">图片</label>
                    <div class="layui-input-inline">
                        <input type="text" name="cover"
                               placeholder="请输入背景图片URL"
                               autocomplete="off"
                               required lay-verify="required"
                               class="layui-input">
                        <div class="layui-form-mid layui-word-aux">
                            <img style="max-width: 100%" src="" alt="" id="fw-banner-show">
                        </div>
                    </div>
                    <div class="layui-input-inline">
                        <button type="button" class="layui-btn layui-btn-normal layui-btn-sm" id="fw-upload-banner">
                            <i class="layui-icon">&#xe67c;</i>上传图片
                        </button>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">是否启用</label>
                    <div class="layui-input-inline">
                        <select name="status" lay-verify="required">
                            <option value="<?php echo BannerExtend::STATUS_ENABLE ?>">启用</option>
                            <option value="<?php echo BannerExtend::STATUS_DISABLE ?>">禁用</option>
                        </select>
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
                layui.use(['form', 'table', 'upload'], function () {
                    const form = layui.form;
                    const table = layui.table;
                    const upload = layui.upload;
                    let saveIdx = 0;
                    $('#fw-nav-order-btn').on('click', function () {
                        let data = ""
                        table.cache['nav-table'].forEach(item => data += (item['id'] + '----' + item['ordered'] + ';'))
                        $.ajax({
                            url: '/banner/order',
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
                    $('#fw-add-btn').on('click', function () {
                        saveIdx = layer.open({
                            type: 1,
                            shadeClose: true,
                            skin: 'layui-layer-rim', //加上边框
                            area: ['600px', '700px'], //宽高
                            content: $('#fw-save-form-content').html(),
                        });
                        form.render('select');
                        upload.render({
                            elem: '#fw-upload-banner',
                            url: '/freewind/upload',
                            done: res => {
                                if (res.success) {
                                    layer.msg('上传成功', {icon: 1})
                                    $('#fw-banner-show').attr('src', res.data)
                                    $('#fw-save-form [name=cover]').val(res.data)
                                } else {
                                    layer.msg(res.msg, {icon: 2})
                                }

                            },
                        })
                    })
                    $('#fw-content').on('click', '.fw-nav-status-btn', function () {
                        let idx = $(this).data('index');
                        table.cache['nav-table'][idx]['status'] = $(this).data('value');
                        $.ajax({
                            url: '/banner/save',
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
                        saveIdx = layer.open({
                            type: 1,
                            shadeClose: true,
                            skin: 'layui-layer-rim', //加上边框
                            area: ['600px', '700px'], //宽高
                            content: $('#fw-save-form-content').html(),
                        });
                        form.render('select');
                        form.val('fw-save-form', data)
                        $('#fw-banner-show').attr('src', data['cover'])
                        upload.render({
                            elem: '#fw-upload-banner',
                            url: '/freewind/upload',
                            done: res => {
                                if (res.success) {
                                    layer.msg('上传成功', {icon: 1})
                                    $('#fw-banner-show').attr('src', res.data)
                                    $('#fw-save-form [name=cover]').val(res.data)
                                } else {
                                    layer.msg(res.msg, {icon: 2})
                                }

                            },
                        })
                    }).on('click', '.fw-nav-del-btn', function () {
                        let id = $(this).data('id')
                        layer.confirm("你确认要删除当前行数据？", {btn: ['确认', '取消']}, function (idx) {
                            layer.close(idx)
                            $.ajax({
                                url: '/banner/del',
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
                            url: '/banner/save',
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
                        url: '/banner/list',
                        page: true,
                        limit: 5,
                        limits: [5, 10, 20],
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
                                field: 'title',
                                title: '名称',
                            },
                            {
                                field: 'cover',
                                title: '图片',
                                templet: function (data) {
                                    return `<img style="max-width: 100px" src="${data['cover']}">`
                                }
                            },
                            {
                                field: 'desc',
                                title: '描述',
                            },
                            {
                                field: 'url',
                                title: '跳转地址',
                                templet: function (data) {
                                    return `<a href="${data['url']}" target="_blank">${data['url']}</a>`
                                }
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
                                    return data['status'] == <?php echo BannerExtend::STATUS_ENABLE?> ? '启用' : '禁用'
                                }
                            },
                            {
                                title: '操作',
                                width: 200,
                                templet: function (data) {
                                    let btn = `<a data-index="${data.LAY_TABLE_INDEX}" class="fw-nav-edit-btn layui-btn layui-btn-xs">编辑</a> <a data-id="${data['id']}" class="fw-nav-del-btn layui-btn layui-btn-danger layui-btn-xs">删除</a>`
                                    if (data['status'] == <?php echo BannerExtend::STATUS_ENABLE?> ) {
                                        btn = `<a class="fw-nav-status-btn layui-btn layui-btn-warm layui-btn-xs" data-index="${data.LAY_TABLE_INDEX}" data-value="<?php echo BannerExtend::STATUS_DISABLE?>" lay-event="detail">禁用</a>` + btn;
                                    } else {
                                        btn = `<a class="fw-nav-status-btn layui-btn layui-btn-normal layui-btn-xs" data-index="${data.LAY_TABLE_INDEX}" data-value="<?php echo BannerExtend::STATUS_ENABLE?>" lay-event="detail">启用</a>` + btn;
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