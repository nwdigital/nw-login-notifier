<?php

if(!defined('ABSPATH')) exit;

add_action( 'admin_menu', 'nwd_add_admin_menu' );
add_action( 'admin_init', 'nwd_settings_init' );


function nwd_add_admin_menu(  ) {

	add_menu_page(
    'NWDigital Login Notifier',
    'NWD Login Notifier',
    'manage_options',
    'nwdigital_login_notifier',
    'nwd_options_page',
    'dashicons-lock',
  );

}


function nwd_settings_init(  ) {

	register_setting( 'pluginPage', 'nwd_settings' );

	add_settings_section(
		'nwd_pluginPage_section',
		__( 'Notification Settings', 'wordpress' ),
		'nwd_settings_section_callback',
		'pluginPage'
	);

	add_settings_field(
		'nwd_text_field_recipients',
		__( 'Notification Recipient(s)', 'wordpress' ),
		'nwd_text_field_recipients_render',
		'pluginPage',
		'nwd_pluginPage_section'
	);

	add_settings_field(
		'nwd_checkbox_include_site_admin',
		__( 'Notify Network Admin', 'wordpress' ),
		'nwd_checkbox_include_site_admin_render',
		'pluginPage',
		'nwd_pluginPage_section'
	);


}


function nwd_text_field_recipients_render(  ) {

	$options = get_option( 'nwd_settings' );
	$recipients = isset( $options['nwd_text_field_recipients'] ) ? $options['nwd_text_field_recipients'] : "";

	?>
	<input type='text' name='nwd_settings[nwd_text_field_recipients]' value='<?php echo wp_strip_all_tags($recipients); ?>'>
	<?php

}


function nwd_checkbox_include_site_admin_render(  ) {

	$options = get_option( 'nwd_settings' );
	$notify_admin = isset( $options['nwd_checkbox_include_site_admin'] ) ? $options['nwd_checkbox_include_site_admin'] : "";
	?>
	<input type='checkbox' name='nwd_settings[nwd_checkbox_include_site_admin]' <?php checked( absint($notify_admin), 1 ); ?> value='1'>
	<?php

}


function nwd_settings_section_callback(  ) {

	echo __( 'Configure the settings for notifications from NWDigital Login Notifier', 'wordpress' );

}


function nwd_options_page(  ) {

		?>
		<form action='options.php' method='post'>

			<h2>NWDigital Login Notifier</h2>

			<?php
			settings_fields( 'pluginPage' );
			do_settings_sections( 'pluginPage' );
			submit_button();
			?>

		</form>
		<?php

}
