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



// disable direct file access
if (!defined('ABSPATH')) {

    exit;
}


// include plugin dependencies: admin only
if (is_admin()) {

    require_once plugin_dir_path(__FILE__) . 'admin/admin-acerca-de.php';
    require_once plugin_dir_path(__FILE__) . 'admin/admin-menu.php';
    require_once plugin_dir_path(__FILE__) . 'admin/admin-category.php';
    require_once plugin_dir_path(__FILE__) . 'admin/admin-question.php';
    // $grid_category = new AdminCategory();
    // $grid_category->grid_category();
}

function publico($atts) {
    global $n_preguntas;
    if ($n_preguntas == null)
        $n_preguntas = 0;
    $p = shortcode_atts(array(
        'id' => '1'
            ), $atts);
    $id_categoria = $p['id'];
    $registros = contar_registros($id_categoria);
    if ($n_preguntas <= $registros) {
        $n_preguntas = $n_preguntas + 1;
        $sacar_ids = obtener_id($id_categoria);
        $result = orden_pregunta($id_categoria, $n_preguntas, $sacar_ids);
        echo $result;
    } else {
        echo esc_html__("Felicidades Termino el Cuestionario");
    }
}

function orden_pregunta($id_categoria, $n_preguntas, $sacar_ids) {
    global $wpdb;
    $posicion_id = $sacar_ids[$n_preguntas];
    $tabla = $wpdb->prefix . "plugin_quiz_question";
    $consulta = "Select * from {$tabla} where id_category=$id_categoria and id = $posicion_id ";
    $resultado = $wpdb->get_results($consulta);
    $output = '';
    $output = $output . '<hr/>';
    $output = $output . esc_html__('Bienvenido por favor responda el Siguiente Cuestionario:');
    $output = $output . '<hr/>';
    foreach ($resultado as $fila) {
        $res1 = $fila->option1;
        $res2 = $fila->option2;
        $res3 = $fila->option3;
        $res4 = $fila->option4;
        $output = $output . '<form action="" method="POST">';
        $output = $output . '<table border="2">';
        $output = $output . '<tr><td style="margin:10px !important;">';
        $output = $output . '<h3>Pregunta:</h3>';
        $output = $output . '</td></tr> ';
        $output = $output . '<tr><td style="margin:10px !important;">';
        $output = $output . $fila->pregunta;
        $output = $output . '</td></tr> ';
        $output = $output . '<tr><td style="margin:10px !important;">';
        $output = $output . '<h3>Opciones:</h3>';
        $output = $output . '</td></tr> ';
        $output = $output . '<tr><td style="margin:10px !important;">';
        if ($res1 != "")
            $output = $output . '<input type="submit" name="responde" value="' . $res1 . '" /><br/><br/>';
        if ($res2 != "")
            $output = $output . '<input type="submit" name="responde" value="' . $res2 . '" /><br/><br/>';
        if ($res3 != "")
            $output = $output . '<input type="submit" name="responde" value="' . $res3 . '" /><br/><br/>';
        if ($res4 != "")
            $output = $output . '<input type="submit" name="responde" value="' . $res4 . '" /><br/><br/>';
        $output = $output . '</td></tr> ';
        $output = $output . '</table>';
        $output = $output . '</form>';
    }
    return $output;
}

function contar_registros($id_categoria) {
    global $wpdb;
    $tabla = $wpdb->prefix . "plugin_quiz_question";
    $resultado = $wpdb->get_var("SELECT COUNT(*) FROM {$tabla} where id_category=$id_categoria");
    return $resultado;
}

function obtener_id($id_categoria) {
    global $wpdb;
    $ids = array();
    $tabla = $wpdb->prefix . "plugin_quiz_question";
    $resultado = $wpdb->get_results("SELECT * FROM {$tabla} where id_category=$id_categoria");
    $i = 0;
    foreach ($resultado as $fila) {
        $ids[$i] = $fila->id;
        $i = $i + 1;
    }
    return $ids;
}

add_shortcode('quiz', 'publico');

// Verifico si no existen mis tablas dependientes para crearlas
require_once plugin_dir_path(__FILE__) . 'includes/createtable.php';
register_activation_hook(__FILE__, array('CreateTable', 'on_activate'));



