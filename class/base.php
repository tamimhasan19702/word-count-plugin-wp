<?php

/**
 * Plugin main class
 * 
 * this is a main class which inits the plugin and has all the methods to create pages, tables and enque script. this method is called when plugin activated
 */


class Base
{
    protected static $intiated = false;
    protected static $sessions_table_name = 'log_sessions';
    protected static $sessions_action_table_name = 'log_total';

    // private static $total_table_name    = 'log_total';

    /**
     * Debug funciton -- remove after development
     * 
     * @return void
     */
    public static function debug()
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }

    /**
     * Initialise WordPress hooks
     * 
     * @return void
     */


    //initialise the base
    public static function base_init()
    {
        add_action('admin_menu', ["Base", "add_menu_page_option"]);
    }

    //plugin activation
    public static function plugin_activation()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        //creating table for logging every session
        $table_name = $wpdb->prefix . self::$sessions_table_name;
        $query = "CREATE TABLE" . $table_name . "(
        id BIGINT(11) NOT NULL AUTO_INCREMENT,
        user_id BIGINT(11) NOT NULL Default 0,
        user_name TEXT NOT NULL DEFAULT '',
        user_email TEXT NOT NULL DEFAULT '',
        user_role TEXT NOT NULL DEFAULT '',
        last_session DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
        PRIMARY KEY (id)
        )" . $charset_collate . ";";
        require_once(ABSPATH . "wp-admin/includes/upgrade.php");
        dbDelta($query);

        //Creating table for log actions
        $table_name = $wpdb->prefix . self::$sessions_action_table_name;
        $query = "CREATE TABLE" . $table_name . "(
        id BIGINT(11) NOT NULL AUTO_INCREMENT,
        user_id BIGINT(11) NOT NULL Default 0,
        action TEXT NOT NULL DEFAULT '',
        date_time DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
        PRIMARY KEY (id)
        )" . $charset_collate . ";";
        dbDelta($query);

        //highlight one or several roles
        $featured_roles = get_option('log_featured_roles');
        if (!$featured_roles) {
            update_option('log_featured_roles', ['']);
        }
        ;

        //display metabox with extra info on the dashboard
        $display_dashboard_metabox = get_option("log_display_dashboard_metabox");
        if (!$display_dashboard_metabox) {
            update_option("log_display_dashboard_metabox", "yes");
        }

        //enable and disable email nortification
        $send_admin_email_nortification = get_option("log_send_admin_email_nortification");
        if (!$send_admin_email_nortification) {
            update_option("log_send_admin_email_nortification", "no");
        }
    }

    // all the init functions are below
    function add_menu_page_option()
    {
        $parent_slug = 'log-record-page';

        //main menu page
        add_menu_page(
            'Log Record',
            'Log Record',
            'administrator',
            $parent_slug,
            ['Base', 'display_main_page'],
            'dashicons-desktop',
        );

        // submenu settings page
        add_submenu_page(
            $parent_slug,
            "Settings",
            "Settings",
            "administrator",
            "log_settings",
            ['Base', 'display_settings_page']
        );

        // submenu single user page
        add_submenu_page(
            $parent_slug,
            "Single User Page",
            "Single User Page",
            "administrator",
            "log_single_user_page",
            ['Base', 'display_single_user_page']
        );
    }

}