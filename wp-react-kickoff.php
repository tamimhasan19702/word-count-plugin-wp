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
{    /**
     * Constructor for the class.
     */
    public function __construct()
    {
        add_action('admin_menu', [$this, 'create_admin_menu']);
        add_action("admin_enqueue_scripts", [$this, "load_scripts"]);
        add_action('rest_api_init', [$this, 'register_rest_api']);
    }

    /**
     * Load scripts for the PHP function.
     *
     */
    public function load_scripts()
    {
        wp_enqueue_script("wp-react-kickoff", plugins_url('dist/bundle.js', __FILE__), ['jquery', 'wp-element'], wp_rand(), true);
        wp_localize_script("wp-react-kickoff", "appLocalizer", [
            'apiUrl' => home_url('/wp-json'),
            'nonce' => wp_create_nonce('wp_rest')
        ]);
    }
    /**
     * Creates an admin menu for the WordPress plugin.
     */
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
    /**
     * A description of the entire PHP function.
     *
     */
    public function menu_page_template()
    {
        echo '<div class="wrap"><div id="wp_react_kickoff_new_plugin"></div></div>';
    }
    /**
     * Register the REST API routes for the plugin.
     */
    public function register_rest_api()
    {
        register_rest_route('wprk/v1', '/settings', [
            'methods' => 'GET',
            'callback' => [$this, 'get_settings'],
            'permission_callback' => [$this, 'get_settings_permission'],
        ]);
        register_rest_route('wprk/v1', '/settings', [
            'methods' => 'POST',
            'callback' => [$this, 'save_settings'],
            'permission_callback' => [$this, 'save_settings_permission'],
        ]);
    }

    public function get_settings()
    {
        $firstName = get_option('wprk_settings_first_name');
        $lastName = get_option('wprk_settings_last_name');
        $email = get_option('wprk_settings_email');
        $response = [
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,
        ];
        return rest_ensure_response($response);
    }

    public function get_settings_permission()
    {
        return true;
    }

    public function save_settings($request)
    {
        $firstName = sanitize_text_field($request->get_param('firstName'));
        $lastName = sanitize_text_field($request->get_param('lastName'));
        $email = sanitize_text_field($request->get_param('email'));
        update_option("wprk_settings_first_name", $firstName);
        update_option("wprk_settings_last_name", $lastName);
        update_option("wprk_settings_email", $email);
        return rest_ensure_response(['success' => true]);
    }

    public function save_settings_permission()
    {
        return current_user_can('publish_posts')
    }
}



new WPRK_Create_Admin_Page();