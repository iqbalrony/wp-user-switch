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
		if ( isset( $_COOKIE[ WP_USERSWITCH_LOGGED_IN_COOKIE ] ) ) {
			$allowed_user_cookie = sanitize_user($_COOKIE[ WP_USERSWITCH_LOGGED_IN_COOKIE ]);
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
	if ( isset( $_COOKIE[ WP_USERSWITCH_LOGGED_IN_COOKIE ] ) ) {
		$allowed_user_cookie = sanitize_user($_COOKIE[ WP_USERSWITCH_LOGGED_IN_COOKIE ]);
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
					 <a href="<?php echo esc_url( $switch_url ); ?>"><?php esc_html_e( $user->data->display_name ); ?></a>
				 </li>
			  <?php
		  }
		  ?>
		</ul>
	</div>
	<?php
}
