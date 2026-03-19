<?php

define('THEME_URL', get_stylesheet_directory());
define('CORE', THEME_URL . '/core');
define('WIDGET', THEME_URL . '/widget');
define('SIDEBARTEMPLADE', THEME_URL . '/templates/rightbar');
define('THEMETEMPLADE', THEME_URL . '/templates');


require_once(CORE . '/init.php');
require_once(THEME_URL . '/inc/demo.php');
require_once(THEME_URL . '/inc/register_sidebar.php');
require_once(THEME_URL . '/inc/ajax.php');
require_once(THEME_URL . '/inc/front.php');
require_once(THEME_URL . '/inc/age-verify.php');
require_once(WIDGET . '/wg_ophim_categories.php');
require_once(WIDGET . '/wg_ophim_footer.php');
require_once(WIDGET . '/wg_ophim_sidebar.php');

function custom_pre_get_posts($query) {
    if (!is_admin() && $query->is_main_query()) {
        $query->set('post_type', array('post', 'ophim'));
    }
}
add_action('pre_get_posts', 'custom_pre_get_posts');

function taxonomy_orderby_modified($query) {
    if (!is_admin() && $query->is_main_query() && is_tax()) {
        $query->set('orderby', 'modified');
        $query->set('order', 'DESC');
    }
}
add_action('pre_get_posts', 'taxonomy_orderby_modified');

// Đăng ký query var cho trang New/Best và Categories
function ophim_add_sort_query_vars($vars) {
    $vars[] = 'ophim_sort';
    $vars[] = 'ophim_page';
    return $vars;
}
add_filter('query_vars', 'ophim_add_sort_query_vars');

// Thêm rewrite rule: /new/, /best/, /categories/ (cả khi dùng index.php trong permalink)
function ophim_add_sort_rewrite_rules() {
    // Dạng pretty: /new/ , /best/ , /categories/
    add_rewrite_rule('^new/?$', 'index.php?ophim_sort=new', 'top');
    add_rewrite_rule('^new/page/([0-9]+)/?$', 'index.php?ophim_sort=new&paged=$matches[1]', 'top');
    add_rewrite_rule('^best/?$', 'index.php?ophim_sort=best', 'top');
    add_rewrite_rule('^best/page/([0-9]+)/?$', 'index.php?ophim_sort=best&paged=$matches[1]', 'top');
    add_rewrite_rule('^categories/?$', 'index.php?ophim_page=categories', 'top');
    add_rewrite_rule('^categories/page/([0-9]+)/?$', 'index.php?ophim_page=categories&paged=$matches[1]', 'top');
    // Dạng có index.php
    add_rewrite_rule('^index\.php/new/?$', 'index.php?ophim_sort=new', 'top');
    add_rewrite_rule('^index\.php/new/page/([0-9]+)/?$', 'index.php?ophim_sort=new&paged=$matches[1]', 'top');
    add_rewrite_rule('^index\.php/best/?$', 'index.php?ophim_sort=best', 'top');
    add_rewrite_rule('^index\.php/best/page/([0-9]+)/?$', 'index.php?ophim_sort=best&paged=$matches[1]', 'top');
    add_rewrite_rule('^index\.php/categories/?$', 'index.php?ophim_page=categories', 'top');
    add_rewrite_rule('^index\.php/categories/page/([0-9]+)/?$', 'index.php?ophim_page=categories&paged=$matches[1]', 'top');
}
add_action('init', 'ophim_add_sort_rewrite_rules');

// Flush rewrite rules 1 lần để /new/ và /best/ không bị 404 (WordPress cache rules trong DB)
define('OPHIM_REWRITE_VERSION', 5);
function ophim_maybe_flush_rewrite_rules() {
    if (get_option('ophim_rewrite_version') != OPHIM_REWRITE_VERSION) {
        flush_rewrite_rules();
        update_option('ophim_rewrite_version', OPHIM_REWRITE_VERSION);
    }
}
add_action('init', 'ophim_maybe_flush_rewrite_rules', 99);

function ophim_parse_runtime_minutes($runtime_str) {
    if (empty($runtime_str)) return 0;
    $str = mb_strtolower(trim($runtime_str), 'UTF-8');
    $minutes = 0;
    if (preg_match('/(\d+)\s*(?:h|giờ|hour)/u', $str, $m)) {
        $minutes += intval($m[1]) * 60;
    }
    if (preg_match('/(\d+)\s*(?:p(?:hút)?|m(?:in)?(?:ute)?)/u', $str, $m)) {
        $minutes += intval($m[1]);
    }
    if ($minutes === 0 && preg_match('/(\d+)/', $str, $m)) {
        $minutes = intval($m[1]);
    }
    return $minutes;
}

add_action('save_post_ophim', 'ophim_auto_store_runtime_minutes', 20);
function ophim_auto_store_runtime_minutes($post_id) {
    $runtime = get_post_meta($post_id, 'ophim_runtime', true);
    $minutes = ophim_parse_runtime_minutes($runtime);
    update_post_meta($post_id, 'ophim_runtime_minutes', $minutes);
}

add_action('admin_init', 'ophim_batch_update_runtime_minutes');
function ophim_batch_update_runtime_minutes() {
    if (get_option('ophim_runtime_minutes_v') == 1) return;
    global $wpdb;
    $rows = $wpdb->get_results("
        SELECT pm.post_id, pm.meta_value
        FROM {$wpdb->postmeta} pm
        INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID AND p.post_type = 'ophim'
        LEFT JOIN {$wpdb->postmeta} pm2 ON pm.post_id = pm2.post_id AND pm2.meta_key = 'ophim_runtime_minutes'
        WHERE pm.meta_key = 'ophim_runtime' AND pm.meta_value != '' AND pm2.meta_id IS NULL
    ");
    foreach ($rows as $row) {
        update_post_meta($row->post_id, 'ophim_runtime_minutes', ophim_parse_runtime_minutes($row->meta_value));
    }
    update_option('ophim_runtime_minutes_v', 1);
}

function ophim_sort_pre_get_posts($query) {
    if (is_admin() || !$query->is_main_query()) {
        return;
    }
    $sort = get_query_var('ophim_sort');
    if ($sort !== 'new' && $sort !== 'best') {
        return;
    }
    $query->set('post_type', 'ophim');
    $query->set('post_status', 'publish');
    $query->set('posts_per_page', get_option('posts_per_page', 20));

    $dsort = isset($_GET['dsort']) ? sanitize_text_field($_GET['dsort']) : '';

    if ($dsort === 'long') {
        $query->set('meta_key', 'ophim_runtime_minutes');
        $query->set('orderby', 'meta_value_num');
        $query->set('order', 'DESC');
    } elseif ($dsort === 'short') {
        $query->set('meta_key', 'ophim_runtime_minutes');
        $query->set('orderby', 'meta_value_num');
        $query->set('order', 'ASC');
    } elseif ($sort === 'best') {
        $query->set('meta_key', 'ophim_view');
        $query->set('orderby', 'meta_value_num');
        $query->set('order', 'DESC');
    } else {
        $query->set('orderby', 'date');
        $query->set('order', 'DESC');
    }

}
add_action('pre_get_posts', 'ophim_sort_pre_get_posts', 20);

// Dùng template archive-ophim cho /new/ và /best/; template riêng cho /categories/
function ophim_sort_template_include($template) {
    if (get_query_var('ophim_sort')) {
        $archive = get_stylesheet_directory() . '/archive-ophim.php';
        if (file_exists($archive)) {
            return $archive;
        }
    }
    if (get_query_var('ophim_page') === 'categories') {
        $categories_tpl = get_stylesheet_directory() . '/template-categories.php';
        if (file_exists($categories_tpl)) {
            return $categories_tpl;
        }
    }
    return $template;
}
add_filter('template_include', 'ophim_sort_template_include');

/**
 * URL trang Categories (danh sách thể loại) theo cấu trúc permalink hiện tại.
 */
function ophim_categories_url() {
    global $wp_rewrite;
    $path = 'categories/';
    if (!empty($wp_rewrite) && $wp_rewrite->using_index_permalinks()) {
        $path = 'index.php/' . $path;
    }
    return home_url('/' . $path);
}

/**
 * Phân trang cho trang Categories (base URL đúng với categories).
 */
function ophim_categories_pagination($total_items, $per_page) {
    $current = max(1, (int) get_query_var('paged'));
    $total_pages = (int) ceil($total_items / $per_page);
    if ($total_pages <= 1) {
        return;
    }
    $base = trailingslashit(ophim_categories_url()) . 'page/%_%';
    $pages = paginate_links(array(
        'base'      => $base,
        'format'    => '%#%/',
        'current'  => $current,
        'total'     => $total_pages,
        'type'      => 'array',
        'prev_text' => '←',
        'next_text' => '→',
    ));
    if (is_array($pages) && !empty($pages)) {
        foreach ($pages as $page) {
            $page = str_replace('page-numbers', 'page_link', $page);
            if (strpos($page, 'current') !== false) {
                $page = str_replace('page-link current', 'page_link', $page);
            } elseif (strpos($page, 'dots') !== false) {
                $page = '<span class="page_link" aria-hidden="true">...</span>';
            }
            echo $page;
        }
    }
}

function flush_rules_on_activation() {
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'flush_rules_on_activation');

// Flush rewrite khi đổi theme để /new/ và /best/ hoạt động
add_action('after_switch_theme', 'flush_rules_on_activation');