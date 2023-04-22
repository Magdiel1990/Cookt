<div class="container my-3">
    <div class="row justify-content-center">
        <!--Código JS para que se seleccionen todos los checkbox de los archivos a eliminar.-->
        <script src="./js/script.js"></script>
        <?php
        //Incluyo el documento de las clases.
        require_once "modules/classes/classes.php";
        //Si la carpeta de respaldo no existe, se crea con los permisos necesarios.
        $ruta = './backup/';
        if (!file_exists($ruta)) {
            mkdir($ruta, 0777);
        }
        //Se crean los objetos.
        $checkbox = new plantilla_checkbox();
        $checkbox->set_ruta($ruta);
        $checkbox->ver_archivos_directorios($ruta);
        ?>
    </div>
    <div>
        <?php
        //Verifico si el formulario viene con información.
        if (isset($_POST['Eliminar'])) {
            //Si viene con información recibo la variable de contraseña.
            $adminpass = $_POST['adminpass'];
            //Se crean los objetos.
            $deletion = new plantilla_checkbox();
            $deletion->eliminar_files($adminpass);
            //Se cierra la conexión.
            $conn->close();
        }

        ?>
    </div>
</div>