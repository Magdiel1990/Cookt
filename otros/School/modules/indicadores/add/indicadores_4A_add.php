<?php
//Incluyo el documento de las clases.
//Incluyo el documento de las clases.
require_once "modules/classes/classes.php";
//Objetos para seleccionar los estudiantes, el mes, la calificación. 
$evaluacion = new evaluacion();
$evaluacion->set_curso('4', 'A');
$evaluacion->student_selection();
/*$evaluacion->mes_selection();
$evaluacion->calificacion_select();

//Limpiar datos después de enviarlos en el formulario.
echo '<script src="./js/script.js"></script>';
//Objeto del botón de guardar y editar.
$evaluacion->submit();
//Si viene información en el formulario del botón de guardar.
if (isset($_POST['calificar'])) {
        //Recibo las variables.
        $indicador = $_POST['indicadores'];
        $firstname = $_POST['estudiantes'];
        $mes = $_POST['mes'];
        $resultados = $_POST['resultado'];
        $curso = '4';
        $section = 'A';
        //Objeto para traer el valor numérico asignado a cada mes.
        $switch = new month_switch();
        $mes_id = $switch->month_switch($mes);
        //Objeto para guardar las calificaciones.   
        $evaluacion->guardar_calificaciones($indicador, $firstname, $mes, $resultados, $curso, $section, $mes_id);
}
//Si viene información en el formulario del botón de editar.*/
$conn->close();
?>