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

    public static function process_settings_form()
    {
        if (!isset($_POST['log_form']) || $_POST['log_form'] !== 'settings') {
            return;
        }

        if (!isset($_POST['log_nonce']) || empty($_POST['log_nonce']) || !wp_verify_nonce($_POST['log_nonce'], 'log_settings_form')) {
            // get user notification data.
            $not_data = array(
                'status' => 'invalid-nonce',
            );
            //redirect user to settings form.
            self::redirect_user('settings', $not_data);
        }

        // featured administrators.
        if (isset($_POST['log_roles']) && is_array($_POST['log_roles']) || !empty($_POST['log_roles'])) {
            update_option('log_featured_roles', serialize($_POST['log_roles']));
        }

        // display metabox
        if (isset($_POST['log_display_metabox']) && !empty($_POST['log_display_metabox'])) {
            update_option('log_display_dashboard_metabox', sanitize_text_field($_POST['log_display_metabox']));
        }

        // send admin notification.
        if (isset($_POST['log_send_admin_notification']) && !empty($_POST['log_send_admin_notification'])) {
            update_option('log_send_admin_email_notification', sanitize_text_field($_POST['log_send_admin_notification']));
        }

        $not_data = array(
            'status' => 'settings-updated',
        );
        self::redirect_user('settings', $not_data);
    }

    public static function redirect_user($location, $not_data = array())
    {
        // set page redirection.
        switch ($location) {
            case 'main':
                $path = 'admin.php?page=log-record-page';
                break;
            case 'settings':
                $path = 'admin.php?page=log-settings';
                break;
            default:
                $path = 'admin.php?page=log-record-page';
                break;
        }
        $base_url = get_admin_url(null, $path);

        // add user notification data.
        if (is_array($not_data) && !empty($not_data)) {
            $base_url = add_query_arg($not_data, $base_url);
        }

        wp_safe_redirect($base_url);
        exit;
    }

    public static function get_summary_data()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . self::$sessions_table_name;
        $data = array();
        $current_roles = get_editable_roles();
        $i = 0;
        $total = count($current_roles);

        // get total users data and number of users by role.
        $data['user_data'] = count_users();

        // get total logins.
        $query = "SELECT COUNT(*) FROM " . $table_name;
        $data['total_logins'] = (int) $wpdb->get_var($query);

        // get logins by role.
        $query = "SELECT";
        foreach ($current_roles as $key => $role) {
            $query .= " ( SELECT COUNT(*) FROM " . $table_name . " 
                        WHERE user_role = '" . sanitize_text_field($key) . "' ) 
                        AS '" . $key . "'";
            $i++;
            if ($i < $total) {
                $query .= ',';
            }
        }
        $data['logins_per_role'] = $wpdb->get_row($query);
        return $data;
    }

    public static function add_single_user_log_profile_link($actions, $user_object)
    {
        if (current_user_can('administrator')) {
            $admin_url = get_admin_url() . 'admin.php';
            $args = [
                'page' => 'log-single-user-page',
                'user-id' => $user_object->ID,
            ];
            $admin_url = add_query_arg($args, $admin_url);
            $actions['view_log_profile'] = "<a href='" . esc_url($admin_url) . "' class='log-wpusers-link'>Log profile</a>";
        }
        return $actions;
    }

}