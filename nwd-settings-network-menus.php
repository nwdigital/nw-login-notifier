<?php

if(!defined('ABSPATH')) exit;

/**
 * This file will only be used when the plugin is network activated
 */

// created from https://rudrastyh.com/wordpress-multisite/options-pages.html

add_action( 'network_admin_menu', 'rudr_network_settings_pages' );
function rudr_network_settings_pages() {

	add_menu_page( 'Login Notifications by NWDigital', 'Login Notifier', 'manage_network_options', 'nwd-login-notifier', 'nwd_login_notifier_cb', 'dashicons-lock' );
	// add_submenu_page( 'themes.php', 'More settings', 'More settings', 'manage_network_options', 'more-settings', 'more_settings_cb' );
}

function nwd_login_notifier_cb() {

	$nwd_login_notifiy_addtl_recipients = get_site_option( 'nwd_login_notifiy_addtl_recipients' );
	$nwd_login_notifiy_network_admin = get_site_option( 'nwd_login_notifiy_network_admin' );
	?>
		<div class="wrap">
			<h1>Login Notifications by NWDigital</h1>
			<form method="post" action="<?php echo add_query_arg( 'action', 'nwdnotiferaction', 'edit.php' ) ?>">
				<?php wp_nonce_field( 'nwd-login-notifier-validate' ); ?>
				
				<h2>Multisite Notification Settings</h2>
				<p class="description">Since this plugin is Network Activated, notifications for all subsites will be sent based on these settings.</p>
				<table class="form-table">
					<tr>
						<th scope="row"><label for="some_field">Additional Recipients</label></th>
						<td>
							<input name="nwd_login_notifiy_addtl_recipients" class="regular-text" type="text" id="nwd_login_notifiy_addtl_recipients" value="<?php echo esc_attr( $nwd_login_notifiy_addtl_recipients ) ?>" />
							<p class="description">Comma separated list of emails you wish to get notified when a user logs into any site or subsite.</p>
						</td>
					</tr>
					<tr>
						<th scope="row">Notify Network Admin</th>
						<td>
							<label>
								<input name="nwd_login_notifiy_network_admin" type="checkbox" value="1" <?php checked( '1', $nwd_login_notifiy_network_admin ) ?>> Yes, check this checkbox to send notification email to the multisite network admin.
							</label>
						</td>
					</tr>
				</table>
				
				<?php submit_button(); ?>
			</form>
		</div>
	<?php

}


// add_action( 'network_admin_edit_{ACTION}', 'nwd_login_notifier_save_settings' );
add_action( 'network_admin_edit_nwdnotiferaction', 'nwd_login_notifier_save_settings' );

function nwd_login_notifier_save_settings(){

	check_admin_referer( 'nwd-login-notifier-validate' ); // Nonce security check

	update_site_option( 'nwd_login_notifiy_addtl_recipients', sanitize_text_field( $_POST[ 'nwd_login_notifiy_addtl_recipients' ] ) );

	$checkbox = isset( $_POST[ 'nwd_login_notifiy_network_admin' ] ) && '1' === $_POST[ 'nwd_login_notifiy_network_admin' ] ? '1' : '';
	update_site_option( 'nwd_login_notifiy_network_admin', $checkbox );

	wp_safe_redirect(
		add_query_arg(
			array(
				'page' => 'nwd-login-notifier',
				'updated' => true
			),
			network_admin_url( 'admin.php' )
		)
	);
	exit;

}

add_action( 'network_admin_notices', 'rudr_notice' );

function rudr_notice(){

	if( isset( $_GET[ 'page' ] ) && 'nwd-login-notifier' === $_GET[ 'page' ] && isset( $_GET[ 'updated' ] )  ) {
		?><div id="message" class="updated notice is-dismissible"><p>Settings saved. Get ready for some emails!</p></div><?php
	}

}

/**
function more_settings_cb() {
}
*/