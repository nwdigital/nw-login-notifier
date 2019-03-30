<?php /**
** Plugin Name: NWDigital Login Notifier
** Description: Email notifications when user logs in
** Version: 1.0.0
** Author: Mathew Moore
** Author URI: https://northwoodsdigital.com
** Plugin URI: https://northwoodsdigital.com
**/

if(!defined('ABSPATH')) exit;

function nwd_send_email_after_login( $user_login, $user ) {
    //combining in one header the From and content-type

    $blog_title = get_bloginfo( 'name' );
    $user_data = get_userdata( $user->ID );
    $email = $user_data->user_email;

    date_default_timezone_set("America/Chicago");
    $time = date('m-d-Y');
    $time .= " at " . date('h:i:s A');

    // static mail stuff here
    $to = get_option('admin_email');
    $subject = "User Login Notification for {$blog_title}";
    $headers = array('Content-Type: text/html; charset=UTF-8');

    $message = "<p>{$user->first_name} {$user->last_name} has logged into {$blog_title} at {$time}.</p>";
    $message .= "<p>Email Address: {$email} </br> Username: {$user_login}</p>";

    // send the mail message
    wp_mail( $to, $subject, $message, $headers );

}
add_action('wp_login', 'nwd_send_email_after_login', 10, 2);
