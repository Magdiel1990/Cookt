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
    <?php
//Messages that are shown in the add_units page
        if(isset($_SESSION['message'])){
        buttonMessage($_SESSION['message'], $_SESSION['message_alert']);        

//Unsetting the messages variables so the message fades after refreshing the page.
        unset($_SESSION['message_alert'], $_SESSION['message']);
        }
    ?>
        <h3>Agregar Receta</h3>
<!--Form for filtering the database info-->
        <form method="POST" action="../actions/create.php">
            <div>
                <label for="quantity">Cantidad: </label>                    
                <input type="number" name="quantity" id="quantity">

                <label for="unit">Unidad: </label>                
                <select name="unit" id="unit">
                    <?php
                    $sql = "SELECT unit FROM units";

                    $result = $conn -> query($sql);

                    while($row = $result -> fetch_assoc()) {
                        echo '<option value="' . $row["unit"] . '">' . $row["unit"] . '</option>';
                    }
                    ?>
                </select>

                <label for="ingredient">Ingrediente: </label>                
                <select name="ingredient" id="ingredient">
                    <?php
                    $sql = "SELECT ingredient FROM ingredients";

                    $result = $conn -> query($sql);

                    while($row = $result -> fetch_assoc()) {
                        echo '<option value="' . $row["ingredient"] . '">' . $row["ingredient"] . '</option>';
                    }
                    ?>
                </select>
                <input type="submit" value="Agregar ingrediente">
            </div>
        </form>
        <!-- Table with ingredients that will conform the recipe-->
        <div>
            <table class="table">
            <thead>
                <tr>
                    <th>Ingredients</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $sql = "SELECT re_id, ingredient, quantity, unit, concat_ws(' ', quantity, unit, 'de' ,ingredient) as fullingredient FROM reholder";

                $result = $conn -> query($sql);

                $row = $result -> fetch_assoc();

                $num_rows = $result -> num_rows;

                if ($num_rows == 0) {
                    $html = "";
                    $html .= "<tr>";
                    $html .= "<td>Agrega los ingredientes...</td>";
                    $html .= "</tr>";
                    echo $html;
                }
                else {

                    while($row = $result -> fetch_assoc()){
                        echo "<tr>";
                        echo "<td><a href='../actions/edit.php?id=" . $row["re_id"] . "'>" . $row["fullingredient"] . "</a></td>";
                        echo "</tr>";
                    }
                    
                }
            ?>
            </tbody>
            </table>
        </div>
        <form method="POST" action="">
            <div>
                <label for="recipename">Nombre: </label>
                <input type="text" id="recipename" name="recipename">             
            </div>
            <div>
                <p>Preparación: </p>
                <textarea name="preparation" id="preparation" cols="30" rows="10"></textarea>
            </div>
            <div>
                <p>Observaciones: </p>
                <textarea name="observation" id="observation" cols="30" rows="10"></textarea>
            </div>
            <div>
                <input type="submit" value="Agregar receta">
            </div>
        </form>
    </div>
</main>
<?php
//Footer of the page.
require_once ("../modules/footer.php");
?>