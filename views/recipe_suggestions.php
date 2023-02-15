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
    <div  class="row mt-2 text-center justify-content-center">
        <h3>RECETAS</h3>
    </div>
    <table>
<?php
if(isset($_POST['match'])){
    $match = $_POST['match'];

    $sql = "SELECT ingredient FROM ingholder;";

    $result = $conn -> query($sql);    

    $ingArray = [];

    while($row = $result -> fetch_assoc()){
        $ingArray [] = $row['ingredient']; 
    }

    $exclusiveIngredients = implode(" AND ", $ingArray);
    $inclusiveIngredients = implode(" OR ", $ingArray);

    if($match == "yes") {
        $sql = "SELECT DISTINCT * FROM recipeinfo WHERE ingredient IN (". $exclusiveIngredients .");";
        $result = $conn -> query($sql);

        $html = "";

        if($result -> num_rows > 0){
            
            while($row = $result -> fetch_assoc()) {
                $html .= "<tr>";
                $html .= "<td ><a href='./views/recipe_viewer.php?recipe=" . $row['recipename'] . "'>" . $row['recipename'] . "</a></td>";
                $html .= "<td>" .$row['category']. "</td>";
                $html .= "<td>";
                $html .= "<a href='actions/edit.php?recipename=" . $row['recipename'] . "' " . "class='btn btn-outline-secondary' title='Editar'><i class='fa-solid fa-pen'></i></a>";
                $html .= "<a href='actions/delete.php?recipename=" . $row['recipename'] . "' " . "class='btn btn-outline-danger' title='Eliminar'><i class='fa-solid fa-trash'></i></a>";
                $html .= "</td>";
                $html .= "</tr>";
            } 
            echo $html;
        } else {
             $html .= "<tr>";
             $html .= "<td colspan='3'>";
             $html .= "<p>Ninguna receta disponible!</p>";
             $html .= "<a href='custom_recipe.php'>Regresar</a>";
             $html .= "</td>";
             $html .= "</tr>";
        }
    } else {
        $sql = "SELECT DISTINCT * FROM recipeinfo WHERE ingredient IN (". $inclusiveIngredients .");";
        $result = $conn -> query($sql);

        $html = "";

        if($result -> num_rows > 0){
            
            while($row = $result -> fetch_assoc()) {
                $html .= "<tr>";
                $html .= "<td ><a href='./views/recipe_viewer.php?recipe=" . $row['recipename'] . "'>" . $row['recipename'] . "</a></td>";
                $html .= "<td>" .$row['category']. "</td>";
                $html .= "<td>";
                $html .= "<a href='actions/edit.php?recipename=" . $row['recipename'] . "' " . "class='btn btn-outline-secondary' title='Editar'><i class='fa-solid fa-pen'></i></a>";
                $html .= "<a href='actions/delete.php?recipename=" . $row['recipename'] . "' " . "class='btn btn-outline-danger' title='Eliminar'><i class='fa-solid fa-trash'></i></a>";
                $html .= "</td>";
                $html .= "</tr>";
            } 
            echo $html;
        } else {
             $html .= "<tr>";
             $html .= "<td colspan='3'>";
             $html .= "<p>Ninguna receta disponible!</p>";
             $html .= "<a href='custom_recipe.php'>Regresar</a>";
             $html .= "</td>";
             $html .= "</tr>";
        }
    }    
}
?>

</table>    
</main>
<?php
//Footer of the page.
require_once ("../modules/footer.php");
?>