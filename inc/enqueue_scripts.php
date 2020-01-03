<?php
/**
 * Enqueue Scripts
 */
add_action('admin_enqueue_scripts', 'wpus_admin_scripts');
add_action('wp_enqueue_scripts', 'wpus_scripts');
function wpus_admin_scripts() {
	wp_enqueue_style('wpus-admin-main-css', wpus_plugin_url('/assets/css/admin-main.css'), '', '');

	wp_localize_script('jquery','wpus_localize',array(
		'wpus_nonce' => wp_create_nonce(),
	));
}
function wpus_scripts() {
	wp_enqueue_style('wpus-main-css', wpus_plugin_url('/assets/css/main.css'), '', '');
	wp_enqueue_script('wpus-main-js', wpus_plugin_url('/assets/js/main.js'), array('jquery'), '', true);
}

/*if(!isset($_COOKIE['userswitch_current_role'])) {
	echo "User Switch Role is not set." . '<br>';
} else {
	echo "User Switch Role  " . $_COOKIE['userswitch_current_role'] . '<br>';
}
if(is_user_logged_in()) {
	$current_user_roles = wpus_get_current_user_roles();
	echo "Current user role: " . $current_user_roles[0].'<br>';
}*/

//$role = get_option('userswitch_role');
//if (!is_array($role) || count($role) == 0) {
//	$role = array('administrator');
//	echo 'set-def';
//}
//if(isset($_COOKIE['userswitch_current_role'])){
//	$allowed_role = ( array ) $_COOKIE['userswitch_current_role'];
//	echo 'cccc';
//}
//else{
//	$allowed_role = wpus_get_current_user_roles();
//	echo 'not ccc';
//}
//var_dump($allowed_role[0], $role);
//if (!in_array($allowed_role[0], $role)) {
//	echo 'some';
//}