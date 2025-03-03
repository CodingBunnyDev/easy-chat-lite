<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function cbec_lite_styles() {
    if ( isset( $_GET['page'] ) ) {
        $page = sanitize_text_field( wp_unslash( $_GET['page'] ) );
        if ( $page === 'coding-bunny-easy-chat' ) {

            $css_file = plugin_dir_path( __FILE__ ) . '../assets/css/styles.css';
            if ( file_exists( $css_file ) ) {
                $version = filemtime( plugin_dir_path( __FILE__ ) . '../assets/css/styles.css' );
                wp_enqueue_style( 'cbec-admin-styles', plugin_dir_url( __FILE__ ) . '../assets/css/styles.css', [], $version );
            }

            $js_file = plugin_dir_path( __FILE__ ) . '../assets/js/scripts.js';
            if ( file_exists( $js_file ) ) {
                $version = filemtime( plugin_dir_path( __FILE__ ) . '../assets/js/scripts.js' );
                wp_enqueue_script( 'cbec-admin-script', plugin_dir_url( __FILE__ ) . '../assets/js/scripts.js', ['jquery'], $version, true );
            }
        }
    }
}
add_action( 'admin_enqueue_scripts', 'cbec_lite_styles' );