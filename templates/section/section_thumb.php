
<div id="list_categories_categories_list">
    <div class="headline">
        <h1><?= $title; ?></h1>

        <div class="thumb_filter">
            <span class="thumb_filter_group">
                <a class="thumb_filter_element tree22" href="<?php echo esc_url(home_url('/new/')); ?>" title="New Videos">New Videos</a>
                <a class="thumb_filter_element tree22" href="<?php echo esc_url(home_url('/best/')); ?>" title="Best Videos">Best Videos</a>
                
                <a class="thumb_filter_element tree a_show_filter" href="#"><img width="25" height="25" alt="Filter" class="show_filter" src="<?= get_template_directory_uri() ?>/assets/images/filter.svg"></a>
        
            </span>
        </div>
    </div>

    <p class="list-videos list_bb" id="list_videos_common_videos_list_items">
        <?php $key =0; while ($query->have_posts()) : $query->the_post();
        $xClass = 'item';
        if ($key === 0 || $key % 6 === 0) {
            $xClass .= ' no-margin-left';
        }
        $key++;
        include THEMETEMPLADE.'/section/section_thumb_item.php';
        endwhile; ?>
    </p>
</div>
<div class="pagination">
    <?php if ($query->max_num_pages > 1) {
        ophim_pagination1($query);
    } ?>
</div>