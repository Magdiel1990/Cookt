<?php
//Head of the page.
require_once ("views/partials/head.php");

//Navigation panel of the page
require_once ("views/partials/nav.php");

?>

<main class="container p-4">

    <?php
    //Messages that are shown in the add_units page
        if(isset($_SESSION['message'])){
        $message = new Messages ($_SESSION['message'], $_SESSION['message_alert']);
        echo $message -> buttonMessage();          

    //Unsetting the messages variables so the message fades after refreshing the page.
        unset($_SESSION['message_alert'], $_SESSION['message']);
        }
    ?>

    <div  class="row mt-2 text-center justify-content-center">
        <h3>Elegir por Ingrediente</h3>
<!--Form for filtering the database info-->
        <form class="m-3 col-auto" method="POST" action="/cookt/create">

           <div class="input-group">
                <label class="input-group-text" for="customingredient">Ingredientes: </label>
                
                <?php
                $num_rows = new IngredientList("ingholder", "ingredients", "ingredient", $_SESSION['username']);
                $num_rows = $num_rows -> ingQuantity();

                if($num_rows > 0) {
                ?>
                <select class="form-select" name="customingredient" id="customingredient">
                    <?php 
                    $options = new IngredientList("ingholder", "ingredients", "ingredient", $_SESSION['username']);
                    $options -> ingredientOptions();                   
                    ?>
                </select> 
                <input class="btn btn-primary" type="submit" value="Agregar"> 
                <?php 
                } else {
                ?>
                <a class="btn btn-primary" href="/cookt/ingredients">Agregar</a>
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
                $html .= "<a href='/cookt/delete?custom=" . $row['ingredient'] . "' " . "title='Eliminar' class='ingredients'>";
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
        <?php
        if(isset($ingArray)){
        $where = "WHERE ";

        $count = count($ingArray);
        for($i=0; $i<$count; $i++){
            $where .= "ingredientid = '". $ingArray[$i] . "' OR ";
        }

        //The final where delection.
        $where = substr_replace($where, "", -4);

        $where .= " AND username = '" . $_SESSION['username'] . "'";
    
        $sql = "SELECT DISTINCT r.recipename FROM recipeinfo ri JOIN  recipe r ON r.recipeid = ri.recipeid " . $where  . " ORDER BY RAND();";
        $result = $conn -> query($sql);

        $html = "";

            if($result -> num_rows > 0){
                $html .= "<div class='suggestion_container'>";
                $html .= "<ul>";
                while($row = $result -> fetch_assoc()) {            
                    $html .= "<li><a href='/cookt/recipes?recipe=" . $row['recipename'] . "&username=" . $_SESSION['username'] . "&path=" . base64_encode(serialize($_SERVER['REQUEST_URI'])) . "&ingredients=" . base64_encode(serialize($ingArray)) ."'>" . $row['recipename'] . "</a></li>";
                } 
                $html .= "</ul>";
                $html .= "</div>";
                echo $html;
            } else {
                $html .= "<p class='text-center'>Ninguna receta disponible!";
                $html .= "<a class='btn btn-secondary' href='/cookt/custom'>Regresar</a>";
                $html .= "</p>";

                echo $html;
            }
        }
        ?>
        </div>
    </div>
</main>

<?php
$conn -> close();
//Footer of the page.
require_once ("views/partials/footer.php");
?>