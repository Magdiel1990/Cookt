<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-3 text-center my-3">
            <!--Formulario para resetear la base de datos.-->
            <div>
                <h3>Reiniciar</h3>
                <form action="" method="POST" class="my-3">
                    <input type="password" class="form-control my-1" id="adminpass"
                        placeholder="Contraseña Administrador" name="adminpass" required>
                    <!--Limpiar datos después de enviarlos en el formulario.-->
                    <script src="./js/script.js"></script>
                    <input type='submit' class='btn btn-danger my-3' value='Reiniciar' name='Habilitar'>
                </form>
            </div>
            <div>
                <?php
                //Incluir la librería PHPspreadsheet para crear los archivos de Excel.
                require_once "libraries/vendor/autoload.php";
                //Incluyo el documento de las clases.
                require_once "modules/classes/classes.php";
                //Incluir la librería PHPspreadsheet para crear los archivos de Excel.
                use PhpOffice\PhpSpreadsheet\Spreadsheet;
                use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
                //Verificar la contraseña de administrador.
                $sql = "SELECT * FROM users WHERE username = 'Admin';";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                //Verifico el formulario trae información.
                if (isset($_POST['Habilitar'])) {
                    //Recibo estas variable.
                    $adminpass = $_POST['adminpass'];
                    //Si las contraseñas coinciden.
                    if (password_verify($adminpass, $row['password'])) {
                        $dir = './backup';
                        //Si no existe se crea la carpeta.
                        if (!file_exists($dir)) {
                            mkdir($dir, 0777);
                        }
                        //Creo el objeto para usar librería de la creación de los archivos de Excel
                        $spreadsheet = new Spreadsheet();
                        //Vamos a establecer las propiedades del documento de excel.
                        $spreadsheet->getProperties()
                            //Establezco el autor del libro
                            ->setCreator($_SESSION['name'])
                            //Establezco el la última persona que lo modifique
                            ->setLastModifiedBy($_SESSION['name'])
                            ->setDescription('Respaldo de trabajo durante el año escolar.')
                            //Establezco el título del libro               
                            ->setTitle("Respaldo(" . date('Y-m-d') . ")");
                        //Creo la página "Estudiantes"
                        $hojaDeEstudiantes = $spreadsheet->getActiveSheet();
                        $hojaDeEstudiantes->setTitle("Estudiantes");
                        //Escribo los headers de los campos.
                        $headers = ["Nombre", "Apellido", "Curso", "Sección", "Sexo"];
                        $hojaDeEstudiantes->fromArray($headers, null, 'A1');
                        //Selecciono los datos de la tabla
                        $sql = "SELECT * FROM students;";
                        $result = $conn->query($sql);
                        //Verifico que si ya está reiniciado todo.
                        if (mysqli_num_rows($result) == 0) {
                            echo "<p class='yes'>Ya está habilitada!</p>";
                            echo "<a class= 'link' href='index.php?action=subject'>Seleccionar Asignatura</a>";
                        } else {
                            //Escribo los datos de esta tabla en el archivo de Excel a partir de la fila 2.
                            $rowNumber = 2;
                            while ($row = $result->fetch_object()) {
                                $hojaDeEstudiantes->setCellValueByColumnAndRow(1, $rowNumber, $row->firstname);
                                $hojaDeEstudiantes->setCellValueByColumnAndRow(2, $rowNumber, $row->lastname);
                                $hojaDeEstudiantes->setCellValueByColumnAndRow(3, $rowNumber, $row->course);
                                $hojaDeEstudiantes->setCellValueByColumnAndRow(4, $rowNumber, $row->section);
                                $hojaDeEstudiantes->setCellValueByColumnAndRow(5, $rowNumber, $row->sexo);
                                $rowNumber++;
                            }
                            //Creo la página "Examenes"
                            $hojaDeExamenes = $spreadsheet->createSheet();
                            $hojaDeExamenes->setTitle("Examenes");
                            //Escribo los headers de los campos.
                            $headers = ["Nombre", "Apellido", "Curso", "Sección", "Calificación", "Fecha", "Descripción"];
                            $hojaDeExamenes->fromArray($headers, null, 'A1');
                            //Selecciono los datos de la tabla
                            $sql = "SELECT * FROM `exams_details`;";
                            $result = $conn->query($sql);
                            //Escribo los datos de esta tabla en el archivo de Excel a partir de la fila 2.
                            $rowNumber = 2;
                            while ($row = $result->fetch_object()) {
                                $hojaDeExamenes->setCellValueByColumnAndRow(1, $rowNumber, $row->firstname);
                                $hojaDeExamenes->setCellValueByColumnAndRow(2, $rowNumber, $row->lastname);
                                $hojaDeExamenes->setCellValueByColumnAndRow(3, $rowNumber, $row->course);
                                $hojaDeExamenes->setCellValueByColumnAndRow(4, $rowNumber, $row->section);
                                $hojaDeExamenes->setCellValueByColumnAndRow(5, $rowNumber, $row->resultado);
                                $hojaDeExamenes->setCellValueByColumnAndRow(6, $rowNumber, $row->fecha);
                                $hojaDeExamenes->setCellValueByColumnAndRow(7, $rowNumber, $row->description);
                                $rowNumber++;
                            }
                            //Creo la página "Indicadores"
                            $hojaDeIndicadores = $spreadsheet->createSheet();
                            $hojaDeIndicadores->setTitle("Indicadores");
                            //Escribo los headers de los campos.
                            $headers = ["Indicador", "course", "competencia", "registro", "competencia_id", "ind_id"];
                            $hojaDeIndicadores->fromArray($headers, null, 'A1');
                            //Selecciono los datos de la tabla
                            $sql = "SELECT * FROM indicadores ORDER BY course, competencia_id;";
                            $result = $conn->query($sql);
                            //Escribo los datos de esta tabla en el archivo de Excel a partir de la fila 2.
                            $rowNumber = 2;
                            while ($row = $result->fetch_object()) {
                                $hojaDeIndicadores->setCellValueByColumnAndRow(1, $rowNumber, $row->indicador);
                                $hojaDeIndicadores->setCellValueByColumnAndRow(2, $rowNumber, $row->course);
                                $hojaDeIndicadores->setCellValueByColumnAndRow(3, $rowNumber, $row->competencia);
                                $hojaDeIndicadores->setCellValueByColumnAndRow(4, $rowNumber, $row->registro);
                                $hojaDeIndicadores->setCellValueByColumnAndRow(5, $rowNumber, $row->competencia_id);
                                $hojaDeIndicadores->setCellValueByColumnAndRow(6, $rowNumber, $row->ind_id);
                                $rowNumber++;
                            }
                            //Creo la página "Resultados de indicadores"
                            $hojaDeIndicadoresResultados = $spreadsheet->createSheet();
                            $hojaDeIndicadoresResultados->setTitle("Resultados por indicadores");
                            //Escribo los headers de los campos.
                            $headers = ["Nombre", "Apellido", "Curso", "Sección", "Periodo", "Indicador", "Competencia", "Resultado"];
                            $hojaDeIndicadoresResultados->fromArray($headers, null, 'A1');
                            //Selecciono los datos de la tabla
                            $sql = "SELECT * FROM indicadores_results_summary;";

                            $result = $conn->query($sql);
                            //Escribo los datos de esta tabla en el archivo de Excel a partir de la fila 2.
                            $rowNumber = 2;
                            while ($row = $result->fetch_object()) {
                                $hojaDeIndicadoresResultados->setCellValueByColumnAndRow(1, $rowNumber, $row->firstname);
                                $hojaDeIndicadoresResultados->setCellValueByColumnAndRow(2, $rowNumber, $row->lastname);
                                $hojaDeIndicadoresResultados->setCellValueByColumnAndRow(3, $rowNumber, $row->course);
                                $hojaDeIndicadoresResultados->setCellValueByColumnAndRow(4, $rowNumber, $row->section);
                                $hojaDeIndicadoresResultados->setCellValueByColumnAndRow(5, $rowNumber, $row->periodo);
                                $hojaDeIndicadoresResultados->setCellValueByColumnAndRow(6, $rowNumber, $row->indicador);
                                $hojaDeIndicadoresResultados->setCellValueByColumnAndRow(7, $rowNumber, $row->competencia);
                                $hojaDeIndicadoresResultados->setCellValueByColumnAndRow(8, $rowNumber, $row->resultado);
                                $rowNumber++;
                            }
                            //Creo la página "Anotaciones"
                            $hojaDeAnotaciones = $spreadsheet->createSheet();
                            $hojaDeAnotaciones->setTitle("Anotaciones");
                            //Escribo los headers de los campos.
                            $headers = ["Anotaciones", "Creación"];
                            $hojaDeAnotaciones->fromArray($headers, null, 'A1');
                            //Selecciono los datos de la tabla
                            $sql = "SELECT * FROM notes;";
                            $result = $conn->query($sql);
                            //Escribo los datos de esta tabla en el archivo de Excel a partir de la fila 2.
                            $rowNumber = 2;
                            while ($row = $result->fetch_object()) {
                                $hojaDeAnotaciones->setCellValueByColumnAndRow(1, $rowNumber, $row->anotacion);
                                $hojaDeAnotaciones->setCellValueByColumnAndRow(2, $rowNumber, $row->created_at);
                                $rowNumber++;
                            }
                            //Creo la página "Participación"
                            $hojaDeParticipacion = $spreadsheet->createSheet();
                            $hojaDeParticipacion->setTitle("Participación");
                            //Escribo los headers de los campos.
                            $headers = ["Nombre", "Apellido", "Curso", "Sección", "Calificación", "Mes"];
                            $hojaDeParticipacion->fromArray($headers, null, 'A1');
                            //Selecciono los datos de la tabla
                            $sql = "SELECT * FROM participation_summary;";
                            $result = $conn->query($sql);
                            //Escribo los datos de esta tabla en el archivo de Excel a partir de la fila 2.
                            $rowNumber = 2;
                            while ($row = $result->fetch_object()) {
                                $hojaDeParticipacion->setCellValueByColumnAndRow(1, $rowNumber, $row->firstname);
                                $hojaDeParticipacion->setCellValueByColumnAndRow(2, $rowNumber, $row->lastname);
                                $hojaDeParticipacion->setCellValueByColumnAndRow(3, $rowNumber, $row->course);
                                $hojaDeParticipacion->setCellValueByColumnAndRow(4, $rowNumber, $row->section);
                                $hojaDeParticipacion->setCellValueByColumnAndRow(5, $rowNumber, $row->calificacion);
                                $hojaDeParticipacion->setCellValueByColumnAndRow(6, $rowNumber, $row->mes);
                                $rowNumber++;
                            }
                            //Creo la página "Registro Anecdótico"                
                            $hojaDeParticipacion = $spreadsheet->createSheet();
                            $hojaDeParticipacion->setTitle("Registro Anecdótico");
                            //Escribo los headers de los campos.
                            $headers = ["Nombre", "Apellido", "Curso", "Sección", "Registro", "Fecha"];
                            $hojaDeParticipacion->fromArray($headers, null, 'A1');
                            //Selecciono los datos de la tabla
                            $sql = "SELECT * FROM register_summary;";
                            $result = $conn->query($sql);
                            //Escribo los datos de esta tabla en el archivo de Excel a partir de la fila 2.
                            $rowNumber = 2;
                            while ($row = $result->fetch_object()) {
                                $hojaDeParticipacion->setCellValueByColumnAndRow(1, $rowNumber, $row->firstname);
                                $hojaDeParticipacion->setCellValueByColumnAndRow(2, $rowNumber, $row->lastname);
                                $hojaDeParticipacion->setCellValueByColumnAndRow(3, $rowNumber, $row->course);
                                $hojaDeParticipacion->setCellValueByColumnAndRow(4, $rowNumber, $row->section);
                                $hojaDeParticipacion->setCellValueByColumnAndRow(5, $rowNumber, $row->register);
                                $hojaDeParticipacion->setCellValueByColumnAndRow(6, $rowNumber, $row->fecha);
                                $rowNumber++;
                            }
                            //Llamo el método para escribir todos los datos.
                            $writer = new Xlsx($spreadsheet);
                            //Guardo el archivo en el directorio establecido con la fecha.
                            $writer->save($dir . "/Respaldo(" . date('Y-m-d') . ").xlsx");
                            //Después de respaldadas, se eliminan todas las tablas, excepto la tabla de usuarios.
                            $sql = "DROP TABLES exams, notes, participation, register, students, results, indicadores;";
                            //Se crean las tablas nuevamente (vacías).
                            $sql .= "CREATE TABLE students (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            firstname VARCHAR(30) NOT NULL UNIQUE,
            lastname VARCHAR(30) NOT NULL,
            sexo CHAR(1) NOT NULL,
            course INT NOT NULL,
            section VARCHAR(1),
            updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
            );";
                            $sql .= "CREATE TABLE exams (
            exam_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            exams INT NOT NULL,
            firstname VARCHAR(30) NOT NULL,
            fecha DATE NOT NULL,
            description VARCHAR(30),
            updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (exam_id),
            CONSTRAINT fk_students_exams FOREIGN KEY (firstname) REFERENCES students(firstname)
            );";
                            $sql .= "CREATE TABLE participation (
            participation_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            calificacion INT NOT NULL,
            firstname VARCHAR(30) NOT NULL,
            mes VARCHAR(11) NOT NULL,
            mes_id INT NOT NULL,
            updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (participation_id),
            CONSTRAINT fk_students_participation FOREIGN KEY (firstname) REFERENCES students(firstname)
            );";
                            $sql .= "CREATE TABLE register (
            register_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            register TEXT,
            firstname VARCHAR(30) NOT NULL,
            fecha DATE NOT NULL,
            updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (register_id),
            CONSTRAINT fk_students_register FOREIGN KEY (firstname) REFERENCES students(firstname)
            );";
                            $sql .= "CREATE TABLE notes (
            id_nota INT UNSIGNED NOT NULL AUTO_INCREMENT,
            anotacion TEXT NOT NULL,                
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id_nota)
            );";
                            $sql .= "CREATE TABLE indicadores (
            ind_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            indicador VARCHAR(500) NOT NULL,
            course INT NOT NULL,
            competencia_id INT NOT NULL,
            competencia VARCHAR(50) NOT NULL,
            registro BOOLEAN NOT NULL,
            PRIMARY KEY (ind_id)
            );";
                            $sql .= "CREATE TABLE results(
            result_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            firstname VARCHAR(30) NOT NULL,
            course INT NOT NULL,
            section VARCHAR(1) NOT NULL;
            mes VARCHAR(11) NOT NULL,
            mes_id INT NOT NULL,
            periodo INT UNSIGNED NOT Null,
            resultado CHAR(1) NOT NULL,
            indicador VARCHAR (500) NOT NULL,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY(result_id)
            );";
                            if ($conn->multi_query($sql) === TRUE) {
                                echo "<p class='yes'>Habilitada exitosamente!</p>";
                                echo "<a class= 'link' href='index.php?action=subject'>Seleccionar Asignatura</a>";
                            } else {
                                echo "<p class='no'>Error!</p>";
                            }
                        }
                    } else {
                        echo "<p class='no'>Contraseña de administrador incorrecta!</p>";
                    }
                }
                //Cierro la conexión
                $conn->close();
                ?>
            </div>
        </div>
    </div>
</div>