<?php
/*
 * Plugin Name:       Word Count
 * Description:       This is a plugin that will count the words in a text.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Tareq Monower
 * Text Domain:      wcpdomain
 * Domain Path:       /languages
 */

class WordCountPluginAndTime
{

    function __construct()
    {

        add_action('admin_menu', [$this, 'adminMenu']);
        add_action('admin_init', [$this, 'settings']);
        add_filter('the_content', [$this, 'ifWrap']);
        add_action('init', [$this, 'languages']);
    }
    function languages()
    {
        load_plugin_textdomain('wcpdomain', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }
    function ifWrap($content)
    {
        if (
            (is_main_query() and is_single()) and (
                get_option("wcp_counter", "1") or
                get_option("wcp_character", "1") or
                get_option("wcp_readTime", "1")
            )
        ) {
            return $this->wrapContentHtml($content);
        }
        return $content;
    }

    function wrapContentHtml($content)
    {
        $html = '<h3>' . esc_html(get_option('wcp_headline', 'Post Statistics')) . '</h3><p>';

        //word count and read time calculation
        if (get_option("wcp_counter", "1") or get_option("wcp_readTime", "1")) {
            $wordCount = str_word_count(strip_tags($content));
        }

        if (get_option("wcp_counter", "1")) {
            $html .= __("This Post has", "wcpdomain") . " " . $wordCount . " " . __('words.', 'wcpdomain') . '<br>';
        }
        if (get_option("wcp_character", "1")) {
            $html .= __("This Post has", "wcpdomain") . " " . strlen(strip_tags($content)) . " " . __('characters.', 'wcpdomain') . '<br>';
        }
        if (get_option("wcp_readTime", "1")) {
            $html .= __("This Post will take", "wcpdomain") . " " . ceil($wordCount / 200) . "" . __('minute.', 'wcpdomain') . '<br>';
        }

        if (get_option('wcp_location', '0') == '0') {
            return $html . $content;
        }
        return $content . $html;
    }

    function adminMenu()
    {
        add_submenu_page('options-general.php', 'Word Count Settings', __('Word Count Settings', 'wcpdomain'), 'manage_options', 'word-count', [$this, 'mainPage']);
    }

    function settings()
    {
        add_settings_section('wcp_first_section', null, null, 'word-count');

        // word count location settings field
        add_settings_field('wcp_location', 'Display Location', [$this, 'locationHtml'], 'word-count', 'wcp_first_section', );
        register_setting('word-count-settings-group', "wcp_location", ['sanitize_callback' => [$this, 'sanitizeTextField'], 'default' => '']);

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

    function sanitizeTextField($input)
    {
        if ($input != 0 and $input != 1) {
            add_settings_error('wcp_location', 'wcp_location_error', 'Display Location must be 0 or 1', 'error');
            return get_option('wcp_location');
        }
        return $input;
    }


    function checkBoxHtml($args)
    {
        ?>
        <input type="checkbox" name="<?php echo $args['the_Name']; ?>" value=" 1" <?php checked(get_option($args['the_Name']), 1); ?>>
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
        <select name=" wcp_location">
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