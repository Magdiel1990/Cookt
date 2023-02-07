<?php
//Head of the page.
require_once ("../modules/head.php");

//Navigation panel of the page
require_once ("../modules/nav.php");

//Models
require_once ("../models/models.php");

//Including the database connection.
require_once ("../config/db_Connection.php");


/************************************************************************************************/
/***************************************INGREDIENTS EDITION CODE********************************/
/************************************************************************************************/


if(isset($_GET["id"])){

$id = $_GET["id"];

$sql = "SELECT * FROM reholder WHERE re_id = $id";

$result = $conn -> query($sql);

$row = $result -> fetch_assoc();

$num_rows = $result -> num_rows;

$quantity = $row["quantity"];
$ingredient = $row["ingredient"];
$unit = $row["unit"];

    if ($num_rows == 0) {
        //Message if the variable is null.
        $_SESSION['message'] = 'Este ingrediente no existe!';
        $_SESSION['message_alert'] = "danger";
            
    //The page is redirected to the add_units.php
        header('Location: ../views/add_recipe.php');

    } else {
?>        
<main>
<?php
//Messages that are shown in the index page
    if(isset($_SESSION['message'])){
    buttonMessage($_SESSION['message'], $_SESSION['message_alert']);        

//Unsetting the messages variables so the message fades after refreshing the page.
    unset($_SESSION['message_alert'], $_SESSION['message']);
}
?>
    <h3>Editar ingrediente</h3>     
    <div>
        <div class="card card-body">
            <form action="update.php?editid=<?php echo $id ?>" method="POST">

                <div>
                    <label for="quantity">Cantidad: </label>
                    <input type="number" name="quantity" value="<?php echo $quantity?>" class="form-control" id="quantity">
                </div>

                <div>
                    <label for="unit">Unidad: </label>
                    <select class="form-control" name="unit" id="unit">
                    <?php
                    $sql = "SELECT unit FROM units";

                    $result = $conn -> query($sql);

                    while($row = $result -> fetch_assoc()) {
                        echo '<option value="' . $row["unit"] . '">' . $row["unit"] . '</option>';
                    }
                    ?>
                    
                    </select>
                </div>
                
                <div>
                    <label for="ingredient">Ingrediente: </label>
                    <select class="form-control" name="ingredient" id="ingredient">
                    <?php
                    $sql = "SELECT ingredient FROM ingredients";

                    $result = $conn -> query($sql);

                    while($row = $result -> fetch_assoc()) {
                        echo '<option value="' . $row["ingredient"] . '">' . $row["ingredient"] . '</option>';
                    }
                    ?>
                    </select>
                </div>                 
                
                <div>
                    <input type="submit" name="edit" value="Actualizar"> 
                    <a href='../views/add_recipe.php' class='btn btn-secondary' title="Regresar"><i class="fa-solid fa-right-from-bracket"></i></a>  
                </div>
            </form>
        </div>
    </div>   
</main>
<?php
    }
}
?>







<?php
//Footer of the page.
require_once ("../modules/footer.php");
?>