<?php
//Conexión a la base de datos.
require_once "connection/connect.php";
//Clase con el método de conteo de los estudiantes
class count
{
    public $curso;
    public $seccion;
    public $sexo;
    //Método de conteo de estudiantes.
    public function conteo($curso, $seccion, $sexo)
    {
        //Conexión a la base de datos.
        include "connection/connect.php";
        //Contar todos los estudiantes
        if ($curso == null && $seccion == null && $sexo == null) {

            $sql = "SELECT count(firstname) FROM students";

            $result = $conn->query($sql);

            if (mysqli_num_rows($result) > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<td>' . $row["count(firstname)"] . '</td></tr>';
                }
            }
        }
        //Contar todos los estudiantes por curso
        elseif ($seccion == null && $sexo == null) {
            $sql = "SELECT count(firstname) FROM students WHERE course = '$curso'";

            $result = $conn->query($sql);

            if (mysqli_num_rows($result) > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<td>' . $row["count(firstname)"] . '</td></tr>';
                }
            }
        }
        //Contar todos los estudiantes por curso y sección
        elseif ($sexo == null) {
            $sql = "SELECT count(firstname) FROM students WHERE course = '$curso' AND section = '$seccion'";

            $result = $conn->query($sql);

            if (mysqli_num_rows($result) > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<td>' . $row["count(firstname)"] . '</td></tr>';
                }
            }
        }
        //Contar todos los estudiantes por curso y sexo
        elseif ($seccion == null) {
            $sql = "SELECT count(firstname) FROM students WHERE course = '$curso' AND sexo='$sexo'";

            $result = $conn->query($sql);

            if (mysqli_num_rows($result) > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<td>' . $row["count(firstname)"] . '</td></tr>';
                }
            }
        }
        //Contar todos los estudiantes por curso, sección y sexo
        else {
            $sql = "SELECT count(firstname) FROM students WHERE course = '$curso' AND section = '$seccion' AND sexo='$sexo'";

            $result = $conn->query($sql);

            if (mysqli_num_rows($result) > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<td>' . $row["count(firstname)"] . '</td></tr>';
                }
            }
        }
    }
}
//Clase para mostrar los indicadores de logro
class evaluacion
{
    public $curso;
    public $section;
    //Método para cambiar el valor de los atributos
    function set_curso($curso, $section)
    {
        $this->curso = $curso;
        $this->section = $section;
    }
    //Método para el selector de indicadores.
    function indicadores_selection()
    {
        //Conexión con la base de datos
        include "connection/connect.php";
        //Consulta que trae los indicadores. 
        $sql = "SELECT * FROM indicadores WHERE course = '{$this->curso}';";
        $result = $conn->query($sql);
        //Si no hay resultados.      
        if (mysqli_num_rows($result) == 0) {
            die("<p class='no'>No hay indicadores agregados!</p>");
        } else {
            //Formulario que muestra los indicadores.
            echo "<form action='index.php?action=indicadores_4A_add' method= 'POST'>";
            echo '<div class="row justify-content-around">';
            echo "<div class='col-md-8 overflow-scroll indicador-overflow'>";
            echo "<div class = 'text-center'>";
            echo "<h4>Indicador</h4>";
            echo "</div>";
            while ($row = $result->fetch_assoc()) {
                //Recibo los indicadores y las competencias.
                $indicador = $row['indicador'];
                $competencia = $row['competencia'];

                //Switch para mostrar las competencias relacionadas con sus siglas.
                echo "<div class='text-center'>";
                echo "<b>" . $competencia . "</b>";
                echo "</div>";
                //Grupo de input radios con los indicadores.
                echo "<div class='form-check'>";
                echo "<input type='checkbox' class='form-check-input' id='$indicador' name='indicadores[]' value='$indicador'>";
                echo '<label class="form-label indicadores-label" for="' . $indicador . '">' . $indicador . '</label>';
                echo "</div>";
            }
            echo "</div>";
            echo "</div>";
            echo "<div class='text-center my-4'>";
            echo "<input class='btn btn-primary' type='submit' name='solicitar' value='Seleccionar'>";
            echo "</div>";
            echo "</form>";
        }
    }
    //Método para el dropdown de selección de los estudiantes.
    function student_selection()
    {
        //Conexión a la base de datos.
        include "connection/connect.php";
        //Consulta que trae de la base de datos los nombres de los estudiantes 
        $sql = "SELECT * FROM students WHERE course = '{$this->curso}' AND section = '{$this->section}' ORDER BY firstname;";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        //Si no hay estudiantes.
        if (mysqli_num_rows($result) == 0) {
            die("<p class='no'>No hay estudiantes!</p>");
        } else {
            //Recibo los indicadores
            $indicadores = $_POST['indicadores'];
            $fila = count($indicadores);

            echo "<div class='container'>";
            echo "<ol class='my-2'>";

            //Muestro los indicadores
            foreach ($indicadores as $i) {


                echo "<li>" . $i . "</li>";


                echo "<div class='form-check my-2'>";
                //Checkbox para seleccionar todos los demás. 
                echo "<input type='checkbox' id='estudiantes' class='form-check-input' onClick='toggle(this)'/>";
                echo "<label for='estudiantes'></label>";
                //Ciclo para mostrar los registros  
                /*while ($row = $result->fetch_assoc()) */
                for ($l = 0; $l <= $fila; $l++) {
                    $name = $row['firstname'];
                    $lastname = $row['lastname'];
                    $resultado = array(' ', 'I', 'P', 'L');
                    echo "<div class='row'>";
                    echo "<div class='col-md-4'>";
                    echo "<input type='checkbox' id='nombre' class='form-check-input' name='estudiantes[]' value=" . $name . ">";
                    echo "<label for='nombre' class='light-label'>" . $name . " " . $lastname . "</label>";
                    echo "</div>";
                    echo "<div class='col-md-1'>";
                    echo "<select class='form-select w-25 h-75'>";
                    foreach ($resultado as $r) {
                        echo "<option value='" . $r . "' name='resultado[]'>" . $r . "</option>";
                    }
                    echo "</select>";
                    echo "</div>";
                    echo "</div>";
                }
                echo "</div>";
            }
        }
        echo "</ol>";
        echo "</div>";
    }
    //Método para el dropdown de la selección del mes.   
    function mes_selection()
    {
        echo '<div>
            <h4 class="mt-3">Mes</h4>
            <select name="mes" class="form-select mt-3" required>  
            <option></option>';
        $meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
        foreach ($meses as $m) {
            echo '<option value="' . $m . '">' . $m . '</option>';
        }
        echo '</select>';
        echo '</div>';
    }
    //Método del input radio para seleccionar el resultado I, L o P.
    function calificacion_select()
    {
        echo '<div class="my-2">';
        echo '<div><b>Resultado</b></div>';
        $resultado = array('I', 'P', 'L');
        foreach ($resultado as $r) {
            echo '<input type="radio" class="form-check-input mx-2" id="' . $r . '" name="resultado" value="' . $r . '" required>
            <label class="form-label" for="' . $r . '">' . $r . '</label>';
        }
        echo '</div>';
    }
    //Método para los botones de editar y guardar. 
    function submit()
    {
        echo "<div class='text-center my-3'>";
        echo "<input type='submit' value='Calificar' class='btn btn-primary mx-5' name='calificar'>";
        echo "</div>";
        echo '</div>';
        echo '</div>';
        echo '</form>';
    }

    //Método para guardar las calificaciones.
    function guardar_calificaciones($indicador, $firstname, $mes, $resultados, $curso, $section, $mes_id)
    {
        include "connection/connect.php";
        //Switch para declarar periodo dependiendo del mes seleccionado.
        switch ($mes) {
            case 'Enero':
                $periodo = 2;
                break;
            case 'Febrero':
                $periodo = 3;
                break;
            case 'Marzo':
                $periodo = 3;
                break;
            case 'Abril':
                $periodo = 4;
                break;
            case 'Mayo':
                $periodo = 4;
                break;
            case 'Junio':
                $periodo = 4;
                break;
            case 'Julio':
                $periodo = 4;
                break;
            case 'Agosto':
                $periodo = 1;
                break;
            case 'Septiembre':
                $periodo = 1;
                break;
            case 'Octubre':
                $periodo = 1;
                break;
            case 'Noviembre':
                $periodo = 2;
                break;
            default:
                $periodo = 2;
        }
        //Verifico si esos datos ya existen en la base de datos.
        $sql = "SELECT * FROM results WHERE firstname = '$firstname' AND mes = '$mes' AND indicador = '$indicador' AND course = '$curso' AND section = '$section';";
        $result = $conn->query($sql);

        if (mysqli_num_rows($result) > 0) {
            echo "<p class='yes'>Esta calificación ya fue agregada!</p>";
        } else {
            $sql = "INSERT INTO results (firstname, mes, resultado, indicador, periodo, course, section, mes_id) VALUES ('$firstname','$mes','$resultados','$indicador','$periodo','$curso','$section','$mes_id');";
            //Verifico que todos los inputs del formulario traigan información.       
            if ($indicador == "" && $firstname == "" && $mes == "" && $resultados == "") {
                echo "<p class='no'>Llene o seleccione los campos correspondientes!</p>";
            }
            //Si traen que se guarden
            else {
                if ($conn->query($sql) === TRUE) {
                    echo "<p class='yes'>Calificación agregada exitosamente!</p>";
                } else {
                    echo "<p class='no'>Error al agregar calificación!</p>";
                }
            }
        }
    }
    //Método para editar las calificaciones.
    function editar_calificaciones($indicador, $firstname, $mes, $resultados, $curso, $section)
    {
        //Conexión con la base de datos.
        include "connection/connect.php";
        //Actualizo el resultado previamente guardado.
        $sql =  "UPDATE results SET indicador = '$indicador', resultado = '$resultados'
        WHERE firstname = '$firstname' AND mes = '$mes' AND course = '$curso' AND section = '$section';";
        //Verifico si todos los campos vienen con información.        
        if ($indicador == "" && $firstname == "" && $mes == "" && $resultados == "") {
            echo "<p class='no'>Llene o seleccione los campos correspondientes!</p>";
        } else {
            if ($conn->query($sql) === TRUE) {
                echo "<p class='yes'>Calificación editada exitosamente!</p>";
            } else {
                echo "<p class='no'>Error al editar calificación!</p>";
            }
        }
    }
}
//Clase para la visualización de archivos y datos en estilo checkbox para elegir y eliminar.
class plantilla_checkbox
{
    public $ruta;
    public $nombre;
    //Asignación de los valores de los atributos de ruta y nombre.
    function set_ruta($ruta)
    {
        $this->ruta = $ruta;
    }
    function set_name($nombre)
    {
        $this->nombre = $nombre;
    }
    //Método para ver los directorios.
    function ver_archivos_directorios($ruta)
    {
        //Me aseguro que los archivos por defecto . y .. no estén incluídos.
        if (count(scandir($ruta)) > 2) {
            echo "<div class='col-md-auto'>";
            echo "<div class='mb-4 text-center'>";
            echo " <h3>Archivos Guardados</h3>";
            echo "</div>";
            //Si la dirección recibida es una ruta abrir la ruta.
            if (is_dir($ruta)) {
                $gestor = opendir($ruta);
                //Formulario para seleccionar el archivo por checkbox.            
                echo "<form action='' method='POST'>";
                echo "<div class='p-4 shadow'>";
                //Checkbox para seleccionar todos los demás. 
                echo "<input type='checkbox' class='form-check-input' onClick='toggle(this)'/>";
                //Ciclo para mostrar los archivos               
                while (($archivo = readdir($gestor)) !== false) {
                    $ruta_completa = $ruta . "/" . $archivo;
                    if ($archivo != "." && $archivo != "..") {
                        echo "<div class='form-check'>";
                        //Codifico el value antes de enviarlo.
                        echo "<input type='checkbox' class='form-check-input' name='archivos[]' value=" . urlencode($archivo) . ">";
                        echo "<a href='" . $ruta_completa . "'>" . $archivo . "</a>";
                        echo "</div>";
                    }
                }
                echo "</div>";
                echo "</div>";
                echo "<div class='row justify-content-center'>";
                //Campo para introducir la contraseña.
                echo "<div class='col-md-3 text-center'>";
                echo "<input type='password' class='form-control mt-5' name='adminpass' placeholder='Contraseña Administrador' required>";
                echo "<input type='submit' value='Eliminar' class='btn btn-outline-warning my-3' name='Eliminar'>";
                echo "</div>";
                echo "</div>";
                //Muevo el flujo de directorio al inicio.
                rewinddir($gestor);
                //Cierro la ruta.
                closedir($gestor);
                echo "</form>";
            } else {
                echo "<p class='no'>Este no es un directorio válido!</p>";
            }
        } else {
            echo "<p class='no'>No hay archivos guardados!</p>";
        }
    }
    //Método para ver los registros anecdóticos almacenados.
    function ver_registros($nombre)
    {
        //Conexión a la base de datos.
        include "connection/connect.php";
        //Si no se introduce nombre me devuelve todos los registros.
        if ($nombre == '') {
            $sql = "SELECT * FROM registros;";
        }
        //Si escribo las iniciales o nombres de los estudiantes recibo la información. 
        else {
            $sql = "SELECT * FROM registros WHERE firstname LIKE '$nombre%';";
        }
        $result = $conn->query($sql);
        //Si hay resultado lo recibo en una tabla
        if (mysqli_num_rows($result) > 0) {
            echo "<div class='d-flex flex-column'>";
            echo "<form action='' method='POST'>";
            echo "<div class='form-check'>";
            //Checkbox para seleccionar todos los demás.   
            echo "<input type='checkbox'  class='form-check-input' onClick='toggle(this)'/>";
            //Ciclo para mostrar los registros  
            while ($row = $result->fetch_assoc()) {
                echo "<div>";
                echo "<input type='checkbox' class='form-check-input' name='registro[]' value=" . $row['register_id'] . ">";
                echo "<div>";
                //Presento los registros normales, nombre del estudiante y curso en negrita, y la fecha.
                echo "<div>" . $row['register'] . "</div><b>(" . $row['firstname'] . " " . $row['lastname'] . " de " . $row['concat(s.course,s.section)'] . ")</b><p>(" . $row['fecha'] . ")</p>";
                echo "</div>";
                echo "</div>";
            }
            //Botón para eliminar registro.
            echo "</div>";
            echo "<div class='text-center my-3'>";
            echo "<input type='submit' value='Eliminar' class='btn btn-secondary mx-5' name='Eliminar'>";
            echo "</div>";
            echo "</form>";
            echo "</div>";
        }
        //Si no hay resultado, aparece este mensaje
        else {
            echo "<p class='no'>No hay resultados para esta consulta!</p>";
        }
    }
    //Método para ver las anotaciones.
    function ver_anotaciones()
    {
        //Conexión a la base de datos
        include "connection/connect.php";
        //Selecciona las anotaciones de la base de datos
        $sql = "SELECT * FROM notes;";

        $result = $conn->query($sql);
        //Si hay resultado lo recibo en una tabla
        if (mysqli_num_rows($result) > 0) {
            echo "<div class='col-md-auto'>";
            echo "<div class='text-center'>";
            echo "<h3>Anotaciones</h3>";
            echo "</div>";
            echo "<form action='' method='POST'>";
            echo "<div class='form-check my-4'>";
            //Checkbox para seleccionar todos los demás.   
            echo "<input type='checkbox'  class='form-check-input' onClick='toggle(this)'/>";
            //Ciclo para ver la anotación y la fecha en la que fue creada.
            while ($row = $result->fetch_assoc()) {
                $date = $row['created_at'];
                $hora = new DateTime($date, new DateTimeZone('America/Santo_Domingo'));
                echo "<div>";
                echo "<input type='checkbox' class='form-check-input' name='registro[]' value=" . $row['id_nota'] . ">";
                echo "<div>";
                echo "<div>" . $row['anotacion'] . "</div><em>(" . $hora->format('j/m/Y') . ")</em>";
                echo "</div>";
                echo "</div>";
            }
            echo "</div>";
            //Botón de eliminar.
            echo "<div class='text-center'>";
            echo "<input type='submit' value='Eliminar' class='btn btn-secondary' name='Eliminar'>";
            echo "</div>";
            echo "</form>";
            echo "</div>";
        } else {
            echo "<p class='no'>No hay anotaciones!</p>";
        }
    }
    //Método para eliminar los archivos seleccionados por el checkbox 
    function eliminar_checkbox($registros, $tabla, $id)
    {
        //Conexión a la base de datos.
        include "connection/connect.php";
        //Cada elemento del arreglo recibido se verifica si existe.        
        foreach ($registros as $registro) {

            $sql = "SELECT * FROM $tabla WHERE $id = $registro;";

            $result = $conn->query($sql);
            //Si existe se elimina.
            if (mysqli_num_rows($result) > 0) {
                $sql = "DELETE FROM $tabla WHERE $id ='$registro';";

                if ($conn->query($sql) === TRUE) {
                    echo "<p class='yes'>Registro eliminado correctamente!</p>";
                } else {
                    echo "<p class='no'>Error al eliminar registro!</p>";
                }
            } else {
                echo "<p class='no'>Este registro ya fue eliminado!</p>";
            }
        }
    }
    //Método para eliminar archivos
    function eliminar_files($adminpass)
    {
        //Conexión a la base de datos.
        include "connection/connect.php";
        //Compruebo la contraseña de administrador introducida con la almacenada en la bd.
        $sql = "SELECT * FROM users WHERE username = 'Admin';";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        if (password_verify($adminpass, $row['password'])) {
            //Si la contraseña es correcta recibo el valor del archivo a eliminar por el checkbox.
            foreach ($_POST['archivos'] as $archivo) {
                //Ubico la ruta del archivo a eliminar.
                $ruta_archivo = './uploads/' . urldecode($archivo);
                //Si ese archivo existe lo elimino.
                if (file_exists($ruta_archivo)) {
                    unlink($ruta_archivo);
                    echo "<p class='yes'>Archivo " . $archivo . " eliminado!</p>";
                }
            }
        }
        //Si la contraseña de administrador no coincide con la de la base de datos, dice que es incorrecta.
        else {
            echo "<p class='no'>Contraseña de administrador incorrecta!</p>";
        }
    }
}

//Clase para limpiar las entradas de caracteres y espacios en blancos.
class input_cleaning
{
    public $entrada;
    //Método para eliminar espacios en blanco, quitar la barra de escape de caracteres, convertir caracteres especiales en entidades HTML. 
    function test_input($entrada)
    {
        $entrada = trim($entrada);
        $entrada = stripslashes($entrada);
        $entrada = htmlspecialchars($entrada);
        $entrada = filter_var($entrada, FILTER_SANITIZE_STRING);
        return $entrada;
    }
}
//Clase para cambiar el mes por valores númericos para poder ordernar por mes en mysql
class month_switch
{
    public $mes;
    //Función que retorna los valores numéricos asignados a los meses.   
    function month_switch($mes)
    {
        switch ($mes) {
            case 'Enero':
                return 1;
                break;
            case 'Febrero':
                return 2;
                break;
            case 'Marzo':
                return 3;
                break;
            case 'Abril':
                return 4;
                break;
            case 'Mayo':
                return 5;
                break;
            case 'Junio':
                return 6;
                break;
            case 'Julio':
                return 7;
                break;
            case 'Agosto':
                return 8;
                break;
            case 'Septiembre':
                return 9;
                break;
            case 'Octubre':
                return 10;
                break;
            case 'Noviembre':
                return 11;
                break;
            default:
                return 12;
        }
    }
}