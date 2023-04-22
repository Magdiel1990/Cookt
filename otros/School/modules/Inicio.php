<!--Página de inicio-->
<div class="container-fluid">
    <!--Hora actual en Santo Domingo-->
    <div class="container text-center p-3 my-3" id="go-up">
        <?php
        $hora = new DateTime("now", new DateTimeZone('America/Santo_Domingo'));
        echo $hora->format('l j \of F Y') . "<br>";
        //Función para saludar al maestro que se loguea, le muestra la fecha y le dice maestro o maestra dependiendo del sexo del usuario.
        function home($saludo)
        {
            echo "<b>" . $saludo . "</b>";
            echo "<div class='username'>";
            if ($_SESSION['sexo'] == 'M') {
                echo "<div>Maestro: </div>";
            } else {
                echo "<div>Maestra: </div>";
            }
            echo "<div>" . $_SESSION['name'] . "</div></div>";
        }
        //Mensaje de bienvenida dependiendo la hora
        if ($hora->format('G') >= 6 && $hora->format('G') < 12) {
            home('Buenos Días');
        } elseif ($hora->format('G') >= 12 && $hora->format('G') < 19) {
            home('Buenas Tardes');
        } else {
            home('Buenas Noches');
        }
        ?>
    </div>
    <div class="container p-3 my-3 text-center">
        <h3 class="text-center">Búsqueda</h3>
        <!--Formulario para buscar el nombre completo, el curso, la calificación, la fecha y la descripción de los exámenes-->
        <form action="" method="POST" class="d-block">
            <input type="text" name="search" class="form-control form-control-md my-4 w-25 mx-auto"
                placeholder="Nombre">
            <input type="submit" class="btn btn-primary" value="Buscar" name="Buscar">
        </form>
    </div>
    <div class="container p-1 my-1 table-responsive">
        <?php
        //Incluyo el documento de las clases.
        require_once "modules/classes/classes.php";
        //Verifico si el botón de enviar del formulario de búsqueda de la página de inicio viene con información
        if (isset($_POST['Buscar'])) {
            //Si viene con información la recibo en esta variable
            $nombre = $_POST['search'];
            //Si viene vacío recibo toda la información almacenada sobre el nombre completo, el curso, la calificación, la fecha y la descripción de los exámenes
            if ($nombre == '') {
                $sql = "SELECT * FROM studentExam;";
            }
            //Si introduzco algún nombre que esté en la base de datos o las iniciales recibo su información
            else {
                $sql = "SELECT * FROM studentExam WHERE fullname LIKE '$nombre%' GROUP BY fullname, description ORDER BY curso, fullname;";
            }
            $result = $conn->query($sql);
            //Si existe resultado para la consulta me lo muestra
            if (mysqli_num_rows($result) > 0) {
                //Cabecera de la tabla
                echo '<table class="table table-sm table-hover table-bordered">
                <thead>
                <tr><th>Nombre</th>              
                <th>Curso</th>
                <th>Calificaciones</th>
                <th>Fecha</th>
                <th>Descripción</th>
                </tr>
                </thead>';
                echo '<tbody>';
                //Ciclo para recibir los resultados
                while ($row = $result->fetch_assoc()) {
                    echo '<tr><td>' . $row["fullname"] . '</td>';
                    echo '<td>' . $row["curso"] . '</td>';
                    echo '<td>' . $row["calificacion"] . '</td>';
                    echo '<td>' . date("d-m-Y", strtotime($row["fecha"])) . '</td>';
                    echo '<td>' . $row["description"] . '</td></tr>';
                }
                echo "</tbody>";
                echo "</table>";
                echo '<div class="container text-center">
                <a href="#go-up">Volver arriba</a></div>';
            }
            //Si no hay resultado muestra este mensaje
            else {
                echo "<p class='no'>Ningún resultado para esta consulta!</p>";
            }
            //Cierro la conexión
            $conn->close();
        }
        ?>
    </div>
</div>