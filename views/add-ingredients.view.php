<?php
//Head
require_once ("views/partials/head.php");

//Nav
require_once ("views/partials/nav.php");

?>

<main class="container p-4">
    <div class="row text-center justify-content-center">
    <?php
//Messages
        if(isset($_SESSION['message'])){
        $message = new Messages ($_SESSION['message'], $_SESSION['message_alert']);
        echo $message -> buttonMessage();           

//Unsetting the messages 
        unset($_SESSION['message_alert'], $_SESSION['message']);
        }
    ?>
<!--Form for adding the ingredients-->   
    <h3>Agregar Ingredientes</h3>

        <form method="POST" action="/create" autocomplete="on" class="mt-3 col-auto" onsubmit="return validation('add_ingredient', /[a-zA-Z\t\h]+|(^$)/)">
            <div class="input-group mb-4">
                <label  class="input-group-text is-required" for="add_ingredient">Ingrediente: </label>
                <input  class="form-control" type="text" id="add_ingredient" name="add_ingredient" pattern="[a-zA-Z áéíóúÁÉÍÓÚñÑ]+" minlength="2" maxlength="20" autofocus required>
                <input class="btn btn-success" type="submit" value="Agregar">
            </div>
        </form>
    </div>
<!--Ingredients list-->      
    <div class="table-responsive-sm mt-4">
         <?php
            $sql = "SELECT ingredient FROM ingredients WHERE username = '" . $_SESSION['username'] . "' ORDER BY ingredient;";
            $result = $conn -> query($sql);

            if($result -> num_rows > 0){
        ?>
        <table class="table table-sm">
            <thead>
                <tr class="bg-primary">
                    <th scope="col">Ingredientes</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>  
            <?php              
                while($row = $result -> fetch_assoc()){
                    $html = "<tr>";
                    $html .= "<td title='ingrediente'>" . ucfirst($row['ingredient']) . "</td>";
                    $html .= "<td>";
//Delete button                    
                    $html .= "<a href='/delete?ingredientname=" . $row['ingredient'] . "' id ='ingredientdel' class='btn btn-outline-danger' title='Eliminar'><i class='fa-solid fa-trash'></i></a>";
                    $html .= "</td>";
                    $html .= "</tr>";
                    echo $html;
                }
            ?>
            </tbody>
        </table>
            <?php
//Text when there is no ingredients
            } else {
                $html = "<p>";
                $html .= "Agregue los ingredientes...";
                $html .= "</p>";
                echo $html;
            }    
            ?>
    </div> 
</main>
<script>
deleteMessage("btn-outline-danger", "ingrediente");   

//Delete message
function deleteMessage(button, pageName){
var deleteButtons = document.getElementsByClassName(button);

    for(var i = 0; i<deleteButtons.length; i++) {
        deleteButtons[i].addEventListener("click", function(event){    
            if(confirm("¿Desea eliminar este " + pageName + "?")) {
                return true;
            } else {
                event.preventDefault();
                return false;
            }
        })
    }
}
</script>
<?php
//exiting connection
$conn -> close();

//Footer of the page.
require_once ("views/partials/footer.php");
?>