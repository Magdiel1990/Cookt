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

    <?php
    //Messages that are shown in the add_units page
        if(isset($_SESSION['message'])){
        buttonMessage($_SESSION['message'], $_SESSION['message_alert']);        

    //Unsetting the messages variables so the message fades after refreshing the page.
        unset($_SESSION['message_alert'], $_SESSION['message']);
        }
    ?>

    <div  class="row mt-2 text-center justify-content-center">
        <h3>ELEGIR POR INGREDIENTE</h3>
<!--Form for filtering the database info-->
        <form class="m-3 col-auto" id="upperinput" method="POST" action="../actions/create.php">

           <div class="input-group">
                <label class="input-group-text" for="customingredient">Ingredientes: </label>                
                <select class="form-select" name="customingredient" id="customingredient">
                    <?php
                    $sql = "SELECT ingredient FROM ingredients;";

                    $result = $conn -> query($sql);

                    while($row = $result -> fetch_assoc()) {
                        echo '<option value="' . $row["ingredient"] . '">' . $row["ingredient"] . '</option>';
           
                    }
                    
                   ?>
                </select>           
                <input class="btn btn-primary" type="submit" value="Agregar">
            </div>
        </form>
    </div>
    <div>
        <div class="mt-5">
        <?php
        $sql = "SELECT ingredient FROM ingholder;";

        $result = $conn -> query($sql);
        
        if($result -> num_rows == 0){
            echo "<h3>Agregue los ingredientes para conseguir recetas...</h3>";

        } else {
            $html = "<ol>";
            while($row = $result -> fetch_assoc()) {
                $html .= '<li>' . $row["ingredient"] . '</li>';
                $html .= "<a href='../actions/delete.php?custom=" . $row['ingredient'] . "' " . "title='Eliminar'>Eliminar</a>";
            }
            $html .= "</ol>";
            echo $html;
        }
        ?>
        </div>
        <div>
            <form action="" method="POST">

                <div class="form-check form-switch m-2">
                    <input class="form-check-input" type="checkbox" id="match" name="match" value="yes" checked>
                    <label class="form-check-label" for="match">Correspondencia total</label>
                </div>
            </form>
        </div>        
    </div>
</main>
<?php
//Footer of the page.
require_once ("../modules/footer.php");
?>