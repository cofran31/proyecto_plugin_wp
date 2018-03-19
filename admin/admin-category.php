<?php
if (!class_exists("AdminCategory")) {

    class AdminCategory {

        public function __construct() {
            
        }

        public function admin_categoria() {
            $msj = '';
            $msjInsert = '';
            if (isset($_POST['submitCategory'])) {
                $category = sanitize_text_field($_POST['category_quiz']);
                if (!empty($category)) {
                    global $wpdb;
                    $table = $wpdb->prefix . "plugin_quiz_category";
                    $result = $wpdb->insert(
                            $table, array(
                        'name' => $category
                            ), array(
                        '%s'
                            )
                    );
                    if (false === $result)
                        $msjInsert = esc_html__('Ocurrio un error verifique e intente nuevamente!!');

                    elseif (0 === $result)
                        $msjInsert = esc_html__('Ocurrio un error verifique e intente nuevamente!!');
                    else
                        $msjInsert = esc_html__('Categoria Insertada Exitosamente!!');
                } else {
                    $msj = esc_html__('Usted debe ingresar una Categoria Valida!!');
                }
            }
            ?>
            <hr/>
            <h2><?php echo esc_html__('Bienvenido a The Quiz Free, crea tus encuestas de la forma mas sencilla e insertalas en tus Post favoritos.') ?> </h2>
            <hr/>
            <table valign='top'><tr><td valign='top'>
                        <form method="post" id="add_book_review" action="">
                            <!-- Nonce fields to verify visitor provenance -->
                            <?php wp_nonce_field('form_categoria', 'br_user_form'); ?>	
                            <table border='0' style='border:3px solid #cdcdcd; padding:10px; border-radius:20px; background-color:#ddd' valign='top'>
                                <tr>
                                    <td><h4><?php echo esc_html__('Inserte el Nombre de la nueva Categoria de la Encuesta:')?></h4></td>
                                    <td><input type="text" name="category_quiz" size='40' placeholder="Nombre Categoria"/></td>
                                </tr>
                                <tr>
                                    <td colspan="2"><label id="msjValidate"><?php echo $msj ?></label></td>
                                </tr>
                                <tr>
                                    <td colspan="2" align='center'><input type="submit" name="submitCategory" value="Crear Nueva Categoria" /></td>
                                </tr>
                            </table>


                        </form>
                        <label id="msjValidate"><?php echo $msjInsert ?></label>
                    </td>
                    <td>
                        <?php
                        if (isset($_POST['SubmitCategoryUpdate'])) {
                            $id = sanitize_text_field($_POST['id']);
                            $nombre_caja = 'cajaCategory' . $id;
                            $caja = sanitize_text_field($_POST[$nombre_caja]);
                            echo "<table style='margin-left: 100px; padding:10px; border:3px solid #cdcdcd; border-radius:20px; background-color:#ddd'><tr><td>";
                            echo "<form action='' name='optionsCategoryUpdate' method='POST' >";
                            echo esc_html__("<h3>Modifique la Categoria:</h3>");
                            echo '<input type="text" value="' . $caja . '" name="cajaUpdate" />';
                            echo '<input type="hidden" value="' . $id . '" name="idUpdate"/>';
                            echo '<input type="submit" value="Grabar" name="SubmitCategoryGrabar">';
                            echo '<input type="submit" value="Cancelar" name="SubmitCategoryCancelar">';
                            echo "</form>";
                            echo "</td></tr></table>";
                        } else {
                            ?>
                            <table style='margin-left: 100px; padding:10px; border:3px solid #cdcdcd; border-radius:20px; background-color:#ddd'>
                                <tr>
                                    <td align='center'><h4>Nº</h4></td>
                                    <td align='center'><h4><?php echo esc_html__('Id Categoria')?></h4></td>
                                    <td align='center'><h4><?php echo esc_html__('Nombre Categoria')?></h4></td>
                                    <td align='center'><h4><?php echo esc_html__('Opciones')?></h4></td>
                                </tr>
                                <?php
                                global $wpdb;
                                $tabla = $wpdb->prefix . "plugin_quiz_category";
                                $consulta = "Select * from {$tabla}  ";
                                $resultado = $wpdb->get_results($consulta);
                                $i = 1;
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
                                ?>

                            </table>
                        <?php } ?>
                    </td></tr>
            </table>
            <?php
            if (isset($_POST['SubmitCategoryGrabar'])) {
                $id = sanitize_text_field($_POST['idUpdate']);
                $nombre = sanitize_text_field($_POST['cajaUpdate']);
                $tabla = $wpdb->prefix . "plugin_quiz_category";
                $result = $wpdb->update($tabla, array('name' => $nombre), array('id' => $id), array('%s'));
                if ($result == 1) {
                    $url = "";
                    echo '<meta http-equiv=refresh content="1; ' . $url . '">';
                    die;
                }
            }
            if (isset($_POST['SubmitCategoryDelete'])) {
                $id = sanitize_text_field($_POST['id']);
                $tabla = $wpdb->prefix . "plugin_quiz_category";
                $result = $wpdb->delete($tabla, array('id' => $id));
                if ($result == 1) {
                    $url = "";
                    echo '<meta http-equiv=refresh content="1; ' . $url . '">';
                    die;
                }
            }
        }

    }

}          
