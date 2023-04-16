<?php
//Including the database connection.
require_once ("config/db_Connection.php");

//Models.
require_once ("models/models.php");

//Head of the page.
require_once ("views/partials/head.php");

//Navigation panel of the page
require_once ("views/partials/nav.php");
?>

<main class="container p-4">
    <div class="row text-center justify-content-center">
    <?php
//Messages that are shown in the add_units page
        if(isset($_SESSION['message'])){
        $message = new Messages ($_SESSION['message'], $_SESSION['message_alert']);
        echo $message -> buttonMessage();           

//Unsetting the messages variables so the message fades after refreshing the page.
        unset($_SESSION['message_alert'], $_SESSION['message']);
        }
    ?>
    <h3>Agregar Ingredientes</h3>
<!--Form for filtering the database info-->
        <form method="POST" action="../actions/create.php" autocomplete="on" class="mt-3 col-auto" onsubmit="return validation('add_ingredient', /[a-zA-Z\t\h]+|(^$)/ )">
            <div class="input-group mb-4">
                <label  class="input-group-text is-required" for="add_ingredient">Ingrediente: </label>
                <input  class="form-control" type="text" id="add_ingredient" name="add_ingredient" pattern="[a-zA-Z áéíóúÁÉÍÓÚñÑ]+" minlength="2" maxlength="20" autofocus required>
                <input class="btn btn-success" type="submit" value="Agregar">
            </div>
        </form>
    </div>
    <div class="row">
         <?php
            $sql = "SELECT ingredient FROM ingredients WHERE username = '" . $_SESSION['username'] . "' ORDER BY ingredient;";

            $result = $conn -> query($sql);

            if($result -> num_rows > 0){
        ?>
        <table class="table table-sm p-4 col-auto">
            <thead>
                <tr class="bg-primary">
                    <th>Ingredientes</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>  
            <?php              
                while($row = $result -> fetch_assoc()){
                    $html = "<tr>";
                    $html .= "<td>" . ucfirst($row['ingredient']) . "</td>";
                    $html .= "<td>";
                    $html .= "<a href='actions/delete.php?ingredientname=" . $row['ingredient'] . "' " . "class='btn btn-outline-danger' title='Eliminar'><i class='fa-solid fa-trash'></i></a>";
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
$conn -> close();
//Footer of the page.
require_once ("views/partials/footer.php");
?>