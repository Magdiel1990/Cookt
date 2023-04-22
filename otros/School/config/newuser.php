
<!--
Debo asegurarme que no hayan usuarios repetidos.

-->






<!--Formulario para recibir los datos para agregar nuevos usuarios.-->
<div class="container my-4">
    <div class="row justify-content-center">
        <form action="" method="POST" class="col-md-3 text-center">
            <h3>Agregar Usuario</h3>
            <label for="adminpass" class="form-label">Contraseña</label>
            <input type="password" class="form-control my-2" id="adminpass" placeholder="Contraseña Administrador"
                name="adminpass" required>
            <label for="username" class="form-label">Nombre de usuario</label>
            <input type="text" class="form-control my-2" id="username" placeholder="Usuario" name="username" required>
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control my-2" id="password" placeholder="Contraseña" name="password"
                required>
            <label for="password_2" class="form-label">Repita la contraseña</label>
            <input type="password" class="form-control my-2" id="password_2" placeholder="Contraseña" name="password_2"
                required>
            <label for="name" class="form-label">Nombre</label>
            <input type="text" class="form-control my-2" id="name" placeholder="Nombre" name="name" required>
            <label>Sexo</label>
            <div class="my-2">
                <input type="radio" class="form-check-input mx-2" name="sexo" id="M" value="M" required>
                <label for="M" class="form-label light-label">M</label>
                <input type="radio" class="form-check-input mx-2" name="sexo" id="F" value="F">
                <label for="F" class="form-label light-label">F</label>
            </div>
            <!--Limpiar datos después de enviarlos en el formulario.-->
            <script src="./js/script.js"></script>
            <input type="submit" class="btn btn-primary" value="Agregar" name="newuser">
        </form>
    </div>
    <div>
        <?php
        //Incluyo el documento de las clases.
        require_once "modules/classes/classes.php";
        //Verifico si el formulario viene con información.
        if (isset($_POST['newuser'])) {
            //Recibo las variables.
            $usuario = $_POST['username'];
            $adminpass = $_POST['adminpass'];
            $pass = $_POST['password'];
            $pass_2 = $_POST['password_2'];
            $nombre = $_POST['name'];
            $sexo = $_POST['sexo'];
            //Objetos que llaman a la función para eliminar espacios en blanco, quitar la barra de escape de caracteres, convertir caracteres especiales en entidades HTML. 
            $input = new input_cleaning();
            $user = $input->test_input($usuario);
            $name = $input->test_input($nombre);
            //Aplico hashing a la contraseña.
            $hash_pass = password_hash($pass, PASSWORD_DEFAULT);
            //Consulto los datos del usuario administración.
            $sql = "SELECT * FROM users WHERE username = 'Admin';";
            $result = $conn->query($sql);
            $row = $result->fetch_array();
            //Verifico que sólo se introduzcan letras y espacios.        
            if (!preg_match("/^[a-zA-Z-' ]*$/", $user) && !preg_match("/^[a-zA-Z-' ]*$/", $name)) {
                echo "<p class='no'>Sólo letras y espacios permitidos!</p>";
            } else {
                //Comparo la contraseña del administrador con la introducida.
                if (password_verify($adminpass, $row['password'])) {
                    //Me aseguro que la contraseña tenga 5 letras o más.
                    if (strlen($pass) < 5) {
                        echo "<p class='no'>La contraseña debe tener al menos 5 dígitos!</p>";
                    } else {
                        //Verifico que la contraseña sea la misma en la repetición de la misma.
                        if ($pass == $pass_2) {
                            //Guardo los datos del nuevo usuario en la bd.
                            $sql = "INSERT INTO users (username, password, name, sexo) VALUES ('$user', '$hash_pass', '$name', '$sexo');";

                            if ($conn->query($sql) === TRUE) {
                                echo "<p class='yes'>Usuario agregado correctamente!</p>";
                            } else {
                                echo "<p class='no'>Error al agregar al usuario o este usuario ya existe!</p>";
                            }
                        } else {
                            echo "<p class='no'>Las contraseñas no coinciden!</p>";
                        }
                    }
                } else {
                    echo "<p class='no'>Contraseña de Administrador incorrecta!</p>";
                }
            }
            //Cierro la conexión
            $conn->close();
        }
        ?>
    </div>
</div>

