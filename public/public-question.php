<?php

function publico($atts) {
    //global $n_preguntas;
    if (empty($_SESSION['user']))
        $_SESSION['user'] = md5(rand(90, 1000));
    if (empty($_SESSION['n_preguntas']))
        $_SESSION['n_preguntas'] = 0;
    $p = shortcode_atts(array(
        'id' => '1'
            ), $atts);
    $id_categoria = $p['id'];
    $registros = contar_registros($id_categoria);
    if ($_SESSION['n_preguntas'] + 1 <= $registros) {
        $sacar_ids = obtener_id($id_categoria);
        $result = orden_pregunta($id_categoria, $_SESSION['n_preguntas'], $sacar_ids);
        echo $result;
        $_SESSION['n_preguntas'] = $_SESSION['n_preguntas'] + 1;
    } else {
        $aprobadas = contar_resultados($_SESSION['user']);
        $totales = contar_total($_SESSION['user']);
        echo esc_html__("Felicidades Termino la encuesta", "the-quiz-free");
        echo "<hr/>Resultados:<hr/>";
        echo $aprobadas . '&nbsp; Acertadas de &nbsp;' . $totales . '&nbsp; totales';
        session_destroy();
    }
    if (isset($_POST['Resultados'])) {
        $id_pregunta = sanitize_text_field($_POST['id_pregunta']);
        $respuesta1 = sanitize_text_field($_POST['radioPregunta1']);
        if (empty($respuesta1)) {
            echo esc_html__('Debes seleccionar una respuesta!!', 'the-quiz-free');
        } else {
            $resultado = validar_respuesta($id_pregunta, $respuesta1);
            $insertResult = insert_result($_SESSION['user'], $id_pregunta, $resultado);
        }
    }
    //echo $resultado;
}

function contar_resultados($id_jugador) {
    global $wpdb;
    $tabla = $wpdb->prefix . "plugin_quiz_users";
    $resultado = $wpdb->get_var("SELECT * FROM {$tabla} where id_jugador='$id_jugador' and valid_answer=1");
    return $resultado;
}

function contar_total($id_jugador) {
    global $wpdb;
    $tabla = $wpdb->prefix . "plugin_quiz_users";
    $resultado = $wpdb->get_var("SELECT * FROM {$tabla} where id_jugador='$id_jugador'");
    return $resultado;
}

function insert_result($id_jugador, $id_question, $respuesta) {
    global $wpdb;
    $table = $wpdb->prefix . "plugin_quiz_users";
    $result = $wpdb->insert(
            $table, array(
        'id_jugador' => $id_jugador,
        'id_question' => $id_question,
        'valid_answer' => $respuesta
            ), array(
        '%s',
        '%d',
        '%s'
            )
    );
    return $result;
}

function validar_respuesta($id_pregunta, $respuesta1) {
    global $wpdb;
    $tabla = $wpdb->prefix . "plugin_quiz_question";
    $resultado = $wpdb->get_var("SELECT COUNT(*) FROM {$tabla} where id=$id_pregunta and valid_answer= '$respuesta1'");
    return $resultado;
}

function orden_pregunta($id_categoria, $n_preguntas, $sacar_ids) {
    global $wpdb;
    $posicion_id = $sacar_ids[$n_preguntas];
    $tabla = $wpdb->prefix . "plugin_quiz_question";
    $consulta = "Select * from {$tabla} where id_category=$id_categoria and  id = $posicion_id ";
    $resultado = $wpdb->get_results($consulta);
    $output = '';
    $output = $output . '<hr/>';
    $output = $output . esc_html__('Bienvenido por favor responda el Siguiente Cuestionario:', 'the-quiz-free');
    $output = $output . '<hr/>';
    $output = $output . '<form action="" method="POST" >';
    $i = 0;
    foreach ($resultado as $fila) {
        $id = $fila->id;
        $res1 = $fila->option1;
        $res2 = $fila->option2;
        $res3 = $fila->option3;
        $res4 = $fila->option4;
        $output = $output . '<input type="hidden" value="' . $id . '" name="id_pregunta"/>';
        $output = $output . '<table border="2" class="greenTable"><thead>';
        $output = $output . '<tr><td style="margin:10px !important;">';
        $output = $output . '<b><font color="white" face="Verdana,Arial">Pregunta:</b></font>';
        $output = $output . '</td></tr> ';
        $output = $output . '<tr><td style="margin:10px !important;">';
        $output = $output . $fila->pregunta;
        $output = $output . '</td></tr> ';
        $output = $output . '<tr><td style="margin:10px !important;">';
        $output = $output . '<b><font color="white" face="Verdana,Arial">Opciones:</b></font>';
        $output = $output . '</td></tr> ';
        $output = $output . '<tr><td style="margin:10px !important;">';
        if ($res1 != "") {
            $i = $i + 1;
            $output = $output . '&nbsp;<input type = "radio" name = "radioPregunta1" value = "' . $res1 . '"/>' . $res1;
        }
        if ($res2 != "") {
            $i = $i + 1;
            $output = $output . '&nbsp;<input type = "radio" name = "radioPregunta1" value = "' . $res2 . '"/>' . $res2;
        }
        if ($res3 != "") {
            $i = $i + 1;
            $output = $output . '&nbsp;<input type = "radio" name = "radioPregunta1" value = "' . $res3 . '"/>' . $res3;
        }
        if ($res4 != "") {
            $i = $i + 1;
            $output = $output . '&nbsp;<input type = "radio" name = "radioPregunta1" value = "' . $res4 . '"/>' . $res4;
        }
        $output = $output . '<input type="hidden" value="' . $i . '" name="n_respuestas"/>';
        $output = $output . '</td></tr>';
        $output = $output . '</thead></table>';
    }
    $output = $output . '<center><input type="submit" name="Resultados" value="Resolver"></center>';
    $output = $output . '</form>';
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
