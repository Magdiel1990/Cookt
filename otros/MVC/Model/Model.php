<?php
class enlacesPaginas{
    public static function enlacesPaginasModel($enlaces) {
        if($enlaces=="nosotros"||
        $enlaces=="contacto"||
        $enlaces=="servicios"){
         
        $modulo= "View/".$enlaces.".php";
        }
        elseif($enlaces=="index"){
            $modulo="View/Inicio.php";
        }
        return $modulo;
    }
}

?>