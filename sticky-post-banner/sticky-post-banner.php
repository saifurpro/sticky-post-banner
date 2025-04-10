<?php
/**
 * Plugin Name: Sticky Post Banner for Elementor
 * Plugin URI: https://saifurpro.netlify.app/
 * Description: A custom Elementor widget to display a sticky post In Hero banner. On the other hand, Use exclude_sticky as the Query ID in Elementor's Posts widget if you don't want it to appear again in your other Elementor Posts widgets (like grid, list, carousel, etc.)
 * Version: 1.1
 * Author: SaifurPro
 * Author URI: https://saifurpro.netlify.app/
 * Requires at least: 5.6
 * Requires PHP: 7.4
 */

if ( ! defined( 'ABSPATH' ) ) exit;

final class SPB_Elementor_Plugin {

    const VERSION = '1.1';
    const MIN_ELEMENTOR_VERSION = '3.0.0';
    const MIN_PHP_VERSION = '7.4';

    public function __construct() {
        // Hook into Elementor init
        add_action( 'plugins_loaded', [ $this, 'init' ] );
    }

    public function init() {
        // Check if Elementor is active and compatible
        if ( ! did_action( 'elementor/loaded' ) ) {
            add_action( 'admin_notices', [ $this, 'missing_elementor_notice' ] );
            return;
        }

        if ( version_compare( PHP_VERSION, self::MIN_PHP_VERSION, '<' ) ) {
            add_action( 'admin_notices', [ $this, 'low_php_version_notice' ] );
            return;
        }

        // Register widget and enqueue assets
        add_action( 'elementor/widgets/register', [ $this, 'register_widget' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_styles' ] );
    }

    public function register_widget( $widgets_manager ) {
        require_once plugin_dir_path( __FILE__ ) . 'widget/sticky-post-banner-widget.php';
        $widgets_manager->register( new \SPB_Sticky_Post_Banner_Widget() );
    }

    public function enqueue_styles() {
        wp_enqueue_style(
            'spb-style',
            plugin_dir_url( __FILE__ ) . 'assets/style.css',
            [],
            self::VERSION
        );
    }

    public function missing_elementor_notice() {
        echo '<div class="notice notice-error"><p><strong>Sticky Post Banner for Elementor</strong> requires <strong>Elementor</strong> to be installed and activated.</p></div>';
    }

    public function low_php_version_notice() {
        echo '<div class="notice notice-error"><p><strong>Sticky Post Banner for Elementor</strong> requires PHP version ' . self::MIN_PHP_VERSION . ' or greater.</p></div>';
    }
}
require_once plugin_dir_path( __FILE__ ) . 'includes/query-hooks.php';

new SPB_Elementor_Plugin();


