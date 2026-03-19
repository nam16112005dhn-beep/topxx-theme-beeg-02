<?php

add_action('wp_ajax_ratemovie', 'ratemovie_init');
add_action('wp_ajax_nopriv_ratemovie', 'ratemovie_init');
function ratemovie_init()
{
    $rate = $_POST['rating'];
    $key = 'ophim_votes';
    $post_id = $_POST['postid'];
    $count = (int)get_post_meta($post_id, $key, true);
    $count++;
    update_post_meta($post_id, $key, $count);

    $rate = $_POST['rating'];
    $key = 'ophim_rating';
    $post_id = $_POST['postid'];
    $rating = (int)get_post_meta($post_id, $key, true);

    $updaterate = $rating + ((int)$rate - $rating) / ($count);
    update_post_meta($post_id, $key, $updaterate);


    $result = array('status' => 'success', 'rating_star' => number_format($rate, 1), 'rating_count' => $count,);
    header('Content-Type: application/json');
    echo json_encode($result);
    die();

}

add_action('wp_ajax_reportbug', 'reportbug_init');
add_action('wp_ajax_nopriv_reportbug', 'reportbug_init');
function reportbug_init()
{
    $result = array('status' => true);
    header('Content-Type: application/json');
    echo json_encode($result);
    die();
}

add_action('wp_ajax_load_actors', 'load_actors_init');
add_action('wp_ajax_nopriv_load_actors', 'load_actors_init');
function load_actors_init() {
    $limit = isset($_POST['limit']) ? intval($_POST['limit']) : 50;
    
    $actors = get_terms(array(
        'taxonomy'   => 'ophim_actors',
        'hide_empty' => true,
        'number'     => $limit,
        'orderby'    => 'count',
        'order'      => 'DESC',
    ));
    
    $result = array();
    
    if (!is_wp_error($actors) && !empty($actors)) {
        foreach ($actors as $actor) {
            $avatar = get_term_meta($actor->term_id, 'actor_avatar', true);
            
            if (empty($avatar)) {
                $avatar = get_template_directory_uri() . '/assets/images/avatar-default.webp';
            }

            $getslug = get_option('ophim_slug_actors');
            if($getslug){
                $slug = $getslug;
            }else{
                $slug = 'actors';
            }
            
            $result[] = array(
                'id'     => $actor->term_id,
                'name'   => $actor->name,
                'slug'   => $actor->slug,
                'count'  => $actor->count,
                'avatar' => $avatar,
                'link'   => home_url($slug . "/" . $actor->slug),
            );
        }
    }
    
    header('Content-Type: application/json');
    echo json_encode(array(
        'status' => 'success',
        'actors' => $result,
        'total'  => count($result),
    ));
    die();
}