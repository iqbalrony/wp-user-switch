<?php

namespace IqbalRony\WP_User_Switch;
if ( ! defined( 'ABSPATH' ) ) {
	die;
}
if ( $_POST['wpus_allow_users_submit'] ) {
	if ( isset( $_POST['wpus_allow_users'] ) && ! empty( $_POST['wpus_allow_users'] ) ) {
		update_option( 'wpus_allow_users', $_POST['wpus_allow_users'] );
	} elseif ( empty( $_POST['wpus_allow_users'] ) ) {
		update_option( 'wpus_allow_users', array() );
	}
}
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
							</li>
					  <?php foreach ( get_users() as $user ):
						  if ( array_key_exists( 'manage_options', $user->allcaps ) == true ) {
							  continue;
						  }
						  ?>
								 <li>
									 <label>
									 <span class="username">
										 <input type="checkbox" name="wpus_allow_users[<?php echo $i; ?>]"
										        value="<?php echo $user->data->user_login; ?>" <?php echo in_array( $user->data->user_login, $role ) == true ? __( 'checked', 'user-switch' ) : ''; ?>><?php echo $user->data->user_login; ?>
									 </span>
									 </label>
									 <span class="display-name"><?php echo $user->data->display_name; ?></span>
								 </li>
						  <?php $i++; endforeach; ?>
						</ul>
					</div>
				</div>
				<button class="wp-core-ui button-primary" name="wpus_allow_users_submit" value="submit"
				        type="submit"><?php esc_html_e( 'Save Changes', 'wp-user-switch' ); ?></button>
			</form>
		</div>
	</div>
<?php
