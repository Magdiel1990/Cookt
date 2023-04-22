<?php
//Incluyo el documento de las clases.
require_once "modules/classes/classes.php";
?>
<!--Formulario para cambiar nombres y usuarios.-->
<div class="container my-4">
    <div class="text-center">
        <h3>Cambiar Nombres y Usuarios</h3>
    </div>
    <div class="row">
        <form action="" method="POST" class="text-center col-sm-3 mx-auto my-3">
            <div>
                <label for="adminpass" class="form-label">Contraseña</label>
                <input type="password" class="form-control my-2" id="adminpass" placeholder="Contraseña Administrador"
                    name="adminpass" required>
                <label for="username" class="form-label">Usuario</label>
                <?php
                //Se seleccionan todos los datos de los usuarios.
                $sql = "SELECT * FROM users;";
                $result = $conn->query($sql);
                //Se muestra en un dropdown los usuarios guardados.
                echo "<select name='username' class='form-select my-2'>
            <option></option>";
                while ($row = $result->fetch_assoc()) {
                    $usuario = $row['username'];
                    echo "<option value='$usuario'>" . $usuario . "</option>";
                }
                echo "</select>";
                ?>
                <label for="newuser">Nuevo nombre de usuario</label>
                <input type="text" class="form-control my-2" id="newuser" placeholder="Usuario Nuevo" name="newuser">
                <label for="newname">Nuevo nombre</label>
                <input type="text" class="form-control my-2" id="newname" placeholder="Nombre Nuevo" name="newname">
                <label>Sexo</label>
                <div class="my-2">
                    <input type="radio" class="form-check-input mx-2 light-label" name="sexo" id="M" value="M">
                    <label for="M" class="form-label light-label">M</label>
                    <input type="radio" class="form-check-input mx-2" name="sexo" id="F" value="F">
                    <label for="F" class="form-label light-label">F</label>
                </div>
                <!--Limpiar datos después de enviarlos en el formulario.-->
                <script src="./js/script.js"></script>
                <div class="text-center my-2">
                    <input type="submit" class="btn btn-primary" value="Cambiar" name="newuserconfig">
                </div>
            </div>
        </form>
    </div>
    <div>
        <?php
        //Verifico si el formulario viene con información.     
        if (isset($_POST['newuserconfig'])) {
            //Si viene con información recibo la variables.
            $adminpass = $_POST['adminpass'];
            $user = $_POST['username'];
            $nuevousuario = $_POST['newuser'];
            $nuevonombre = $_POST['newname'];
            $sexo = $_POST['sexo'];
            //Objetos que llaman a la función para eliminar espacios en blanco, quitar la barra de escape de caracteres, convertir caracteres especiales en entidades HTML. 
            $input = new input_cleaning();
            $newuser = $input->test_input($nuevousuario);
            $newname = $input->test_input($nuevonombre);
            //Verifico que solo se escriban letras y espacios.       
            if (!preg_match("/^[a-zA-Z-' ]*$/", $newuser) && !preg_match("/^[a-zA-Z-' ]*$/", $newname)) {
                echo "<p class='no'>Sólo letras y espacios permitidos!</p>";
            }
            //Si no hay letras y espacios verifico la contraseña de administración.
            else {
                $sql = "SELECT * FROM users WHERE username = 'Admin';";
                $result = $conn->query($sql);
                $row = mysqli_fetch_array($result);
                //Verifico si la contraseña introducida coincide con la guardada en la bd.        
                if (password_verify($adminpass, $row['password'])) {
                    //Verifico si el usuario, el nuevo usuario y el nuevo nombre viene con información y no es el administrador.
                    if ($user !== "") {
                        if ($user !== 'Admin') {
                            if ($newuser !== "" && $newname !== "") {
                                //Verifico si el nuevo usuario ya existe en la bd.
                                $sql = "SELECT * FROM users WHERE username = '$newuser';";
                                $result = $conn->query($sql);
                                //Si existe.
                                if (mysqli_num_rows($result) > 0) {
                                    echo "<p class='no'>Este usuario ya ha sido editado con estos datos!</p>";
                                }
                                //Si no existe selecciono el nombre del usuario que se va a editar.
                                else {
                                    $sql = "SELECT * FROM users WHERE username = '$user';";
                                    $result = $conn->query($sql);
                                    $row = $result->fetch_assoc();
                                    $name = $row['name'];
                                    //Verifico si los datos existentes son diferentes a los nuevos.                  
                                    if (($user !== $newuser) && ($name !== $newname)) {
                                        $sql = "UPDATE users SET username = '$newuser' WHERE username = '$user';";
                                        $sql .= "UPDATE users SET name = '$newname' WHERE name = '$name';";
                                        //Actualizo los datos existentes con los datos nuevos de nombre de usuario y nombre.
                                        if ($conn->multi_query($sql) === TRUE) {
                                            echo "<p class='yes'>El nombre y el usuario ha sido cambiado exitosamente!</p>";
                                        } else {
                                            echo "<p class='no'>Error al cambiar el nombre y el usuario!</p>";
                                        }
                                    } else {
                                        echo "<p class='no'>Los datos nuevos deben ser diferentes a los que se quieren cambiar!</p>";
                                    }
                                }
                            } else {
                                //Verifico si solo escriben el nombre de usuario y éste es diferente al existente.
                                if ($newuser !== "") {
                                    if ($user !== $newuser) {
                                        //Verifico si el nuevo usuario ya existe en la bd.
                                        $sql = "SELECT * FROM users WHERE username = '$newuser';";
                                        $result = $conn->query($sql);
                                        //Si existe.   
                                        if (mysqli_num_rows($result) > 0) {
                                            echo "<p class='no'>Este usuario ya ha sido editado con estos datos!</p>";
                                        }
                                        //Si no existe cambio el nombre de usuario.                                    
                                        else {
                                            $sql = "UPDATE users SET username = '$newuser' WHERE username = '$user';";
                                            if ($conn->query($sql) === TRUE) {
                                                echo "<p class='yes'>El nombre de usuario ha sido cambiado exitosamente!</p>";
                                            } else {
                                                echo "<p class='no'>Error al cambiar nombre de usuario!</p>";
                                            }
                                        }
                                    } else {
                                        echo "<p class='no'>Los datos nuevos deben ser diferentes a los que se quieren cambiar!</p>";
                                    }
                                }
                                //Verifico si solo escriben el nombre. 
                                else {
                                    if ($newname !== "") {
                                        //Verifico si el nuevo nombre ya existe en la bd.                            
                                        $sql = "SELECT * FROM users WHERE name = '$newname';";
                                        $result = $conn->query($sql);
                                        //Si existe. 
                                        if (mysqli_num_rows($result) > 0) {
                                            echo "<p class='no'>Este usuario ya ha sido editado con estos datos!</p>";
                                        }
                                        //Si no existe selecciono el nombre del usuario que se va a editar.
                                        else {
                                            $sql = "SELECT * FROM users WHERE username = '$user';";
                                            $result = $conn->query($sql);
                                            $row = $result->fetch_assoc();
                                            $name = $row['name'];
                                            //Verifico si el nombre es diferente al nuevo.
                                            if ($name !== $newname) {
                                                $sql = "UPDATE users SET name = '$newname' WHERE name = '$name';";
                                                if ($conn->query($sql) === TRUE) {
                                                    echo "<p class='yes'>El nombre ha sido cambiado exitosamente!</p>";
                                                } else {
                                                    echo "<p class='no'>Error al cambiar nombre!</p>";
                                                }
                                            } else {
                                                echo "<p class='no'>Los datos nuevos deben ser diferentes a los que se quieren cambiar!</p>";
                                            }
                                        }
                                    } else {
                                        echo "<p class='no'>Llene los campos correspondientes!</p>";
                                    }
                                }
                            }
                        } else {
                            //Verifico si el usuario es el administrador, no se introduce el nuevo nombre de usuario y solo se introduce el nuevo nombre.
                            if ($newuser == "") {
                                if ($newname !== "") {
                                    //Verifico si el nuevo nombre ya se ha editado con esos datos.
                                    $sql = "SELECT * FROM users WHERE name = '$newname';";
                                    $result = $conn->query($sql);
                                    //Si fue editado.    
                                    if (mysqli_num_rows($result) > 0) {
                                        echo "<p class='no'>Este usuario ya ha sido editado con estos datos!</p>";
                                    }
                                    //Si no ha sido editado.
                                    else {
                                        $sql = "SELECT * FROM users WHERE username = 'Admin';";
                                        $result = $conn->query($sql);
                                        $row = $result->fetch_assoc();
                                        $name = $row['name'];
                                        $sexo = $_POST['sexo'];
                                        //Verifico si el nuevo nombre es diferente al nombre existente y si el sexo viene con información.
                                        if ($name !== $newname) {
                                            if ($sexo !== "") {
                                                //Se actualizan el nombre del administrador y el sexo.
                                                $sql = "UPDATE users SET name = '$newname' WHERE name = '$name';";
                                                $sql .= "UPDATE users SET sexo = '$sexo' WHERE username = 'Admin';";
                                                if ($conn->multi_query($sql) === TRUE) {
                                                    echo "<p class='yes'>El nombre ha sido cambiado exitosamente!</p>";
                                                } else {
                                                    echo "<p class='no'>Error al cambiar nombre!</p>";
                                                }
                                            }
                                            //Si el sexo viene vacío solo se cambia el nuevo nombre. 
                                            else {
                                                $sql = "UPDATE users SET name = '$newname' WHERE name = '$name';";
                                                if ($conn->query($sql) === TRUE) {
                                                    echo "<p class='yes'>El nombre ha sido cambiado exitosamente!</p>";
                                                } else {
                                                    echo "<p class='no'>Error al cambiar nombre!</p>";
                                                }
                                            }
                                        } else {
                                            echo "<p class='no'>Los datos nuevos deben ser diferentes a los que se quieren cambiar!</p>";
                                        }
                                    }
                                } else {
                                    echo "<p class='no'>Debe ingresar el nuevo nombre del usuario Administrador!</p>";
                                }
                            } else {
                                echo "<p class='no'>Sólo puede cambiar el nombre al Administrador, no el usuario!</p>";
                            }
                        }
                    } else {
                        echo "<p class='no'>Elija el usuario a editar!</p>";
                    }
                } else {
                    echo "<p class='no'>La contraseña de administrador es incorrecta!</p>";
                }
            }
        }
        //Cierro la conexión.
        $conn->close();
        ?>
    </div>
</div>