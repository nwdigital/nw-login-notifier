<?php /**
** Plugin Name: Login Notifications by NWDigital
** Description: Email notifications when user logs in, also displays last login date/time in the users list. Multisite
** Version: 1.0.6
** Author: Mathew Moore
** Author URI: https://nwdigital.cloud/
** Plugin URI: https://nwdigital.cloud/plugins/nwd-login-notifier
**/

if(!defined('ABSPATH')) exit;

/**
 * Activation
 */
function nwd_login_notifier_activate() {
    add_option('nwd_login_notifier_settings');

    $options = array(
        'notification_recipient_s_0' => get_option('admin_email'),
        'notify_site_admin_1' => "1"
    );
    update_option('nwd_login_notifier_settings', $options);

    if ( is_multisite() ) {
        update_site_option( 'nwd_login_notifiy_addtl_recipients', get_site_option( 'admin_email' ) );
        update_site_option( 'nwd_login_notifiy_network_admin', '1' );
    }
    
}
register_activation_hook( __FILE__, "nwd_login_notifier_activate" );

/**
 * Deactivation
 */
function nwd_login_notifier_deactivate() {
    delete_option('nwd_login_notifier_settings');
    delete_site_option( 'nwd_login_notifiy_addtl_recipients' );
    delete_site_option( 'nwd_login_notifiy_network_admin' );
}
register_deactivation_hook( __FILE__, "nwd_login_notifier_deactivate" );

/**
 * Include files
 */
if( is_multisite() ) {
    include_once(ABSPATH.'wp-admin/includes/plugin.php');
    // echo "This is a multisite network";
    if( is_plugin_active_for_network( 'nwd-login-notifier/nwd-login-notifier.php' ) ) {
        // echo "Plugin is network activated";
        require('nwd-settings-network-menus.php');
    } else {
        // echo "Plugin is not network activated";
        require('nwd-settings-menus.php');
    }
} else {
    // echo "This is not a multisite network";
    require('nwd-settings-menus.php');
}

require('nwd-email-functions.php');
require('nwd-login-notifier-extra-columns.php');