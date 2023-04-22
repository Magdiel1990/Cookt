<?php
//Inclusión del modelo.
require_once "model/modelo.php";
//Inclusión del controller.
require_once "controller/controlador.php";
//Creación de objeto de la clase del controlador.
$mvc = new MVCcotroller();
$mvc -> template();
?>