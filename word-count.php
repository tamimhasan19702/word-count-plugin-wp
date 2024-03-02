<?php
/*
 * Plugin Name:       Word Count
 * Description:       This is a plugin that will count the words in a text.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Tareq Monower
 * Text Domain:       word-count
 */

class WordCountPluginAndTime
{

    function __construct()
    {

        add_action('admin_menu', [$this, 'adminMenu']);
        add_action('admin_init', [$this, 'settings']);
    }

    function settings()
    {
        add_settings_section('wcp_first_section', null, null, 'word-count');
        add_settings_field('wcp_location', 'Display Location', [$this, 'locationHtml'], 'word-count', 'wcp_first_section', );
        register_setting('word-count-settings-group', "wcp_location", ['sanitize_callback' => 'sanitize_text_field', 'default' => '']);
    }
    function adminMenu()
    {
        add_submenu_page('options-general.php', 'Word Count Settings', 'Word Count', 'manage_options', 'word-count', [$this, 'mainPage']);
    }

    function locationHtml()
    {
        ?>
        <select name="wcp_location">
            <option value="0">Begining of the Page</option>
            <option value="1">Ending of the Page</option>
        </select>
        <?php
    }
    function mainPage()
    {
        ?>
        <div class="wrap">

            <h1>Hello world</h1>
            <form action="options.php" method="post">
                <?php
                settings_fields("word-count-settings-group");
                do_settings_sections("word-count");
                submit_button();
                ?>
            </form>
        </div>
        <?php

    }

}

$wordCountPlugin = new WordCountPluginAndTime();