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