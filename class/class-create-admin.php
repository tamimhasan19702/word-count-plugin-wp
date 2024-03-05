<?php

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

new WPRK_Create_Admin_Page();