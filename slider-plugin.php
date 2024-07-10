<?php
/*
Plugin Name: slider Plugin
Author: tareq Monower
Author Url: https://github.com/tareqmonower
Version: 1.0.0
Description: WordPress slider plugin for WordPress
Text Domain: wp-custom-plugin
*/

// if accessed directly, exit
if (!defined('ABSPATH')) {
    exit;
}

// Initialize the plugin class
class WP_Custom_Slider_Plugin {
    public function __construct() {
        // Add custom post type and taxonomies
        add_action('init', array($this, 'create_slider_post_type'));
        add_action("wp_enqueue_scripts", array($this, "enqueue_scripts"));
        add_shortcode('wp_custom_slider', array($this, 'wp_custom_slider'));
    }

    public function enqueue_scripts() {
        wp_enqueue_style('custom-style', plugins_url('assets/css/style.css', __FILE__));
        wp_enqueue_style('bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css' );
        wp_enqueue_script('custom-script', plugins_url('assets/js/script.js', __FILE__));
    }

    public function wp_custom_slider() {
         
            ?>
            <div class="container text-center">
         <div class="col-md-12">
            <div class="slider">
                <?php 

                $slider = new WP_Query(array(
                    'post_type' => 'slider',
                ));

                while ($slider-> have_posts()) : $slider-> the_post();
                ?>
                <div class="slide">
                    <img src="<?php the_post_thumbnail() ?>" alt="<?php the_title(); ?>">
                </div>
                <?php 
                endwhile;
                ?>
            </div>
         </div>
            </div>
            <?php 

         
       
    }

    public function create_slider_post_type() {
        $labels = array(
            'name'               => _x('Sliders', 'post type general name', 'textdomain'),
            'singular_name'      => _x('Slider', 'post type singular name', 'textdomain'),
            'menu_name'          => _x('Sliders', 'admin menu', 'textdomain'),
            'name_admin_bar'     => _x('Slider', 'add new on admin bar', 'textdomain'),
            'add_new'            => _x('Add New Slider', 'slider', 'textdomain'),
            'add_new_item'       => __('Add New Slider', 'textdomain'),
            'new_item'           => __('New Slider', 'textdomain'),
            'edit_item'          => __('Edit Slider', 'textdomain'),
            'view_item'          => __('View Slider', 'textdomain'),
            'all_items'          => __('All Sliders', 'textdomain'),
            'search_items'       => __('Search Sliders', 'textdomain'),
            'parent_item_colon'  => __('Parent Sliders:', 'textdomain'),
            'not_found'          => __('No sliders found.', 'textdomain'),
            'not_found_in_trash' => __('No sliders found in Trash.', 'textdomain')
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array('slug' => 'slider'),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array('title', 'editor', 'thumbnail'),
            'menu_icon'          => 'dashicons-images-alt2'
           
        );

        register_post_type('slider', $args);
    }

    
}

if (class_exists('WP_Custom_Slider_Plugin')) {
    $wp_custom_slider_plugin = new WP_Custom_Slider_Plugin();
}
