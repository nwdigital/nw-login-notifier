<?php if(!defined('ABSPATH')) exit;


/**
 * Add custom columns to the user list
 *
 * @param array $columns The existing columns in the user list.
 * @return array The updated columns with custom ones.
 */

 // displays the custom column
add_filter('manage_users_columns' , 'nwd_user_notifier_columns');
function nwd_user_notifier_columns($columns) {
	$new_columns = array(
		'nwd_login_notify_last_login' => __('Last Login (NWD)', 'nwd-login-notifier'),
	);

    return array_merge($columns, $new_columns);
}

//Adds Content To The Custom Added Column
add_filter('manage_users_custom_column',  'nwd_login_notify_show_user_id_column_content', 10, 3);
function nwd_login_notify_show_user_id_column_content($value, $column_name, $user_id) {
    $last_login = get_user_meta( $user_id, "nwd_login_notify_last_login", true );
    $login_date = !empty( $last_login ) ? $last_login->format('Y-m-d g:i a') : "Never";
	if ( 'nwd_login_notify_last_login' == $column_name )
		return $login_date;
    return $value;
}
