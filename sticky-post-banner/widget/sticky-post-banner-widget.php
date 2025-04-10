<?php

use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) exit;

class SPB_Sticky_Post_Banner_Widget extends Widget_Base {

    public function get_name() {
        return 'sticky_post_banner';
    }

    public function get_title() {
        return __( 'Sticky Post Banner', 'spb' );
    }

    public function get_icon() {
        return 'eicon-post';
    }

    public function get_categories() {
        return [ 'general' ];
    }

    protected function render() {
        $sticky = get_option('sticky_posts');
        if ( empty($sticky) ) return;

        $args = [
            'post__in' => $sticky,
            'posts_per_page' => 1,
            'ignore_sticky_posts' => 1,
        ];
        $query = new WP_Query($args);

        if ( $query->have_posts() ) :
            while ( $query->have_posts() ) : $query->the_post();
                $background_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
                $category = get_the_category();
                $first_category = $category ? esc_html( $category[0]->name ) : '';
                ?>
                <div class="spb-banner" style="background-image: url('<?php echo esc_url($background_url); ?>');">
                    <div class="spb-banner-content">
                        <div class="spb-banner-category"><?php echo esc_html($first_category); ?></div>
                        <h2 class="spb-banner-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
                        <div class="spb-banner-date"><?php echo get_the_date(); ?></div>
                    </div>
                </div>
                <?php
            endwhile;
            wp_reset_postdata();
        endif;
    }
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \SPB_Sticky_Post_Banner_Widget() );
