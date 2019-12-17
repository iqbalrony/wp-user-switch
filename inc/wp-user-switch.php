<?php
/**
 * wp user switch class
 */
namespace IqbalRony\WP_User_Switch;

use WP_Admin_Bar;

class WP_User_Switch {

	public static $_instance;

	public function __construct() {
		add_action( 'init', array( $this, 'set_cookie_if_not_set' ) );
		add_action( 'wp_login', array( $this, 'set_cookie' ), 99 );
		add_action( 'wp_logout', array( $this, 'remove_cookie' ) );
		$this->includes();
		add_action('admin_menu', array( $this, 'menu_page' ) );
		add_action('admin_bar_menu', array( $this, 'admin_bar_item'), 500);
		$this->user_switch();
		register_deactivation_hook( __FILE__, array( $this, 'remove_cookie' ) );
	}


	/**
	 * set user role cookie by user login
	 * @param $user_login
	 */
	public function set_cookie_by_login( $user_login ) {
		$user = get_user_by('login',$user_login);
		$roles = ( array ) $user->roles;
		setcookie( 'wpus_current_role', $roles[0], time() + (30 * MINUTE_IN_SECONDS), COOKIEPATH, COOKIE_DOMAIN );
	}


	/**
	 * set user role cookie if it is not set by login
	 */
	public function set_cookie_if_not_set( ) {
		if(is_user_logged_in() && !isset($_COOKIE['wpus_current_role'])) {
			$user = wp_get_current_user();
			$roles = ( array )$user->roles;
			setcookie('wpus_current_role', $roles[0], time() + (30 * MINUTE_IN_SECONDS), COOKIEPATH, COOKIE_DOMAIN);
		}
	}

	/**
	 * remove user role cookie when user logout or deactivate
	 */
	public function remove_cookie( ) {
		unset( $_COOKIE['wpus_current_role'] );
		setcookie( 'wpus_current_role', '', time()  - ( 15 * 60 ), COOKIEPATH, COOKIE_DOMAIN );
	}

	/**
	 * Create user switch settings page
	 */
	public function menu_page() {
		$role = get_option('wpus_role');
		if (!is_array($role) || count($role) == 0) {
			$role = array('administrator');
		}
		if(isset($_COOKIE['wpus_current_role'])){
			$allowed_role = ( array ) $_COOKIE['wpus_current_role'];
		}else{
			$allowed_role = wpus_get_current_user_roles();
		}
		if (!in_array($allowed_role[0], $role)) {
			return;
		}
		add_menu_page(
			__('User Switch Title', 'wp-user-switch'),
			'User Switch',
			'manage_options',
//			'read',
			WP_USERSWITCH_SLUG,
			array( $this, 'menu_page_markup' ),
			'dashicons-buddicons-buddypress-logo'
		);
	}

	/**
	 * Include setting page markup
	 */
	public function menu_page_markup() {
		require_once wpus_get_plugin_path('templates/admin.php');
		require_once wpus_get_plugin_path('templates/settings.php');
	}

	/**
	 * set user switch admin bar menu
	 * @param WP_Admin_Bar $admin_bar
	 */
	public function admin_bar_item(\WP_Admin_Bar $admin_bar) {

		//!isset($_COOKIE['wpus_current_role'])
		$role = get_option('wpus_role');
		if (!is_array($role) || count($role) == 0) {
			$role = array('administrator');
		}
		if(isset($_COOKIE['wpus_current_role'])){
			$allowed_role = ( array ) $_COOKIE['wpus_current_role'];
		}else{
			$allowed_role = wpus_get_current_user_roles();
		}
		if (!in_array($allowed_role[0], $role)) {
			return;
		}
		$admin_bar->add_menu(array(
			'id' => 'wpus',
			'parent' => null,
			'group' => null,
			'title' => 'User Switch',
			'href' => admin_url('admin.php?page=') . WP_USERSWITCH_SLUG,
			'meta' => [
				'title' => __('User Switch', 'wp-user-switch'),
			]
		));

		foreach (get_users() as $user) {
			$switch_url = admin_url('admin.php?page=') .
				WP_USERSWITCH_SLUG .
				'&wpus_username=' .
				$user->data->user_login .
				'&wpus_userid=' .
				$user->data->ID .
				'&redirect=' .
				$_SERVER['REQUEST_URI'];
			$admin_bar->add_menu(array(
				'id' => 'wpus-user-' . $user->data->user_login,
				'parent' => 'wpus',
				'title' => $user->data->display_name,
				'href' => $switch_url,
				'meta' => [
					'class' => $user->data->user_login . $user->data->ID,
				]
			));
		}
	}

	/**
	 * User Switch function
	 */
	public function user_switch(){
		if (is_user_logged_in()) {
			if (isset($_REQUEST['wpus_username']) && !empty($_REQUEST['wpus_username']) && isset($_REQUEST['wpus_userid']) && !empty($_REQUEST['wpus_userid'])) {
				$username = $_REQUEST['wpus_username'];
				$userid = $_REQUEST['wpus_userid'];
				wp_clear_auth_cookie();
				$user = get_user_by('login', $username);
				$user_id = $user->ID;
				if ($userid != $user_id) return;
				//var_dump(isset($_REQUEST['user_switch_username']), !empty($_REQUEST['user_switch_username']));

				wp_set_current_user($user_id, $username);
				wp_set_auth_cookie($user_id);
				$redirect_loc = admin_url('admin.php?page=') . WP_USERSWITCH_SLUG;
				if ($_REQUEST['redirect']) {
					$redirect_loc = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . '://' . $_SERVER['HTTP_HOST'] . $_REQUEST['redirect'];
				}
				wp_redirect($redirect_loc);
				exit();
			}
		}
	}




	/**
	 * Includes files
	 */
	public function includes(){
		require_once wpus_get_plugin_path( 'inc/functions.php' );
		require_once wpus_get_plugin_path( 'inc/enqueue_scripts.php' );
	}




	/**
	 * Instantiate the plugin
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
}

