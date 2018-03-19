<?php
if (!class_exists("AdminQuestion")) {

    class AdminQuestion {

        function __construct() {
            
        }

        public function admin_question() {
            // global $contador_respuestas;

            global $datos_respuestas;
            $datos_respuestas = '';
            $ok = 0;
            ?>
            <hr/>
            <h2><?php echo esc_html__('Bienvenido a The Quiz Free, crea tus encuestas de la forma mas sencilla e insertalas en tus Post favoritos.')?></h2>
            <hr/>
            <table valign='top' border="0" style="width: 90%">
                <tr><td valign='top' >
                        <table border='0' style=' width:100%;border:3px solid #cdcdcd; padding:0px; border-radius:20px; background-color:#ddd' valign='top'>
                            <tr>
                                <td style="width: 35%"><h2>Seleccione la Categoria para crear Pregunas:</h2></td>
                                <td style="width: 65%">
                                    <?php
                                    global $wpdb;
                                    $tabla = $wpdb->prefix . "plugin_quiz_category";
                                    $consulta = "Select * from {$tabla}  ";
                                    $resultado = $wpdb->get_results($consulta);
                                    echo '<form method="POST" action="" name="form_category" style="float:left">';
                                    echo '<input type="hidden" name="validarGridPreguntas" placeholder="Pregunta" value="1" />';
                                    echo '<select name="selectCategory" style="width:200px" onchange="this.form.submit()">';
                                    echo '<option value="0">SELECCIONE CATEGORIA</option>';
                                    foreach ($resultado as $fila) {
                                        echo '<option value="' . $fila->id . '">' . $fila->name . '</option>';
                                    }
                                    echo '</select>';
                                    echo '</form>';
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"><label id="msjValidate"><?php ?></label></td>
                            </tr>
                            <tr>
                            </tr>
                        </table>
                    </td></tr><tr><td>
                        <?php
                        if (isset($_POST['selectCategory'])) {
                            $category = sanitize_text_field($_POST['selectCategory']);
                            $tabla = $wpdb->prefix . "plugin_quiz_category";
                            $consulta = "Select * from {$tabla} where id=$category ";
                            $resultado = $wpdb->get_results($consulta);
                            foreach ($resultado as $fila) {
                                $nombre = $fila->name;
                            }
                            ?>
                            <form action="" method="POST">
                                <input type="hidden" name="validarGridPreguntas" placeholder="Pregunta" value="1" />
                                <table border='0' style='width:100%; border:3px solid #cdcdcd; padding:5px; border-radius:20px; background-color:#ddd' valign='top'>
                                    <tr>
                                        <td>
                                            <h2 style="float:left ">Categoria Seleccionada: <b><?php echo $nombre ?></b></h2>
                                            <input type="submit" name="form_pregunta" value="Grabar" style="float:right "/>
                                        </td>
                                    </tr><tr>
                                        <td>
                                            <?php echo esc_html__('Pregunta:')?><br/>
                                            <input type="text" name="pregunta" placeholder="Pregunta" value="<?php
                                            if (isset($_POST['form_pregunta']))
                                                echo $_POST['pregunta'];
                                            else
                                                echo "";
                                            ?>" size="90"/>
                                            <input type="hidden" name="selectCategory" placeholder="Pregunta" value="<?php
                                            if ($category == "")
                                                echo "";
                                            else
                                                echo $category
                                                ?>" />

                                            <br/><br/>

                                        </td>
                                    </tr><tr>
                                        <td> 
                                            <table><tr>
                                                    <td>        
                                                        <?php echo esc_html__('Respuestas:')?>
                                                    </td>
                                                    <td>        
                                                        <?php echo esc_html__('Respuesta Correcta:')?>
                                                    </td></tr>
                                                <tr><td>
                                                        <input type="text" name="respuesta1" placeholder="respuesta1" value="<?php if (isset($_POST['form_pregunta'])) echo $_POST['respuesta1']; ?>" size="70"/><br/>
                                                    </td><td align="center">
                                                        <input type="radio" name="radioPregunta" value="1"/><br/>
                                                    </td></tr>
                                                <tr><td>
                                                        <input type="text" name="respuesta2" placeholder="respuesta2" value="<?php if (isset($_POST['form_pregunta'])) echo $_POST['respuesta2']; ?>" size="70"/><br/>
                                                    </td><td align="center">
                                                        <input type="radio" name="radioPregunta" value="2"/><br/>
                                                    </td></tr>
                                                <tr><td>
                                                        <input type="text" name="respuesta3" placeholder="respuesta3" value="<?php if (isset($_POST['form_pregunta'])) echo $_POST['respuesta3']; ?>" size="70"/><br/>
                                                    </td><td align="center">
                                                        <input type="radio" name="radioPregunta" value="3"/><br/>
                                                    </td></tr>
                                                <tr><td>
                                                        <input type="text" name="respuesta4" placeholder="respuesta4" value="<?php if (isset($_POST['form_pregunta'])) echo $_POST['respuesta4']; ?>" size="70"/><br/>
                                                    </td><td align="center">
                                                        <input type="radio" name="radioPregunta" value="4"/><br/>
                                                    </td></tr></table>
                                            <label><?php echo esc_html__('Atencion: Usted debe ingresar minimamente 2 respuestas y maximo 4 y seleccionar cual es la respuesta correcta.')?></label>
                                        </td>                                    
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <?php
                                            $error = '';
                                            $cont = 0;
                                            $cont1 = 1;

                                            if (isset($_POST['form_pregunta'])) {
                                                $contador_respuestas = '';
                                                $id_category = sanitize_text_field($_POST['selectCategory']);
                                                $pregunta = sanitize_text_field($_POST['pregunta']);
                                                $respuesta1 = sanitize_text_field($_POST['respuesta1']);
                                                $respuesta2 = sanitize_text_field($_POST['respuesta2']);
                                                $respuesta3 = sanitize_text_field($_POST['respuesta3']);
                                                $respuesta4 = sanitize_text_field($_POST['respuesta4']);
                                                if (isset($_POST['radioPregunta']))
                                                    $respuesta_valida = sanitize_text_field($_POST['radioPregunta']);
                                                else
                                                    $respuesta_valida = '';
                                                if (empty($pregunta)) {
                                                    $error = $error . '<label>'.esc_html__('Usted debe escribir una pregunta.').'</label><br/><br/>';
                                                }
                                                if (empty($respuesta1)) {
                                                    $cont = $cont + 1;
                                                } else {
                                                    $contador_respuestas = $contador_respuestas . '1';
                                                    $datos_respuestas = $datos_respuestas;
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
                                                    $error = $error . '<label>'.esc_html__('Usted debe escribir al mejos 2 respuestas.').'</label><br/><br/>';
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
                                                            global $wpdb;
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
                                                            $error = $error . "<label>Usted tiene que Seleccionar una respuesta correcta.</label><br/><br/>";
                                                        }
                                                    } else
                                                        $error = $error . "<label>Usted debe seleccionar una respuesta correcta.</label><br/><br/>";
                                                }
                                            }
                                            ?>
                                            <label id="msjValidate"><?php if ($error != "") echo '<center><h4><i><u>Verifique los siguientes Errores:</i></u></h4>' . esc_html__($error) . '</center>' . "</center>" ?></label>

                                        </td>
                                    </tr>
                                </table>
                            </form>

                        </td></tr>
                    <tr><td>


                            <?php
                            if (isset($_POST['validarGridPreguntas'])) {
                                $category = sanitize_text_field($_POST['selectCategory']);
                                $tabla = $wpdb->prefix . "plugin_quiz_category";
                                $consulta = "Select * from {$tabla} where id=$category ";
                                $resultado = $wpdb->get_results($consulta);
                                foreach ($resultado as $fila) {
                                    $nombre = $fila->name;
                                }
                                ?>

                                <table border="0" style='width:100%;border:3px solid #cdcdcd; padding:5px; border-radius:20px; background-color:#ddd' valign='top'>
                                    <tr>
                                        <td colspan="2">
                                            <h2 style="float:left "><?php echo esc_html__('Lista de Preguntas de la Categoria:')?> <b><?php echo $nombre ?></b></h2>
                                        </td>
                                    </tr>
                                    <tr style="border:1px solid black">
                                        <td align="center" style="border:1px solid black"><h3>Nº</h3></td> 
                                        <td align="center"style="border:1px solid black"><h3>Pregunta</h3></td> 
                                        <td align="center"style="border:1px solid black"><h3>Respuestas</h3></td> 
                                        <td align="center"style="border:1px solid black"><h3>Valida</h3></td> 
                                    </tr>
                                    <?php
                                    $category = sanitize_text_field($_POST['selectCategory']);
                                    $tabla = $wpdb->prefix . "plugin_quiz_question";
                                    $consulta = "Select * from {$tabla} where id_category=$category ";
                                    $resultado = $wpdb->get_results($consulta);
                                    $i = 1;
                                    $j = 0;
                                    $res = '';
                                    foreach ($resultado as $fila) {
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
                                        echo '</tr>';
                                        $j = 0;
                                        $res = '';
                                    }
                                }
                                ?>
                            </table>
                        </td></tr>
                </table>
                <?php
            }
            ?>


            <?php
        }

    }

}
    