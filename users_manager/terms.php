<?php
session_name("signup");

session_start();

//Models.
require_once ("models/models.php");

$header = new PageHeaders($_SERVER["REQUEST_URI"]);
$header = $header -> pageHeader();    
?>

<!DOCTYPE html>
<html lang="es" data-lt-installed="true">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="Magdiel Castillo Mills">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="Keywords" content="receta, recipe, cocina, kitchen, sugerencias, recommendations">
    <meta name="ltm:project" content="recetas personalizadas">
    <meta property="og:type" content="website">
    <meta name="ltm:domain" content="recipeholder.net">
    <meta name="description" content="Encuentra la receta de cocina fácil que estás buscando personalizadas de acuerdo a los ingredientes que tengas en tu casa.">
    <title><?php echo $header;?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="shortcut icon" href="imgs/logo/logo2.png">
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@600;900&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/65a5e79025.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
</head>
<body>
    <header class="container-fluid p-3">
        <div class="row">   
            <div class="col-4"></div>         
            <div class="col-4 text-center">
                <h1 class="text-light terms-title">Términos y condiciones</h1>
            </div>
            <div class="col-2"></div> 
            <div class="col-2">
                <a class="nav-link text-white" href="<?php echo root. "signup";?>">Signup</a>
            </div>
        </div>
    </header>
    <main class="container-fluid">
        <div class="row m-5">
            <div class="col-xs-12 terms">
                <p>
                    Bienvenido/a a nuestro sitio web, una plataforma que ofrece sugerencias de recetas a sus usuarios. Antes de utilizar nuestros servicios, te pedimos que leas detenidamente los siguientes términos y condiciones. Al acceder y utilizar nuestro sitio web, aceptas cumplir con estos términos y cualquier otra regla o directriz adicional que pueda ser publicada ocasionalmente en nuestro sitio.
                </p>
                <h3>Uso de la plataforma</h3>
                <p>
                    a. Nuestro sitio web está diseñado para proporcionar ideas y sugerencias de recetas a nuestros usuarios. Tienes permiso para acceder y utilizar nuestra plataforma para tu uso personal y no comercial.
                </p>
                <p>
                    b. No debes utilizar nuestros servicios para cualquier actividad ilegal, fraudulenta o no autorizada. No puedes acceder a áreas restringidas de nuestro sitio, interferir con el funcionamiento normal de la plataforma o intentar eludir las medidas de seguridad implementadas.
                </p>
                <h3>Contenido</h3>
                <p>
                a. El contenido publicado en nuestro sitio web, incluyendo las recetas, imágenes y cualquier otro material, es proporcionado únicamente con fines informativos y de entretenimiento. No garantizamos la precisión, actualidad o completitud de dicho contenido.
                </p>
                <p>
                b. No puedes copiar, modificar, distribuir o reproducir el contenido de nuestro sitio sin nuestro consentimiento expreso por escrito.
                </p>
                <h3>Propiedad intelectual</h3>
                <p>
                    a. Todos los derechos de propiedad intelectual relacionados con nuestro sitio web y su contenido, incluyendo pero no limitado a marcas registradas, derechos de autor y patentes, son propiedad exclusiva nuestra.
                </p>
                <p>
                    b. No está permitido utilizar, modificar o eliminar cualquier marca registrada, logotipo, nombre de empresa o cualquier otro contenido protegido por derechos de propiedad intelectual sin nuestro consentimiento previo por escrito.
                </p>
                <h3>Privacidad</h3>
                <p>
                a. Valoramos tu privacidad y nos comprometemos a proteger tus datos personales. Consulta nuestra Política de Privacidad para obtener más información sobre cómo recopilamos, utilizamos y protegemos tus datos.
                </p>
                <p>
                b. Al utilizar nuestro sitio web, aceptas que podemos recopilar y procesar tus datos personales de acuerdo con nuestra Política de Privacidad.
                </p>
                <h3>Enlaces a sitios de terceros</h3>
                <p>
                Nuestro sitio web puede contener enlaces a sitios web de terceros. Estos enlaces son proporcionados únicamente para tu conveniencia y no implican nuestro respaldo o responsabilidad por el contenido o las prácticas de privacidad de dichos sitios. Te recomendamos revisar los términos y políticas de privacidad de esos sitios antes de utilizarlos.
                </p>
                <h3>Limitación de responsabilidad</h3>
                <p>
                a. No ofrecemos garantías ni representaciones sobre la precisión, confiabilidad o disponibilidad de nuestro sitio web y su contenido. Utilizas nuestro sitio bajo tu propio riesgo.
                </p>
                <p>
                b. No seremos responsables de ningún daño directo, indirecto, incidental, especial o consecuente derivado del uso de nuestro sitio web o la imposibilidad de utilizarlo.
                </p>
                <h3>Modificaciones de los términos de servicio</h3>
                <p>
                Nos reservamos el derecho de modificar estos Términos de Servicio en cualquier momento sin previo aviso. Te recomendamos revisar periódicamente los términos actualizados. El uso continuado de nuestro sitio web después de cualquier modificación constituye tu aceptación de los términos modificados.
                </p>
                <h3>Ley aplicable</h3>
                <p>
                Estos Términos de Servicio se regirán e interpretarán de acuerdo con las leyes vigentes de República Dominicana. Cualquier disputa o reclamo relacionado con estos términos estará sujeta a la jurisdicción exclusiva de los tribunales competentes en la ciudad de Salcedo, República Dominicana.
                </p>
                <p>
                Si tienes alguna pregunta o inquietud sobre nuestros Términos de Servicio, no dudes en ponerte en contacto con nosotros a través de los canales de comunicación proporcionados en nuestro sitio web.
                </p>
            </div>
        </div>
    </main>
    <?php
    //Footer of the page.
    require_once ("views/partials/footer.php");
    ?>
  </body>
</html>