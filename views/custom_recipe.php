<?php
//Head of the page.
require_once ("../modules/head.php");

//Including the database connection.
require_once ("../config/db_Connection.php");

//Models.
require_once ("../models/models.php");
?>
<main>
    <div>
        <h3>Elegir por ingredientes</h3>
<!--Form for filtering the database info-->
        <form method="POST" action="../actions/create.php">
            <div>
                <label for="customingredient">Unidad: </label>                
                <select name="customingredient" id="customingredient">
                    <?php
                    $sql = "SELECT ingredient FROM ingredients";

                    $result = $conn -> query($sql);

                    while($row = $result -> fetch_assoc()) {
                        echo '<option value="' . $row["ingredient"] . '">' . $row["ingredient"] . '</option>';
                    }
                    ?>
                </select>
                <input type="submit" value="Agregar">
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