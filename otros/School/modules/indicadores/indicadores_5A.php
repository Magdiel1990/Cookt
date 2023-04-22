<div class="container py-1 my-4 shadow">
    <div class="text-center my-4 ml-4">
        <h3>Quinto A</h3>
    </div>
    <?php
        //Incluyo el documento de las clases.
        require_once "modules/classes/classes.php";
        //Objetos para seleccionar los estudiantes, el mes, la calificación. 
        $evaluacion = new evaluacion();
        $evaluacion->set_curso('5', 'A');
        $evaluacion->indicadores_selection('5');
        $evaluacion->student_selection('5', 'A');
        $evaluacion->mes_selection();
        $evaluacion->calificacion_select();
        //Limpiar datos después de enviarlos en el formulario.
        echo '<script src="./js/script.js"></script>';
        //Objeto del botón de guardar y editar.
        $evaluacion->submit();
        //Si viene información en el formulario del botón de guardar.
        if (isset($_POST['guardar'])) {
                //Recibo las variables.
                $indicador = $_POST['indicadores'];
                $firstname = $_POST['firstname'];
                $mes = $_POST['mes'];
                $resultados = $_POST['resultado'];
                $curso = '5';
                $section = 'A';
                //Objeto para traer el valor numérico asignado a cada mes.
                $switch = new month_switch();
                $mes_id = $switch->month_switch($mes);
                //Objeto para guardar las calificaciones.   
                $evaluacion->guardar_calificaciones($indicador, $firstname, $mes, $resultados, $curso, $section, $mes_id);
        }
        //Si viene información en el formulario del botón de editar.
        if (isset($_POST['editar'])) {
                //Recibo las variables.
                $indicador = $_POST['indicadores'];
                $firstname = $_POST['firstname'];
                $mes = $_POST['mes'];
                $resultados = $_POST['resultado'];
                $curso = '5';
                $section = 'A';
                //Objeto para editar las calificaciones.        
                $evaluacion->editar_calificaciones($indicador, $firstname, $mes, $resultados, $curso, $section);
                //Cierro la conexión.
                $conn->close();
        }
        //Objeto para botón de volver atrás.
        $evaluacion->atras();
        ?>
</div>