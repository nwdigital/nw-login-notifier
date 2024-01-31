<?php

if(!defined('ABSPATH')) exit;

/**
 * This file will only be used when the plugin is NOT network activated
 */

 class NwdLoginNotifier {
	private $login_notifier_options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'login_notifier_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'login_notifier_page_init' ) );
	}

	public function login_notifier_add_plugin_page() {
		add_menu_page(
			'Login Notifications by NWDigital', // page_title
			'Login Notifier', // menu_title
			'manage_options', // capability
			'nwd-login-notifier', // menu_slug
			array( $this, 'login_notifier_create_admin_page' ), // function
			'dashicons-lock', // icon_url
			// 2 // position
		);
	}

	public function login_notifier_create_admin_page() {
		$this->login_notifier_options = get_option( 'nwd_login_notifier_settings' ); ?>

		<div class="wrap">
			<h2>Login Notifications by NWDigital</h2>
			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php
					settings_fields( 'login_notifier_option_group' );
					do_settings_sections( 'login-notifier-admin' );
					submit_button();
				?>
			</form>
		</div>
	<?php }

	public function login_notifier_page_init() {
		register_setting(
			'login_notifier_option_group', // option_group
			'nwd_login_notifier_settings', // option_name
			array( $this, 'login_notifier_sanitize' ) // sanitize_callback
		);

		add_settings_section(
			'login_notifier_setting_section', // id
			'Single Site Notification Settings', // title
			array( $this, 'login_notifier_section_info' ), // callback
			'login-notifier-admin' // page
		);

		add_settings_field(
			'notification_recipient_s_0', // id
			'Notification Recipient(s)', // title
			array( $this, 'notification_recipient_s_0_callback' ), // callback
			'login-notifier-admin', // page
			'login_notifier_setting_section' // section
		);

		add_settings_field(
			'notify_site_admin_1', // id
			'Notify Site Admin', // title
			array( $this, 'notify_site_admin_1_callback' ), // callback
			'login-notifier-admin', // page
			'login_notifier_setting_section' // section
		);
	}

	public function login_notifier_sanitize($input) {
		$sanitary_values = array();
		if ( isset( $input['notification_recipient_s_0'] ) ) {
			$sanitary_values['notification_recipient_s_0'] = sanitize_text_field( $input['notification_recipient_s_0'] );
		}

		if ( isset( $input['notify_site_admin_1'] ) ) {
			$sanitary_values['notify_site_admin_1'] = $input['notify_site_admin_1'];
		}

		return $sanitary_values;
	}

	public function login_notifier_section_info() {
		
	}

	public function notification_recipient_s_0_callback() {
		printf(
			'<input class="regular-text" type="text" name="nwd_login_notifier_settings[notification_recipient_s_0]" id="notification_recipient_s_0" value="%s">',
			isset( $this->login_notifier_options['notification_recipient_s_0'] ) ? esc_attr( $this->login_notifier_options['notification_recipient_s_0']) : ''
		);
	}

	public function notify_site_admin_1_callback() {
		printf(
			'<input type="checkbox" name="nwd_login_notifier_settings[notify_site_admin_1]" id="notify_site_admin_1" value="1" %s>',
			( isset( $this->login_notifier_options['notify_site_admin_1'] ) && $this->login_notifier_options['notify_site_admin_1'] === "1" ) ? 'checked' : ''
		);
	}

}
if ( is_admin() )
	$login_notifier = new NwdLoginNotifier();

/* 
 * Retrieve this value with:
 * $login_notifier_options = get_option( 'nwd_login_notifier_settings' ); // Array of All Options
 * $notification_recipient_s_0 = $login_notifier_options['notification_recipient_s_0']; // Notification Recipient(s)
 * $notify_site_admin_1 = $login_notifier_options['notify_site_admin_1']; // Notify Network Admin
 */
