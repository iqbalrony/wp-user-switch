<?php

namespace IqbalRony\WP_User_Switch;
if ( ! defined( 'ABSPATH' ) ) {
	die;
}
if ( isset($_POST['wpus_allow_users_submit']) && wp_verify_nonce( $_POST['wpus_allow_users_nonce'], 'wpus_allow_users_nonce' ) ) {
	if ( isset( $_POST['wpus_allow_users'] ) && ! empty( $_POST['wpus_allow_users'] ) && is_array( $_POST['wpus_allow_users'] ) ) {
			$allow_users = array();
		   foreach ( $_POST['wpus_allow_users'] as $key => $value ) {
		     $allow_users[ sanitize_key( $key ) ] = sanitize_text_field( $value );
		   }
	   update_option( 'wpus_allow_users', $allow_users );
	} elseif ( empty( $_POST['wpus_allow_users'] ) ) {
		update_option( 'wpus_allow_users', array() );
	}

	// selected user set
	if ( isset( $_POST['wpus_allow_selected_users'] ) && ! empty( $_POST['wpus_allow_selected_users'] ) && is_array( $_POST['wpus_allow_selected_users'] ) ) {
			$selected_users = array();
		   foreach ( $_POST['wpus_allow_selected_users'] as $key => $value ) {
		    //  $selected_users[ sanitize_key( $key ) ] = ! empty( $value ) ? array_push($value,$key) : [] ;

			 if( is_array( $value ) && ! empty( $value ) ){
				array_push($value,$key);
			 }else{
				 continue;
			 }
		    //  $selected_users[ sanitize_key( $key ) ] = sanitize_text_field( $value );
			 foreach ( $value as $key2 => $value2 ) {
				$selected_users[ sanitize_key( $key ) ][] = sanitize_text_field( $value2 );
			  }
		   }
		//    echo '<pre>';
		//    var_dump($_POST['wpus_allow_selected_users']);
		//    var_dump($selected_users);
		//    echo '</pre>';
		//    error_log( print_r( $_POST['wpus_allow_selected_users'] , 1 ) );
		//    error_log( print_r( $selected_users , 1 ) );
	   update_option( 'wpus_allow_selected_users', $selected_users );
	//    update_option( 'wpus_allow_selected_users', $_POST['wpus_allow_selected_users'] );
	} elseif ( empty( $_POST['wpus_allow_selected_users'] ) ) {
		update_option( 'wpus_allow_selected_users', array() );
	}
}
// update_option( 'wpus_allow_selected_users', array() );
// echo '<pre>';
// var_dump(get_option( 'wpus_allow_selected_users' ));
// echo '</pre>';
$role = get_option( 'wpus_allow_users' ) ? get_option( 'wpus_allow_users' ) : array();
$i = 0;
?>
	<div class="wpus-settings-area">
		<h2><?php esc_html_e( 'User Switch Settings', 'user-switch' ); ?></h2>
		<div class="wpus-settings">
			<form method="post" class="user_switch" action="">
				<div class="wpus-allow-user-table">
					<div class="wpus-allow-user-title">
						<h4><?php esc_html_e( 'Allowed User To Switch', 'user-switch' ); ?></h4>
					</div>
					<div class="wpus-allow-user-wrap">
						<ul>
							<li class="title">
								<span class="username"><?php esc_html_e( 'Username', 'wp-user-switch' ); ?></span>
								<span class="name"><?php esc_html_e( 'Name', 'wp-user-switch' ); ?></span>
								<span class="selected"><?php esc_html_e( 'Selected User', 'wp-user-switch' ); ?></span>
							</li>
					  <?php foreach ( get_users() as $user ):
						  if ( array_key_exists( 'manage_options', $user->allcaps ) == true ) {
							  continue;
						  }
						  ?>
								 <li>
									 <span class="username">
									 <label>
										 <input type="checkbox" name="wpus_allow_users[<?php echo $i; ?>]"
										        value="<?php echo sanitize_user( $user->data->user_login ); ?>" <?php echo in_array( $user->data->user_login, $role ) == true ? __( 'checked', 'user-switch' ) : ''; ?>><?php echo sanitize_user( $user->data->user_login ); ?>
											</label>
									</span>
									 <span class="display-name"><?php echo esc_html( $user->data->display_name ); ?></span>
									 <span class="selected-users">
										<?php
											echo wpus_get_user_list(sanitize_user( $user->data->user_login ));
										?>
									 </span>
								 </li>
						  <?php $i++; endforeach; ?>
						</ul>
					</div>
				</div>
				<input type="hidden" name="wpus_allow_users_nonce"
				       value="<?php echo wp_create_nonce( 'wpus_allow_users_nonce' ); ?>">
				<button class="wp-core-ui button-primary" name="wpus_allow_users_submit" value="submit"
				        type="submit"><?php esc_html_e( 'Save Changes', 'wp-user-switch' ); ?></button>
			</form>
		</div>
	</div>
<?php
