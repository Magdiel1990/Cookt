<div>
    <div class="container text-center my-4 d-flex flex-column justify-content-center align-items-center">
        <h3>Calificaciones de Exámenes</h3>
        <!--Formulario para agregar resultados de los exámenes-->
        <form action="" method="POST" class="my-2">
            <!--Nombre de los estudiantes-->
            <label for="firstname" class="form-label my-2">Nombre</label>
            <input type="text" id="firstname" class="form-control" name="firstname" required placeholder="Nombre">
            <!--Calificación de los estudiantes-->
            <label for="exams" class="form-label my-2">Calificación</label>
            <input type="number" id="exams" class="form-control" name="exams" min="0" max="100" required="">
            <!--Descripción del exámen-->
            <label for="description" class="form-label my-2">Descripción</label>
            <textarea name="description" id="description" class="form-control" cols="20" rows="2" required></textarea>
            <!--Fecha de inserción-->
            <label for="date" class="form-label my-2">Fecha</label>
            <input type="date" class="form-control" name="date" required="">
            <!--Limpiar datos después de enviarlos en el formulario.-->
            <script src="./js/script.js"></script>
            <!--Botón de enviar formulario-->
            <input type="submit" value="Agregar" class="btn btn-primary my-4" id="Enviar" name="enviar">
        </form>
    </div>
    <div>
        <?php
                //Incluyo el documento de las clases.
                require_once "modules/classes/classes.php";
                //Verifico si el botón de enviar del formulario viene con información
                if (isset($_POST['enviar'])) {
                        //Si viene con información las recibo en estas variables
                        $firstname = $_POST['firstname'];
                        $exams = $_POST['exams'];
                        $date = $_POST['date'];
                        $entrada = $_POST['description'];
                        //Objetos que llaman a la función para eliminar espacios en blanco, quitar la barra de escape de caracteres, convertir caracteres especiales en entidades HTML. 
                        $input = new input_cleaning();
                        $description = $input->test_input($entrada);
                        //Verifico que lo que se quiere agregar no exista previamente.
                        $sql = "SELECT * FROM exams WHERE firstname = '$firstname' AND description = '$description';";
                        $result = $conn->query($sql);
                        //Si existe.
                        if (mysqli_num_rows($result) > 0) {
                                echo "<p class='no'>Estos resultados ya existen!</p>";
                        } else {
                                //Si no existe introduzco los datos del nombre, calificación, fecha y descripción en la base de datos 
                                $sql = "INSERT INTO exams (firstname,exams,fecha,description) VALUES('$firstname','$exams','$date','$description');";
                                //Verifico si la inserción tuvo éxito
                                if ($conn->query($sql) === TRUE) {
                                        echo "<p class='yes'>Calificación agregada correctamente!</p>";
                                } else {
                                        echo "<p class='no'>Error al agregar la calificación!</p>";
                                }
                        }
                        //Cierro la conexión
                        $conn->close();
                }

                ?>
    </div>
</div>