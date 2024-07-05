<?php
/*
Plugin Name: custom-plugin
Author: tareq Monower
Author Url: https://github.com/tareqmonower
Version: 1.0.0
Description: wordpress login record plugin  to keep track and save who logged in the website
Text Domain: wp-custom-plugin
*/

define('WP_CUSTOM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WP_CUSTOM_PLUGIN_URL', plugin_dir_url(__FILE__));


function wp_custom_plugin() {
    add_menu_page('Custom Plugin', 'Custom Plugin', 'manage_options', 'custom-plugin', 'wp_custom_plugin_callback', 'dashicons-shield', 99);
    add_submenu_page('custom-plugin', 'Add Pages', 'Add new page', 'manage_options', 'custom-plugin', 'wp_custom_plugin_callback');
    add_submenu_page('custom-plugin', 'All Pages', 'All pages', 'manage_options', 'All Pages', 'wp_custom_submenu_callback');
}

function wp_custom_submenu_callback() {
    include_once(WP_CUSTOM_PLUGIN_DIR . 'views/all-page.php');
}

function wp_custom_plugin_callback() {
    include_once(WP_CUSTOM_PLUGIN_DIR . 'views/add-new.php');
}

add_action('admin_menu', 'wp_custom_plugin');