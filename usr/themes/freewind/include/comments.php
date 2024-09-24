<?php use Freewind\Config\Constants;

if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php if ($this->allow('comment')): ?>
    <input type="hidden" id="comPname" value="">
    <div class="comment-box bottom-shadow" id="comment-box">
        <h2 id="comment-box-title">评论(<?php $this->commentsNum(); ?>)</h2>
        <form id="comment-form" class="text-box" method="post"
              action="<?php echo '?' . Constants::FREEWIND_PARAM . '=' . Constants::COMMENT_PARAMS ?>">
            <div id="common-edit"></div>
            <?php $security = $this->widget('Widget_Security'); ?>
            <input type="hidden" name="_" value="<?php echo $security->getToken($this->request->getReferer()) ?>">
            <textarea id="comment-text" name="text" hidden></textarea>
            <input type="hidden" name="parent" value="0" id="comPid">
            <div class="commentor-info row">
                <?php if ($this->user->hasLogin()): ?>
                    <input type="hidden" id="com-name" name="author" value="<?php $this->user->screenName(); ?>">
                    <input type="hidden" id="com-mail" name="mail" value="<?php $this->user->mail(); ?>">
                    <input type="hidden" id="com-url" name="url" value="<?php $this->user->url(); ?>">
                <?php else: ?>
                    <div class="col-sm-6 col-xs-12 input-item pos-rlt">
                        <input type="number" placeholder="输入QQ快速获取信息..." id="com-qq"
                               value="<?php $this->remember('qq'); ?>">
                        <i class="fa fa-qq pos-abs"></i>
                    </div>
                    <div class="col-sm-6 col-xs-12 input-item pos-rlt">
                        <input name="author" required type="text" placeholder="请输入昵称..." id="com-name"
                               value="<?php $this->remember('author'); ?>">
                        <i class="fa fa-user pos-abs"></i>
                    </div>
                    <div class="col-sm-6 col-xs-12 input-item pos-rlt">
                        <input name="mail" required type="text" placeholder="请输入邮箱..." id="com-mail"
                               value="<?php $this->remember('mail'); ?>">
                        <i class="fa fa-envelope pos-abs"></i>
                    </div>
                    <div class="col-sm-6 col-xs-12 input-item pos-rlt">
                        <input name="url" type="text" placeholder="请输入主页地址(选填)..." id="com-url"
                               value="<?php $this->remember('url'); ?>">
                        <i class="fa fa-link pos-abs"></i>
                    </div>
                <?php endif; ?>
                <div class="col-sm-6 col-sm-offset-6 col-xs-12 input-item pos-rlt">
                    <button id="commit-comment" type="submit" class="fr">提交评论</button>
                </div>
            </div>
        </form>
    </div>
<?php else: ?>
    <div class="comment-box" style="text-align: center">
        此处评论已关闭
    </div>
<?php endif; ?>
<div class="comments-list">

    <?php $this->comments()->to($comments); ?>
    <?php if ($comments->have()) : ?>
        <?php $comments->listComments(); ?>
    <?php else: ?>

    <?php endif; ?>
    <?php $comments->pageNav('<i class="iconfont icon-angle-left"></i>', '<i class="iconfont icon-angle-right"></i>', '5', '...'); ?>
</div>
