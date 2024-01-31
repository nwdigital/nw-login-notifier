<?php

if(!defined('ABSPATH')) exit;

function nwd_send_email_after_login( $user_login, $user ) {
    //combining in one header the From and content-type

    $blog_title = get_bloginfo( 'name' );
    // Get the user object.
    $user_data = get_userdata( $user->ID );
    // Get all the user roles as an array.
    $user_roles = $user_data->roles;

    $roles = implode( ", ", $user_roles );

    $email = $user_data->user_email;

    date_default_timezone_set("America/Chicago");
    $datestamp = current_datetime();
    $time = date('m-d-Y');
    $time .= " at " . date('h:i:s A');

    global $wpdb;

    $nwd_last_login_option_name = $wpdb->get_blog_prefix() . "_nwd_login_notify_last_login"; // allows this to be site specific based on the database prefix
    $last_login = update_user_option( $user->ID, $nwd_last_login_option_name, $datestamp );

    // Single Site Activated Stuff
    $login_notifier_options = get_option( 'nwd_login_notifier_settings' ); // Array of All Options
    $notification_recipient_s_0 = isset( $login_notifier_options['notification_recipient_s_0'] ) ? esc_html( $login_notifier_options['notification_recipient_s_0']) : "";
    
    
    if( is_multisite() ) {
        // echo "This is a multisite network";
        if( is_plugin_active_for_network( 'nwd-login-notifier/nwd-login-notifier.php' ) ) {
            // echo "Plugin is network activated";
            // Multisite Network Activated Stuff
            $nwd_login_notifiy_addtl_recipients = get_site_option( 'nwd_login_notifiy_addtl_recipients' ); // comma separateed list of recipients
            $nwd_admin_email = get_site_option( 'admin_email' ); // network admin email
            $notify_site_admin = ( !empty( get_site_option( 'nwd_login_notifiy_network_admin' ) ) && get_site_option( 'nwd_login_notifiy_network_admin' ) === "1" ) ? true : false;
        } else {
            // echo "Plugin is not network activated";
            $nwd_login_notifiy_addtl_recipients = $notification_recipient_s_0;
            $notify_site_admin = ( isset( $login_notifier_options['notify_site_admin_1'] ) && $login_notifier_options['notify_site_admin_1'] === "1" ) ? true : false;
            $nwd_admin_email = get_option( 'admin_email' ); // single site (sub-site) admin email
        }
    } else {
        // echo "This is not a multisite network";
        $nwd_login_notifiy_addtl_recipients = $notification_recipient_s_0;
        $notify_site_admin = ( isset( $login_notifier_options['notify_site_admin_1'] ) && $login_notifier_options['notify_site_admin_1'] === "1" ) ? true : false;
        $nwd_admin_email = get_option( 'admin_email' ); // single site (sub-site) admin email
    }

    $notify = true;

    if( $notify_site_admin && !empty( $nwd_login_notifiy_addtl_recipients) ) {
        $to =  $nwd_login_notifiy_addtl_recipients . "," . $nwd_admin_email;
    } elseif( $notify_site_admin && empty( $nwd_login_notifiy_addtl_recipients)  ) {
        $to = $nwd_admin_email;
    } elseif ( !$notify_site_admin && !empty( $nwd_login_notifiy_addtl_recipients) ) {
        $to = $nwd_login_notifiy_addtl_recipients;
    } else {
        $notify = false;
    }

    if( $notify ) {
        $subject = "User Login Notification for {$blog_title}";
        $headers = array('Content-Type: text/html; charset=UTF-8');
    
        $user = !empty($user->first_name) ? $user->first_name . ' ' . $user->last_name : $user_login;
        $siteURL = site_url();
        $site = "<a href='{$siteURL}'>{$blog_title}</a>";
    
        $message = "<p>A user with {$roles} rights has logged into {$site} at {$time}.</p>";
        $message .= "<p>User: {$user} </br> Email Address: {$email} </br> Username: {$user_login} </br> Site URL: {$siteURL}</p>";
    
        // send the mail message
        wp_mail( $to, $subject, $message, $headers );
    }
    
}
add_action('wp_login', 'nwd_send_email_after_login', 10, 2);

add_shortcode("test_emails_for_nwd_notifier", "test_emails_for_nwd_notifier");
function test_emails_for_nwd_notifier() {
    ob_start();

    $login_notifier_options = get_option( 'nwd_login_notifier_settings' ); // Array of All Options
    $notification_recipient_s_0 = isset( $login_notifier_options['notification_recipient_s_0'] ) ? esc_html( $login_notifier_options['notification_recipient_s_0']) : "";
    $notify_site_admin = ( isset( $login_notifier_options['notify_site_admin_1'] ) && $login_notifier_options['notify_site_admin_1'] === "1" ) ? true : false;

    // Single Site Activated Stuff
    $login_notifier_options = get_option( 'nwd_login_notifier_settings' ); // Array of All Options
    $notification_recipient_s_0 = isset( $login_notifier_options['notification_recipient_s_0'] ) ? esc_html( $login_notifier_options['notification_recipient_s_0']) : "";
    
    
    if( is_multisite() ) {
        // echo "This is a multisite network";
        if( is_plugin_active_for_network( 'nwd-login-notifier/nwd-login-notifier.php' ) ) {
            echo "Plugin is network activated";
            // Multisite Network Activated Stuff
            $nwd_login_notifiy_addtl_recipients = get_site_option( 'nwd_login_notifiy_addtl_recipients' ); // comma separateed list of recipients
            $nwd_admin_email = get_site_option( 'admin_email' ); // network admin email
            $notify_site_admin = ( !empty( get_site_option( 'nwd_login_notifiy_network_admin' ) ) && get_site_option( 'nwd_login_notifiy_network_admin' ) === "1" ) ? true : false;
        } else {
            echo "Plugin is not network activated";
            $nwd_login_notifiy_addtl_recipients = $notification_recipient_s_0;
            $notify_site_admin = ( isset( $login_notifier_options['notify_site_admin_1'] ) && $login_notifier_options['notify_site_admin_1'] === "1" ) ? true : false;
            $nwd_admin_email = get_option( 'admin_email' ); // single site (sub-site) admin email
        }
    } else {
        echo "This is not a multisite network";
        $nwd_login_notifiy_addtl_recipients = $notification_recipient_s_0;
        $notify_site_admin = ( isset( $login_notifier_options['notify_site_admin_1'] ) && $login_notifier_options['notify_site_admin_1'] === "1" ) ? true : false;
        $nwd_admin_email = get_option( 'admin_email' ); // single site (sub-site) admin email
    }

    echo "$nwd_login_notifiy_addtl_recipients<br>";
    echo "$nwd_admin_email<br>";

    return ob_get_clean();
}