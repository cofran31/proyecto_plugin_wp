
<?php
if (!defined('ABSPATH')) {

    exit;
}
if (!class_exists("AdminQuestion")) {

    class AdminQuestion {

        public $forms;

        public function __construct() {
            $this->forms = new AdminForms;
        }

        private function select_category() {
            global $wpdb;
            $output = '';
            $output .= "<table border='0' style=' width:100%;border:3px solid #cdcdcd; padding:0px; border-radius:20px; background-color:#ddd' valign='top'>";
            $output .= '<tr>';
            $output .= '<td style="width: 35%"><h2>Seleccione la Categoria para crear Pregunas:</h2></td>';
            $output .= '<td style="width: 65%">';
            $tabla = $wpdb->prefix . "plugin_quiz_category";
            $consulta = "Select * from {$tabla} ";
            $resultado = $wpdb->get_results($consulta);
            $output .= '<form method="POST" action="" name="form_category" style="float:left">';
            $output .= '<input type="hidden" name="validarGridPreguntas" placeholder="Pregunta" value="1" />';
            $output .= '<select name="selectCategory" style="width:200px" onchange="this.form.submit()">';
            $output .= '<option value="0">SELECCIONE CATEGORIA</option>';
            foreach ($resultado as $fila) {
                $output .= '<option value="' . $fila->id . '">' . $fila->name . '</option>';
            }
            $output .= '</select>';
            $output .= '</form>';
            $output .= '</td>';
            $output .= '</tr>';
            $output .= '<tr>';
            $output .= '<td colspan="2"><label id="msjValidate"></td>';
            $output .= '</tr>';
            $output .= ' </table><hr/>';
            return $output;
        }

        private function get_category_id($category) {
            global $wpdb;
            $tabla = $wpdb->prefix . "plugin_quiz_category";
            $consulta = "Select * from {$tabla} where id = $category ";
            $resultado = $wpdb->get_results($consulta);
            foreach ($resultado as $fila) {
                $nombre = $fila->name;
            }
            return $nombre;
        }

        private function delete_question($id) {
            global $wpdb;
            $tabla = $wpdb->prefix . "plugin_quiz_question";
            $result = $wpdb->delete($tabla, array('id' => $id));
            return $result;
        }

        private function validation_insert_question($id_category, $pregunta, $respuesta1, $respuesta2, $respuesta3, $respuesta4, $respuesta_valida) {
            global $wpdb;
            $contador_respuestas = '';
            $error = '';
            $cont = 0;
            $cont1 = 1;
            $ok = 0;
            if ($respuesta_valida == 0)
                $error .= esc_html__('Debe Tickear la respuesta correcta','the-quiz-free') . '<br/>';

            if (empty($pregunta)) {
                $error .= esc_html__('Usted debe escribir una pregunta.','the-quiz-free') . '<br/>';
            }
            if (empty($respuesta1)) {
                $cont = $cont + 1;
            } else {
                $contador_respuestas = $contador_respuestas . '1';
            }
            if (empty($respuesta2)) {
                $cont = $cont + 1;
            } else {
                $contador_respuestas = $contador_respuestas . '2';
            }
            if (empty($respuesta3)) {
                $cont = $cont + 1;
            } else {
                $contador_respuestas = $contador_respuestas . '3';
            }
            if (empty($respuesta4)) {
                $cont = $cont + 1;
            } else {
                $contador_respuestas = $contador_respuestas . '4';
            }

            if ($cont > 2) {
                $error .= esc_html__('Usted debe escribir al menos 2 respuestas.','the-quiz-free') . '<br/>';
            } else {
                if ($respuesta_valida == 1)
                    $cont1 = $cont1 + 1;
                else if ($respuesta_valida == 2)
                    $cont1 = $cont1 + 1;
                else if ($respuesta_valida == 3)
                    $cont1 = $cont1 + 1;
                else if ($respuesta_valida == 4)
                    $cont1 = $cont1 + 1;
                if ($cont1 == 2) {
                    for ($i = 0; $i <= strlen($contador_respuestas); $i++) {
                        if ($contador_respuestas[$i] == $respuesta_valida) {
                            $ok = 1;
                        }
                    }
                    if ($respuesta_valida == 1)
                        $reponse = $respuesta1;
                    else if ($respuesta_valida == 2)
                        $reponse = $respuesta2;
                    else if ($respuesta_valida == 3)
                        $reponse = $respuesta3;
                    else if ($respuesta_valida == 4)
                        $reponse = $respuesta4;

                    if ($ok == 1) {
                        $table = $wpdb->prefix . "plugin_quiz_question";
                        $result = $wpdb->insert(
                                $table, array(
                            'id_category' => $id_category,
                            'pregunta' => $pregunta,
                            'option1' => $respuesta1,
                            'option2' => $respuesta2,
                            'option3' => $respuesta3,
                            'option4' => $respuesta4,
                            'valid_answer' => $reponse
                                ), array(
                            '%d',
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                            '%s',
                            '%s'
                                )
                        );
                    } else {
                        $error .= esc_html__('Debe Tickear la respuesta correcta, en una pregunta válida.','the-quiz-free') . '<br/>';
                    }
                }
            }
            if (empty($error)) {
                $valid = 1;
            } else {
                $valid = $error;
            }
            return $valid;
        }

        public function admin_question() {
            $this->forms->title_the_quiz_free();
            if (isset($_POST['form_pregunta'])) {
                $id_category = sanitize_text_field($_POST['selectCategory']);
                $pregunta = sanitize_text_field($_POST['pregunta']);
                $respuesta1 = sanitize_text_field($_POST['respuesta1']);
                $respuesta2 = sanitize_text_field($_POST['respuesta2']);
                $respuesta3 = sanitize_text_field($_POST['respuesta3']);
                $respuesta4 = sanitize_text_field($_POST['respuesta4']);
                $respuesta_valida = sanitize_text_field($_POST['radioPregunta']);
                if (isset($_POST['radioPregunta']))
                    $respuesta_valida = sanitize_text_field($_POST['radioPregunta']);
                else
                    $respuesta_valida = 0;
                $validar = $this->validation_insert_question($id_category, $pregunta, $respuesta1, $respuesta2, $respuesta3, $respuesta4, $respuesta_valida);
                if ($validar == 1) {
                    echo "<div id='message' class='updated fade'>";
                    echo "<p><strong>" . esc_html__('Pregunta insertada correctamente!!','the-quiz-free') . "</strong></p></div>";
                } else {
                    echo "<div id='message' class='updated fade'>";
                    echo "<p><strong>" . esc_html__('Verifique los siguientes Errores:!!','the-quiz-free') . "<br/>" . $validar . "</strong></p></div>";
                }
            }
            if (isset($_POST['deletedCuestionario'])) {
                $id = sanitize_text_field($_POST['id_pregunta']);
                $this->delete_question($id);
                if (false === $result || 0 === $result) {
                    echo "<div id='message' class='updated fade'>";
                    echo "<p><strong>" . esc_html__('Ocurrio un error verifique e intente nuevamente!!','the-quiz-free') . "</strong></p></div>";
                } else {
                    echo "<div id='message' class='updated fade'>";
                    echo "<p><strong>" . esc_html__('Pregunta Eliminada Exitosamente!!','the-quiz-free') . "</strong></p></div>";
                }
            }

            $ver_categorias = $this->select_category();
            echo $ver_categorias;
            if (isset($_POST['selectCategory'])) {
                $category = sanitize_text_field($_POST['selectCategory']);
                $nombre = $this->get_category_id($category);
                $this->forms->form_insert_question($nombre, $category);
            }
            
            if (isset($_POST['selectCategory'])) {
                $category = sanitize_text_field($_POST['selectCategory']);
                echo '<table border="0" style="width:100%;border:3px solid #cdcdcd; padding:5px; border-radius:20px; background-color:#ddd" valign="top">';
                echo '<tr>';
                echo '<td colspan="2">';
                echo '<h2 style= "float:left ">' . esc_html__("Lista de Preguntas de la Categoria:","the-quiz-free") . ' <b>' . $nombre . '</b></h2>';
                echo '</td>';
                echo ' </tr>';
                echo '<tr style="border:1px solid black">';
                echo '<td align = "center" style = "border:1px solid black"><h3>Nº</h3></td>';
                echo ' <td align="center"style="border:1px solid black"><h3>Pregunta</h3></td> ';
                echo '<td align = "center"style = "border:1px solid black"><h3>Respuestas</h3></td>';
                echo '<td align="center"style="border:1px solid black"><h3>Valida</h3></td>';
                echo '<td align="center"style="border:1px solid black"><h3>&nbsp;&nbsp;</h3></td>';
                echo '</tr>';
                global $wpdb;
                $category = sanitize_text_field($_POST['selectCategory']);
                $tabla = $wpdb->prefix . "plugin_quiz_question";
                $consulta = "Select * from {$tabla} where id_category=$category ";
                $resultado = $wpdb->get_results($consulta);
                $i = 1;
                $j = 0;
                $res = '';
                foreach ($resultado as $fila) {
                    $id = $fila->id;
                    $opcionx1 = $fila->option1;
                    $opcionx2 = $fila->option2;
                    $opcionx3 = $fila->option3;
                    $opcionx4 = $fila->option4;
                    if ($opcionx1) {
                        $j = 1;
                        $res = $res . '(' . $j . ' ) ' . '[' . $opcionx1 . "]<br/>";
                    }
                    if ($opcionx2) {
                        $j = 2;
                        $res = $res . '(' . $j . ' ) ' . '[' . $opcionx2 . "]<br/>";
                    }
                    if ($opcionx3) {
                        $j = 3;
                        $res = $res . '(' . $j . ' ) ' . '[' . $opcionx3 . "]<br/>";
                    }
                    if ($opcionx4) {
                        $j = 4;
                        $res = $res . '(' . $j . ' ) ' . '[' . $opcionx4 . "]<br/>";
                    }
                    echo '<tr style="border:1px solid black">';
                    echo '<td style="border:1px solid black">' . $i++ . '</td>';
                    echo '<td style="border:1px solid black">' . $fila->pregunta . '</td>';

                    echo '<td style="border:1px solid black">' . $res . '</td>';
                    echo '<td style="border:1px solid black">' . $fila->valid_answer . '</td>';
                    echo '<td style="border:1px solid black"><form action="" method="POST" ><input type="submit" value="X" name="deletedCuestionario"/><input type="hidden" name="id_pregunta" value="' . $fila->id . '"/><input type="hidden" name="selectCategory" value="' . $category . '"/></form></td>';
                    echo '</tr>';
                    $j = 0;
                    $res = '';
                }
            }
            echo '</table>';
        }

    }

}