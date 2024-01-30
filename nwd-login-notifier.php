<?php /**
** Plugin Name: NWDigital Login Notifier
** Description: Email notifications when user logs in, also displays last login date/time in the users list.
** Version: 1.0.3
** Author: Mathew Moore
** Author URI: https://nwdigital.cloud/
** Plugin URI: https://nwdigital.cloud/plugins/nwd-login-notifier
**/

if(!defined('ABSPATH')) exit;

require('nwd-settings-menus.php');
require('nwd-email-functions.php');
require('nwd-login-notifier-extra-columns.php');