<?php

function add_theme_widgets() {
    $activate = array(
        'widget-footer' => array(
            'wg_footer-0',
        )
    );
    update_option('widget_wg_footer', array(
        0 => array('footer' => '<div class="footer">
    <div class="copyright"> <a href="/" class="footer-logo" aria-label="Home"> </a></div>
    <div class="txt_intro"> By entering this site you swear that you are of legal age in your area to view adult material and that you wish to view such material.All porn videos and images are property and copyright of their owners.All models appearing on this website are 18 years or older. This site is rated with RTA label. Parents, you can easily block access to this site. Please read this page for more informations. Cookies help us deliver our services. By using our services, you agree to our use of cookies</div> 2026 © BeegPorn. All rights reserved. <br>
</div>')
    ));
    update_option('sidebars_widgets',  $activate);

}

add_action('after_switch_theme', 'add_theme_widgets', 10, 2);