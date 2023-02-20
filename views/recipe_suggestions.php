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
            $where .= "ingredient = '". $ingArray[$i] . "' OR ";
        }

        //The final where delection.
        $where = substr_replace($where, "", -4);
    
        $sql = "SELECT DISTINCT recipename FROM recipeinfo " . $where  . " ORDER BY RAND();";
        $result = $conn -> query($sql);

        $html = "";

        if($result -> num_rows > 0){
            $html .= "<ol>";
            while($row = $result -> fetch_assoc()) {            
                $html .= "<li><a href='../views/recipe_viewer.php?recipe=" . $row['recipename'] . "'>" . $row['recipename'] . "</a></li>";
            } 
            $html .= "</ol>";
            $html .= "<a class='btn btn-secondary' href='custom_recipe.php'>Regresar</a>";
            echo $html;
        } else {
                $html .= "<p class='text-center'>Ninguna receta disponible!";
                $html .= "<a class='btn btn-secondary' href='custom_recipe.php'>Regresar</a>";
                $html .= "</p>";

                echo $html;
        }
    }      
    ?>
    </div>
</main>
<?php
//Footer of the page.
require_once ("../modules/footer.php");
?>