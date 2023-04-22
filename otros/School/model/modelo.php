<?php
class enlacespaginas{
//Función que valida los enlaces recibidos y devuelve el solicitado.
    public static function enlacespaginasmodelo($enlaces){
        if($enlaces == "admin_students"||
        $enlaces == "examenes"||
        $enlaces == "participaciones"||
        $enlaces == "registro"||
        $enlaces =="listado_estudiantes"||
        $enlaces == "ver_anotaciones"||
        $enlaces == "resumen"){
            $module = "modules/".$enlaces.".php";
        }
        elseif ($enlaces == "newuser"||
        $enlaces == "passchange"||
        $enlaces == "userdelete"||
        $enlaces == "userconfig"||
        $enlaces == "admin_files"||
        $enlaces == "reset"||
        $enlaces == "backup_view"||
        $enlaces == "add_indicadores"||
        $enlaces == "subject"||
        $enlaces == "evaluationSetting") {
            $module = "config/".$enlaces.".php";
        }
        elseif ($enlaces == "indicadores"||
        $enlaces == "indicadores_4A"||
        $enlaces == "indicadores_4B"||
        $enlaces == "indicadores_5A"||
        $enlaces == "indicadores_5B"||
        $enlaces == "indicadores_6A") {
            $module = "modules/indicadores/".$enlaces.".php";
        }
        elseif($enlaces == "indicadores_4A_add"||
        $enlaces == "indicadores_4B_add"||
        $enlaces == "indicadores_5A_add"||
        $enlaces == "indicadores_5B_add"||
        $enlaces == "indicadores_6A_add"){
            $module = "modules/indicadores/add/".$enlaces.".php";
        }
//Si la variable get no trae valor, me envía a la página de inicio.
        elseif($enlaces == "index"){
            $module = "modules/Inicio.php";            
        }
//Manda a página de error 404 en caso de ingresar una dirección que no exista.
        else {
            $module = "modules/error404.php";
        }
        return $module;
    }
}