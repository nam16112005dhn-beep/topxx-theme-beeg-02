<?php
get_header();
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
            <?php if ( is_active_sidebar('widget-area') ) {
                dynamic_sidebar( 'widget-area' );
            } else {
                _e(' Go to Appearance -> Widgets to add some widgets.', 'ophim');
            }
            ?>
        </div>
    </div>
</div>
<?php
get_footer();
?>