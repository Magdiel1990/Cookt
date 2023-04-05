<?php
//Head of the page.
require_once ("../modules/head.php");

//Navigation panel of the page
require_once ("../modules/nav.php");

//Models
require_once ("../models/models.php");

//Including the database connection.
require_once ("../config/db_Connection.php");
?>

<?php
/************************************************************************************************/
/***************************************INGREDIENTS (AGREGAR RECETA) EDITION CODE********************************/
/************************************************************************************************/


/*if(isset($_GET["id"])){

$id = $_GET["id"];

$sql = "SELECT i.ingredient, rh.quantity, rh.unit FROM reholder rh JOIN ingredients i ON i.id = rh.ingredientid WHERE re_id = $id AND rh.username = '" . $_SESSION['username'] . "';";

$result = $conn -> query($sql);

$row = $result -> fetch_assoc();

$num_rows = $result -> num_rows;

$quantity = $row["quantity"];
$unit = $row["unit"];
$ingredient = $row["ingredient"];


    if ($num_rows == 0) {
        //Message if the variable is null.
        $_SESSION['message'] = 'Este ingrediente no existe!';
        $_SESSION['message_alert'] = "danger";
            
    //The page is redirected to the add_units.php
        header('Location: ../views/add-recipe.php');

    } else {
?>        
<main class="container p-4">
<?php
//Messages that are shown in the index page
    if(isset($_SESSION['message'])){
    $message = new Messages ($_SESSION['message'], $_SESSION['message_alert']);
    echo $message -> buttonMessage();           

//Unsetting the messages variables so the message fades after refreshing the page.
    unset($_SESSION['message_alert'], $_SESSION['message']);
    }
?>
    <div class="row mt-2 text-center justify-content-center">
        <h3>EDITAR INGREDIENTE</h3>     
        <div class="mt-3 col-auto">
            <form class="bg-form card card-body" action="update.php?editid=<?php echo $id ?>" method="POST">

                <div class="input-group mb-3">
                    <label class="input-group-text" for="quantity">Cantidad: </label>
                    <input type="number" name="quantity" value="<?php echo $quantity?>" class="form-control" id="quantity">
                </div>

                <div class="input-group mb-3">
                    <label class="input-group-text" for="unit">Unidad: </label>
                    <select class="form-select" name="unit" id="unit">
                    <?php
                    $sql = "SELECT unit FROM units";

                    $result = $conn -> query($sql);

                    while($row = $result -> fetch_assoc()) {
                        echo '<option value="' . $row["unit"] . '">' . ucfirst($row["unit"]) . '</option>';
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
                        echo '<option value="' . $row["ingredient"] . '">' . ucfirst($row["ingredient"]) . '</option>';
                    }
                    ?>
                    </select>
                </div>                 
                
                <div class="mb-3">
                    <input class='btn btn-primary' type="submit" name="edit" value="Actualizar"> 
                    <a href='../views/add-recipe.php' class='btn btn-secondary' title="Regresar"><i class="fa-solid fa-right-from-bracket"></i></a>  
                </div>
            </form>
        </div>
    </div>      
</main>
<?php
    }
}

*/
/************************************************************************************************/
/******************************************CATEGORY EDITION CODE***********************************/
/************************************************************************************************/


if(isset($_GET['categoryid'])){
$categoryId = $_GET['categoryid'];

$sql = "SELECT * FROM categories WHERE categoryid = '$categoryId';";

$row = $conn -> query($sql) -> fetch_assoc();

$category = $row["category"];

?>
<main class="container p-4">
<?php
//Messages that are shown in the index page
    if(isset($_SESSION['message'])) {
    $message = new Messages ($_SESSION['message'], $_SESSION['message_alert']);
    echo $message -> buttonMessage();         

//Unsetting the messages variables so the message fades after refreshing the page.
    unset($_SESSION['message_alert'], $_SESSION['message']);
    }
?>
    <div class="row mt-2 text-center justify-content-center">
        <h3>EDITAR CATEGORÍA</h3>     
        <div class="mt-3 col-auto">
            <form  enctype="multipart/form-data" class="bg-form card card-body" action="update.php?categoryid=<?php echo $categoryId; ?>" method="POST">

                <div class="input-group mb-3">
                    <label class="input-group-text is-required" for="categoryName">Nombre: </label>
                    <input type="text" name="categoryName" value="<?php echo $category;?>" class="form-control" id="categoryName" pattern="[a-zA-Z áéíóúÁÉÍÓÚñÑ]+" max-length="20" min-length="2" required>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="categoryImage">Foto de la categoría</label>
                    <input type="file" name="categoryImage" accept=".png, .jpeg, .jpg, .gif" class="form-control" id="categoryImage">
                </div> 
                <div class="mt-2">
                    <input class="btn btn-primary" type="submit" value="Editar" name="categoryeditionsubmit">
                    <a href="../views/add-categories.php" class="btn btn-secondary">Regresar</a>
                </div>
                </form>
            </div>
       </div>                  
    </div>     
</main>

<?php
}
/************************************************************************************************/
/******************************************RECIPE EDITION CODE***********************************/
/************************************************************************************************/


if(isset($_GET['recipename']) && isset($_GET['username'])) {
$recipeName = $_GET['recipename'];
$userName = $_GET['username'];

$sql = "SELECT r.recipeid, 
r.recipename,
r.cookingtime, 
r.preparation,
c.category, 
r.username
from recipe r 
join categories c 
on r.categoryid = c.categoryid
WHERE r.recipename = '$recipeName' 
AND r.username = '$userName';";

$row = $conn -> query($sql) -> fetch_assoc();

if(isset($row["cookingtime"]) && isset($row["preparation"]) && isset($row["category"])) {
    $cookingTime = $row["cookingtime"];

    $filter = new Filter ($row["preparation"], FILTER_SANITIZE_STRING, $conn);  
    $preparation = $filter -> sanitization();

    $category = $row["category"];
}
?>
<main class="container p-4">
<?php
//Messages that are shown in the index page
    if(isset($_SESSION['message'])){
    $message = new Messages ($_SESSION['message'], $_SESSION['message_alert']);
    echo $message -> buttonMessage();       

//Unsetting the messages variables so the message fades after refreshing the page.
    unset($_SESSION['message_alert'], $_SESSION['message']);
}
?>
    <div class="row mt-2 justify-content-center">
        <h3 class="text-center">Editar Receta</h3>     
        <div class="mt-3 col-auto">
            <div class="bg-form card card-body">
                <form enctype="multipart/form-data" action="update.php?editname=<?php echo $recipeName;?>&username=<?php echo $userName;?>" method="POST" onsubmit="return validationNumberText('cookingTime', 'newRecipeName', /[a-zA-Z\t\h]+|(^$)/)">

                    <div class="input-group mb-3">
                        <label class="input-group-text is-required" for="newRecipeName">Nombre: </label>
                        <input type="text" name="newRecipeName" value="<?php echo $recipeName;?>" class="form-control" id="newRecipeName" pattern="[a-zA-Z áéíóúÁÉÍÓÚñÑ]+" max-length="50" min-length="7" required>
                    </div>

                    <div class="mb-3 text-center">
                        <label class="form-label" for="recipeImage">Imagen:</label>
                        <input type="file" name="recipeImage" accept=".jpg, .jpeg, .png, .gif" class="form-control" id="recipeImage">
                    </div> 
                    <div class="row">
                        <div class="input-group mb-3 col">
                            <label class="input-group-text" for="category">Categoría: </label>                
                            <select class="form-select" name="category" id="category">
                                <?php
                                $sql = "SELECT category FROM categories WHERE NOT category='$category';";

                                $result = $conn -> query($sql);
                                echo '<option value="' . $category . '">' .  ucfirst($category) . '</option>';
                                while($row = $result -> fetch_assoc()) {
                                    echo '<option value="' . $row["category"]  . '">' . ucfirst($row["category"]) . '</option>';
                                }
                                ?>
                            </select>
                        </div>                  

                        <div class="input-group mb-3 col">
                            <label class="input-group-text" for="cookingTime">Tiempo: </label>
                            <input type="number" name="cookingTime" value="<?php echo $cookingTime;?>" class="form-control" id="cookingTime" min="5" max="180">
                        </div>
                    </div>
                            
                    <div class="mb-3 text-center">
                        <label  class="form-label is-required" for="preparation">Preparación: </label>
                        <textarea name="preparation"  cols="4" rows="4" class="form-control" id="preparation" required>
                            <?php echo $preparation;?>
                        </textarea>
                    </div>
                                      
                    <div class="mb-3 text-center">
                        <input class='btn btn-primary' type="submit" name="edit" value="Actualizar"> 
                        <a href='../index.php' class='btn btn-secondary' title="Regresar"><i class="fa-solid fa-right-from-bracket"></i></a>  
                    </div>
                </form>
            </div>
        </div>
         
        <div class="mt-3 col-md-9 col-sm-11 col-lg-6 col-xl-5">
            <div class="card card-body bg-form">
                <h3 class="text-center">Editar Ingredientes</h3>
                <div class="mt-2">
                <?php
                $sql = "SELECT concat_ws(' ', ri.quantity, ri.unit, 'de' , i.ingredient, ri.detail) as indications 
                        from recipe r 
                        join recipeinfo ri 
                        on ri.recipeid = r.recipeid
                        join ingredients i 
                        on i.id = ri.ingredientid
                        WHERE r.recipename = '$recipeName' 
                        AND r.username = '$userName';";

                $result = $conn -> query($sql);
                
                $html = "<ul>";
                while($row = $result -> fetch_assoc()){
                    $html .= "<li class='my-2'>";
                    $html .= "<i><a href='delete.php?indication=" . $row['indications'] . "&rpename=" . $recipeName . "&username=" . $userName . "'>";
                    $html .= $row['indications'];
                    $html .= "</i></a>";
                    $html .= "</li>";
                }
                $html .= "</ul>";
                echo $html;
                ?>
                </div>
                <div class="my-4 text-center">
                    <form method="POST" action="create.php?rname=<?php echo $recipeName;?>&username=<?php echo $userName;?>" onsubmit="return validationNumber('quantity')">
                        <div class="input-group mb-3">
                            <label class="input-group-text is-required" for="quantity">Cantidad: </label>                    
                            <input class="form-control" type="number" name="qty" id="quantity" step="0.05" max="1000" min="0" required>
                        </div>

                        <div class="input-group mb-3">
                            <label class="input-group-text" for="unit">Unidad: </label>                
                            <select class="form-select" name="units" id="unit">
                                <?php
                                $sql = "SELECT unit FROM units";

                                $result = $conn -> query($sql);

                                while($row = $result -> fetch_assoc()) {
                                    echo '<option value="' . $row["unit"] . '">' . $row["unit"] . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="input-group mb-3 justify-content-center">
                        <?php
                            $sql = "SELECT i.ingredient FROM recipeinfo ri JOIN recipe r ON ri.recipeid = r.recipeid JOIN ingredients i ON i.id = ri.ingredientid WHERE r.recipename = '$recipeName' AND r.username = '$userName';";
                            $result = $conn -> query($sql);
                            $num_rows = $result -> num_rows;

                            if($num_rows == 0) { 
                                $where = "WHERE username = '$userName';";
                            } else {
                                $where = "WHERE NOT ingredient IN (";
                                while($row = $result -> fetch_assoc()){
                                    $where .= "'" . $row["ingredient"] . "', ";
                                }
                                $where = substr_replace($where, "", -2);
                                $where .= ") AND username = '$userName'";
                            }
                        
                            $sql = "SELECT ingredient FROM ingredients " . $where;
                            $result = $conn -> query($sql);
                            $num_rows = $result -> num_rows;

                            if($num_rows > 0) {                            
                        ?>
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="ingredient">Ingrediente: </label>                
                            <select class="form-select" name="ing" id="ingredient">

                                <?php
                                while($row = $result -> fetch_assoc()) {
                                    echo '<option value="' . $row["ingredient"] . '">' . $row["ingredient"] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="detail">Detalle:</label>
                            <input class="form-control" type="text" name="detail" id="detail" maxlength="100">
                        </div>
                        <div>
                            <input class="btn btn-primary" type="submit" title="Agregar ingredientes" value="Agregar">
                        </div>
                        <?php
                        } else {
                        ?>
                        <div>
                            <a class="btn btn-secondary" href="../views/add-ingredients.php">Ingredientes</a>
                        </div>
                        <?php
                        }
                        ?>
                        </div>                       
                    </form>
                </div>
            </div>
       </div>                  
    </div>     
</main>

<?php
}

/************************************************************************************************/
/********************************************USER EDITION CODE***********************************/
/************************************************************************************************/


if(isset($_GET['userid'])) {
$userId = $_GET['userid'];

$sql = "SELECT username FROM users WHERE type = 'Admin' AND userid = " . $userId . ";";
$result = $conn -> query($sql);
$num_rows = $result -> num_rows;
$row = $result -> fetch_assoc();

if($num_rows == 1 && $_SESSION['username'] == $row['username']){
    $userNameState = "hidden";
    $userNameLabelState = "display: none;";    
} else {
    $userNameState = $userNameLabelState = "";
}

$sql = "SELECT * FROM users WHERE userid = '$userId';";

$row = $conn -> query($sql) -> fetch_assoc();

$userName = $row["username"];
$firstName=  $row["firstname"];
$lastName=  $row["lastname"];
$type = $row["type"];
$state = $row["state"];
$email = $row["email"];
$currentPassword = $row["password"];
$sex = $row["sex"];

if($state == 1) {
    $check = "checked";
} else {
    $check = "";
}
?>
<main class="container p-4">
<?php
//Messages that are shown in the index page
if(isset($_SESSION['message'])){
    $message = new Messages ($_SESSION['message'], $_SESSION['message_alert']);
    echo $message -> buttonMessage();        

    //Unsetting the messages variables so the message fades after refreshing the page.
    unset($_SESSION['message_alert'], $_SESSION['message']);
}
?>
    <div class="row mt-2 justify-content-center">
        <h3 class="text-center">EDITAR USUARIO</h3>     
        <div class="mt-3 col-auto">
            <form class="bg-form card card-body" action="update.php?userid=<?php echo $userId; ?>" method="POST">

                <div class="input-group mb-3">
                    <label class="input-group-text is-required" for="firstname">Nombre: </label>
                    <input class="form-control"  value="<?php echo $firstName; ?>" type="text" id="firstname" name="firstname"  pattern="[a-zA-Z áéíóúÁÉÍÓÚñÑ]+" minlength="2" maxlength="30">
                </div>

                <div class="input-group mb-3">
                    <label class="input-group-text is-required" for="lastname">Apellido: </label>
                    <input class="form-control"  value="<?php echo $lastName; ?>" type="text" id="lastname" name="lastname"  pattern="[a-zA-Z áéíóúÁÉÍÓÚñÑ]+" minlength="2" maxlength="40">
                </div>

                <div style="<?php echo $userNameLabelState;?>" class="input-group mb-3">
                    <label class="input-group-text is-required" for="username">Usuario: </label>
                    <input class="form-control" value="<?php echo $userName; ?>" type="text" id="username" name="username"  pattern="[a-zA-Z áéíóúÁÉÍÓÚñÑ]+" minlength="2" maxlength="30" <?php echo $userNameState; ?>>
                </div>
                
                <div class="input-group mb-3">
                    <label class="input-group-text" for="current_password">Contraseña actual: </label>
                    <input class="form-control" type="password" id="current_password" name="current_password"  minlength="8">
                </div>      
                
                <div class="input-group mb-3">
                    <label class="input-group-text" for="new_password">Nueva contraseña: </label>
                    <input class="form-control" type="password" id="new_password" name="new_password"  minlength="8">
                </div>   

                <div class="input-group mb-3">
                    <label class="input-group-text" for="repite_password">Repite nueva contraseña: </label>
                    <input class="form-control" type="password" id="repite_password" name="repite_password" minlength="8">
                </div>   

                <div style="<?php echo $userNameLabelState;?>" class="input-group mb-3">
                    <label class="input-group-text" for="userrol">Rol: </label>
                    <select class="form-select" name="userrol" id="userrol" <?php echo $userNameState; ?>>
                    <?php
                        $sql = "SELECT type FROM type WHERE NOT type = '". $type ."'ORDER BY rand();";
                        $result = $conn -> query($sql);

                            echo "<option value='" . $type . "'>" . $type . "</option>";

                        while($row = $result -> fetch_assoc()){
                            echo "<option value='" . $row["type"] . "'>" . $row["type"] . "</option>";
                        }
                    ?>               
                    </select>
                </div>

                <div class="input-group mb-3">
                    <label class="input-group-text" for="useremail">Email: </label>
                    <input class="form-control" value="<?php echo $email; ?>"  type="email" id="useremail" name="useremail" minlength="15" maxlength="70">
                </div>
                <div class="col text-center mb-3">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="sex" id="M" value="M" <?php if($sex == "M"){ echo "checked";}?> required>
                        <label class="form-check-label" for="M">M</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="sex" id="F" value="F" <?php if($sex == "F"){ echo "checked";}?>>
                        <label class="form-check-label" for="F">F</label>
                    </div>
                     <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="sex" id="O" value="O" <?php if($sex == "O"){ echo "checked";}?>>
                        <label class="form-check-label" for="O">O</label>
                    </div>
                </div>  

                <div class="m-auto">
                    <div class="form-switch mb-3" style="<?php echo $userNameLabelState;?>">
                        <input class="form-check-input" type="checkbox" id="activeuser" name="activeuser" value="yes" <?php echo $check; ?>  <?php echo $userNameState; ?>>
                        <label class="form-check-label" for="activeuser">Activo</label>
                    </div>
                    <div class="text-center">
                        <input  class="btn btn-primary" name="usersubmit" type="submit" value="Editar">
                        <a class="btn btn-secondary" href="../views/add-users.php">Regresar</a>
                    </div>
                </div>       
            </form>
        </div>                   
    </div>     
</main>

<?php
}
$conn -> close();    

//Footer of the page.
require_once ("../modules/footer.php");
?>