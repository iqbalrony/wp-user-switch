<?php
if (!defined('ABSPATH')) {
	die;
}

/**
 * return current user role if login
 * @return array
 */
function wpus_get_current_user_roles() {
	if (is_user_logged_in()) {
		$user = wp_get_current_user();
		$roles = ( array ) $user->roles;
		return $roles; // This returns an array
		// Use this to return a single value
		// return $roles[0];
	} else {
		return array();
	}
}

/**
 * @return bool
 */
function wpus_allow_user_to_admin_bar_menu() {
	$allow = false;
	$allowed_user = get_option( 'wpus_allow_users' ) ? get_option( 'wpus_allow_users' ) : array();
	$allowed_user_cookie = '';
	if ( current_user_can( 'manage_options' ) ) {
		$allow = true;
	} else{
		if ( isset( $_COOKIE['wpus_who_switch'] ) ) {
			$allowed_user_cookie = $_COOKIE['wpus_who_switch'];
		}
		$user = get_user_by( 'login', $allowed_user_cookie );
		$allcaps = is_object($user)? (array) $user->allcaps : array();
		if ( array_key_exists( 'manage_options' , $allcaps ) == true ) {
			$allow = true;
		} elseif ( in_array( $allowed_user_cookie, $allowed_user ) == true ) {
			$allow = true;
		}
	}
	return $allow;
}

/**
 * @return bool
 */
function wpus_is_switcher_admin() {
	$allowed_user_cookie = '';
	if ( isset( $_COOKIE['wpus_who_switch'] ) ) {
		$allowed_user_cookie = $_COOKIE['wpus_who_switch'];
	}
	$user = get_user_by( 'login', $allowed_user_cookie );
	$allcaps = is_object($user)? (array) $user->allcaps : array();
	if ( array_key_exists( 'manage_options' , $allcaps ) == true ) {
		return true;
	}else{
		return false;
	}
}

/*$user = wp_get_current_user();
echo '<pre>';
var_dump($user->user_login);
echo '</pre>';*/