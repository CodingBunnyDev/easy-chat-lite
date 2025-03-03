<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function cbec_lite_menu() {
	add_menu_page(
	esc_html__( 'CodingBunny Easy Chat', 'coding-bunny-easy-chat-lite' ),
	esc_html__( 'Easy Chat', 'coding-bunny-easy-chat-lite' ),
	'manage_options',
	'coding-bunny-easy-chat',
	'cbec_settings',
	'data:image/svg+xml;base64,' . base64_encode(file_get_contents(plugin_dir_path(__FILE__) . '../assets/images/cbec-icon.svg')),
	11
);
}
add_action( 'admin_menu', 'cbec_lite_menu' );