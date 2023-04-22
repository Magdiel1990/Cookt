<?php
//Reviso el estado de la sesión.
session_name("loginUsuario");
//Inicio una nueva sesión. 
session_start();
//Declaro la variable mensaje que usaré más tarde.
$message = "";
//Inclusión de la conexión a la base de datos
require "connection/connect.php";
//Si hay variables post enviadas las recibo.
if (!empty($_POST)) {
    $user = $_POST['user'];
    $password = $_POST['password'];
    //Verifico los datos del usuario.
    $sql = "SELECT * FROM users WHERE username = '$user';";
    $result = $conn->query($sql);
    $row = mysqli_fetch_array($result);
    //Si el usuario existe verifico la contraseña.
    if (is_array($row)) {
        if (password_verify($password, $row['password'])) {
            //Creo la cookie.        
            session_set_cookie_params(0, "/", $_SERVER["HTTP_HOST"], 0);
            //Declaro las variables de la sesión.
            $_SESSION["autentificado"] = "SI";
            //Calcula la hora y fecha del momento en el que se crea la sesión.
            $_SESSION["ultimoAcceso"] = date("Y-n-j H:i:s");
            $_SESSION['id'] = $row['id'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['sexo'] = $row['sexo'];
            //Si los datos coinciden con los de la bd se redirecciona al index.
            header("location:index.php");
        }
    } else {
        $message = "<p class='no'>Usuario o contraseña incorrecta!</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <title>T-friend</title>
    <meta charset="UTF-8">
    <meta name="description" content="programa para almacenar los resultados de las evaluaciones de los estudiantes">
    <meta name="keywords" content="estudiante, maestro, escuela, evaluación">
    <meta name="author" content="Magdiel Castillo Mills">
    <meta name="viewport" content="width=device-width, user-scalable=no, 
    initial-scale=1, maximum-scale=1, minimum-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="css/estilos.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- JavaScript Bundle Bootstrap-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
</head>

<body>
    <!--Formulario para hacer el login-->
    <div class="login_container">
        <div class="login">
            <h2>Login</h2>
            <form action="" method="POST">
                <label for="user">Usuario:</label>
                <input type="text" id="user" class="text_field" name="user" required>
                <label for="password">Contraseña:</label>
                <input type="password" id="password" class="text_field" name="password" required>
                <input type="submit" name="login" class="btn_form" value="Ingresar">
                <div><?php if ($message != "") {
                            echo $message;
                        } ?></div>
            </form>
        </div>
    </div>
</body>

</html>