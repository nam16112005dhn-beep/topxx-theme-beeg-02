<style>
    .button_root_menu,.datePublished:before,.footer-logo,.form_submit,.item .duration.hd_video:before,.item_cs:before,.logo,.open .menu_close:before,.show_search,.top_menu .top_trends_menu a:before,.top_menu a:before,.top_menu span:before,.total_videos:before,h1:before,h2:before {
        background-image: url();
        background-repeat: no-repeat
    }
    .button_root_menu, .datePublished:before, .footer-logo, .form_submit, .item .duration.hd_video:before, .item_cs:before, .logo, .open .menu_close:before, .show_search, .top_menu .top_trends_menu a:before, .top_menu a:before, .top_menu span:before, .total_videos:before, h1:before, h2:before {
        background-image: url(<?= get_template_directory_uri() ?>/assets/images/sprite.svg);
        background-repeat: no-repeat /*! background-size:440px */;
    }
</style>
<header>
    <div class="container">
        <?php if (has_custom_logo()): ?>
            <a href="/" class="logo logo-custom">
                <img src="<?php echo esc_url(wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full')); ?>" alt="<?php bloginfo('name'); ?>">
            </a>
        <?php else: ?>
            <a href="/" class="logo"></a>
        <?php endif; ?>
        <span class="show_menu button_root_menu"> </span>

        <span class="show_search" onclick="this.className=this.className=='show_search'? 'show_search open': 'show_search';"> <span class="show_search_button"> </span> <span class="close_search_button"> </span> </span>

        <ul class="top_menu">
            <?php
            $menu_items = op_get_menu_array('primary-menu');
            foreach ($menu_items as $key => $item) : ?>
                <li><a href="<?= $item['url'] ?>"><?= $item['title'] ?></a></li>
            <?php endforeach; ?>
        </ul>

        <div class="search">
            <form id="search_form" action="/" method="get">
                <span class="search-button"> <label for="wordinput">Search:</label> </span>
                <div class="search-text"> 
                    <input id="wordinput" type="text" name="s" value="">
                </div> 
                <input type="submit" value="search" class="form_submit">
            </form>
            <div id="worddiv"></div>
        </div>
    </div>
</header>