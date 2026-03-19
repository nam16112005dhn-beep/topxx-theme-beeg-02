<?php
$taxonomyName = 'ophim_genres';
$taxonomy = get_the_terms(get_the_id(), $taxonomyName);
$runtime = op_get_runtime();
$preview_enabled = get_theme_mod('ophim_hover_preview', true);
$preview_images = function_exists('op_get_preview_images') ? op_get_preview_images() : array();
$use_preview = $preview_enabled && count($preview_images) >= 2;
$preview_attr = $use_preview ? ' data-preview-images="' . esc_attr(wp_json_encode($preview_images)) . '"' : '';
$preview_class = $use_preview ? ' js-hover-preview' : '';
?>
<span class="item"> 
    <a class="item_a<?php echo $preview_class; ?>" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"<?php echo $preview_attr; ?>> 
        <span style="display:block;width:100%;aspect-ratio:16/9;overflow:hidden;position:relative;background:#1a1a1a;">
        <img src="<?php echo op_get_poster_url(); ?>" class="img_thumb lazyload js-preview-img" alt="<?php the_title(); ?>" width="400" height="225" style="position:absolute;top:0;left:0;width:100%;height:100%;object-fit:cover;object-position:center;display:block;">
        </span>
    </a> 
    <span class="item_title"> <?php the_title(); ?> </span> 
    <span class="item_info_link"> 
        <?php foreach ($taxonomy as $term) : ?>
            <a href="<?php echo get_term_link($term->term_id); ?>" title="<?php echo $term->name; ?>" class="item_cs item_model "><?php echo $term->name; ?></a> 
        <?php endforeach; ?>
    </span> 
    <span class="duration"><?php echo $runtime; ?></span> 
</span>