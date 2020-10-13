<div id="sidebar-wrapper">
    <ul class="sidebar-nav">
        <li class="sidebar-brand">
            <a href="<?php $this->options->siteUrl(); ?>">
                <?php $this->options->title() ?></a>
        </li>
        <li>
            <div id="search">
                <form method="post" action="./" role="search">
                    <input id="search-input" name="s" type="search" class="form-control" placeholder="<?php _e('搜索'); ?>">
                </form>
            </div>
        </li>
        <?php $this->widget('Widget_Contents_Page_List')->to($pages); ?>
        <?php while($pages->next()): ?>
        <li>
            <a <?php if($this->is('page', $pages->slug)): ?>
                <?php endif; ?>href="
                <?php $pages->permalink(); ?>" title="
                <?php $pages->title(); ?>">
                <?php $pages->title(); ?></a>
        </li>
        <?php endwhile; ?>
        <li>
            <a href="https://www.fyscu.com">飞扬官网</a>
        </li>
        <?php $this->widget('Widget_Metas_Category_List') ->parse('
        <li><a href="{permalink}">{name}({count})</a> 
        </li>'); ?>
        <li>
            <a href="<?php $this->options->feedUrl(); ?>">
                <?php _e( '文章 RSS'); ?>
            </a>
        </li>
        <?php if($this->user->hasLogin()): ?>
        <li>
            <a href="<?php $this->options->adminUrl(); ?>">
                <?php _e( '控制台'); ?>(
                <?php $this->user->screenName(); ?>)</a>
        </li>
        <li>
            <a href="<?php $this->options->logoutUrl(); ?>">
                <?php _e( '退出'); ?>
            </a>
        </li>
        <?php else: ?>
        <li class="last">
            <a href="<?php $this->options->adminUrl('login.php'); ?>">
                <?php _e( '登录'); ?>
            </a>
        </li>
        <?php endif; ?>
    </ul>
</div>