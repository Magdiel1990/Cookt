<!--Formulario para agregar estudiantes.-->
<div class="row justify-content-evenly">
    <div class="col-md-3 my-5">
        <div>
            <form action="" method="POST" class="text-center">
                <h3>Agregar Estudiantes</h3>
                <!--Nombre y apellido de los estudiantes.-->
                <label for="firstname" class="form-label">Nombres</label>
                <input type="text" id="firstname" class="form-control my-2" name="firstname" required=""
                    placeholder="Nombre">
                <label for="lastname" class="form-label">Apellidos</label>
                <input type="text" id="lastname" class="form-control my-2" name="lastname" required=""
                    placeholder="Apellido">
                <!--Sexo de los estudiantes.-->
                <label>Sexo</label>
                <div class="my-2">
                    <input type="radio" class="form-check-input mx-2" id="M" name="sexo" value="M" required="">
                    <label for="M" class="form-check-label light-label">M</label>
                    <input type="radio" class="form-check-input mx-2" id="F" name="sexo" value="F">
                    <label for="F" class="form-check-label light-label">F</label>
                </div>
                <!--Curso de los estudiantes.-->
                <label>Curso</label>
                <div class="my-2">
                    <input type="radio" class="form-check-input mx-2" id="_4" name="course" value="4" required="">
                    <label for="_4" class="form-check-label light-label">4</label>
                    <input type="radio" class="form-check-input mx-2" id=" _5" name="course" value="5">
                    <label for="_5" class="form-check-label light-label">5</label>
                    <input type="radio" class="form-check-input mx-2" id="_6" name="course" value="6">
                    <label for="_6" class="form-check-label light-label">6</label>
                </div>
                <!--Sección de los estudiantes.-->
                <label>Sección</label>
                <div class="my-2">
                    <input type="radio" class="form-check-input mx-2" id="A" name="section" value="A" required="">
                    <label for="A" class="form-label light-label">A</label>
                    <input type="radio" class="form-check-input mx-2" id="B" name="section" value="B">
                    <label for="B" class="form-label light-label">B</label>
                </div>
                <!--Botón de enviar formulario.-->
                <div class="text-center">
                    <input type="submit" value="Agregar" class="btn btn-primary" id="Enviar" name="Agregar">
                </div>
            </form>
        </div>
        <div>
            <?php
            //Incluyo el documento de las clases.
            require_once "modules/classes/classes.php";
            //Verifico si el botón de enviar del formulario viene con información.
            if (isset($_POST['Agregar'])) {
                //Si viene con información las recibo en estas variables.
                $nombre = $_POST['firstname'];
                $apellido = $_POST['lastname'];
                $course = $_POST['course'];
                $section = $_POST['section'];
                $sexo = $_POST['sexo'];
                //Objetos que llaman a la función para eliminar espacios en blanco, quitar la barra de escape de caracteres, convertir caracteres especiales en entidades HTML. 
                $input = new input_cleaning();
                $firstname = $input->test_input($nombre);
                $lastname = $input->test_input($apellido);
                //Verificar si el estudiante ya existe.
                $sql = "SELECT * FROM students WHERE firstname = '$firstname' AND lastname = '$lastname' AND course = '$course' AND section = '$section' AND sexo = '$sexo';";
                $result = $conn->query($sql);
                //Si ya existe.
                if (mysqli_num_rows($result) > 0) {
                    echo "<p class='no'>Este estudiante ya había sido agregado!</p>";
                } else {
                    //Si no existe introduzco los datos del nombre, apellido, curso, sección y sexo en la base de datos. 
                    $sql = "INSERT INTO students (firstname,lastname,course,section,sexo) VALUES('$firstname','$lastname','$course','$section','$sexo');";
                    //Verifico si la inserción tuvo éxito y obtengo el último id.
                    if ($conn->query($sql) === TRUE) {
                        $last_id = $conn->insert_id;
                        echo "<p class='yes'>Estudiante agregado correctamente!</p><br>";
                        echo "<p class='yes'>El registro es el número: " . $last_id . "</p>";
                    } else {
                        echo "<p class='no'>Error al agregar al estudiante!</p>";
                    }
                }
            }
            ?>
        </div>
    </div>
    <div class="col-md-3 my-5">
        <!--Formulario para eliminar estudiantes.-->
        <div class="text-center">
            <h3>Eliminar Estudiante</h3>
            <?php
            //Consulta que trae de la base de datos los nombres de los estudiantes. 
            $sql = "SELECT * FROM students ORDER BY firstname;";
            $result = $conn->query($sql);
            if (mysqli_num_rows($result) > 0) {
                //Formulario para obtener los estudiantes inscritos y poderlos seleccinar para eliminarlos.
                echo "<form action='' method= 'POST'>
                <input type='password' class='form-control my-3' placeholder='Contraseña Administrador' name='adminpass' required>
                <select name='drop' class='form-select my-3'>
                <option></option>";
                while ($row = $result->fetch_assoc()) {
                    $nombre = $row['firstname'];
                    echo "<option value ='$nombre'>" . $nombre . " " . $row['lastname'] . '</option>';
                }
                echo "</select>";
                echo "<input type='submit' value='Eliminar' class='btn btn-secondary' name='submit'>";
                echo "</form>";
            } else {
                echo "<p class='no'>No hay estudiantes!</p>";
            }
            ?>
        </div>
        <div>
            <?php
            //Verifico si el botón de enviar del formulario viene con información.
            if (isset($_POST['submit'])) {
                //Si viene con información las recibo en estas variables.    
                $drop = $_POST['drop'];
                $adminpass = $_POST['adminpass'];
                //Verifico la contraseña de administrador.           
                $sql = "SELECT * FROM users WHERE username = 'Admin';";
                $result = $conn->query($sql);
                $row = $result->fetch_array();
                if (password_verify($adminpass, $row['password'])) {
                    //Verifico si se ha elegido un nombre del dropdown. De no haberse elegido se escribe este mensaje.
                    if ($drop == "") {
                        echo "<p class='no'>Elija un nombre de la lista!</p>";
                    }
                    //Elimino con un multiquery el estudiante introducido de la base de datos.
                    else {
                        $sql = "DELETE FROM exams WHERE firstname='$drop';";
                        $sql .= "DELETE FROM results WHERE firstname='$drop';";
                        $sql .= "DELETE FROM participation WHERE firstname='$drop';";
                        $sql .= "DELETE FROM register WHERE firstname='$drop';";
                        $sql .= "DELETE FROM students WHERE firstname='$drop';";
                        //Verifico si el borrado tuvo éxito.
                        if ($conn->multi_query($sql) === TRUE) {
                            echo "<p class='yes'>Estudiante eliminado exitosamente!</p>";
                        } else {
                            echo "<p class='no'>Error al eliminar el estudiante!</p>";
                        }
                        //Destruyo la variable una vez utilizada.        
                    }
                } else {
                    echo "<p class='no'>Contraseña de Administrador incorrecta!</p>";
                }
            }
            //Cierro la conexión.
            $conn->close();
            ?>
        </div>
    </div>
</div>