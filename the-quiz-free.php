<?php

/*
  Plugin Name:  The Quiz Free
  Description:  Plugin para realizar encuestas Online.
  Plugin URI:   https://facebook.com/carlos.ortube
  Author:       Juan Carlos Ortube
  Version:      1.0
  Text Domain:  the-quiz-free
  Domain Path:  /languages
  License:      GPL v2 or later
  License URI:  https://www.gnu.org/licenses/gpl-2.0.txt
 */
session_start();
// disable direct file access
if (!defined('ABSPATH')) {
    exit;
}

function myplugin_load_textdomain() {
    load_plugin_textdomain('the-quiz-free', false, plugin_dir_path(__FILE__) . 'languages/');
}

add_action('plugins_loaded', 'myplugin_load_textdomain');

// include plugin dependencies: admin only
if (is_admin()) {

    require_once plugin_dir_path(__FILE__) . 'admin/admin-acerca-de.php';
    require_once plugin_dir_path(__FILE__) . 'admin/admin-menu.php';
    require_once plugin_dir_path(__FILE__) . 'admin/admin-forms.php';
    require_once plugin_dir_path(__FILE__) . 'admin/admin-category.php';
    require_once plugin_dir_path(__FILE__) . 'admin/admin-question.php';
    // $grid_category = new AdminCategory();
    // $grid_category->grid_category();
}
//style y js Publico
add_action('wp_enqueue_scripts', 'the_quiz_free_css_js');
function the_quiz_free_css_js() {
    wp_enqueue_style('the-quiz-free', plugins_url('public/css/the-quiz-free.css', __FILE__));
    wp_enqueue_style('the-quiz-free', plugins_url('public/js/the-quiz-free.js', __FILE__));
}



require_once plugin_dir_path(__FILE__) . 'public/public-question.php';
add_shortcode('quiz', 'publico');


// Verifico si no existen mis tablas dependientes para crearlas
require_once plugin_dir_path(__FILE__) . 'includes/createtable.php';
register_activation_hook(__FILE__, array('CreateTable', 'on_activate'));



