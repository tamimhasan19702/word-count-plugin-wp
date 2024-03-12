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

        add_action('admin_init', array('Base', 'process_settings_form'));

        add_action('admin_enqueue_scripts', array('Base', 'enqueue_js_scripts'));

        add_action('admin_enqueue_scripts', array('Base', 'enqueue_css_files'));

        add_filter('user_row_actions', array('Base', 'add_single_user_log_profile_link'), 10, 2);
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

    public static function enqueue_js_scripts()
    {
        // load scripts only in plugin main page.
        if (isset($_GET['page']) && $_GET['page'] == 'log-record-page') {

            $summary_data = self::get_summary_data();

            $plugin_dir_path = plugin_dir_url(dirname(__FILE__));
            // load google charts library from local.
            wp_enqueue_script('google_charts_local', $plugin_dir_path . 'js/googlecharts.min.js', array(), true);

            // load custom charts js.
            wp_enqueue_script('charts_js', $plugin_dir_path . 'js/drawcharts.js', array(), true);

            // pass summary data to charts.js.
            wp_localize_script('charts_js', 'summary_data', $summary_data);
        }
    }

    public static function enqueue_css_files()
    {

        $plugin_dir_path = plugin_dir_url(dirname(__FILE__));
        wp_enqueue_style('log-records-css', $plugin_dir_path . 'css/style.css');
    }

    //all the init functions are below
    public static function add_menu_page_option()
    {
        $parent_slug = 'log-record-page';

        // main menu page
        add_menu_page(
            'Log Record',
            'Log Record',
            'administrator',
            $parent_slug,
            array('Base', 'display_main_page'),
            'dashicons-desktop'
        );

        // submenu settings page
        add_submenu_page(
            $parent_slug,
            'Settings',
            'Settings',
            'administrator',
            'log-settings',
            array('Base', 'display_settings_page')
        );

        // submenu settings page
        add_submenu_page(
            $parent_slug,
            'Single User Page',
            'Single User Page',
            'administrator',
            'log-single-user-page',
            array('Base', 'display_single_user_page')
        );
    }

    public static function display_main_page()
    {
        require_once(plugin_dir_path(dirname(__FILE__)) . 'views/main-page.php');
    }

    public static function display_listing_users_page()
    {
        require_once(plugin_dir_path(dirname(__FILE__)) . 'views/user-listing-page.php');
    }

    public static function display_settings_page()
    {
        require_once(plugin_dir_path(dirname(__FILE__)) . 'views/settings-page.php');
    }

    public static function display_single_user_page()
    {
        require_once(plugin_dir_path(dirname(__FILE__)) . 'views/single-user-page.php');
    }

}