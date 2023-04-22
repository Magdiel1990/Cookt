<div class="container py-1 my-4 shadow">
    <div class="text-center my-4 ml-4">
        <h3>Cuarto A</h3>
    </div>
    <div>
        <?php
            //Incluyo el documento de las clases.
            require_once "modules/classes/classes.php";
            //Objetos para seleccionar los estudiantes, el mes, la calificación. 
            $evaluacion = new evaluacion();
            $evaluacion->set_curso('4', 'A');
            $evaluacion->indicadores_selection();
        ?>
    </div>
</div>