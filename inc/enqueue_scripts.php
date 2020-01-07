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