<div>
    <div class="container my-3">
        <div class="text-center">
            <h3>Resumen</h3>
        </div>
        <div class="row justify-content-center">
            <?php
            //Incluyo el documento de las clases.
            require_once "modules/classes/classes.php";
            //Llamado a la clase de conteo
            $conteo = new count();
            //Tabla para representar los datos
            echo "<table class='table col-md-auto w-50'>";
            echo "<thead>";
            echo "<tr><th>Descripción</th>
            <th>Cantidad</th></tr>";
            echo "</thead>";
            echo "<tbody>";
            //Conteo del total de estudiantes
            echo "<tr><td>Total de estudiantes</td>";
            $conteo->conteo(null, null, null);
            echo "<tr><td colspan='2'><b>Resumen 4 de Primaria</b></td></tr>";
            //Conteo del total de estudiantes de 4
            echo "<tr><td>Total de 4</td>";
            $conteo->conteo('4', null, null);
            //Conteo del total de estudiantes varones de 4
            echo "<tr><td>Total de varones de 4</td>";
            $conteo->conteo('4', null, 'M');
            //Conteo del total de estudiantes hembras de 4
            echo "<tr><td>Total de hembras de 4</td>";
            $conteo->conteo('4', null, 'F');
            //Conteo del total de estudiantes de 4A
            echo "<tr><td>Total de 4A</td>";
            $conteo->conteo('4', 'A', null);
            //Conteo del total de estudiantes varones de 4A
            echo "<tr><td>Total de varones de 4A</td>";
            $conteo->conteo('4', 'A', 'M');
            //Conteo del total de estudiantes hembras de 4A
            echo "<tr><td>Total de hembras de 4A</td>";
            $conteo->conteo('4', 'A', 'F');
            //Conteo del total de estudiantes de 4B
            echo "<tr><td>Total de 4B</td>";
            $conteo->conteo('4', 'B', null);
            //Conteo del total de estudiantes varones de 4B
            echo "<tr><td>Total de varones de 4B</td>";
            $conteo->conteo('4', 'B', 'M');
            //Conteo del total de estudiantes hembras de 4B
            echo "<tr><td>Total de hembras de 4B</td>";
            $conteo->conteo('4', 'B', 'F');
            echo "<tr><td colspan='2'><b>Resumen 5 de Primaria</b></td></tr>";
            //Conteo del total de estudiantes de 5
            echo "<tr><td>Total de 5</td>";
            $conteo->conteo('5', null, null);
            //Conteo del total de estudiantes varones de 5
            echo "<tr><td>Total de varones de 5</td>";
            $conteo->conteo('5', null, 'M');
            //Conteo del total de estudiantes hembras de 5
            echo "<tr><td>Total de hembras de 5</td>";
            $conteo->conteo('5', null, 'F');
            //Conteo del total de estudiantes de 5A
            echo "<tr><td>Total de 5A</td>";
            $conteo->conteo('5', 'A', null);
            //Conteo del total de estudiantes varones de 5A
            echo "<tr><td>Total de varones de 5A</td>";
            $conteo->conteo('5', 'A', 'M');
            //Conteo del total de estudiantes hembras de 5A
            echo "<tr><td>Total de hembras de 5A</td>";
            $conteo->conteo('5', 'A', 'F');
            //Conteo del total de estudiantes de 5B
            echo "<tr><td>Total de 5B</td>";
            $conteo->conteo('5', 'B', null);
            //Conteo del total de estudiantes varones de 5B
            echo "<tr><td>Total de varones de 5B</td>";
            $conteo->conteo('5', 'B', 'M');
            //Conteo del total de estudiantes hembras de 5B
            echo "<tr><td>Total de hembras de 5B</td>";
            $conteo->conteo('5', 'B', 'F');
            echo "<tr><td colspan='2'><b>Resumen 6 de Primaria</b></td></tr>";
            //Conteo del total de estudiantes de 6
            echo "<tr><td>Total de 6</td>";
            $conteo->conteo('6', null, null);
            //Conteo del total de estudiantes varones de 6
            echo "<tr><td>Total de varones de 6</td>";
            $conteo->conteo('6', null, 'M');
            //Conteo del total de estudiantes hembras de 6
            echo "<tr><td>Total de hembras de 6</td>";
            $conteo->conteo('6', null, 'F');
            //Cierre de la tabla
            echo "</tr>";
            echo "</tbody>";
            echo "</table>";
            ?>
        </div>
    </div>
    <div>
        <?php
        //*************Trabajar aquí**************


        /*
            -- Código para todos los períodos e indicadores
                select concat_ws(' ', s.firstname, s.lastname), ir.periodo, ir.indicador, ir.resultado, i.registro, concat(s.course,s.section), i.competencia
                from `results` as ir join indicadores as i on ir.indicador = i.indicador
                join students as s on s.firstname = ir.firstname 
                group by ir.firstname, ir.indicador, ir.periodo
                having registro = 1 
                order by s.firstname, competencia_id, periodo,  created_at;

                -- Código para los períodos e indicadores maximo
                select concat_ws(' ', s.firstname, s.lastname), max(ir.periodo), ir.indicador, ir.resultado, i.registro, concat(s.course,s.section), i.competencia
                from `results` as ir join indicadores as i on ir.indicador = i.indicador
                join students as s on s.firstname = ir.firstname 
                group by ir.firstname, ir.indicador
                having registro = 1
                order by ir.firstname, competencia_id, max(ir.periodo), ir.created_at;*/
        ?>
    </div>
</div>