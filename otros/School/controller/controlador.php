<?php
class MVCcotroller {
    //función que recibe la información de la plantilla
    public function template(){
        include "view/template.php";
    }
    //función que recibe todos los enlaces de las páginas por el método GET y las envía al modelo
    public function enlacescontrolador(){
        if(isset($_GET["action"])){
        $enlaces = $_GET["action"];
        }
        else {
        $enlaces = "index";
        }
        $respuesta = enlacespaginas::enlacespaginasmodelo($enlaces);
        include $respuesta;
    }
}