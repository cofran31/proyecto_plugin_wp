<?php

if (!defined('ABSPATH')) {

    exit;
}
if (!class_exists("AdminForms")) {

    class AdminForms {

        public function title_the_quiz_free() {
            echo "<hr/>";
            echo "<h2>" . esc_html__('Bienvenido a The Quiz Free, crea tus encuestas de la forma mas sencilla e insertalas en tus Post favoritos.','the-quiz-free') . "</h2>";
            echo "<hr/>";
        }

        public function form_insert_category() {
            echo '<form method="post" id="add_book_review" action="">';
            echo '<table border="0" style="border:3px solid #cdcdcd; padding:10px; border-radius:20px; background-color:#ddd" valign="top">';
            echo '<tr>';
            echo '<td><h4>' . esc_html__("Inserte el Nombre de la nueva Categoria de la Encuesta:","the-quiz-free") . '</h4></td>';
            echo '<td><input type="text" name="category_quiz" size="40" placeholder="Nombre Categoria"/></td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td colspan="2"></td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td colspan="2" align="center"><input type="submit" name="submitCategory" value="Crear Nueva Categoria" /></td>';
            echo '</tr>';
            echo '</table>';
            echo '</form>';
        }

        public function form_update_category($id, $caja) {
            echo "<table style='margin-left: 100px; padding:10px; border:3px solid #cdcdcd; border-radius:20px; background-color:#ddd'><tr><td>";
            echo "<form action='' name='optionsCategoryUpdate' method='POST' >";
            echo "<h3>" . esc_html__("Modifique la Categoria:","the-quiz-free") . "</h3>";
            echo '<input type="text" value="' . $caja . '" name="cajaUpdate" />';
            echo '<input type="hidden" value="' . $id . '" name="idUpdate"/>';
            echo '<input type="submit" value="Grabar" name="SubmitCategoryGrabar">';
            echo '<input type="submit" value="Cancelar" name="SubmitCategoryCancelar">';
            echo "</form>";
            echo "</td></tr></table>";
        }

        public function form_insert_question($nombre, $category) {
            echo '<form action = "" method = "POST">';
            echo ' <input type = "hidden" name = "validarGridPreguntas" placeholder = "Pregunta" value = "1" />';
            echo " <table border = '0' style = 'width:100%; border:3px solid #cdcdcd; padding:5px; border-radius:20px; background-color:#ddd' valign = 'top'>";
            echo "<tr>";
            echo " <td>";
            echo '<h2 style = "float:left ">' . esc_html__("Categoria Seleccionada:","the-quiz-free") . '<b>' . $nombre;
            echo '</b></h2>';
            echo '<input type="submit" name="form_pregunta" value="Grabar" style="float:right "/>';
            echo '</td>';
            echo ' </tr><tr>';
            echo '<td>';
            echo esc_html__('Pregunta:','the-quiz-free') . '<br/>';
            echo '<input type="text" name="pregunta" placeholder="Pregunta" value="';
            //if (isset($_POST['form_pregunta']))
            //    echo $_POST['pregunta'];
            // else
            //   echo "";
            echo '" size="90"/>';
            echo '<input type="hidden" name="selectCategory" placeholder="Pregunta" value="';
            if ($category == "")
                echo "";
            else
                echo $category;
            echo '" />';
            echo '<br/><br/>';
            echo '</td>';
            echo '</tr><tr>';
            echo '<td>';
            echo '<table><tr>';
            echo '<td>';
            echo esc_html__('Respuestas:','the-quiz-free');
            echo '</td>';
            echo '<td>';
            echo esc_html__('Respuesta Correcta:','the-quiz-free');
            echo '</td></tr>';
            echo '<tr><td>';
            echo '<input type = "text" name = "respuesta1" placeholder = "respuesta1" value = "';
            //if (isset($_POST['form_pregunta']))
            //   echo $_POST['respuesta1'];
            echo '" size = "70"/><br/>';
            echo '</td><td align="center">';
            echo ' <input type = "radio" name = "radioPregunta" value = "1"/><br/>';
            echo ' </td></tr>';
            echo ' <tr><td>';
            echo '<input type="text" name="respuesta2" placeholder="respuesta2" value="';
            // if (isset($_POST['form_pregunta']))
            ///    echo $_POST['respuesta2'];
            echo '" size="70"/><br/>';
            echo '</td><td align = "center">';
            echo '<input type="radio" name="radioPregunta" value="2"/><br/>';
            echo '</td></tr>';
            echo '<tr><td>';
            echo '<input type = "text" name = "respuesta3" placeholder = "respuesta3" value = "';
            // if (isset($_POST['form_pregunta']))
            //    echo $_POST['respuesta3'];
            echo '" size = "70"/><br/>';
            echo '</td><td align = "center">';
            echo '<input type = "radio" name = "radioPregunta" value = "3"/><br/>';
            echo '</td></tr>';
            echo '<tr><td>';
            echo '<input type = "text" name = "respuesta4" placeholder = "respuesta4" value = "';
            // if (isset($_POST['form_pregunta']))
            //     echo $_POST['respuesta4'];
            echo '" size = "70"/><br/>';
            echo '</td><td align = "center">';
            echo '<input type = "radio" name = "radioPregunta" value = "4"/><br/>';
            echo '</td></tr></table>';
            echo " <label>" . esc_html__('Atencion: Usted debe ingresar minimamente 2 respuestas y maximo 4. Ademas debe seleccionar la respuesta correcta.','the-quiz-free') . "</label>";
            echo '</table>';
            echo '</form>';
            echo '<hr/>';
        }

    }

}