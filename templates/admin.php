<?php

namespace IqbalRony\WP_User_Switch;
if ( ! defined( 'ABSPATH' ) ) {
	die;
}
?>
	<div class="wpus_wraper">
		<h2><?php esc_html_e( 'User Switch', 'wp-user-switch' ) ?></h2>
		<div class="wpus-table">
			<table class="table table-striped">
				<thead class="thead-light">
				<tr>
					<th><?php esc_html_e( 'Username', 'wp-user-switch' ); ?></th>
					<th><?php esc_html_e( 'Name', 'wp-user-switch' ); ?></th>
					<th><?php esc_html_e( 'Email', 'wp-user-switch' ); ?></th>
					<th><?php esc_html_e( 'Role', 'wp-user-switch' ); ?></th>
					<th><?php esc_html_e( 'Actions', 'wp-user-switch' ); ?></th>
				</tr>
				</thead>
				<tbody>
			<?php foreach ( get_users() as $user ):
				$current_user = wp_get_current_user();
				$switch_url = admin_url( 'admin.php?page=' ) .
					WP_USERSWITCH_MENU_PAGE_SLUG .
					'&wpus_username=' .
					sanitize_user( $user->data->user_login ) .
					'&wpus_userid=' .
					esc_html( $user->data->ID ) .
					'&wpus_nonce=' .
					wp_create_nonce( 'wp_user_switch_req' );
				?>
					<tr>
						<td><?php echo esc_html( $user->data->user_login ); ?></td>
						<td><?php echo esc_html( $user->data->display_name ); ?></td>
						<td><?php echo esc_html( $user->data->user_email ); ?></td>
						<td><?php echo esc_html( $user->roles[0] ); ?></td>
						<td>
					  <?php if ( $current_user->user_login == $user->data->user_login ): ?>
								 <span class="user active"><?php esc_html_e( 'Active', 'wp-user-switch' ); ?></span>
					  <?php else: ?>
								 <a class="user_switch_btn"
								    href="<?php echo esc_url( $switch_url ); ?>"><?php esc_html_e( 'Switch&nbsp;To', 'wp-user-switch' ); ?></a>
					  <?php endif; ?>
						</td>
					</tr>
			<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
<?php
