<?php use Freewind\Core\Article;
use Freewind\Extend\ArticleExtend;

if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php
//$postFile = $this->fields->postFileList ?: '{postFile:1}';
//$fileType = json_decode($postFile, true);
?>
<?php if ($this->fields->postFileType >= 2): ?>
    <?php if (Article::enclosure($this, $this->fields->postFileType)): ?>
        <?php
        $fileList = $this->fields->postFileList ?: '[]';
        $fileList = json_decode($fileList, true);
        ?>
        <div class="file-down bottom-shadow">
            <h3 style="padding: 0;margin: 0;font-weight: 100;font-size: 16px">附件下载</h3>
            <ul>
                <?php foreach ($fileList as $fileItem): ?>
                    <?php if ($fileItem['url']): ?>
                        <li>
                            <img class="file-icon" src="<?php echo $fileItem['icon'] ?>" alt="">
                            <div class="file-source">来源：<?php echo $fileItem[name] ?></div>
                            <div class="file-pwd">提取密码：<?php echo $fileItem['pwd'] ?: '暂无' ?></div>
                            <a class="file-download" data-pwd="<?php echo $fileItem['pwd'] ?: '' ?>"
                               href="<?php echo $fileItem['url'] ?>" target="_blank">
                                <svg t="1639818585206" class="icon" viewBox="0 0 1024 1024" version="1.1"
                                     xmlns="http://www.w3.org/2000/svg" p-id="29104" width="48" height="48">
                                    <path d="M523.73504 319.29344h-204.8c-16.896 0-30.72-13.824-30.72-30.72s13.824-30.72 30.72-30.72h204.8c16.896 0 30.72 13.824 30.72 30.72s-13.824 30.72-30.72 30.72zM605.65504 452.41344h-286.72c-16.896 0-30.72-13.824-30.72-30.72s13.824-30.72 30.72-30.72h286.72c16.896 0 30.72 13.824 30.72 30.72s-13.824 30.72-30.72 30.72z"
                                          fill="#FFFFFF" p-id="29105"></path>
                                    <path d="M512 512m-389.12 0a389.12 389.12 0 1 0 778.24 0 389.12 389.12 0 1 0-778.24 0Z"
                                          fill="#3889FF" p-id="29106"></path>
                                    <path d="M466.86208 291.1232a17.28512 17.28512 0 0 0-17.29536 17.29536v154.19392h-57.05728a23.0912 23.0912 0 0 0-14.11072 41.32864l112.49664 86.528a34.59072 34.59072 0 0 0 42.19904 0l112.49664-86.528a23.10144 23.10144 0 0 0-14.11072-41.32864h-56.99584l-0.06144-154.19392c0-9.55392-7.74144-17.29536-17.29536-17.29536h-90.2656z m0 0M660.48 696.91392h-296.96c-16.896 0-30.72-13.824-30.72-30.72s13.824-30.72 30.72-30.72h296.96c16.896 0 30.72 13.824 30.72 30.72s-13.824 30.72-30.72 30.72z"
                                          fill="#FFFFFF" p-id="29107"></path>
                                </svg>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php else: ?>
        <div class="file-not-view bottom-shadow">
            <p>
                对不起，作者设置了附件 <a href="#common-edit"><?php echo ArticleExtend::File_POWER_LIST[$this->fields->postFileType] ?></a>
            </p>
        </div>
    <?php endif ?>
<?php endif; ?>
