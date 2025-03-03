<?php

/**
* Plugin Name: CodingBunny Easy Chat Lite
* Plugin URI:  https://coding-bunny.com/easy-chat/
* Description: Allow visitors to your site to contact you via WhatsApp with just one click.
* Version:     1.0.0
* Requires at least: 6.0
* Requires PHP: 8.0
* Author:      CodingBunny
* Author URI:  https://coding-bunny.com
* Text Domain: coding-bunny-easy-chat-lite
* Domain Path: /languages
* License: GPLv2 or later
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CBEC_LITE_VERSION', '1.0.0' );

class CodingBunnyEasyChatLite {

    private $inc_dir;

    public function __construct() {
        $this->inc_dir = plugin_dir_path( __FILE__ ) . 'admin/';

        $this->load_dependencies();
        $this->register_hooks();
    }

    private function load_dependencies() {
        $files_to_include = [
			'admin-menu.php',
			'settings-page.php',
			'display-button.php',
			'enqueue-scripts.php'
        ];

        foreach ( $files_to_include as $file ) {
            $file_path = $this->inc_dir . $file;
            if ( file_exists( $file_path ) ) {
                require_once $file_path;
            }
        }
    }

    private function register_hooks() {
        add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), [ $this, 'add_action_links' ] );
    }

    public function add_action_links( $links ) {
        if ( is_array( $links ) ) {
            $settings_link = '<a href="' . esc_url( admin_url( 'admin.php?page=cbec-settings' ) ) . '">' . esc_html__( 'Settings', 'coding-bunny-easy-chat-lite' ) . '</a>';
            array_unshift( $links, $settings_link );
        }
        return $links;
    }
}

new CodingBunnyEasyChatLite();