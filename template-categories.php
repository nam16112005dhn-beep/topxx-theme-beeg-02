<?php
/**
 * Template: Trang Categories - hiển thị tất cả thể loại (ophim_genres)
 */
get_header();

$per_page = 60;
$paged    = max(1, (int) get_query_var('paged'));
$offset   = ($paged - 1) * $per_page;

// Tổng số thể loại (để tính phân trang)
$total_terms = get_terms(array(
    'taxonomy'   => 'ophim_genres',
    'hide_empty' => false,
    'fields'     => 'count',
));
$total_terms = is_wp_error($total_terms) ? 0 : (int) $total_terms;

// Lấy 10 item cho trang hiện tại
$genres = get_terms(array(
    'taxonomy'   => 'ophim_genres',
    'hide_empty' => false,
    'orderby'    => 'name',
    'order'      => 'ASC',
    'number'     => $per_page,
    'offset'     => $offset,
));
$genres = is_wp_error($genres) ? array() : $genres;
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
    <style type="text/css">
    .img_thumb_cat {
        width: 100%;
    }
    /* Grid giống danh sách video: hàng ngang đều, có tiêu đề dưới ảnh */
    #list_categories_categories_list .categories_list .list-videos {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-start;
        gap: 12px;
        margin: 0 12px;
        padding: 0;
    }
    #list_categories_categories_list .categories_list .list-videos .item {
        display: flex;
        flex-direction: column;
        width: calc((100% - 48px) / 5);
        margin: 0;
        overflow: hidden;
        vertical-align: top;
    }
    #list_categories_categories_list .categories_list .list-videos .item a {
        display: flex;
        flex-direction: column;
        width: 100%;
        height: auto;
        position: relative;
    }
    #list_categories_categories_list .categories_list .list-videos .item a .img_thumb {
        width: 100%;
        aspect-ratio: 2 / 1;
        object-fit: cover;
        object-position: center;
        display: block;
        flex-shrink: 0;
    }
    #list_categories_categories_list .categories_list .list-videos .item_title.categories_title {
        display: block;
        padding: 6px 0 0;
        line-height: 1.3;
        text-overflow: ellipsis;
        overflow: hidden;
        white-space: nowrap;
    }
    @media (max-width: 992px) {
        #list_categories_categories_list .categories_list .list-videos .item {
            width: calc((100% - 12px) / 2);
        }
    }
    @media (max-width: 576px) {
        #list_categories_categories_list .categories_list .list-videos .item {
            width: calc((100% - 12px) / 2);
        }
    }
    </style>
    <div class="container">
        <div id="list_categories_categories_list">
            <div class="headline">
                <h1> Categories</h1>
            </div>
            <div class="categories_list rotation_thumb" id="list_categories_categories_list_items">
                <div class="list-videos rotation_item">
                    <?php foreach ($genres as $term) { ?>
                        <span class="item">
                            <a href="<?php echo esc_url(get_term_link($term)); ?>" title="<?php echo esc_attr($term->name); ?>">
                            <img src="<?php echo esc_url(get_term_meta($term->term_id, 'genre_thumbnail', true)); ?>" class="img_thumb lazyloaded" alt="<?php echo esc_attr($term->name); ?>" width="400" height="200" data-id="9-1978140" data-hz="dcec/c17b/1edb/230b/816f/9ebe/a3ea/fc63/1" data-hzo="2" data-hzt="2" data-hzn="9" data-hosted="1" onerror="img_error_hz(this);">
                            <span class="item_title categories_title"><?php echo esc_html($term->name); ?></span>
                            </a>
                        </span>
                    <?php } ?>
                </div>
            </div>

            <div class="pagination">
                <?php ophim_categories_pagination($total_terms, $per_page); ?>
            </div>
        </div>
    </div>
</div>
<?php
get_footer();
