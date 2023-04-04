<?php
//Including the database connection.
require_once ("../config/db_Connection.php");

//Models.
require_once ("../models/models.php");

//Head of the page.
require_once ("../modules/head.php");

//Navigation panel of the page
require_once ("../modules/nav.php");
?>

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
        <form class="m-3 col-auto" method="POST" action="../actions/create.php">

           <div class="input-group">
                <label class="input-group-text" for="customingredient">Ingredientes: </label>
                
                <?php
                $sql = "SELECT i.ingredient FROM ingholder ih JOIN ingredients i ON i.id = ih.ingredientid WHERE ih.username = '" . $_SESSION['username'] . "';";
                $result = $conn -> query($sql);
                $num_rows = $result -> num_rows;

                if ($num_rows == 0) {
                    $where = "WHERE username = '" . $_SESSION['username'] . "'";                                               
                } else {
                    $where = "WHERE NOT ingredient IN (";

                    while($row = $result -> fetch_assoc()) {
                        $where .= "'" . $row["ingredient"] . "', ";
                    }
                    
                    $where = substr_replace($where, "", -2);
                    $where .= ") AND username = '" . $_SESSION['username'] . "'";                        
                }
                $sql = "SELECT ingredient FROM ingredients $where;"; 
                
                $result = $conn -> query($sql);
                $num_rows = $result -> num_rows;

                if($num_rows > 0) {
                ?>
                <select class="form-select" name="customingredient" id="customingredient">
                    <?php           

                    while($row = $result -> fetch_assoc()) {          
                        echo '<option value="' . $row["ingredient"] . '">' . ucfirst($row["ingredient"]) . '</option>';
                    }      
                    
                   ?>
                </select> 
                <input class="btn btn-primary" type="submit" value="Agregar"> 
                <?php 
                } else {
                ?>
                <a class="btn btn-primary" href="add-ingredients.php">Agregar</a>
                <?php 
                } 
                ?> 
            </div>
        </form>
    </div>
    <div class="row mt-4">
        <div class="col-auto">
        <?php
        $sql = "SELECT i.ingredient, ih.ingredientid FROM ingholder ih JOIN ingredients i ON i.id = ih.ingredientid WHERE ih.username = '" . $_SESSION['username'] . "';";

        $result = $conn -> query($sql);
        
        if($result -> num_rows == 0){
            echo "<p class='text-center'>Agregue los ingredientes para conseguir recetas...</p>";

        } else {
            $html = "<div>";
            $html .= "<ol>";
            while($row = $result -> fetch_assoc()) {
                $html .= "<li>";
                $html .= "<a href='../actions/delete.php?custom=" . $row['ingredient'] . "' " . "title='Eliminar' class='ingredients'>";
                $html .= ucfirst($row["ingredient"]);
                $html .= "</a>";
                $html .= "</li>";
                $ingArray[] = $row["ingredientid"];
            }
            $html .= "</ol>";
            $html .= "</div>";                   
            $html .= "</div>";      
            echo $html;
        }
        ?>
        </div>
            
        <div class="col-auto">
            <ol id="ingredientlist">
            </ol>
        </div>
    </div>
</main>

<script>
var miJSON = <?php echo json_encode($ingArray); ?>;

var request = new Request({
   url: "/Cookt/ajax/suggestion-ajax.php",
   data: "datos=" + miJSON,
   onSuccess: function(textoRespuesta){
      $('ingredientlist').set("html", textoRespuesta);
   },
   onFailure: function(){
      $('ingredientlist').set("html", "fallo en la conexión Ajax");
   }
})
request.send();
</script>
<?php
$conn -> close();
//Footer of the page.
require_once ("../modules/footer.php");
?>