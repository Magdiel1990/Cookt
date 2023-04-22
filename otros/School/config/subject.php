<div>
    <!--Formulario para elegir la asignatura.-->
    <form action="" method="POST" class="select-form">
        <h1>Asignatura</h1>
        <div>
            <input type="radio" id="Inglés" name="asignatura" value="Inglés">
            <label for="Inglés">Inglés</label>
            <!-- <input type="radio" id="Lengua_Española" name="asignatura" value="Lengua Española" required="">
            <label for="Lengua_Española">Lengua Española</label>        
            <input type="radio" id="Ciencias_Naturales" name="asignatura" value="Ciencias Naturales">
            <label for="Ciencias_Naturales">Ciencias Naturales</label>
            <input type="radio" id="Matemáticas" name="asignatura" value="Matemáticas">
            <label for="Matemáticas">Matemáticas</label> -->
        </div>
        <!-- <div>    
            <input type="radio" id="Ciencias_Sociales" name="asignatura" value="Ciencias Sociales">
            <label for="Ciencias_Sociales">Ciencias Sociales</label>   
            <input type="radio" id="Educación_Artística" name="asignatura" value="Educación Artística">
            <label for="Educación_Artística">Educación Artística</label>
            <input type="radio" id="Educación_Física" name="asignatura" value="Educación Física">
            <label for="Educación_Física">Educación Física</label>   
            <input type="radio" id="Formación_Integral_Humana_y_Religiosa" name="asignatura" value="Formación Humana">
            <label for="Formación_Integral_Humana_y_Religiosa">Formación Humana</label>
        </div> -->
        <!--Limpiar datos después de enviarlos en el formulario.-->
        <script src="./js/script.js"></script>
        <input type='submit' class='btn_form' value='Elegir' name='select'>
    </form>
    <div>
        <?php
        //Incluir la librería PHPspreadsheet para crear los archivos de Excel.
        require_once "libraries/vendor/autoload.php";
        //Incluyo el documento de las clases.
        require_once "modules/classes/classes.php";
        //Si no existe se crea la carpeta.
        $dir = "./subjects";
        if (!file_exists($dir)) {
            mkdir($dir, 0777);
        }
        //Selecciono los indicadores.
        $sql = "SELECT * FROM indicadores;";
        $result = $conn->query($sql);
        //Si no hay indicadores agregados los agrego.
        if (mysqli_num_rows($result) == 0) {
            //Verifico el formulario trae información.
            if (isset($_POST['select'])) {
                //Recibo estas variable.
                $subject = $_POST['asignatura'];
                //Establezco la ruta del archivo elegido.  
                $ruta = $dir . "/" . $subject . ".xlsx";
                //Si el archivo está en la ruta.  
                if (file_exists($ruta)) {
                    //Cargo el archivo para leerlo.
                    $document = \PhpOffice\PhpSpreadsheet\IOFactory::load($ruta);
                    $document->setActiveSheetIndex(0);
                    //Consigo la fila más alta.                
                    $numRows = $document->setActiveSheetIndex(0)->getHighestRow();
                    //Agrego los valores de los archivos de Excel a la base de datos.           
                    for ($i = 2; $i <= $numRows; $i++) {
                        $ind_id = $document->getActiveSheet()->getCell('A' . $i)->getCalculatedValue();
                        $indicador = $document->getActiveSheet()->getCell('B' . $i)->getCalculatedValue();
                        $competencia_id = $document->getActiveSheet()->getCell('C' . $i)->getCalculatedValue();
                        $course = $document->getActiveSheet()->getCell('D' . $i)->getCalculatedValue();
                        $competencia = $document->getActiveSheet()->getCell('E' . $i)->getCalculatedValue();
                        $registro = $document->getActiveSheet()->getCell('F' . $i)->getCalculatedValue();

                        $sql = "INSERT INTO indicadores (ind_id, indicador, course, competencia, registro, competencia_id)
                    VALUES ('$ind_id','$indicador','$course','$competencia','$registro','$competencia_id');";

                        $conn->query($sql);
                    }
                    if ($conn->query($sql) === TRUE) {
                        echo "<p class = 'yes'>Asignatura agregada correctamente!</p>";
                    }
                } else {
                    echo "<p class = 'no'>Esta asignatura no está disponible!</p>";
                }
            }
        } else {
            echo "<p class = 'yes'>Ya la asignatura ha sido agregada!</p>";
        }
        $conn->close();
        ?>
    </div>
    <!--Link para volver a la página anterior.-->
    <div>
        <a class='link' href="index.php?action=reset">Atrás</a>
    </div>
</div>