<?php
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * return current user role if login
 * @return array
 */
function wpus_get_current_user_roles () {
	if ( is_user_logged_in() ) {
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
function wpus_allow_user_to_admin_bar_menu () {
	$allow = false;
	$allowed_user = get_option( 'wpus_allow_users' ) ? get_option( 'wpus_allow_users' ) : array();
	$allowed_user_cookie = '';
	if ( current_user_can( 'manage_options' ) ) {
		$allow = true;
	} else {
		$set_option = wpus_get_switched_user();
		if ( $set_option ) {
			$allowed_user_cookie = sanitize_user($set_option->data->user_login);
		}
		$user = get_user_by( 'login', $allowed_user_cookie );
		$allcaps = is_object( $user ) ? (array) $user->allcaps : array();
		if ( array_key_exists( 'manage_options', $allcaps ) == true ) {
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
function wpus_is_switcher_admin () {
	$allowed_user_cookie = '';
	$set_option = wpus_get_switched_user();
	if ( $set_option ) {
		$allowed_user_cookie = sanitize_user($set_option->data->user_login);
	}
	$user = get_user_by( 'login', $allowed_user_cookie );
	$allcaps = is_object( $user ) ? (array) $user->allcaps : array();
	if ( array_key_exists( 'manage_options', $allcaps ) == true ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Footer Markup
 */
function wpus_frontend_userswitch_list () {
	?>
	<div class="wpus_front_list">
		<span class="wpus_front_icon">
			<img src="<?php echo wpus_plugin_url( '/assets/images/front-icon.png' ) ?>" alt="">
			<span class="wpus_front_title"><?php esc_html_e( 'User Switch', 'wp-user-switch' ) ?></span>
		</span>
		<ul>
		  <?php
		  foreach ( get_users() as $user ) {
			  if ( wpus_is_switcher_admin() !== true && array_key_exists( 'manage_options', $user->allcaps ) == true ) {
				  continue;
			  }
			  $switch_url = admin_url( 'admin.php?page=' ) .
				  WP_USERSWITCH_MENU_PAGE_SLUG .
				  '&wpus_username=' .
				  sanitize_user( $user->data->user_login ) .
				  '&wpus_userid=' .
				  esc_html( $user->data->ID ) .
				  '&redirect=' .
				  $_SERVER['REQUEST_URI'] .
				  '&wpus_nonce=' .
				  wp_create_nonce( 'wp_user_switch_req' );
			  ?>
				 <li>
					 <a href="<?php echo esc_url( $switch_url ); ?>"><?php echo esc_html( $user->data->display_name ); ?></a>
				 </li>
			  <?php
		  }
		  ?>
		</ul>
	</div>
	<?php
}

function wpus_set_user_cookie( $user_id ) {
	$token = function_exists( 'wp_get_session_token' ) ? wp_get_session_token() : '';
	$expiration = time() + ( 1 * DAY_IN_SECONDS ); // 24 hours
	$auth_cookie = wp_generate_auth_cookie( $user_id, $expiration, 'logged_in', $token );
	$secure_cookie = ( is_ssl() && ( 'https' === parse_url( home_url(), PHP_URL_SCHEME ) ) );
	$http_only = true;
	setcookie( WP_USERSWITCH_LOGGED_IN_COOKIE, $auth_cookie, $expiration, COOKIEPATH, COOKIE_DOMAIN, $secure_cookie, $http_only );
}


function wpus_get_user_cookie() {
	if ( isset( $_COOKIE[ WP_USERSWITCH_LOGGED_IN_COOKIE ] ) ) {
		return wp_unslash( $_COOKIE[ WP_USERSWITCH_LOGGED_IN_COOKIE ] );
	}
	return false;
}

function wpus_get_switched_user() {
	$cookie = wpus_get_user_cookie();
	if ( ! empty( $cookie ) ) {
		$old_user_id = wp_validate_auth_cookie( $cookie, 'logged_in' );
		if ( $old_user_id ) {
			return get_userdata( $old_user_id );
		}
		return false;
	}
	return false;
}
