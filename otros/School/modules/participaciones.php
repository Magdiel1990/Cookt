<div class="container my-4">
    <div class="row justify-content-center text-center" id="go-up">
        <div class="col-md-3">
            <h3>Agregar o Editar</h3>
            <!--Formulario para agregar la evaluación de los estudiantes-->
            <form action="" method="POST">
                <!--Nombre de los estudiantes-->
                <label for="firstname" class="form-label">Nombre</label>
                <input type=" text" id="firstname" class="form-control my-2" name="firstname" required=""
                    placeholder="Nombre">
                <!--Calificación de los estudiantes-->
                <label for="exams" class="form-label">Calificación</label>
                <input type="number" id="exams" class="form-control my-2" name="calif" min="0" max="100" required="">
                <!--Dropdown del mes trabajado-->
                <select name="mes" class="form-select my-3" required="">
                    <option></option>
                    <?php
                    $meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
                    foreach ($meses as $m) {
                        echo '<option value="' . $m . '">' . $m . '</option>';
                    }
                    ?>
                </select>
                <!--Limpiar datos después de enviarlos en el formulario.-->
                <script src="./js/script.js"></script>
                <!--Botón de enviar formulario-->
                <div class="my-2">
                    <input type="submit" value="Agregar" class="btn btn-primary" id="Enviar" name="Envio">
                    <input type="submit" value="Editar" class="btn btn-secondary" id="Enviar" name="Editar">
                </div>
            </form>
            <!--Formulario para consultar la evaluación acumulada-->
            <form action="" method="POST" class="my-5">
                <h3>Consultar</h3>
                <!--Campo para ingresar el nombre o inicial del estudiante-->
                <input type="text" name="eva_con" class="form-control my-3" placeholder="Nombre">
                <!--Botón de enviar formulario-->
                <input type="submit" class="btn btn-primary" value="Consultar" name="ev_consultar">
            </form>
        </div>
    </div>
    <div class="row justify-content-center text-center">
        <div class="col-md-auto">
            <div>
                <?php
                //Incluyo el documento de las clases.
                require_once "modules/classes/classes.php";

                /*************************************************************************
                 **************************** Primer submit *******************************
                 *************************************************************************/

                //Verifico si el botón de enviar del formulario viene con información
                if (isset($_POST['Envio'])) {
                    //Si viene con información las recibo en estas variables
                    $firstname = $_POST['firstname'];
                    $calif = $_POST['calif'];
                    $mes = $_POST['mes'];
                    //Objeto para traer el valor numérico asignado a cada mes.
                    $switch = new month_switch();
                    $mes_id = $switch->month_switch($mes);
                    //Verifico si los datos ya existen en la base de datos.
                    $sql = "SELECT * FROM participation WHERE mes = '$mes' AND firstname = '$firstname';";
                    $result = $conn->query($sql);

                    if (mysqli_num_rows($result) > 0) {
                        echo "<p class='no'>Este registro ya fue agregado!</p>";
                    } else {
                        //Introduzco los datos del nombre, calificacion y mes en la base de datos 
                        $sql = "INSERT INTO participation (mes, mes_id, calificacion, firstname) VALUES('$mes','$mes_id', '$calif','$firstname');";
                        //Verifico si la inserción tuvo éxito
                        if ($conn->query($sql) === TRUE) {
                            echo "<p class='yes'>Calificación agragada correctamente!</p>";
                        } else {
                            echo "<p class='no'>Error al agregar la calificación!</p>";
                        }
                    }
                }

                /*************************************************************************
                 **************************** Segundo submit ******************************
                 *************************************************************************/

                //Verifico si el botón de enviar del formulario viene con información
                if (isset($_POST['Editar'])) {
                    //Si viene con información las recibo en estas variables
                    $firstname = $_POST['firstname'];
                    $calif = $_POST['calif'];
                    $mes = $_POST['mes'];
                    //Actualizo los datos. 
                    $sql = "UPDATE participation SET calificacion = '$calif' WHERE firstname = '$firstname' AND mes = '$mes';";

                    if ($conn->query($sql) === TRUE) {
                        echo "<p class='yes'>Este registro ha sido editado exitosamente!</p>";
                    } else {
                        echo "<p class='no'>Error al editar registro!</p>";
                    }
                }

                /********************************************************************** 
                 ************************** Segundo formulario**************************
                 ***********************************************************************/
                echo "<div class='table-responsive'>";
                //Verifico si el botón de enviar del formulario viene con información
                if (isset($_POST['ev_consultar'])) {
                    //Si viene con información las recibo en esta variable
                    $nombre = $_POST['eva_con'];
                    //Si viene vacío recibo todos los registros
                    if ($nombre == '') {
                        $sql = "SELECT * FROM grading;";
                    }
                    //Si escribo las iniciales o nombres de los estudiantes recibo la información 
                    else {
                        $sql = "SELECT * FROM grading ORDER BY curso, fullname;";
                    }
                    $result = $conn->query($sql);
                    //Si hay resultado lo recibo en una tabla
                    if (mysqli_num_rows($result) > 0) {
                        echo "<table class='table table-sm'>";
                        echo "<thead>";
                        echo "<tr><th>Nombre</th>       
                <th>Curso</th>
                <th>Calificación</th>
                <th>Mes</th></tr>";
                        echo "</thead>";
                        echo "<tbody>";

                        while ($row = $result->fetch_assoc()) {
                            echo '<tr><td>' . $row["fullname"] . '</td>';
                            echo '<td>' . $row["curso"] . '</td>';
                            echo '<td>' . $row["calificacion"] . '</td>';
                            echo '<td>' . $row["mes"] . '</td></tr>';
                        }
                        echo "</tbody>";

                        echo "</table>";
                        echo "<div class='go-back-up-link'>
            <a href='#go-up'>Volver arriba</a>
            </div>";
                    }
                    //Si no hay resultado, aparece este mensaje
                    else {
                        echo "<p class='no'>No hay resultados para esta consulta!</p>";
                    }
                    echo "</div>";
                    //Cierro la conexión
                    $conn->close();
                }
                ?>
            </div>
        </div>
    </div>
</div>