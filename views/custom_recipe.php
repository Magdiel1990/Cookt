<?php
//Head of the page.
require_once ("../modules/head.php");

//Including the database connection.
require_once ("../config/db_Connection.php");

//Models.
require_once ("../models/models.php");

//Navigation panel of the page
require_once ("../modules/nav.php");
?>
<link rel="stylesheet" href="../styles/styles.css">

<main class="container p-4">
    <div  class="row mt-2 text-center justify-content-center">
        <h3>ELEGIR POR INGREDIENTE</h3>
<!--Form for filtering the database info-->
        <form class="mt-3 col-auto" id="upperinput" method="POST" action="../actions/create.php">
            <div class="input-group mb-3">
                <label class="input-group-text" for="customingredient">Unidad: </label>                
                <select class="form-select" name="customingredient" id="customingredient">
                    <?php
                    $sql = "SELECT ingredient FROM ingredients";

                    $result = $conn -> query($sql);

                    while($row = $result -> fetch_assoc()) {
                        echo '<option value="' . $row["ingredient"] . '">' . $row["ingredient"] . '</option>';
                    }
                    ?>
                </select>
                <input class="btn btn-primary" type="submit" value="Agregar">
            </div>
        </form>
        <div>


        
        <!-- Lista de ingredientes  -->



        </div>




        <div>




        <!-- Botón para el pedido  -->





        </div>        
    </div>
</main>
<?php
//Footer of the page.
require_once ("../modules/footer.php");
?>