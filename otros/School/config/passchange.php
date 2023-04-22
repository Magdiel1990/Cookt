<!--Formulario para recibir los datos para cambiar la contraseña.-->
<div class="container my-4">
    <div class="row justify-content-center">
        <form action="" method="POST" class="col-md-3 text-center">
            <div class="text-center">
                <h3>Cambiar Contraseña</h3>
            </div>
            <label for="adminpass" class="form-label">Contraseña</label>
            <input type="password" class="form-control my-2" id="adminpass" placeholder="Contraseña Administrador"
                name="adminpass" required>
            <label for="username" class="form-label">Nombre de usuario</label>
            <input type="text" class="form-control my-2" id="username" placeholder="Usuario" name="username" required>
            <label for="password" class="form-label">Contraseña Antigua:</label>
            <input type="password" class="form-control my-2" id="password" placeholder="Antigua Contraseña"
                name="password" required>
            <label for="newpassword" class="form-label">Contraseña Nueva</label>
            <input type="password" class="form-control my-2" id="newpassword" placeholder="Nueva Contraseña"
                name="newpassword" required>
            <label for="newpassword_2" class="form-label">Repita Contraseña Nueva</label>
            <input type="password" class="form-control my-2" id="newpassword_2" placeholder="Nueva Contraseña"
                name="newpassword_2" required>
            <!--Limpiar datos después de enviarlos en el formulario.-->
            <script src="./js/script.js"></script>
            <div class="text-center my-3">
                <input type="submit" class="btn btn-primary" value="Cambiar" name="newpass">
            </div>
        </form>
    </div>
    <div>
        <?php
        //Incluyo el documento de las clases.
        require_once "modules/classes/classes.php";
        //Verifico si el formulario viene con información.
        if (isset($_POST['newpass'])) {
            //Recibo las variables.
            $user = $_POST['username'];
            $adminpass = $_POST['adminpass'];
            $pass = $_POST['password'];
            $newpass = $_POST['newpassword'];
            $newpass_2 = $_POST['newpassword_2'];
            //Aplico hashing a la contraseña.
            $hash_pass = password_hash($newpass, PASSWORD_DEFAULT);
            //Consulto los datos del usuario administración.
            $sql = "SELECT * FROM users WHERE username = 'Admin';";
            $result = $conn->query($sql);
            $row = $result->fetch_array();
            //Comparo la contraseña del administrador con la introducida.            
            if (password_verify($adminpass, $row['password'])) {
                //Me aseguro que la contraseña tenga 5 letras o más.
                if (strlen($newpass) < 5) {
                    echo "<p class='no'>La contraseña debe tener al menos 5 dígitos!</p>";
                } else {
                    //Verifico que la contraseña vieja es la diferente a la nueva. 
                    if ($pass !== $newpass) {
                        $sql = "SELECT * FROM users WHERE username = '$user';";
                        $result = $conn->query($sql);
                        $row = mysqli_fetch_array($result);
                        //Verifico si el usuario existe.
                        if (is_array($row)) {
                            //Comparo la contraseña del administrador con la introducida.   
                            if (password_verify($pass, $row['password'])) {
                                //Cambio la contraseña.
                                $sql = "UPDATE users SET password = '$hash_pass' WHERE username = '$user';";
                                if ($conn->query($sql) === TRUE) {
                                    echo "<p class='yes'>Contraseña cambiada correctamente!</p>";
                                } else {
                                    echo "<p class='no'>Error al cambiar contraseña!</p>";
                                }
                            } else {
                                echo "<p class='no'>La contraseña es incorrecta!</p>";
                            }
                        } else {
                            echo "<p>El usuario no existe!</p>";
                        }
                    } else {
                        echo "<p class='no'>La contraseña nueva debe ser distinta a la que quiere cambiar!</p>";
                    }
                }
            } else {
                echo "<p class='no'>Contraseña de Administrador incorrecta!</p>";
            }
            //Cierro la conexión
            $conn->close();
        }
        ?>
    </div>
</div>