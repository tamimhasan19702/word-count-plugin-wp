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
        echo '<div id="new-dashboard-widget">Hello montu</div>';
    }

}


$chartFilterPlugin = new ChartFilterPlugin();


?>