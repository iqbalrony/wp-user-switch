<?php
namespace IqbalRony\WP_User_Switch;
if (!defined('ABSPATH')) {
	die;
}
$current_user_roles = wpus_get_current_user_roles();
if ($current_user_roles[0] == 'administrator' && $_COOKIE['wpus_current_role'] == 'administrator') :

	if (isset($_POST['wpus_role']) && !empty($_POST['wpus_role'])) {
		update_option('wpus_role', $_POST['wpus_role']);
	}
	$role = get_option('wpus_role');
//var_dump($role);
	if (!is_array($role) || count($role) == 0) {
		$role = array('administrator');
	}

//var_dump(get_option( 'user_switch_role' ));
	?>
	<div class="user-switch-settings-area">
		<h2><?php esc_html_e('User Switch Settings', 'user-switch'); ?></h2>
		<div class="user-switch-settings">
			<form method="post" class="user_switch" action="">

				<table class="form-table">
					<tbody>
					<tr>
						<th class="user_switch_role_title">
							<label><?php esc_html_e('Allowed User Roles', 'user-switch'); ?></label>
						</th>
						<td class="user_switch_role_input">
							<input type="checkbox" name="wpus_role[0]"
							       value="administrator" <?php echo in_array("administrator", $role) == true ? __('checked', 'user-switch') : ''; ?>><?php esc_html_e('Administrator', 'user-switch'); ?>
							<input type="checkbox" name="wpus_role[1]"
							       value="editor" <?php echo in_array("editor", $role) == true ? __('checked', 'user-switch') : ''; ?>><?php esc_html_e('Editor', 'user-switch'); ?>
							<input type="checkbox" name="wpus_role[2]"
							       value="author" <?php echo in_array("author", $role) == true ? __('checked', 'user-switch') : ''; ?>><?php esc_html_e('Author', 'user-switch'); ?>
							<input type="checkbox" name="wpus_role[3]"
							       value="contributor" <?php echo in_array("contributor", $role) == true ? __('checked', 'user-switch') : ''; ?>><?php esc_html_e('Contributor', 'user-switch'); ?>
							<input type="checkbox" name="wpus_role[4]"
							       value="subscriber" <?php echo in_array("subscriber", $role) == true ? __('checked', 'user-switch') : ''; ?>><?php esc_html_e('Subscriber', 'user-switch'); ?>
							<p class="description"><?php esc_html_e('Only allowed user can switch from one user to another user.', 'user-switch'); ?></p>
						</td>
					</tr>
					</tbody>
				</table>
				<button class="wp-core-ui button-primary"
				        type="submit"><?php esc_html_e('Save Changes', 'user-switch'); ?></button>
			</form>
		</div>
	</div>
<?php
endif;