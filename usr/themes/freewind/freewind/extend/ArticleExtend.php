<?php
/**
 * Author: Mr丶冷文
 * Date: 2022/10/20 12:47
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description:
 */

namespace Freewind\Extend;

use Freewind\Core\CookieHelper;
use Freewind\Core\FreewindHelper;
use Freewind\Router\FileRouter;
use Typecho\Widget\Helper\Form\Element\Hidden;
use Typecho\Widget\Helper\Layout;

class ArticleExtend
{

    //文章类型
    const POST_TYPE_ARTICLE = 1;
    const POST_TYPE_MOMENTS = 2;
    const POST_TYPE_PICTURE = 3;

    const NAME_POST_TYPE = 'postType';

    //SEO
    const NAME_SEO_KEYWORDS = 'postKeywords';
    const NAME_SEO_DESC = 'postDesc';

    // 列表图片
    const NAME_LIST_COVER = 'postShowImg';
    //朋友圈图片
    const NAME_MOMENTS_PICURES = 'postShuoPic';

    // 朋友圈音乐
    const NAME_MOMENTS_MUSIC_PROVIDE = 'postShuoMP';
    const MUSIC_PROVIDE_NETEASE = 'netease';
    const MUSIC_PROVIDE_TENCENT = 'tencent';
    const NAME_MOMENTS_MUSIC_TYPE = 'postShuoMT';
    const MUSIC_TYPE_PLAYLIST = 'playlist';
    const MUSIC_TYPE_SONG = 'song';
    const NAME_MOMENTS_MUSIC_ID = 'postShuoMusic';

    //朋友圈分享B站
    const NAME_MOMENTS_BVID = "postShuoBvid";
    const NAME_MOMENTS_BVNUM = "postShuoPage";

    //文件权限
    const NAME_FILE_TYPE = 'postFileType';
    const NAME_FILE_LIST = 'postFileList';

    const FILE_POWER_DISABLE = 1;
    const FILE_POWER_ENABLE = 2;
    const FILE_POWER_COMMENT = 3;
    const FILE_POWER_LOGIN = 4;
    const FILE_POWER_COMMENT_LOGIN = 5;
    const File_POWER_LIST = [
        self::FILE_POWER_DISABLE => '关闭',
        self::FILE_POWER_ENABLE => '开启',
        self::FILE_POWER_COMMENT => '回复可见',
        self::FILE_POWER_LOGIN => '登录可见',
        self::FILE_POWER_COMMENT_LOGIN => '登录且回复可见',
    ];


    static function fieldList(Layout $layout)
    {
        //文章类型
        self::addHidden($layout, self::NAME_POST_TYPE);
        //文章
        self::addHidden($layout, self::NAME_FILE_TYPE);
        self::addHidden($layout, self::NAME_FILE_LIST);
        self::addHidden($layout, self::NAME_SEO_KEYWORDS);
        self::addHidden($layout, self::NAME_SEO_DESC);
        self::addHidden($layout, self::NAME_LIST_COVER);

        //朋友圈
        self::addHidden($layout, self::NAME_MOMENTS_BVNUM);
        self::addHidden($layout, self::NAME_MOMENTS_BVID);
        self::addHidden($layout, self::NAME_MOMENTS_PICURES);
        self::addHidden($layout, self::NAME_MOMENTS_MUSIC_PROVIDE);
        self::addHidden($layout, self::NAME_MOMENTS_MUSIC_TYPE);
        self::addHidden($layout, self::NAME_MOMENTS_MUSIC_ID);
    }

    static function addHidden(Layout $layout, $name)
    {
        $layout->addItem(new Hidden($name, null, null, '', ''));
    }

    static function script()
    {
        ?>
        <script type="text/html" id="fw-custom-html">
            <div id="fw-article-custom">
                <ul id="fw-article-type-bar">
                    <li data-target="<?php echo self::POST_TYPE_ARTICLE ?>" class="current">文章</li>
                    <li data-target="<?php echo self::POST_TYPE_MOMENTS ?>">朋友圈</li>
                    <li data-target="<?php echo self::POST_TYPE_PICTURE ?>">相册</li>
                </ul>
                <?php if (!CookieHelper::get(CookieHelper::COOKIE_POST_TIPS)): ?>
                    <div id="fw-type-tips"><b style="margin-right: 10px;">tips:</b>选中文章类型发布后就不可再更改 <a
                                href="javascript:void(0)"><i class="fa fa-close"></i></a></div>
                <?php endif; ?>
                <div id="fw-custom-<?php echo self::POST_TYPE_ARTICLE ?>" class="fw-custom-show fw-custom-content">
                    <div id="fw-article-fields">
                        <div class="fw-form-item row">
                            <div class="col-tb-3">
                                <label for="fw-seo-keywords">关键字(SEO优化)</label>
                            </div>
                            <div class="col-tb-9">
                                <input type="text" id="fw-seo-keywords" placeholder="多个关键字用 , 隔开">
                            </div>
                        </div>
                        <div class="fw-form-item row">
                            <div class="col-tb-3">
                                <label for="fw-seo-desc">描述信息(SEO优化)</label>
                            </div>
                            <div class="col-tb-9">
                                <textarea id="fw-seo-desc" placeholder="请输入描述信息"></textarea>
                            </div>
                        </div>
                        <div class="fw-form-item row">
                            <div class="col-tb-3">
                                <label for="fw-seo-desc">列表展示大图</label>
                            </div>
                            <div class="col-tb-9" style="position:relative;">
                                <input type="text" id="fw-show-path"
                                       placeholder="图片外链外地，也可以使用右边的上传按钮上传">
                                <button id="fw-show-upload-btn" type="button"><i class="fa fa-upload"></i> 上传展示图
                                </button>
                            </div>
                            <div class="col-tb-3">
                                <label for="fw-seo-desc"></label>
                            </div>
                            <div class="col-tb-9">
                                <img src="" id="fw-show-img" alt="" style="max-width: 100%;">
                            </div>
                        </div>
                    </div>
                </div>
                <div id="fw-custom-<?php echo self::POST_TYPE_MOMENTS ?>" class="fw-custom-content">
                    <div id="fw-moments-text"></div>
                    <div id="fw-moments-picture" class="fw-picture-upload">
                        <input type="file" accept="image/jpg,image/jpeg,image/png,image/gif,image/bmp"
                               id="fw-upload-file" style="display: none">
                        <ul>
                            <li id="fw-moments-upload" class="fw-upload-li">
                                <img src="<?php echo FreewindHelper::freeCdn('image/add.png') ?>" alt="">
                            </li>
                        </ul>
                    </div>
                    <ul id="fw-moments-other">
                        <li id="fw-moments-music">分享音乐<i class="fa fa-angle-right"></i><span></span></li>
                        <li id="fw-moments-bili">B站视频<i class="fa fa-angle-right"></i><span></span></li>
                    </ul>
                </div>
                <div id="fw-custom-<?php echo self::POST_TYPE_PICTURE ?>" class="fw-custom-content">
                    <div id="fw-photo-picture" class="fw-picture-upload">
                        <input type="file" accept="image/jpg,image/jpeg,image/png,image/gif,image/bmp"
                               id="fw-upload-file" style="display: none">
                        <ul>
                            <li id="fw-picture-upload" class="fw-upload-li">
                                <img src="<?php echo FreewindHelper::freeCdn('image/add.png') ?>" alt="">
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </script>
        <script type="text/html" id="fw-music-box">
            <div id="fw-music-content" class="fw-form-box">
                <div class="fw-form-item">
                    <label for="fw-music-product">音乐提供方:</label>
                    <select id="fw-music-product">
                        <option value="<?php echo self::MUSIC_PROVIDE_NETEASE ?>">网易云</option>
                        <option value="<?php echo self::MUSIC_PROVIDE_TENCENT ?>">QQ音乐</option>
                    </select>
                </div>
                <div class="fw-form-item">
                    <label for="fw-music-type">音乐类型:</label>
                    <select id="fw-music-type">
                        <option value="<?php echo self::MUSIC_TYPE_SONG ?>">单曲</option>
                        <option value="<?php echo self::MUSIC_TYPE_PLAYLIST ?>">歌单</option>
                    </select>
                </div>
                <div class="fw-form-item">
                    <label for="fw-music-id">音乐/歌单ID:</label>
                    <input type="text" id="fw-music-id">
                </div>
                <div class="fw-form-item">
                    <label></label>
                    <button type="button" id="fw-music-right">确认分享</button>
                </div>
            </div>
        </script>
        <script type="text/html" id="fw-bilibili-box">
            <div id="fw-music-content" class="fw-form-box">
                <div class="fw-form-item">
                    <label for="fw-bili-id">视频的BvId:</label>
                    <input type="text" id="fw-bili-id">
                </div>
                <div class="fw-form-item">
                    <label for="fw-bili-num">视频的剧集数:</label>
                    <input type="text" value="1" id="fw-bili-num">
                </div>
                <div class="fw-form-item">
                    <label></label>
                    <button type="button" id="fw-bili-right">确认分享</button>
                </div>
            </div>
        </script>
        <script type="text/html" id="fw-upload-box">
            <div id="fw-upload-content">
                <span>图片外链</span>
                <input type="text" id="fw-file-path" placeholder="请输入外链或点击右边的上传按钮">
                <button type="button" id="fw-upload-btn"><i class="fa fa-upload"></i> 本地上传</button>
                <p>
                    <button id="fw-right-upload" type="button">确认</button>
                </p>
            </div>
        </script>
        <script type="text/html" id="fw-file-list-box">
            <div id="fw-file-list-content">
                <?php $fileList = FileExtend::lst(FileExtend::TABLE_NAME, FileExtend::CACHE_PREFIX, ['status' => FileExtend::STATUS_ENABLE], 'ordered') ?>
                <?php foreach ($fileList as $fileItem): ?>
                    <div class="fw-file-item" data-name="<?php echo $fileItem['name'] ?>"
                         data-icon="<?php echo $fileItem['picture'] ?>">
                        <label for=""><?php echo $fileItem['name'] ?>:</label>
                        <input class="fw-file-url" type="text"
                               placeholder="请输入<?php echo $fileItem['name'] ?>的链接"><br>
                        <?php if ($fileItem['passwd'] == FileExtend::PASSWD_SUPPORT): ?>
                            <label></label>
                            <input class="fw-file-pwd" type="text"
                                   placeholder="请输入<?php echo $fileItem['name'] ?>的提取密码">
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
                <div class="fw-file-item">
                    <label>附件权限</label>
                    <select id="fw-file-power">
                        <?php foreach (self::File_POWER_LIST as $value => $name): ?>
                            <option value="<?php echo $value ?>"><?php echo $name ?></option>
                        <?php endforeach; ?>
                    </select>

                </div>
                <div class="fw-file-item">
                    <label></label>
                    <button id="fw-save-file-btn" class="btn primary">保存附件</button>
                </div>
            </div>
        </script>
        <script src="<?php echo FreewindHelper::freeCdn('plugin/layer-v3.5.1/layer/layer.js') ?>"></script>
        <script src="<?php echo FreewindHelper::freeCdn('plugin/wangEdit/wangEditor.min.js') ?>"></script>
        <script>
            $(function () {
                if (window['postwrite']) return;
                window['postwrite'] = true;
                $('#custom-field').before($('#fw-custom-html').html())
                $('form[name=write_post] .typecho-list-table input[type=hidden]').each((index, element) => {
                    $(element).parent().parent().parent().hide()
                })

                $('#fw-show-upload-btn').on('click', function () {

                    $('#fw-upload-file').trigger('click')
                })

                $('#advance-panel').append(`<sction class="typecho-post-option"><label for="trackback" class="typecho-label">文章置顶</label></sction>`)

                <?php if (count($fileList)): ?>
                $('#upload-panel').after(`<button style="width: 100%;" id="fw-file-list-btn" type="button" class="btn btn-warn">Freewind主题外链附件</button>`)
                let fileInfo = []
                $('#fw-file-list-btn').on('click', function () {
                    let idx = layer.open({
                        type: 1,
                        title: '外链附件',
                        skin: 'layui-layer-demo', //样式类名
                        anim: 2,
                        shadeClose: true, //开启遮罩关闭
                        content: $('#fw-file-list-box').html()
                    })
                    fileInfo.forEach(item => {
                        let element = $(`#fw-file-list-content .fw-file-item[data-name=${item.name}]`)
                        if (element.length === 0) {
                            let html = `<div class="fw-file-item" data-name="${item['name']}"
                         data-icon="${item['icon']}">
                        <label for="">${item['name']}:</label>
                        <input class="fw-file-url" type="text" value="${item['url']}"
                               placeholder="请输入${item['name']}的链接"><br>`
                            if (item['pwd']) {
                                html += `<label></label><input value="${item['pwd']}" class="fw-file-pwd" type="text" placeholder="请输入${item['name']}的提取密码">`
                            }
                            html += '</div>'
                            $('#fw-file-power').parent().before(html)
                        } else {
                            element.find('.fw-file-url').val(item['url'])
                            element.find('.fw-file-pwd').val(item['pwd'])
                        }
                    })
                    $('#fw-file-power').val(typechoField('<?php echo self::NAME_FILE_TYPE?>').val())
                    $('#fw-save-file-btn').on('click', function () {
                        layer.close(idx)
                        fileInfo = []
                        $('#fw-file-list-content .fw-file-item').each((index, element) => {
                            if ($(element).data('name')) {
                                let fileItem = {}
                                fileItem['name'] = $(element).data('name')
                                fileItem['icon'] = $(element).data('icon')
                                fileItem['url'] = $(element).find('.fw-file-url').val()
                                fileItem['pwd'] = $(element).find('.fw-file-pwd').val()
                                fileInfo.push(fileItem)
                            }
                        })
                        typechoField('<?php echo self::NAME_FILE_TYPE?>').val($('#fw-file-power').val())
                    })
                })
                <?php endif; ?>

                let content = $('form[name=write_post] textarea[name=text]');

                $('#fw-article-type-bar li').on('click', function () {
                    $('#fw-article-type-bar li.current').removeClass('current')
                    $(this).addClass('current');
                    let type = typechoField('<?php echo self::NAME_POST_TYPE?>')
                    let target = $(this).data('target')
                    type.val(target)
                    $('#fw-article-custom .fw-custom-show').removeClass('fw-custom-show');
                    $(`#fw-custom-${target}`).addClass('fw-custom-show');
                    if (target == <?php echo self::POST_TYPE_MOMENTS?>) {
                        $('#edit-secondary').hide()
                    } else {
                        $('#edit-secondary').show()
                    }
                })


                $('#fw-custom-<?php echo self::POST_TYPE_ARTICLE ?>').prepend($('#wmd-preview').next())
                $('#fw-custom-<?php echo self::POST_TYPE_ARTICLE ?>').prepend($('div#wmd-preview'))
                $('#fw-custom-<?php echo self::POST_TYPE_ARTICLE ?>').prepend($('#wmd-button-bar'))

                $('#fw-type-tips a').on('click', function () {
                    $.get('/close/postips', function (res) {
                            $('#fw-type-tips').remove()
                        }
                    )
                })

                let momentsPicList = {};
                let pictureList = {};
                let momentsMusic = {};
                let momentsBiliBili = {};

                pictureList.add = function (path) {
                    let idx = Object.keys(pictureList).length
                    pictureList['fwpic' + idx] = path;
                    $('#fw-picture-upload').before(`<li><a data-idx="${idx}" href="javascript:void(0);"><i class="fa fa-close"></i></a><img src="${path}"></li>`)
                }

                pictureList.remove = function (idx) {
                    delete pictureList['fwpic' + idx]
                    $(`#fw-photo-picture a[data-idx=${idx}]`).parent().remove()
                }
                pictureList.toList = function () {
                    let result = []
                    Object.keys(pictureList).forEach(key => {
                        if (key.indexOf('fwpic') > -1) {
                            result.push(pictureList[key])
                        }
                    })
                    return result;
                }

                pictureList.picSize = function () {
                    return pictureList.toList().length;
                }

                momentsPicList.add = function (path) {
                    let idx = Object.keys(momentsPicList).length
                    momentsPicList['fwpic' + idx] = path;
                    $('#fw-moments-upload').before(`<li><a data-idx="${idx}" href="javascript:void(0);"><i class="fa fa-close"></i></a><img src="${path}"></li>`)
                }

                momentsPicList.remove = function (idx) {
                    delete momentsPicList['fwpic' + idx]
                    $(`#fw-moments-picture a[data-idx=${idx}]`).parent().remove()
                }
                momentsPicList.toList = function () {
                    let result = []
                    Object.keys(momentsPicList).forEach(key => {
                        if (key.indexOf('fwpic') > -1) {
                            result.push(momentsPicList[key])
                        }
                    })
                    return result;
                }

                momentsPicList.picSize = function () {
                    return momentsPicList.toList().length;
                }

                $('#fw-photo-picture').on('click', 'a', function () {
                    pictureList.remove($(this).data('idx'))
                })


                $('#fw-moments-picture').on('click', 'a', function () {
                    momentsPicList.remove($(this).data('idx'))
                })

                $('#fw-moments-music').on('click', function () {
                    let idx = layer.open({
                        type: 1,
                        title: '选择音乐',
                        skin: 'layui-layer-demo', //样式类名
                        anim: 2,
                        shadeClose: true, //开启遮罩关闭
                        content: $('#fw-music-box').html()
                    })
                    $('#fw-music-right').on('click', function () {
                        momentsMusic['<?php echo self::NAME_MOMENTS_MUSIC_ID?>'] = $('#fw-music-id').val()
                        if (!momentsMusic['<?php echo self::NAME_MOMENTS_MUSIC_ID?>']) {
                            layer.msg('歌曲/歌单ID不能为空', {icon: 2})
                            return;
                        }
                        momentsMusic['<?php echo self::NAME_MOMENTS_MUSIC_TYPE?>'] = $('#fw-music-type').val()
                        momentsMusic['<?php echo self::NAME_MOMENTS_MUSIC_PROVIDE?>'] = $('#fw-music-product').val()
                        layer.close(idx)
                        $('#fw-moments-music span').html(`分享
                            ${momentsMusic['<?php echo self::NAME_MOMENTS_MUSIC_PROVIDE?>'] == '<?php echo self::MUSIC_PROVIDE_NETEASE?>' ? '网易云' : 'QQ音乐'}
                            ${momentsMusic['<?php echo self::NAME_MOMENTS_MUSIC_TYPE?>'] == '<?php echo self::MUSIC_TYPE_PLAYLIST?>' ? '歌单' : '单曲'}ID为
                            ${momentsMusic['<?php echo self::NAME_MOMENTS_MUSIC_ID?>']}
                            <a href="javascript:void(0)"><i class="fa fa-close"></i></a>
                        `)
                    })
                }).on('click', 'a', function () {
                    momentsMusic = {}
                    $('#fw-moments-music span').html('')
                    return false;
                })

                $('#fw-moments-bili').on('click', function () {
                    let idx = layer.open({
                        type: 1,
                        title: '选择B站视频',
                        skin: 'layui-layer-demo', //样式类名
                        anim: 2,
                        shadeClose: true, //开启遮罩关闭
                        content: $('#fw-bilibili-box').html()
                    })
                    $('#fw-bili-right').on('click', function () {
                        momentsBiliBili['<?php echo self::NAME_MOMENTS_BVID?>'] = $('#fw-bili-id').val()
                        if (!momentsBiliBili['<?php echo self::NAME_MOMENTS_BVID?>']) {
                            layer.msg('B站视频的BvId不能为空', {icon: 2})
                            return;
                        }
                        momentsBiliBili['<?php echo self::NAME_MOMENTS_BVNUM?>'] = $('#fw-bili-num').val()
                        layer.close(idx)
                        $('#fw-moments-bili span').html(`分享B站视频
                            BVID为${momentsBiliBili['<?php echo self::NAME_MOMENTS_BVID?>']}
                            剧集数为${momentsBiliBili['<?php echo self::NAME_MOMENTS_BVNUM?>']}
                            <a href="javascript:void(0)"><i class="fa fa-close"></i></a>
                        `)
                    })
                }).on('click', 'a', function () {
                    momentsBiliBili = {}
                    $('#fw-moments-bili span').html('')
                    return false;
                })
                let editor = new window.wangEditor('#fw-moments-text')
                let emotions = [];
                let expression = [];
                $.ajax({
                    url: '<?php echo FreewindHelper::freeCdn("json/emotions.json")?>',
                    async: false,
                    success: res => {
                        for (let i = 0; i < res.length; i++) {
                            emotions.push({
                                src: '<?php echo FreewindHelper::freeCdn()?>' + res[i]['src'],
                                alt: res[i]['alt']
                            })
                        }
                    }
                })
                $.ajax({
                    url: '<?php echo FreewindHelper::freeCdn("json/expression.json")?>',
                    async: false,
                    success: res => {
                        for (let i = 0; i < res.length; i++) {
                            expression.push({
                                src: '<?php echo FreewindHelper::freeCdn()?>' + res[i]['src'],
                                alt: res[i]['alt']
                            })
                        }
                    }
                })
                editor.config.onchange = function (html) {
                    $('form[name=write_post] textarea[name=text]').val(html)
                }
                editor.config.menus = [
                    'foreColor',
                    'emoticon'
                ]
                editor.config.emotions = [
                    {
                        title: 'QQ表情',
                        type: 'image',
                        content: emotions
                    }, {
                        title: '其它表情',
                        type: 'image',
                        content: expression
                    }
                ]
                editor.create()


                function typechoField(name) {
                    return $('form[name=write_post] [name=fields\\[' + name + '\\]]')
                }

                $('form[name=write_post]').on('submit', function () {
                    if (typechoField('<?php echo self::NAME_POST_TYPE?>').val() == <?php echo self::POST_TYPE_MOMENTS?>) {
                        if (momentsPicList.picSize() > 0) {
                            typechoField('<?php echo self::NAME_MOMENTS_PICURES?>').val(momentsPicList.toList().join('\n'))
                        }
                        if (momentsMusic['<?php echo self::NAME_MOMENTS_MUSIC_ID?>']) {
                            Object.keys(momentsMusic).forEach(key => {
                                typechoField(key).val(momentsMusic[key])
                            })
                        } else {
                            typechoField('<?php echo self::NAME_MOMENTS_MUSIC_ID?>').val('');
                        }
                        if (momentsBiliBili['<?php echo self::NAME_MOMENTS_BVID?>']) {
                            Object.keys(momentsBiliBili).forEach(key => {
                                typechoField(key).val(momentsBiliBili[key])
                            })
                        } else {
                            typechoField('<?php echo self::NAME_MOMENTS_BVID?>').val('');
                        }
                        if (editormd) {
                            $.ajax({
                                url: $(this).attr('action'),
                                method: $(this).attr('method'),
                                data: $(this).serializeArray(),
                                success: res => {
                                    location.href = '/admin/manage-posts.php';
                                }
                            })
                            return false;
                        }

                    } else if (typechoField('<?php echo self::NAME_POST_TYPE?>').val() == <?php echo self::POST_TYPE_PICTURE?>) {
                        if (pictureList.picSize() > 0) {
                            content.val(pictureList.toList().join('\n'))
                            if (editormd) {
                                $.ajax({
                                    url: $(this).attr('action'),
                                    method: $(this).attr('method'),
                                    data: $(this).serializeArray(),
                                    success: res => {
                                        location.href = '/admin/manage-posts.php';
                                    }
                                })
                                return false;
                            }
                        } else {
                            layer.msg('相册图片不能为空')
                        }
                    } else {
                        typechoField('<?php echo self::NAME_POST_TYPE?>').val('<?php echo self::POST_TYPE_ARTICLE?>')
                        let path = $('#fw-show-path').val()
                        let seoKeys = $('#fw-seo-keywords').val()
                        let seoDesc = $('#fw-seo-desc').val()
                        if (path)
                            typechoField('<?php echo self::NAME_LIST_COVER?>').val(path)
                        if (seoKeys)
                            typechoField('<?php echo self::NAME_SEO_KEYWORDS?>').val(seoKeys)
                        if (seoDesc)
                            typechoField('<?php echo self::NAME_SEO_DESC?>').val(seoDesc)
                        if (fileInfo.length > 0)
                            typechoField('<?php echo self::NAME_FILE_LIST?>').val(JSON.stringify(fileInfo))
                        return true;
                    }
                })

                if (typechoField('<?php echo self::NAME_POST_TYPE?>').val()) {
                    $('#fw-article-type-bar li[data-target=' + typechoField('<?php echo self::NAME_POST_TYPE?>').val() + ']').trigger('click')
                    $('#fw-article-type-bar li').off('click')
                    $('#fw-article-type-bar').on('click', 'li', function () {
                        layer.msg("发布之后的文章不支持修改类型", {icon: 2})
                    })
                    if (typechoField('<?php echo self::NAME_POST_TYPE?>').val() ==<?php echo self::POST_TYPE_MOMENTS ?>) {
                        if (typechoField('<?php  echo self::NAME_MOMENTS_PICURES?>').val()) {
                            typechoField('<?php  echo self::NAME_MOMENTS_PICURES?>').val().split("\n").forEach(item => momentsPicList.add(item))
                        }
                        if (typechoField('<?php  echo self::NAME_MOMENTS_MUSIC_ID?>').val()) {
                            momentsMusic['<?php echo self::NAME_MOMENTS_MUSIC_ID?>'] = typechoField('<?php  echo self::NAME_MOMENTS_MUSIC_ID?>').val()
                            momentsMusic['<?php echo self::NAME_MOMENTS_MUSIC_TYPE?>'] = typechoField('<?php  echo self::NAME_MOMENTS_MUSIC_TYPE?>').val()
                            momentsMusic['<?php echo self::NAME_MOMENTS_MUSIC_PROVIDE?>'] = typechoField('<?php  echo self::NAME_MOMENTS_MUSIC_PROVIDE?>').val()
                            $('#fw-moments-music span').html(`分享
                            ${momentsMusic['<?php echo self::NAME_MOMENTS_MUSIC_PROVIDE?>'] == '<?php echo self::MUSIC_PROVIDE_NETEASE?>' ? '网易云' : 'QQ音乐'}
                            ${momentsMusic['<?php echo self::NAME_MOMENTS_MUSIC_TYPE?>'] == '<?php echo self::MUSIC_TYPE_PLAYLIST?>' ? '歌单' : '单曲'}ID为
                            ${momentsMusic['<?php echo self::NAME_MOMENTS_MUSIC_ID?>']}
                            <a href="javascript:void(0)"><i class="fa fa-close"></i></a>
                        `)
                        }
                        if (typechoField('<?php  echo self::NAME_MOMENTS_BVID?>').val()) {
                            momentsBiliBili['<?php echo self::NAME_MOMENTS_BVID?>'] = typechoField('<?php  echo self::NAME_MOMENTS_BVID?>').val()
                            momentsBiliBili['<?php echo self::NAME_MOMENTS_BVNUM?>'] = typechoField('<?php  echo self::NAME_MOMENTS_BVNUM?>').val()
                            $('#fw-moments-bili span').html(`分享B站视频
                            BVID为${momentsBiliBili['<?php echo self::NAME_MOMENTS_BVID?>']}
                            剧集数为${momentsBiliBili['<?php echo self::NAME_MOMENTS_BVNUM?>']}
                            <a href="javascript:void(0)"><i class="fa fa-close"></i></a>
                        `)
                        }
                        editor.txt.html(content.val())
                    } else if (typechoField('<?php echo self::NAME_POST_TYPE?>').val() ==<?php echo self::POST_TYPE_PICTURE ?>) {
                        content.val().split("\n").forEach(item => pictureList.add(item))
                    } else if (typechoField('<?php echo self::NAME_POST_TYPE?>').val() ==<?php echo self::POST_TYPE_ARTICLE ?>) {
                        $('#fw-seo-keywords').val(typechoField('<?php echo self::NAME_SEO_KEYWORDS?>').val())
                        $('#fw-seo-desc').val(typechoField('<?php echo self::NAME_SEO_DESC?>').val())
                        $('#fw-show-path').val(typechoField('<?php echo self::NAME_LIST_COVER?>').val())
                        $('#fw-show-img').attr('src', typechoField('<?php echo self::NAME_LIST_COVER?>').val())
                        let fInfo = typechoField('<?php echo self::NAME_FILE_LIST?>').val();
                        if (fInfo) {
                            fileInfo = JSON.parse(fInfo)
                        }
                    }


                }


                $('#fw-picture-upload').on('click', function () {
                    let idx = layer.open({
                        type: 1,
                        title: '选择图片',
                        skin: 'layui-layer-demo', //样式类名
                        anim: 2,
                        shadeClose: true, //开启遮罩关闭
                        content: $('#fw-upload-box').html()
                    });
                    $('#fw-upload-btn').on('click', function () {
                        $('#fw-upload-file').trigger('click')
                    })

                    $('#fw-right-upload').on('click', function () {
                        let path = $('#fw-file-path').val()
                        if (path) {
                            layer.close(idx)
                            pictureList.add(path);
                        } else {
                            layer.msg('图片路径不能为空', {icon: 2})
                        }
                    })
                })


                $('#fw-moments-upload').on('click', function () {
                    let idx = layer.open({
                        type: 1,
                        title: '选择图片',
                        skin: 'layui-layer-demo', //样式类名
                        anim: 2,
                        shadeClose: true, //开启遮罩关闭
                        content: $('#fw-upload-box').html()
                    });
                    $('#fw-upload-btn').on('click', function () {
                        $('#fw-upload-file').trigger('click')
                    })

                    $('#fw-right-upload').on('click', function () {
                        let path = $('#fw-file-path').val()
                        if (path) {
                            layer.close(idx)
                            momentsPicList.add(path);
                        } else {
                            layer.msg('图片路径不能为空', {icon: 2})
                        }
                    })
                })

                $('#fw-upload-file').on('change', function () {
                    let file = this.files[0]
                    let fd = new FormData();
                    fd.append('file', file);
                    $.ajax({
                        url: '/freewind/upload',
                        method: 'post',
                        data: fd,
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        success: res => {
                            let type = typechoField('<?php echo self::NAME_POST_TYPE?>').val()
                            if (res.success) {
                                if (type ==<?php echo self::POST_TYPE_MOMENTS ?> || type ==<?php echo self::POST_TYPE_PICTURE ?>)
                                    $('#fw-file-path').val(res.data)
                                else {
                                    $('#fw-show-path').val(res.data)
                                    $('#fw-show-img').attr('src', res.data)
                                }
                            } else {
                                layer.msg(res.msg, {icon: 2})
                            }
                        },
                        error: res => {
                            layer.msg('上传上错', {icon: 2})
                        }
                    })
                })

                console.log('\n %c Freewind 主题 v1.5 %c https://kevinlu98.cn/ \n', 'color: #fadfa3; background: #030307; padding:5px 0;', 'background: #fadfa3; padding:5px 0;')

            })
        </script>
        <?php
    }
}