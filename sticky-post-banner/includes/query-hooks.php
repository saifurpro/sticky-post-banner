<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// // Exclude sticky posts in Elementor Posts widget
add_action( 'elementor/query/exclude_sticky', function( $query ) {
    $query->set( 'post__not_in', get_option( 'sticky_posts' ) );
});

add_action( 'pre_get_posts', function( $query ) {
    if ( is_admin() || $query->get( 'ignore_sticky_filter' ) ) {
        return;
    }

    if ( is_front_page() && $query->is_main_query() === false ) {
        $sticky_posts = get_option( 'sticky_posts' );
        if ( ! empty( $sticky_posts ) ) {
            $query->set( 'post__not_in', $sticky_posts );
        }
    }
});
