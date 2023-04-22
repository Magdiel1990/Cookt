<?php
//Código para cerrar sesión en un tiempo después de dejar de usar la aplicación.
//Reviso el estado de la sesión.
session_name("loginUsuario");
//Inicio una nueva sesión.
session_start();
//Si ningún usuario se ha logueado se redirige hacia el login.
if (!isset($_SESSION['id'])) {
    header("location:login.php");
    die();
} else {
    //Sino, calculamos el tiempo transcurrido desde la última actualización.
    $fechaGuardada = $_SESSION["ultimoAcceso"];
    $ahora = date("Y-n-j H:i:s");
    //Se resta el tiempo de la página del login y el tiempo de esta página. 
    $tiempo_transcurrido = (strtotime($ahora) - strtotime($fechaGuardada));

    //Comparamos el tiempo transcurrido.
    if ($tiempo_transcurrido >= 3000) {
        //Si pasa del tiempo establecido se destruye la sesión.
        session_destroy();
        //Envío al usuario a la página de login.
        header("Location: login.php");
        //Sino, actualizo la fecha de la sesión.
    } else {
        $_SESSION["ultimoAcceso"] = $ahora;
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
    <!--CSS Bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- JavaScript Bundle Bootstrap-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
</head>

<body>
    <header>
        <?php
        //Inclusión del header.
        include "modules/header.php";
        ?>
    </header>
    <main>
        <?php
        //Objetos correspondientes a los enlaces recibidos por el controlador y reenviados por el modelo.
        $mvc = new MVCcotroller();
        $mvc->enlacescontrolador();
        ?>
    </main>
    <footer>
        <?php
        //Inclusión del footer.
        include "modules/footer.php";
        ?>
    </footer>
</body>

</html>