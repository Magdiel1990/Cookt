<?php
//Head
require_once ("views/partials/head.php");

//Nav
require_once ("views/partials/nav.php");

//Current location in order to come back
$_SESSION['location'] = $_SERVER["REQUEST_URI"];
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

        <form method="POST" id="ingform" action="<?php echo root;?>create" autocomplete="on" class="mt-3 col-auto">
            <div class="input-group mb-4">
                <label  class="input-group-text is-required" for="add_ingredient">Ingrediente: </label>
                <input  class="form-control" type="text" id="add_ingredient" name="add_ingredient" pattern="[a-zA-Z áéíóúÁÉÍÓÚñÑ,;:]+" minlength="2" maxlength="20" autofocus required>
                <input class="btn btn-success" type="submit" value="Agregar">
            </div>
        </form>
    </div>
    <div id="message"></div>
<!--Ingredients list-->      
    <div class="table-responsive-sm mt-4">
         <?php
            $sql = "SELECT ingredient FROM ingredients WHERE username = ? AND state = 1 ORDER BY ingredient;";
            $stmt = $conn -> prepare($sql); 
            $stmt->bind_param("s",  $_SESSION['username']);
            $stmt->execute();

            $result = $stmt -> get_result(); 

            if($result -> num_rows > 0){
        ?>
        <table class="table table-sm shadow">
            <thead>
                <tr class="table_header">
                    <th class='px-2' scope="col">Ingredientes</th>
                    <th class='px-2'scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>  
            <?php              
            while($row = $result -> fetch_assoc()){
                $html = "<tr>";
                $html .= "<td class='ingredient px-2' title='ingrediente'>" . ucfirst($row['ingredient']) . "</td>";
                $html .= "<td class='px-2'>";
//Delete button                    
                $html .= "<a href='" . root . "delete?ingredientname=" . $row['ingredient'] . "' class='btn btn-outline-danger' title='Eliminar'><i class='fa-solid fa-trash'></i></a>";
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
add_ingredient_validation();
deleteMessage("btn-outline-danger", "ingrediente");   

//Delete message
function add_ingredient_validation() {
    var form = document.getElementById("ingform");    

    form.addEventListener("submit", function (event){
    var regExp = /[a-zA-Z,;:\t\h]+|(^$)/;
    var message = document.getElementById("message");    
    var ingredient = document.getElementById("add_ingredient").value;

//Empty validation
        if(ingredient == ""){
            event.preventDefault();
            message.innerHTML = "¡Escribir el ingrediente por favor!";             
            return false;
        }

//Regular Expression    
        if(!ingredient.match(regExp)){
            event.preventDefault();
            message.innerHTML = "¡Nombre de ingrediente incorrecto!";                 
            return false;
        }

//length validation
        if(ingredient.length < 2 || ingredient.length > 20){
            event.preventDefault();
            message.innerHTML = "¡El ingrediente debe tener entre 2 y 20 caracteres!";             
            return false;
        }

        return true;                
    })
}

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