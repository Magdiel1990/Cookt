<?php
//Incluyo el documento de las clases.
require_once "modules/classes/classes.php";
?>
<!--Tabla que muestra las anotaciones guardadas con checkbox.-->
<div class="container my-3">
    <div class="row justify-content-center">
        <?php
        //Objeto para ver las anotaciones. 
        $anotaciones = new plantilla_checkbox();
        $anotaciones->ver_anotaciones();
        ?>
    </div>
    <div>
        <?php
        //Verifico si el botón de enviar del formulario viene con información.
        if (isset($_POST['Eliminar'])) {
            //Si viene con información las recibo en esta variable.
            $registros = $_POST['registro'];
            //Variables argumentos de la clase.
            $tabla = 'notes';
            $id = 'id_nota';
            //Objeto para eliminar anotaciones.
            $deletion = new plantilla_checkbox();
            $deletion->eliminar_checkbox($registros, $tabla, $id);
            //Cierro la conexión.
            $conn->close();
        }
        ?>
    </div>
</div>