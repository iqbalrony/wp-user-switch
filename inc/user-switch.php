<?php
/**
 * wp user switch class
 */

namespace IqbalRony\WP_User_Switch;

use WP_Admin_Bar;

class User_Switch {

	/**
	 * @var
	 */
	public static $_instance;


	/**
	 * User_Switch constructor.
	 */
	public function __construct () {
		add_action( 'init', array( $this, 'set_cookie_if_not_set' ) );
		add_action( 'admin_page_access_denied', array( $this, 'access_denied' ) );
		add_action( 'wp_login', array( $this, 'set_cookie_by_login' ), 99 );
		add_action( 'wp_logout', array( $this, 'remove_cookie' ) );
		$this->includes();
		add_action( 'admin_menu', array( $this, 'menu_page' ) );
		add_action( 'admin_bar_menu', array( $this, 'admin_bar_item' ), 500 );
		add_action( 'wp_footer', array( $this, 'footer_markup' ), 10 );
		$this->user_switch();
		register_deactivation_hook( __FILE__, array( $this, 'remove_cookie' ) );
	}


	/**
	 * Set cookie on login
	 * @param $user_login
	 */
	public function set_cookie_by_login ( $user_login ) {
		$user = get_user_by( 'login', $user_login );
		wpus_set_user_cookie( sanitize_user( $user->ID ) );
	}


	/**
	 * Set cookie if it is not set by login
	 */
	public function set_cookie_if_not_set () {
		if ( is_user_logged_in() && ! isset( $_COOKIE[ WP_USERSWITCH_LOGGED_IN_COOKIE ] ) ) {
			$user = wp_get_current_user();
			wpus_set_user_cookie( sanitize_user( $user->ID ) );
		}
	}


	/**
	 * remove user role cookie when user logout or deactivate
	 */
	public function remove_cookie () {
		unset( $_COOKIE[ WP_USERSWITCH_LOGGED_IN_COOKIE ] );
		setcookie( WP_USERSWITCH_LOGGED_IN_COOKIE, '', time() - ( 15 * 60 ), COOKIEPATH, COOKIE_DOMAIN );
	}


	/**
	 * Create user switch settings page
	 */
	public function menu_page () {
		add_menu_page(
			__( 'User Switch Title', 'wp-user-switch' ),
			__( 'User Switch', 'wp-user-switch' ),
			'manage_options',
			WP_USERSWITCH_MENU_PAGE_SLUG,
			array( $this, 'menu_page_markup' ),
			'dashicons-buddicons-buddypress-logo'
		);
	}


	/**
	 * Include setting page markup
	 */
	public function menu_page_markup () {
		require_once wpus_get_plugin_path( 'templates/admin.php' );
		require_once wpus_get_plugin_path( 'templates/settings.php' );
	}


	/**
	 * Set user switch admin bar menu
	 * @param WP_Admin_Bar $admin_bar
	 */
	public function admin_bar_item ( \WP_Admin_Bar $admin_bar ) {
		$allow = false;
		if ( wpus_allow_user_to_admin_bar_menu() === false ) {
			return;
		}
		$admin_bar->add_menu( array(
			'id' => 'wpus',
			'parent' => null,
			'group' => null,
			'title' => __( 'User Switch', 'wp-user-switch' ),
			'href' => admin_url( 'admin.php?page=' ) . WP_USERSWITCH_MENU_PAGE_SLUG,
			'meta' => [
				'title' => __( 'User Switch', 'wp-user-switch' ),
			]
		) );

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
			$admin_bar->add_menu( array(
				'id' => 'wpus-user-' . esc_html( $user->data->user_login ),
				'parent' => 'wpus',
				'title' => esc_html( $user->data->display_name ),
				'href' => esc_url( $switch_url ),
				'meta' => [
					'class' => esc_attr( $user->data->user_login . $user->data->ID ),
				]
			) );

		}
	}


	/**
	 * User Switch function
	 */
	public function user_switch () {

		if ( is_user_logged_in() && wpus_allow_user_to_admin_bar_menu() != false ) {
			if ( isset( $_REQUEST['wpus_username'] ) && ! empty( $_REQUEST['wpus_username'] ) && isset( $_REQUEST['wpus_userid'] ) && ! empty( $_REQUEST['wpus_userid'] ) ) {
				if ( empty( $_REQUEST['wpus_nonce'] ) ) return;
				if ( ! wp_verify_nonce( $_REQUEST['wpus_nonce'], 'wp_user_switch_req' ) ) return;

				$user_caps_count = wpus_user_caps_count( $_REQUEST['wpus_userid'] );
				$allow_to_switch = wpus_check_caps_level( $user_caps_count );

				$username = sanitize_user( $_REQUEST['wpus_username'] );
				$requested_userid = esc_html( $_REQUEST['wpus_userid'] );

				wp_clear_auth_cookie();

				$user = get_user_by( 'login', $username );
				$database_user_id = esc_html( $user->ID );

				if ( $requested_userid != $database_user_id ) return;
				if ( ! $allow_to_switch ) return;

				wp_set_current_user( $database_user_id, $username );
				wp_set_auth_cookie( $database_user_id );
				$redirect_loc = admin_url( 'admin.php?page=' ) . WP_USERSWITCH_MENU_PAGE_SLUG;
				if ( isset( $_REQUEST['redirect'] ) && $_REQUEST['redirect'] ) {
					$redirect_loc = ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ? "https" : "http" ) . '://' . $_SERVER['HTTP_HOST'] . $_REQUEST['redirect'];
				}

				wp_safe_redirect( $redirect_loc );
				exit();
			}
		}
	}


	/**
	 * if user has no access permit of existing page redirect to admin page
	 */
	public function access_denied () {
		if ( is_user_logged_in() ) {
			wp_redirect( admin_url() );
			exit();
		}
	}


	/**
	 * Footer Markup
	 */
	public function footer_markup () {
		if ( wpus_allow_user_to_admin_bar_menu() === false ) return;
		if ( ! class_exists( 'WooCommerce' ) ) return;
		if ( is_user_logged_in() ) {
			wpus_frontend_userswitch_list();
		}
	}


	/**
	 * Includes files
	 */
	public function includes () {
		require_once wpus_get_plugin_path( 'inc/functions.php' );
		require_once wpus_get_plugin_path( 'inc/enqueue_scripts.php' );
	}


	/**
	 * @return WP_User_Switch Class instance
	 */
	public static function instance () {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
}
