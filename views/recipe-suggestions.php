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
    <div  class="my-4 text-center">
        <h3>RECETAS</h3>
    </div>
    <div class="mt-4">
    <?php
    if(isset($_GET['ingredients'])){

        $ingArray = (unserialize(base64_decode($_GET['ingredients'])));

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
            $html .= "<ol>";
            while($row = $result -> fetch_assoc()) {            
                $html .= "<li><a href='../views/recipes.php?recipe=" . $row['recipename'] . "'>" . $row['recipename'] . "</a></li>";
            } 
            $html .= "</ol>";
            $html .= "<a class='btn btn-secondary' href='custom-recipe.php'>Regresar</a>";
            echo $html;
        } else {
                $html .= "<p class='text-center'>Ninguna receta disponible!";
                $html .= "<a class='btn btn-secondary' href='custom-recipe.php'>Regresar</a>";
                $html .= "</p>";

                echo $html;
        }
    }      
    ?>
    </div>
</main>
<?php
$conn -> close();
//Footer of the page.
require_once ("../modules/footer.php");
?>