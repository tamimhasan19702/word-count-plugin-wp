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

function wp_custom_plugin_enqueue() {
    wp_enqueue_style('custom-plugin', WP_CUSTOM_PLUGIN_URL . '/assets/css/style.css', [], "1.0", 'all');
    wp_enqueue_script('custom-plugin', WP_CUSTOM_PLUGIN_URL . '/assets/js/index.js', [], "1.0", true);
}

function wp_custom_plugin_create_table() {
    global $wpdb;
    

    // Define table name with the correct prefix
    $table_name = $wpdb->prefix . 'custom_plugin_table';

    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {

$charset_collete = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE `wp_custom_plugin` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(255) NOT NULL,
            `email` varchar(255) NOT NULL,
            `address` varchar(255) NOT NULL,
            PRIMARY KEY (`id`)
          ) $charset_collete";

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
           dbDelta($sql);
    }

    
}

add_action('admin_menu', 'wp_custom_plugin');
add_action('admin_enqueue_scripts', 'wp_custom_plugin_enqueue');
register_activation_hook(__FILE__, "wp_custom_plugin_create_table");