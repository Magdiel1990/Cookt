<div class="container row justify-content-center mx-auto">
    <div class="text-center">
        <?php
        //Incluyo el documento de las clases.
        require_once "modules/classes/classes.php";
        //Verifico si el botón de enviar del formulario viene con información
        if (isset($_POST['r_enviar'])) {
            //Si viene con información las recibo en estas variables
            $firstname = $_POST['firstname'];
            $entrada = $_POST['register'];
            $fecha = $_POST['fecha'];
            //Objetos que llaman a la función para eliminar espacios en blanco, quitar la barra de escape de caracteres, convertir caracteres especiales en entidades HTML. 
            $input = new input_cleaning();
            $register = $input->test_input($entrada);
            //Verifico si ese registro existe.
            $sql = "SELECT * FROM register WHERE register = '$register' AND fecha = '$fecha' AND firstname = '$firstname';";
            $result = $conn->query($sql);
            //Si no existe.
            if (mysqli_num_rows($result) == 0) {
                //Introduzco los datos del nombre, apellido, curso, sección y sexo en la base de datos 
                $sql = "INSERT INTO register (register,fecha,firstname) VALUES('$register','$fecha','$firstname');";
                //Verifico si la inserción tuvo éxito y obtengo el último id
                if ($conn->query($sql) === TRUE) {
                    $last_id = $conn->insert_id;
                    echo "<p class='yes'>Registro número: " . $last_id . " agregado!</p>";
                } else {
                    echo "<p class='no'>Este estudiante no existe!</p>";
                }
            } else {
                echo "<p class='no'>Esta registro ya fue agregado!</p>";
            }
        }
        ?>
    </div>
    <!--Formulario para agregar el registro anecdótico-->
    <div class="col-md-3 my-4 text-center">
        <h3>Agregar Registro</h3>
        <form action="" method="POST" class="my-3">
            <!--Nombre del estudiante-->
            <label for="firstname" class="form-label">Nombre:</label>
            <input type="text" id="firstname" class="form-control" name="firstname" placeholder="Nombre" required>
            <!--Registro anecdótico-->
            <label for="register" class="form-label">Registro:</label>
            <textarea name="register" id="register" class="form-control" cols="50" rows="5" required></textarea>
            <!--Fecha de registro-->
            <label for="date" class="form-label">Fecha:</label>
            <input type="date" id="date" class="form-control" name="fecha" required>
            <!--Botón de enviar formulario-->
            <input type="submit" value="Agregar" class="btn btn-primary my-4" id="Enviar" name="r_enviar">
        </form>
        <!--Formulario para consultar el registro anecdótico-->
        <form action="" method="POST">
            <h3>Consulta</h3>
            <!--Campo para ingresar el nombre o inicial del estudiante-->
            <input type="text" name="nombreconsulta" class="form-control" placeholder="Nombre">
            <!--Botón de enviar formulario-->
            <input type="submit" class="btn btn-primary my-4" value="Consultar" name="rv_consultar">
        </form>
    </div>
    <div>
        <?php
        //Código para agregar registros.
        //Verifico si el botón de enviar del formulario viene con información
        if (isset($_POST['rv_consultar'])) {
            //Si viene con información las recibo en esta variable        
            $nombre = $_POST['nombreconsulta'];
            //Objeto para ver los registros.      
            $consulta = new plantilla_checkbox();
            $consulta->set_name($nombre);
            $consulta->ver_registros($nombre);
        }
        //Código para consultar registros.
        //Verifico si el botón de enviar del formulario viene con información.
        if (isset($_POST['Eliminar'])) {
            //Si viene con información las recibo en esta variable.   
            $registros = $_POST['registro'];
            //Variables argumentos de la clase.
            $tabla = 'register';
            $id = 'register_id';
            //Objeto para eliminar registro.
            $deletion = new plantilla_checkbox();
            $deletion->eliminar_checkbox($registros, $tabla, $id);
        }
        //Cierro la conexión.
        $conn->close();
        ?>
    </div>
</div>
<!--Limpiar datos después de enviarlos en el formulario.-->
<script src="./js/script.js"></script>