<!--Formulario para agregar indicadores.-->
<div class="container">
    <div class="my-3 text-center">
        <h3>Agregar Indicador</h3>
        <form action="" method="POST" class="text-center my-3">
            <textarea name="indicador" id="indicador" class="field" cols="40" rows="8" required></textarea>
            <div>
                <input type="radio" class="form-check-input" name="competencia" id="CO" value="CO" required>
                <label for="CO" class="form-label mx-2 light-label">CO</label>
                <input type="radio" class="form-check-input" name="competencia" id="PO" value="PO">
                <label for="PO" class="form-label mx-2 light-label">PO</label>
                <input type="radio" class="form-check-input" name="competencia" id="CE" value="CE">
                <label for="CE" class="form-label mx-2 light-label">CE</label>
                <input type="radio" class="form-check-input" name="competencia" id="PE" value="PE">
                <label for="PE" class="form-label mx-2 light-label">PE</label>
                <input type="radio" class="form-check-input" name="competencia" id="IC" value="IC">
                <label for="IC" class="form-label mx-2 light-label">IC</label>
            </div>
            <div>
                <input type="radio" class="form-check-input" name="curso" id="_4" value="4" required>
                <label for="_4" class="form-label mx-2 light-label">4</label>
                <input type="radio" class="form-check-input" name="curso" id="_5" value="5">
                <label for="_5" class="form-label mx-2 light-label">5</label>
                <input type="radio" class="form-check-input" name="curso" id="_6" value="6">
                <label for="_6" class="form-label mx-2 light-label">6</label>
            </div>
            <!--Limpiar datos después de enviarlos en el formulario.-->
            <script src="./js/script.js"></script>
            <input type="submit" value="Guardar" class="btn btn-primary my-2" name="Guardar">
        </form>
    </div>
    <div>
        <?php
        //Incluyo el documento de las clases.
        require_once "modules/classes/classes.php";
        //Verifico si el formulario viene con información
        if (isset($_POST['Guardar'])) {
            //Recibo las variables
            $indicadores = $_POST['indicador'];
            $curso = $_POST['curso'];
            $competencia = $_POST['competencia'];
            //Objetos que llaman a la función para eliminar espacios en blanco, quitar la barra de escape de caracteres, convertir caracteres especiales en entidades HTML. 
            $input = new input_cleaning();
            $indicador = $input->test_input($indicadores);
            //Confirmo que ese indicador no exista en la base de datos.
            $sql = "SELECT * FROM indicadores WHERE indicador = '$indicador' AND course = '$curso';";
            $result = $conn->query($sql);
            //Si no existe entonces lo guardo.
            if (mysqli_num_rows($result) == 0) {
                $sql = "INSERT INTO indicadores (indicador, course, competencia) VALUES ('$indicador','$curso','$competencia');";
                //Si el indicador viene vacío, pido que lo escriban.
                if ($indicador == "") {
                    die("<p class='no'>Escriba el indicador!</p>");
                }
                //Si no viene vacío lo guardo.
                else {
                    if ($conn->query($sql) === TRUE) {
                        echo "<p class='yes'>Indicador agregado correctamente!</p>";
                    }
                    //Si no se pudo guardar me da error.
                    else {
                        echo "<p class='no'>Error al agregar indicador!</p>";
                    }
                }
            }
            //Si existe este indicador entonces dice que ya ha sido agregado.
            else {
                echo "<p class='no'>Este indicador ya ha sido agregado para este curso!</p>";
            }
            //Cierro la conexión.
            $conn->close();
        }
        ?>
    </div>
</div>