<?php
/*
plugin Name: Chart Filter Plugin
Author: tareq Monower
Author Url: https://github.com/tareqmonower
version: 1.0.0
Description: Chart Filter Plugin made with React JS
*/

if (!defined("ABSPATH")):
    exit();
endif;

define('WPWR_PATH', trailingslashit(plugin_dir_path(__FILE__)));
define('WPWR_URL', trailingslashit(plugin_dir_url(__FILE__)));
class ChartFilterPlugin
{
    function __construct()
    {
        add_action("wp_dashboard_setup", [$this, "new_dashboard_setup"]);
        add_action("admin_enqueue_scripts", [$this, "load_scripts"]);
    }

    function load_scripts()
    {
        wp_enqueue_script("wp-react-plugin", WPWR_URL . "./src/index.js", ['jquery', 'wp-element'], "1.0.0", true);
        wp_localize_script("wp-react-plugin", 'appLocalizer', [
            'apiUrl' => home_url('/wp-json'),
            'nonce' => wp_create_nonce('wp_rest'),
        ]);
    }
    function new_dashboard_setup()
    {
        wp_add_dashboard_widget(
            "new_dashboard_widget",
            "New Chart Filter",
            [$this, "new_dashboard_widget_callback"],
        );
    }

    function new_dashboard_widget_callback()
    {
        echo '<div id="new-dashboard-widget"></div>';
    }

}


$chartFilterPlugin = new ChartFilterPlugin();


?>