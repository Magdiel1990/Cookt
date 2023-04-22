<div class="container">
    <div class="row justify-content-center my-4" id="go-up">
        <div class="text-center">
            <h3>Listado de Estudiantes</h3>
        </div>
        <!--Tabla que muestra un listado de todos los estudiantes.-->
        <div class="table-responsive col-md-auto mt-3">
            <?php
                        //Conexión de a la base de datos.
                        require_once "connection/connect.php";
                        //Selecciono el nombre, curso y sección de los estudiantes ordenados por curso y sección
                        $sql = "SELECT * FROM student_list;";
                        //Me aseguro que haya resultados
                        $result = $conn->query($sql);

                        if (mysqli_num_rows($result) > 0) {
                                //Si hay resultado lo muestro en una tabla
                                echo "<table class='table table-bordered table-hover'><thead>
                        <tr><th>Nombre</th>                                
                        <th>Curso</th>
                        <th>Sección</th></tr>
                        </thead>";
                                echo "<tbody>";

                                while ($row = $result->fetch_assoc()) {
                                        echo "<tr><td>" . $row["fullname"] . "</td>";
                                        echo "<td>" . $row["course"] . "</td>";
                                        echo "<td>" . $row["section"] . "</td></tr>";
                                }
                                echo "</tbody>";
                                echo "</table>";
                                echo "<div class='text-center'>
                        <a href='#go-up'>Volver arriba</a>
                        </div>";
                        }
                        //Si no hay resultado muestro el siguiente mensaje.
                        else {
                                echo "<p class='no'>Ningún estudiante agregado!</p>";
                        }
                        $conn->close();
                        ?>
        </div>
    </div>
</div>