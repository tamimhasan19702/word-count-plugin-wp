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
    }
    function adminMenu()
    {
        add_submenu_page('options-general.php', 'Word Count Settings', 'Word Count', 'manage_options', 'word-count', [$this, 'mainPage']);
    }

    function mainPage()
    {
        ?>
<h1>Hello world</h1>
<?php

    }

}

$wordCountPlugin = new WordCountPluginAndTime();