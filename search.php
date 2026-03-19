<?php
get_header();
?>

<?php
if (!isset($_GET['filter'])){
    $_GET['filter']['categories'] ='';
    $_GET['filter']['genres'] ='';
    $_GET['filter']['regions'] ='';
    $_GET['filter']['years'] ='';
}
?>
<div class="container">
    <div class="container">
        <div id="list_videos_latest_videos_list">
            <div id="list_categories_categories_list">
                <div class="headline">
                    <h1>SEARCH: "<?= $_GET['s'] ?>"</h1>
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
                     ?>
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
