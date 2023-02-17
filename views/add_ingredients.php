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
    <div class="row mt-2 text-center justify-content-center">
    <?php
//Messages that are shown in the add_units page
        if(isset($_SESSION['message'])){
        buttonMessage($_SESSION['message'], $_SESSION['message_alert']);        

//Unsetting the messages variables so the message fades after refreshing the page.
        unset($_SESSION['message_alert'], $_SESSION['message']);
        }
    ?>
    <h3>AGREGAR INGREDIENTES</h3>
<!--Form for filtering the database info-->
        <form method="POST" action="../actions/create.php" autocomplete="off" class="mt-3 col-auto">
            <div class="input-group mb-4">
                <label  class="input-group-text" for="add_ingredient">Ingrediente: </label>
                <input  class="form-control" type="text" id="add_ingredient" name="add_ingredient">
                <input class="btn btn-primary" type="submit" value="Agregar">
            </div>
        </form>
    </div>
    <div>
         <?php
            $sql = "SELECT ingredient FROM ingredients";

            $result = $conn -> query($sql);

            if($result -> num_rows > 0){
        ?>
        <table class="table table-sm">
            <thead>
                <tr>
                    <th>Ingredientes</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>  
            <?php              
                while($row = $result -> fetch_assoc()){
                    $html = "<tr>";
                    $html .= "<td>" . $row['ingredient'] . "</td>";
                    $html .= "<td>";
                    $html .= "<a href='../actions/delete.php?ingredientname=" . $row['ingredient'] . "' " . "class='btn btn-outline-danger' title='Eliminar'><i class='fa-solid fa-trash'></i></a>";
                    $html .= "</td>";
                    $html .= "</tr>";
                    echo $html;
                }
            ?>
            </tbody>
        </table>
            <?php
            } else {
                $html = "<p>";
                $html .= "Agregue los ingredientes...";
                $html .= "</p>";
                echo $html;
            }    
            ?>
    </div>
</main>
<?php
//Footer of the page.
require_once ("../modules/footer.php");
?>