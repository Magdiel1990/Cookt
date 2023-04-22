<!--Formulario para eliminar usuario.-->
<div class="container my-4 d-flex flex-column justify-content-center align-items-center">
    <div>
        <form action="" method="POST">
            <?php
            //Incluyo el documento de las clases.
            require_once "modules/classes/classes.php";
            //Seleccionar los datos de los usuarios que no son administradores.
            $sql = "SELECT * FROM users WHERE NOT username = 'Admin';";
            $result = $conn->query($sql);
            //Si hay usuarios que no son administradores.       
            if (mysqli_num_rows($result) > 0) {
                echo '<div class="text-center">';
                echo '<h3>Eliminar Usuario</h3>';
                echo '</div>';
                echo '<label for="adminpass" class="form-label">Contraseña de administrador:</label>
                <input type="password" class="form-control" id="adminpass" placeholder="Contraseña Administrador" name="adminpass" required>';
                //Dropdown para mostrar los usuarios que no son admin.      
                echo "<select name='username' class='form-select'>
                <option></option>";
                while ($row = $result->fetch_assoc()) {
                    $usuario = $row['username'];
                    echo "<option value='$usuario'>" . $usuario . "</option>";
                }
                echo "</select>";
                //Limpiar datos después de enviarlos en el formulario. 
                echo '<script src="./js/script.js"></script>';
                echo '<div class="text-center my-3">';
                echo '<input type="submit" class="btn btn-secondary" value="Eliminar" name="userdel"></form>';
                echo '</div>';
                //Verifico si el formulario viene con información.
                if (isset($_POST['userdel'])) {
                    //Recibo las variables.                
                    $user = $_POST['username'];
                    $adminpass = $_POST['adminpass'];
                    //Consulto los datos del usuario administración.
                    $sql = "SELECT * FROM users WHERE username = 'Admin';";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    //Verifico si el usuario viene con información, si tiene información.                
                    if ($user !== "") {
                        //Comparo la contraseña del administrador con la introducida.
                        if (password_verify($adminpass, $row['password'])) {
                            //Elimino el usuario seleccionado.                            
                            $sql = "DELETE FROM users WHERE username ='$user';";

                            if ($conn->query($sql) === TRUE) {
                                echo "<p class='yes'>Usuario eliminado correctamente!</p>";
                            } else {
                                echo "<p class='no'>Error al eliminar usuario!</p>";
                            }
                        } else {
                            echo "<p class='no'>Contraseña de Administrador incorrecta!</p>";
                        }
                    } else {
                        echo "<p class='no'>Elija el usuario a eliminar!</p>";
                    }
                }
            }
            //Si solo está el usuario administrador.             
            else {
                echo "<p class='no'>No hay usuarios agregados!</p>";
            }
            //Cierro la conexión.
            $conn->close();
            ?>
    </div>
</div>