<?php
class MVCcontroller {
public function plantilla() {
 include "View/template.php";
}
public function enlacesPaginasController(){
    if(isset($_GET['action'])){
    $enlaces=$_GET['action'];
    }
    else{
    $enlaces="index";
    }

    $respuesta= enlacesPaginas::enlacesPaginasModel($enlaces);
    include $respuesta;
}
}
?>