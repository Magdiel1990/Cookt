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
        <h3>AGREGAR RECETA</h3>
<!--Form for filtering the database info-->
        <form class="mt-3 col-auto" id="randomform" method="POST" action="../actions/create.php">
            <div class="input-group mb-3">
                <label class="input-group-text" for="quantity">Cantidad: </label>                    
                <input class="form-control" type="number" name="quantity" id="quantity">
            </div>
            <div class="input-group mb-3">
                <label class="input-group-text" for="unit">Unidad: </label>                
                <select class="form-select" name="unit" id="unit">
                    <?php
                    $sql = "SELECT unit FROM units";

                    $result = $conn -> query($sql);

                    while($row = $result -> fetch_assoc()) {
                        echo '<option value="' . $row["unit"] . '">' . $row["unit"] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="input-group mb-3">
                <label class="input-group-text" for="ingredient">Ingrediente: </label>                
                <select class="form-select" name="ingredient" id="ingredient">
                    <?php
                    $sql = "SELECT ingredient FROM ingredients";

                    $result = $conn -> query($sql);

                    while($row = $result -> fetch_assoc()) {
                        echo '<option value="' . $row["ingredient"] . '">' . $row["ingredient"] . '</option>';
                    }
                    ?>
                </select>
            </div>
                <input class="btn btn-primary" type="submit" value="Agregar ingrediente">
            
        </form>
        <!-- Table with ingredients that will conform the recipe-->
        <div class="table table-sm mt-5 col-auto">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Ingredientes</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $sql = "SELECT re_id, concat_ws(' ', quantity, unit, 'de' ,ingredient) as fullingredient FROM reholder;";

                $result = $conn -> query($sql);

                $row = $result -> fetch_assoc();

                $num_rows = $result -> num_rows;

                if ($num_rows != 0) {
                    $html = "";
                    while($row = $result -> fetch_assoc()){
                        $html .= "<tr>";
                        $html .= "<td>";
                        $html .= "<a href='../actions/edit.php?id=" . $row["re_id"] . "'>" . $row["fullingredient"];
                        $html .= "</a>";
                        $html .= "<a class='btn btn-danger' href='../actions/delete.php?id=" . $row["re_id"] . "'>";
                        $html .= "Eliminar"; 
                        $html .=  "</a>";
                        $html .= "</td>";                   
                        $html .= "</tr>";                        
                    }
                    echo $html;
                }  else {                          
                    $html = "";
                    $html .= "<tr>";
                    $html .= "<td>";
                    $html .= "Agrega los ingredientes...";
                    $html .= "</td>";
                    $html .= "</tr>";
                    echo $html;                                   
                }
                ?>
                </tbody>
            </table>
        </div>
        <form class="mt-3 col-auto" id="randomform" method="POST" action="../actions/create.php">
            
            <div class="input-group mb-3">
                <label class="input-group-text" for="recipename">Nombre: </label>
                <input  class="form-control" type="text" id="recipename" name="recipename">             
            </div>
            
            <div class="input-group mb-3">
                <label class="input-group-text" for="category">Categoría: </label>                
                <select class="form-select" name="category" id="category">
                    <?php
                    $sql = "SELECT category FROM categories";

                    $result = $conn -> query($sql);

                    while($row = $result -> fetch_assoc()) {
                        echo '<option value="' . $row["category"] . '">' . $row["category"] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="input-group mb-3">
                <label class="input-group-text" for="cookingtime">Tiempo de cocción: </label>
                <input class="form-control" type="number" id="cookingtime" name="cookingtime">             
            </div>
            <div class="row">
                <div class="col-sm-6 col-md-6 col-lg-6 mb-3">
                    <label for="preparation" class="form-label">Preparación: </label>
                    <textarea class="form-control" name="preparation" id="preparation" cols="30" rows="10"></textarea>
                </div>           
                <div class="col-sm-6 col-md-6 col-lg-6 mb-3">
                    <label for="observation" class="form-label">Observaciones: </label>
                    <textarea class="form-control" name="observation" id="observation" cols="30" rows="10"></textarea>
                </div>
            </div>
            <div>
                <input class="btn btn-primary" type="submit" value="Agregar receta" name="addrecipe">
            </div>
        </form>
    </div>
</main>
<?php
//Footer of the page.
require_once ("../modules/footer.php");
?>