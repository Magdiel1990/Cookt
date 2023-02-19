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
<script>
    if (window.history.replaceState) { // verificamos disponibilidad
    window.history.replaceState(null, null, window.location.href);
}
</script>

<link rel="stylesheet" href="../styles/styles.css">

<main class="container p-4">
    <div  class="row mt-2 text-center justify-content-center">
        <h3>RECETAS</h3>
    </div>
    <table>
<?php
if(isset($_GET['ingredients'])){

    $ingArray = (unserialize(base64_decode($_GET['ingredients'])));

    $ingredients = implode(", ", $ingArray);  

    $sql = "SELECT DISTINCT recipename FROM recipeinfo WHERE ingredient IN (". $ingredients .") ORDER BY RAND();";
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
</table>    
</main>
<?php
//Footer of the page.
require_once ("../modules/footer.php");
?>