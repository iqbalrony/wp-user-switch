<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
	die;
}

/**
 * Enqueue Admin Scripts
 */
add_action('admin_enqueue_scripts', 'wpus_admin_scripts');
function wpus_admin_scripts() {
	wp_enqueue_style('wpus-admin-main-css', wpus_plugin_url('/assets/css/admin-main.css'), '', '');

	// select2
		wp_enqueue_style(
			'select2',
			'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
			[],
			'4.1.0',
			'all'
		);

		wp_enqueue_script(
			'select2',
			'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
			['jquery'],
			'4.1.0',
			true
		);

		wp_enqueue_script(
			'wpus-admin-js',
			wpus_plugin_url('/assets/js/admin.js'),
			['jquery'],
			'',
			true
		);

	wp_localize_script('jquery','wpus_localize',array(
		'wpus_nonce' => wp_create_nonce(),
	));
}

/**
 * Enqueue Scripts
 */
add_action('wp_enqueue_scripts', 'wpus_scripts');
function wpus_scripts() {
	wp_enqueue_style('wpus-main-css', wpus_plugin_url('/assets/css/main.css'), '', '');
	wp_enqueue_script('wpus-main-js', wpus_plugin_url('/assets/js/main.js'), array('jquery'), '', true);
}
