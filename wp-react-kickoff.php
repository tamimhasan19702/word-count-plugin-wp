<?php
/*
Plugin Name: Wp React Kickoff
Author: tareq Monower
Author Url: https://github.com/tareqmonower
Version: 1.0.0
Description: Wordpress React kickoff
Text Domain: wp-react-kickoff
*/

if (!defined("ABSPATH")):
    exit();
endif; //No direct access is allowed

class WPRK_Create_Admin_Page
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'create_admin_menu']);
    }

    public function create_admin_menu()
    {
        $capability = 'manage_options';
        $slug = 'wprk-settings';

        add_menu_page(
            __('Wp React Kickoff', 'wp-react-kickoff'),
            __('Wp React Kickoff', 'wp-react-kickoff'),
            $capability,
            $slug,
            [$this, 'menu_page_template'],
            'dashicons-buddicons-replies',
            99
        );
    }

    public function menu_page_template()
    {
        echo '<div class="wrap"><div id="wp_react_kickoff_new_plugin"></div></div>';
    }
}

function load_scripts()
{
    wp_enqueue_script("wp-react-kickoff", plugins_url('dist/bundle.js', __FILE__), ['jquery', 'wp-element'], wp_rand(), true);
    wp_localize_script("wp-react-kickoff", "appLocalizer", [
        'apiUrl' => home_url('/wp-json'),
        'nonce' => wp_create_nonce('wp_rest')
    ]);
}

new WPRK_Create_Admin_Page();
add_action("admin_enqueue_scripts", "load_scripts");