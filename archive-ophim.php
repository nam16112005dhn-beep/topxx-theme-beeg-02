<?php
get_header();
?>
<?php
$ophim_sort = get_query_var('ophim_sort');
$breadcrumb_title = 'Tất cả phim';
if ($ophim_sort === 'new') $breadcrumb_title = 'New Videos';
if ($ophim_sort === 'best') $breadcrumb_title = 'POPULAR Videos';
?>
<script defer src="https://static.addtoany.com/menu/page.js"></script>

<center>
    <div class="bt_share">
        <div class="a2a_kit a2a_kit_size_32 a2a_default_style" style="padding-top: 20px; padding-bottom: 10px; width: 288px; height: 32px; line-height: 32px;">
            <a class="a2a_button_facebook"></a>
            <a class="a2a_button_twitter"></a>
            <a class="a2a_button_reddit"></a>
            <a class="a2a_button_facebook_messenger"></a>
            <a class="a2a_button_tumblr"></a>
            <a class="a2a_button_email"></a>
            <a class="a2a_button_copy_link"></a>
            <a class="a2a_dd" href="https://www.addtoany.com/share"></a>
        </div>
    </div>
</center>

<div class="container">
    <div class="container">
        <div id="list_videos_latest_videos_list">
            <div id="list_categories_categories_list">
                <div class="headline">
                    <h1><?php echo $breadcrumb_title; ?></h1>

                    <div class="thumb_filter">
                        <span class="thumb_filter_group">
                            <a class="thumb_filter_element tree22 <?php if ($ophim_sort === 'new') echo 'active'; ?>" href="<?php echo esc_url(home_url('/new/')); ?>" title="New Videos">New Videos</a>
                            <a class="thumb_filter_element tree22 <?php if ($ophim_sort === 'best') echo 'active'; ?>" href="<?php echo esc_url(home_url('/best/')); ?>" title="Best Videos">Best Videos</a>
                            
                            <a class="thumb_filter_element tree a_show_filter" href="#"><img width="25" height="25" alt="Filter" class="show_filter" src="<?= get_template_directory_uri() ?>/assets/images/filter.svg"></a>
                    
                        </span>
                    </div>
                </div>

                <p class="list-videos list_bb">
                <?php $key =0; if (have_posts()) {
                    while (have_posts()) {
                    the_post();
                        $xClass = 'item';
                        if ($key === 0 || $key % 4 === 0) {
                            $xClass .= ' no-margin-left';
                        }
                        include THEMETEMPLADE.'/section/section_thumb_item.php';
                    } wp_reset_postdata();  }
                else { ?>
                <p>Rất tiếc, không có nội dung nào trùng khớp yêu cầu</p>
                <?php } ?>
                </p>

                <div class="pagination">
                    <?php ophim_pagination();?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
get_footer();
?>
