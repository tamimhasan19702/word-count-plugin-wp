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

    function adminMenu()
    {
        add_submenu_page('options-general.php', 'Word Count Settings', 'Word Count', 'manage_options', 'word-count', [$this, 'mainPage']);
    }

    function settings()
    {
        add_settings_section('wcp_first_section', null, null, 'word-count');

        // word count location settings field
        add_settings_field('wcp_location', 'Display Location', [$this, 'locationHtml'], 'word-count', 'wcp_first_section', );
        register_setting('word-count-settings-group', "wcp_location", ['sanitize_callback' => 'sanitize_text_field', 'default' => '']);

        //word count headline settings field
        add_settings_field('wcp_headline', 'Display Headline', [$this, 'headerHtml'], 'word-count', 'wcp_first_section', );
        register_setting('word-count-settings-group', "wcp_headline", ['sanitize_callback' => 'sanitize_text_field', 'default' => '']);

        //word count counter settings field
        add_settings_field('wcp_counter', 'Word Count', [$this, 'checkBoxHtml'], 'word-count', 'wcp_first_section', ['the_Name' => "wcp_counter"]);
        register_setting('word-count-settings-group', "wcp_counter", ['sanitize_callback' => 'sanitize_text_field', 'default' => '']);

        //word count counter settings field
        add_settings_field('wcp_character', 'Charecter Count', [$this, 'checkBoxHtml'], 'word-count', 'wcp_first_section', ['the_Name' => "wcp_character"]);
        register_setting('word-count-settings-group', "wcp_character", ['sanitize_callback' => 'sanitize_text_field', 'default' => '']);

        //word count counter settings field
        add_settings_field('wcp_readTime', 'Read Time', [$this, 'checkBoxHtml'], 'word-count', 'wcp_first_section', ['the_Name' => "wcp_readTime"]);
        register_setting('word-count-settings-group', "wcp_readTime", ['sanitize_callback' => 'sanitize_text_field', 'default' => '']);
    }



    function checkBoxHtml($args)
    {
        ?>
        <input type="checkbox" name="<?php echo $args['the_Name']; ?>" value="1" <?php checked(get_option($args['the_Name']), 1); ?>>
        <?php
    }


    function headerHtml()
    {
        ?>
        <input type="text" name="wcp_headline" value="<?php echo esc_attr(get_option('wcp_headline')); ?>">
        <?php
    }

    function locationHtml()
    {
        ?>
        <select name="wcp_location">
            <option value="0" <?php selected(get_option('wcp_location'), '0') ?>>Begining of the Page</option>
            <option value="1" <?php selected(get_option('wcp_location'), '1') ?>>Ending of the Page</option>
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