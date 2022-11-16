<?php

if(!defined('ABSPATH')) exit;

function nwd_send_email_after_login( $user_login, $user ) {
    //combining in one header the From and content-type

    $blog_title = get_bloginfo( 'name' );
    // Get the user object.
    $user_data = get_userdata( $user->ID );
    // Get all the user roles as an array.
    $user_roles = $user_data->roles;
    if ($user_roles == NULL) {
        $role = 'none';
    } else {
      $role = "";
      foreach ($user_roles as $user_role) {
        $role .= $user_role;
      }

    }
    if ( in_array( 'subscriber', $user_roles, true ) ) {
        $role = 'subscriber';
    }
    if ( in_array( 'contributor', $user_roles, true ) ) {
        $role = 'contributor';
    }
    if ( in_array( 'author', $user_roles, true ) ) {
        $role = 'author';
    }
    if ( in_array( 'editor', $user_roles, true ) ) {
        $role = 'editor';
    }
    if ( in_array( 'administrator', $user_roles, true ) ) {
        $role = 'administrator';
    }

    $email = $user_data->user_email;

    date_default_timezone_set("America/Chicago");
    $time = date('m-d-Y');
    $time .= " at " . date('h:i:s A');

    // static mail stuff here
    $to = get_option('admin_email');
    $subject = "User Login Notification for {$blog_title}";
    $headers = array('Content-Type: text/html; charset=UTF-8');
    $user = !empty($user->first_name) ? $user->first_name . ' ' . $user->last_name : $user_login;
    $siteURL = site_url();
    $site = "<a href='{$siteURL}'>{$blog_title}</a>";

    $message = "<p>A user with {$role} rights has logged into {$site} at {$time}.</p>";
    $message .= "<p>User: {$user} </br> Email Address: {$email} </br> Username: {$user_login} </br> Site URL: {$siteURL}</p>";

    // send the mail message
    wp_mail( $to, $subject, $message, $headers );

}
add_action('wp_login', 'nwd_send_email_after_login', 10, 2);
