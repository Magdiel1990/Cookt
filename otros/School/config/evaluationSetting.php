<div class="d-flex flex-column w-25 justify-content-center text-center my-4 m-auto">    
    <div class="container shadow my-4">
    <?php 
        //Incluyo el documento de las clases.
        require_once "modules/classes/classes.php";

        $username = $_SESSION['username'];

        $sql = "SELECT * FROM criterioevaluacion WHERE username = '$username' GROUP BY criterioNombre;";
        $result = $conn -> query($sql);
        echo '<h3>Eliminar Criterios</h3>';
        echo '<form action="" method="POST">';
        echo '<select class="form-select form-select-sm mt-3" name="criterio">';
        echo '<option></option>';

        while($row = $result -> fetch_assoc()){
            echo '<option value="' . $row['criterioNombre'] . '">'. $row['criterioNombre'] .'</option>';
        }            

        echo "</select>";
    ?>
    <!--Limpiar datos después de enviarlos en el formulario.-->
    <script src="./js/script.js"></script>
    <?php
        echo "<input type='submit' class='btn btn-danger my-4' name='eliminar' value='Eliminar'>";
        echo '</form>';
    ?>
    </div>
    <div class="container">
    <?php
    if(isset($_POST['eliminar'])){
        $criterioNombre = $_POST['criterio'];

        $sql = "SELECT * FROM criterioevaluacion WHERE criterioNombre = '$criterioNombre'";
        $result = $conn -> query($sql);

        if(mysqli_num_rows($result)>0){
            $sql = "DELETE FROM criterioevaluacion WHERE criterioNombre = '$criterioNombre';";
            if($conn -> query($sql) === TRUE){
                echo "<p class='yes'>Criterio eliminado correctamente!</p>";
            }
        }       
        else {
            echo "<p class='no'>Este criterio no existe!</p>";
        }
    }
    ?>  
    </div>
    <div class="container shadow my-3">
    <?php    
        $descriptors = range(1,8);        
        echo '<form action="" method="POST" class="my-4">';
        echo '<h3>Cantidad de Criterios</h3>';
        echo '<select class="form-select form-select-sm" name="descriptor" id="descriptor">';
        foreach($descriptors as $d){
            echo '<option value="' . $d . '">'. $d .'</option>';
        }
        echo "</select>";
        echo "<input type='submit' class='btn btn-primary my-3' name='elegir' value='Utilizar'>";
        echo "</form>";
    ?>
    </div>
    <div class="container shadow my-3">
    <?php    
        if(isset($_POST['elegir'])){
            $quantityDescriptors = $_POST['descriptor'];
            echo '<form action="" method="POST" class="my-4">';
            echo '<h3>Agregar Criterios</h3>';
            echo '<h4 class="mark mb-4"><label for="nombre">Nombre</label></h4>';
            echo '<input type="text" maxlength="15" minlength="2" id="nombre" class="form-control my-2" name="nombre" required>';
            echo '<h4 class="small my-4">Criterios</h4>';
            echo '<ol>';
            for($i=0;$i<$quantityDescriptors;$i++){
                echo '<li><input type="text" class="form-control my-2" name="descriptors[]"></li>';
            }
            echo '</ol>';
            echo '<input type="submit" class="btn btn-secondary" value="Crear" name="agregar">';
            echo '</form>';
        }
    ?>
    </div>
    <div class="container">
    <?php    
        if(isset($_POST['agregar'])){
            $descriptors = $_POST['descriptors'];
            $name = $_POST['nombre'];
            $username = $_SESSION['username'];

            foreach($descriptors as $d){
                $sql = "SELECT * FROM criterioevaluacion WHERE instrumentoNombre = '$d' AND criterioNombre = '$name' AND username = '$username';";
                $result = $conn -> query($sql);
            }
            
            if(mysqli_num_rows($result) == 0){
                foreach($descriptors as $d){
                    $sql = "INSERT INTO criterioevaluacion (instrumentoNombre, criterioNombre, username) VALUES ('$d', '$name', '$username');";
                    $result = $conn -> query($sql);
                }
                if($result === TRUE){
                    echo "<p class='yes'>Los criterios han sido agregados!</p>";
                }
                else {
                    echo "<p class='no'>Error al agregar criterios!</p>";
                }
            }
            else {
                echo "<p class='no'>Este criterio ya existe!</p>";
            }
        }
    ?>
    </div>
</div>
<!--Limpiar datos después de enviarlos en el formulario.-->
<script src="./js/script.js"></script>