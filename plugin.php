<?php
/**
 * Plugin Name: WP User Switch
 * Description: Switch instantly between one user account to another account in WordPress. This plugin make this easy for you.
 * Author: IqbalRony
 * Author URI: http://www.iqbalrony.com
 * Version: 1.0.1
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wp-user-switch
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
	die;
}
/**
 * Define plugin directory path
 */
if (!defined('WP_USERSWITCH_PATH')) {
	define('WP_USERSWITCH_PATH', plugin_dir_path(__FILE__));
}
if (!function_exists('wpus_get_plugin_path')) {
	function wpus_get_plugin_path($file) {
		return WP_USERSWITCH_PATH . $file;
	}
}
if (!function_exists('wpus_plugin_url')) {
	function wpus_plugin_url($url) {
		return plugins_url($url, __FILE__);
	}
}
/**
 * Define plugin assets directory folder path
 */
if (!defined('WP_USERSWITCH_ASSETS')) {
	define('WP_USERSWITCH_ASSETS', plugins_url('/assets/', __FILE__));
}
/**
 * Define Menu page slug
 */
if (!defined('WP_USERSWITCH_MENU_PAGE_SLUG')) {
	$menu_slug = sanitize_key( 'wp-userswitch' );
	define('WP_USERSWITCH_MENU_PAGE_SLUG', $menu_slug);
}
/**
 * Hooks
 */
add_action('plugins_loaded', 'wpus_element_load');
function wpus_element_load() {
	load_plugin_textdomain('wp-user-switch', false, plugin_basename(dirname(__FILE__)) . '/languages/');
	require_once wpus_get_plugin_path( 'inc/wp-user-switch.php' );
	$WP_User_Switch = 'IqbalRony\WP_User_Switch\WP_User_Switch';
	$WP_User_Switch::instance();
}
