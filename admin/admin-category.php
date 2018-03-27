<?php

if (!defined('ABSPATH')) {

    exit;
}
if (!class_exists("AdminCategory")) {

    class AdminCategory {

        public $forms;

        public function __construct() {
            $this->forms = new AdminForms;
        }

        private function insert_category($category) {
            global $wpdb;
            $table = $wpdb->prefix . "plugin_quiz_category";
            $result = $wpdb->insert(
                    $table, array(
                'name' => $category
                    ), array(
                '%s'
                    )
            );
            return $result;
        }

        private function update_category($nombre, $id) {
            global $wpdb;
            $tabla = $wpdb->prefix . "plugin_quiz_category";
            $result = $wpdb->update($tabla, array('name' => $nombre), array('id' => $id), array('%s'));
            return $result;
        }

        private function delete_category($id) {
            global $wpdb;
            $tabla = $wpdb->prefix . "plugin_quiz_category";
            $result = $wpdb->delete($tabla, array('id' => $id));
            return $result;
        }

        private function all_category() {
            global $wpdb;
            $tabla = $wpdb->prefix . "plugin_quiz_category";
            $consulta = "Select * from {$tabla}  ";
            $resultado = $wpdb->get_results($consulta);
            $i = 1;
            echo "<table style='margin-left: 100px; padding:10px; border:3px solid #cdcdcd; border-radius:20px; background-color:#ddd'>";
            echo "<tr>";
            echo "<td align='center'><h4>Nº</h4></td>";
            echo "<td align='center'><h4>" . esc_html__('Id Categoria', 'the-quiz-free') . "</h4></td>";
            echo "<td align='center'><h4>" . esc_html__('Nombre Categoria', 'the-quiz-free') . "</h4></td>";
            echo "<td align='center'><h4>" . esc_html__('Opciones', 'the-quiz-free') . "</h4></td>";
            echo "</tr>";
            foreach ($resultado as $fila) {
                echo "<form action='' name='optionsCategory' method='POST' >";
                echo '<tr><td>' . $i++ . '</td>';
                echo '<td><input type="text" value="' . $fila->id . '" name="caja' . $fila->id . '" size="3" disabled/></td>';
                echo '<td><input type="text" value="' . $fila->name . '" name="caja' . $fila->id . '" disabled/>';
                echo '<input type="hidden" value="' . $fila->id . '" name="id"/>';
                echo '<input type="hidden" value="' . $fila->name . '" name="cajaCategory' . $fila->id . '"/></td>';
                echo '<td><input type="submit" value="Delete" name="SubmitCategoryDelete">';
                echo '<input type="submit" value="Update" id="SubmitCategoryUpdate" name="SubmitCategoryUpdate"></td></tr>';
                echo "</form>";
            }
            echo "</table>";
        }

        public function admin_categoria() {
            $this->forms->title_the_quiz_free();
            if (isset($_POST['submitCategory'])) {
                $category = sanitize_text_field($_POST['category_quiz']);
                if (!empty($category)) {
                    $result = $this->insert_category($category);
                    if (false === $result || 0 === $result) {
                        echo "<div id='message' class='updated fade'>";
                        echo "<p><strong>" . esc_html__('Ocurrio un error verifique e intente nuevamente!!', 'the-quiz-free') . "</strong></p></div>";
                    } else {
                        echo "<div id='message' class='updated fade'>";
                        echo "<p><strong>" . esc_html__('Categoria Insertada Exitosamente!!', 'the-quiz-free') . "</strong></p></div>";
                    }
                } else {
                    echo "<div id='message' class='updated fade'>";
                    echo "<p><strong>" . esc_html__('Usted debe ingresar una Categoria Valida!!', 'the-quiz-free') . "</strong></p></div>";
                }
            }

            if (isset($_POST['SubmitCategoryGrabar'])) {
                $id = sanitize_text_field($_POST['idUpdate']);
                $nombre = sanitize_text_field($_POST['cajaUpdate']);
                $result = $this->update_category($nombre, $id);
                if (false === $result || 0 === $result) {
                    echo "<div id='message' class='updated fade'>";
                    echo "<p><strong>" . esc_html__('Ocurrio un error verifique e intente nuevamente!!', 'the-quiz-free') . "</strong></p></div>";
                } else {
                    echo "<div id='message' class='updated fade'>";
                    echo "<p><strong>" . esc_html__('Categoria Actualizada Exitosamente!!', 'the-quiz-free') . "</strong></p></div>";
                }
            }
            if (isset($_POST['SubmitCategoryDelete'])) {
                $id = sanitize_text_field($_POST['id']);
                $this->delete_category($id);
                if (false === $result || 0 === $result) {
                    echo "<div id='message' class='updated fade'>";
                    echo "<p><strong>" . esc_html__('Ocurrio un error verifique e intente nuevamente!!', 'the-quiz-free') . "</strong></p></div>";
                } else {
                    echo "<div id='message' class='updated fade'>";
                    echo "<p><strong>" . esc_html__('Categoria Eliminada Exitosamente!!', 'the-quiz-free') . "</strong></p></div>";
                }
            }

            echo "<table valign='top'>";
            echo "<tr><td valign='top'>";
            $this->forms->form_insert_category();
            echo "</td><td>";
            if (isset($_POST['SubmitCategoryUpdate'])) {
                $id = sanitize_text_field($_POST['id']);
                $nombre_caja = 'cajaCategory' . $id;
                $caja = sanitize_text_field($_POST[$nombre_caja]);
                $this->forms->form_update_category($id, $caja);
            } else {
                $this->all_category();
            }
            echo "</td></tr>";
            echo "</table>";
        }

    }

}          
