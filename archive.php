<?php
get_header();
?>
<div class="container">
    <div class="container">
        <div id="list_videos_latest_videos_list">
            <div id="list_categories_categories_list">
                <div class="headline">
                    <h1><?php single_term_title(); ?></h1>
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
