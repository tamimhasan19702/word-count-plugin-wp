<?php
/*
Plugin Name: login-record
Author: tareq Monower
Author Url: https://github.com/tareqmonower
Version: 1.0.0
Description: wordpress login record plugin  to keep track and save who logged in the website
Text Domain: wp-login-record
*/

//No direct access is allowed
if (!defined("ABSPATH")):
    exit();
endif;

// make sure this plugin is exposing data
if (!function_exists("add_action")) {
    die("You are not allowed to access this file directly.");
}
;



//defining variables
define("LOGIN_RECORD_VERSION", "1.0.0");
define("LOGIN_RECORD_FILE_DIR", plugin_dir_path(__FILE__));

//activate the plugin 
register_activation_hook(__FILE__, ['Base', 'plugin_activation']);

// loading the css classes - load base clss 1st
require_once(LOGIN_RECORD_FILE_DIR . 'class/base.php');
require_once(LOGIN_RECORD_FILE_DIR . 'class/action.php');

//plugin initiation
add_action('init', ['Base', 'base_init']);
add_action('init', ['Action', 'action_init']);