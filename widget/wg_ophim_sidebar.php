<?php
class WG_oPhim_Sidebar extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
            'wg_sidebar', // Base ID
            __( 'Ophim Sidebar', 'ophim' ), // Name
            array( 'description' => __( 'Top 10 phim lượt xem cao (PHIM HOT TRONG TUẦN)', 'ophim' ), ) // Args
        );
    }

    /**
     * Front-end display of widget.
     * Hiển thị 10 video có lượt xem cao nhất (theo meta ophim_view).
     * Theme hiện chỉ lưu tổng lượt xem; muốn "theo ngày" cần thêm meta/cron lưu lượt xem theo ngày.
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        extract($args);
        $title = !empty($instance['title']) ? $instance['title'] : __( 'PHIM HOT TRONG TUẦN', 'ophim' );

        $query = new WP_Query(array(
            'post_type'      => 'ophim',
            'post_status'    => 'publish',
            'posts_per_page' => 10,
            'meta_key'       => 'ophim_view',
            'orderby'        => 'meta_value_num',
            'order'          => 'DESC',
        ));

        ob_start();
        echo $before_widget;
        ?>
        <div class="sidebar_list_categories sidebar-hot-week">
            <div class="sidebar_list_categories_bg">
                <div class="headline">
                    <h2><?php echo esc_html($title); ?></h2>
                </div>
                <ul class="list-videos sidebar-hot-list">
                    <?php
                    if ($query->have_posts()) :
                        while ($query->have_posts()) :
                            $query->the_post();
                            ?>
                            <li class="sidebar-hot-item">
                                <a class="sidebar-hot-poster-wrap" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                                    <?php
                                    $poster_url = function_exists('op_get_poster_url') ? op_get_poster_url() : get_the_post_poster_url(get_the_ID(), 'poster');
                                    if (empty($poster_url)) {
                                        $poster_url = get_template_directory_uri() . '/assets/images/placeholder.png';
                                    }
                                    if (empty($poster_url)) {
                                        $poster_url = 'data:image/svg+xml,' . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" width="80" height="120" viewBox="0 0 80 120"><rect fill="#2c2c31" width="80" height="120"/><text x="50%" y="50%" fill="#666" text-anchor="middle" dy=".3em" font-size="10">No image</text></svg>');
                                    }
                                    ?>
                                    <img src="<?php echo esc_url($poster_url); ?>" class="sidebar-hot-poster img_thumb" alt="<?php the_title_attribute(); ?>" width="80" height="120" loading="lazy" />
                                </a>
                                <div class="sidebar-hot-content">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </div>
                            </li>
                        <?php
                        endwhile;
                        wp_reset_postdata();
                    else :
                        ?>
                        <li class="sidebar-hot-item empty"><?php _e('Chưa có dữ liệu.', 'ophim'); ?></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
        <?php
        echo $after_widget;
        $html = ob_get_clean();
        echo $html;
    }

    /**
     * Back-end widget form.
     *
     * @param array $instance Previously saved values from database.
     */
    function form($instance) {
        $instance = wp_parse_args((array) $instance, array(
            'title' => __( 'PHIM HOT TRONG TUẦN', 'ophim' ),
        ));
        $title = $instance['title'];
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Tiêu đề', 'ophim'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p class="description"><?php _e('Hiển thị 10 phim có lượt xem cao nhất (sắp xếp theo meta ophim_view).', 'ophim'); ?></p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = sanitize_text_field($new_instance['title']);
        return $instance;
    }
}

function register_new_WG_oPhim_Sidebar() {
    register_widget( 'WG_oPhim_Sidebar' );
}
add_action( 'widgets_init', 'register_new_WG_oPhim_Sidebar' );
