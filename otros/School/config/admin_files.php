<!--Formulario para recibir los archivos a subidos.-->
<div class="container my-4 d-flex flex-column justify-content-center align-items-center">
    <div class="text-center">
        <form action="" method=" POST" enctype="multipart/form-data">
            <h3>Cargar Archivo</h3>
            <div class="drop-zone">
                <span class="drop-zone__prompt">Arrastre los archivos aquí o haga click</span>
                <input type="file" name="myFile" class="drop-zone__input" multiple>
            </div>
            <div>Máximo tamaño por archivo: <strong>100 MB</strong>.</div>
            <!--Limpiar datos después de enviarlos en el formulario.-->
            <input type="submit" class='btn btn-primary my-3 mb-4' value="Subir" name="Enviar">
        </form>
    </div>
    <div>
        <?php
        //Incluyo el documento de las clases.
        require_once "modules/classes/classes.php";
        //Se crea la carpeta para cargar los archivos si no existe.
        $ruta = './uploads/';
        if (!file_exists($ruta)) {
            mkdir($ruta, 0777);
        }
        //Verifico si el formulario viene con información.
        if (isset($_POST['Enviar'])) {
            //Creo la ruta completa del nuevo archivo. 
            $target_file = $ruta . basename($_FILES['myFile']['name']);
            //Extraigo la extensión en letra minúscula del archivo.
            $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            //Verifico si el o los archivos pesan más de 100MB.
            if ($_FILES['myFile']['size'] > 100000000) {
                echo "<p class='no'>Este archivo es demasiado grande!</p>";
            }
            //Si pesa menos de 100MB, muevo el archivo hacia la ruta establecida.        
            else {
                if (move_uploaded_file($_FILES['myFile']['tmp_name'], $target_file)) {
                    echo "<p class='yes'>El archivo " . htmlspecialchars(basename($_FILES['myFile']['name'])) . " ha sido subido!</p>";
                }
                //Si no se pudo cargar, entonces lanza un error.
                else {
                    echo "<p class='no'>Error al cargar el archivo!</p>";
                }
            }
        }
        ?>
    </div>
    <!--Formulario para ver los archivos guardados.-->
    <div>
        <!--Código JS para que se seleccionen todos los checkbox de los archivos a eliminar.-->
        <script src="./js/script.js"></script>
        <?php
        //Creación de los objetos de la visualización de los directorios.
        $checkbox = new plantilla_checkbox();
        $checkbox->set_ruta($ruta);
        $checkbox->ver_archivos_directorios($ruta);
        ?>
    </div>
    <div>
        <?php
        //Verifico si el formulario viene con información.
        if (isset($_POST['Eliminar'])) {
            //Si viene con información recibo las variable de contraseña.
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
<!--Inclusión del código que controla el drag and drop.-->
<script src="./js/script.js"></script>