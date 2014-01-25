<?php

add_theme_support('menus');

add_action('wp_print_styles', array('Tide', 'enqueue_styles'));
add_action('wp_print_scripts', array('Tide', 'enqueue_scripts'));

register_sidebar(array(
    'name' => 'header',
    'id' => 'header'
));

class Tide {

    function enqueue_styles() {
        if (!is_admin()) {
            wp_enqueue_style('open-sans', 'http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,400,700');
            wp_enqueue_style('gotham', get_stylesheet_directory_uri() . '/css/gotham/stylesheet.css');
            wp_enqueue_style('typography', get_stylesheet_directory_uri() . '/css/typography.css');
            wp_enqueue_style('layout', get_stylesheet_directory_uri() . '/css/layout.css');
            wp_enqueue_style('navigation', get_stylesheet_directory_uri() . '/css/navigation.css');
            wp_enqueue_style('miscellaneous', get_stylesheet_directory_uri() . '/css/misc.css');
        }
    }

    function enqueue_scripts() {
        if (!is_admin()) {
            wp_enqueue_script('jquery');
            wp_enqueue_script('miscellaneous', get_stylesheet_directory_uri() . '/js/misc.js');
        }
    }

}

include_once "weather/weather.php";
