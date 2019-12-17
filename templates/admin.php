<?php
namespace IqbalRony\WP_User_Switch;
if (!defined('ABSPATH')) {
	die;
}
?>
	<div class="wrap">
		<h2>User Switch</h2>
		<div class="user-switch-table">
			<table class="table table-striped">
				<thead class="thead-light">
				<tr>
					<th><?php esc_html_e('Username', 'wp-user-switch'); ?></th>
					<th><?php esc_html_e('Name', 'wp-user-switch'); ?></th>
					<th><?php esc_html_e('Email', 'wp-user-switch'); ?></th>
					<th><?php esc_html_e('Role', 'wp-user-switch'); ?></th>
					<th><?php esc_html_e('Actions', 'wp-user-switch'); ?></th>
				</tr>
				</thead>
				<tbody>
				<?php foreach (get_users() as $user):
					$current_user = wp_get_current_user();
					$switch_url = admin_url('admin.php?page=').
						WP_USERSWITCH_SLUG .
						'&wpus_username=' .
						$user->data->user_login .
						'&wpus_userid=' .
						$user->data->ID;
					?>
					<tr>
						<td><?php echo $user->data->user_login; ?></td>
						<td><?php echo $user->data->display_name; ?></td>
						<td><?php echo $user->data->user_email; ?></td>
						<td><?php echo $user->roles[0]; ?></td>
						<td>
							<?php if($current_user->user_login == $user->data->user_login):?>
								<span class="user active"><?php esc_html_e('Active','wp-user-switch'); ?></span>
							<?php else: ?>
								<a class="user_switch_btn" href="<?php echo esc_url($switch_url); ?>"><?php esc_html_e('Switch&nbsp;To','wp-user-switch'); ?></a>
							<?php endif;?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
<?php
