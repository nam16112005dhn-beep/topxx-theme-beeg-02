<?php

if (!function_exists('ophim_theme_setup')) {
    function ophim_theme_setup()
    {

        /*
         * Tự chèn RSS Feed links trong <head>
         */
        add_theme_support('automatic-feed-links');
        /*
         * Thêm chức năng title-tag để tự thêm <title>
         */
        add_theme_support('title-tag');
        add_theme_support('custom-logo');
        add_theme_support('post-thumbnails');

        /*
         * Tạo menu cho theme
         */
        register_nav_menu('primary-menu', __('Primary Menu', 'ophim'));

    }

    add_action('init', 'ophim_theme_setup');

}

// Cài đặt theme: bật/tắt ảnh preview khi hover vào item phim
add_action('customize_register', 'nqt_beeg_customize_preview_hover');
function nqt_beeg_customize_preview_hover($wp_customize)
{
    $wp_customize->add_section('ophim_preview_section', array(
        'title'    => __('Ảnh preview phim', 'ophim'),
        'priority' => 130,
    ));
    $wp_customize->add_setting('ophim_hover_preview', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('ophim_hover_preview', array(
        'label'   => __('Bật ảnh preview khi hover vào item phim', 'ophim'),
        'section' => 'ophim_preview_section',
        'type'    => 'checkbox',
    ));
}




function wp_get_menu_array($current_menu)
{
    $menu_name = $current_menu;
    $locations = get_nav_menu_locations();
    $menu = wp_get_nav_menu_object($locations[$menu_name]);
    $array_menu = wp_get_nav_menu_items($menu->term_id);
    $menu = array();
    foreach ($array_menu as $m) {
        if (empty($m->menu_item_parent)) {
            $menu[$m->ID] = array();
            $menu[$m->ID]['ID'] = $m->ID;
            $menu[$m->ID]['title'] = $m->title;
            $menu[$m->ID]['url'] = $m->url;
            $menu[$m->ID]['children'] = array();
        }
    }
    $submenu = array();
    foreach ($array_menu as $m) {
        if ($m->menu_item_parent) {
            $submenu[$m->ID] = array();
            $submenu[$m->ID]['ID'] = $m->ID;
            $submenu[$m->ID]['title'] = $m->title;
            $submenu[$m->ID]['url'] = $m->url;
            $menu[$m->menu_item_parent]['children'][$m->ID] = $submenu[$m->ID];
        }
    }
    return $menu;
}

function ophim_pagination1($custom_query = null) {
    global $wp_rewrite;
    $query_obj = $custom_query ? $custom_query : $GLOBALS['wp_query'];
    
    if ($query_obj->max_num_pages <= 1) return;
    
    $paged = get_query_var('paged') ? get_query_var('paged') : 1;
    
    $pagenum_link = html_entity_decode(get_pagenum_link());
    $query_args   = array();
    $url_parts    = explode('?', $pagenum_link);
    
    if (isset($url_parts[1])) {
        wp_parse_str($url_parts[1], $query_args);
    }
    
    $pagenum_link = remove_query_arg(array_keys($query_args), $pagenum_link);
    $pagenum_link = trailingslashit($pagenum_link) . '%_%';
    
    $format = $wp_rewrite->using_index_permalinks() && ! strpos($pagenum_link, 'index.php') ? 'index.php/' : '';
    $format .= $wp_rewrite->using_permalinks() ? user_trailingslashit('page/%#%', 'paged') : '?paged=%#%';
    
    $pages = paginate_links(array(
        'base'      => $pagenum_link,
        'format'    => $format,
        'current'   => max(1, $paged),
        'total'     => $query_obj->max_num_pages,
        'type'      => 'array',
        'prev_text' => '←',
        'next_text' => '→',
        'add_args'  => array_map('urlencode', $query_args),
    ));
    
    if (is_array($pages) && !empty($pages)) {
        foreach ($pages as $page) {
            $page = str_replace('page-numbers', 'page_link', $page);
            
            if (strpos($page, 'current') !== false) {
                $page = str_replace('page-link current', 'page_link', $page);
                echo $page;
            }
            else if (strpos($page, 'dots') !== false) {
                echo '<span class="page_link" aria-hidden="true">...</span>';
            }
            else {
                echo $page;
            }
        }
    }
}

function op_get_genre_link($genre_slug) {
    $term = get_term_by('slug', $genre_slug, 'ophim_genres');
    
    if (!$term || is_wp_error($term)) {
        return '';
    }
    
    return get_term_link($term);
}

function ophim_pagination()
{

    global $wp_query;

    if ($wp_query->max_num_pages <= 1) return;
    $big = 999999999;

    $pages = paginate_links(array('base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))), 'prev_text'          => '<',
        'next_text'          => '>',
        'format' => '?paged=%#%', 'current' => max(1, get_query_var('paged')), 'total' => $wp_query->max_num_pages, 'type' => 'array',));

    if (is_array($pages) && !empty($pages)) {
        foreach ($pages as $page) {
            $page = str_replace('page-numbers', 'page_link', $page);
            
            if (strpos($page, 'current') !== false) {
                $page = str_replace('page-link current', 'page_link', $page);
                echo $page;
            }
            else if (strpos($page, 'dots') !== false) {
                echo '<span class="page_link" aria-hidden="true">...</span>';
            }
            else {
                echo $page;
            }
        }
    }
}

add_action('admin_enqueue_scripts', 'ophim_include_vung_admin_script');
function ophim_include_vung_admin_script()
{
    wp_enqueue_style('admin_vung', get_stylesheet_directory_uri() . '/admin/css/admin.css', false, '');
}