<?php
//Head of the page.
require_once ("views/partials/head.php");

//Navigation panel of the page
require_once ("views/partials/nav.php");

?>

<?php
/************************************************************************************************/
/******************************************CATEGORY EDITION CODE***********************************/
/************************************************************************************************/


if(isset($_GET['categoryid'])){
$categoryId = $_GET['categoryid'];

//Verify the category existance
$sql = "SELECT * FROM categories WHERE categoryid = '$categoryId';";

$result = $conn -> query($sql);

if($result -> num_rows > 0) {
    $row =  $result -> fetch_assoc();
    $category = $row["category"];
} else {
    require ("views/error_pages/404.php");
    die();
}
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
            <form id="category_form" enctype="multipart/form-data" class="form card card-body" action="update?categoryid=<?php echo $categoryId; ?>" method="POST">

                <div class="input-group mb-3">
                    <label class="input-group-text is-required" for="categoryName">Nombre: </label>
                    <input type="text" name="categoryName" value="<?php echo $category;?>" class="form-control" id="categoryName" pattern="[a-zA-Z áéíóúÁÉÍÓÚñÑ]+" maxlength="20" minlength="2" required>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="categoryImage">Foto de la categoría</label>
                    <input type="file" name="categoryImage" accept=".png, .jpeg, .jpg, .gif" class="form-control" id="categoryImage">
                </div> 
                <div class="mt-2">
                    <input class="btn btn-primary" type="submit" value="Editar" name="categoryeditionsubmit">
                    <a href= "<?php echo root;?>categories" class="btn btn-secondary">Regresar</a>
                </div>
                </form>
                <script>
                formValidation();   
               
//Image format validation
                function formValidation(){

                    var form = document.getElementById("category_form");    

                    form.addEventListener("submit", function(event) { 
//Accepted formats            
                        var ext=/(.jpg|.JPG|.jpeg|.JPEG|.png|.PNG)$/i;
                        var regExp = /[a-zA-Z\t\h]+|(^$)/;
                        var categoryImageInput = document.getElementById('categoryImage');
                        var categoryImage = categoryImageInput.value;                            
                        var categoryNameInput = document.getElementById('categoryName');
                        var categoryName = categoryNameInput.value;
                        var allowedImageTypes = ["image/jpeg", "image/gif", "image/png", "image/jpg"];  

                        if(categoryName == "") {
                            event.preventDefault();                
                            confirm ("¡Escriba el nombre de la categoría!");                                
                            return false;
                        }

                        if(categoryName.length < 2 || categoryName.length > 20) {
                            event.preventDefault();
                            confirm("¡Longitud de categoría incorrecta!");               
                            return false;                
                        }
                        
                        if(!categoryName.match(regExp)){ 
                            event.preventDefault();                
                            confirm ("¡Nombre de categoría incorrecto!");                                
                            return false;
                        }

                        if (categoryImage != "") {
                            var file = categoryImageInput.files[0];                   
                            var fileType = file.type;  
//Weight of the file                        
                            var weight = file.size;
//Size in Bytes     
                            if(weight > 300000) {
                                event.preventDefault();
                                confirm ("¡El tamaño de la imagen debe ser menor que 300 KB!");  
                                return false;
                            }       
//Image format validation
                            if(!allowedImageTypes.includes(fileType)){
                                event.preventDefault();
                                confirm ("¡Formatos de imagen admitidos: jpg, png y gif!");
                                return false;
                            }                            
                        }
                        return true;               
                    })
                }
                </script>    
            </div>
       </div>                  
    </div>     
</main>

<?php
}
/************************************************************************************************/
/******************************************RECIPE EDITION CODE***********************************/
/************************************************************************************************/


if(isset($_GET['recipename']) || isset($_GET['username'])) {
$recipeName = isset($_GET['recipename']) ? $_GET['recipename'] : "";
$userName = isset($_GET['username']) ? $_GET['username'] : "";

    if($recipeName != "" && $userName != "") {
    $sql = "SELECT r.recipeid, 
    r.recipename,
    r.cookingtime, 
    r.ingredients, 
    r.preparation,
    c.category
    from recipe r 
    join categories c 
    on r.categoryid = c.categoryid
    WHERE r.recipename = '$recipeName' 
    AND r.username = '$userName';";

    $row = $conn -> query($sql) -> fetch_assoc();

    if(isset($row["cookingtime"]) && isset($row["ingredients"]) && isset($row["preparation"]) && isset($row["category"])) {
        $cookingTime = $row["cookingtime"];
        $ingredients = $row["ingredients"];
        $preparation = $row["preparation"];        
        $ingredients = $row["ingredients"];
        $category = $row["category"];
    } else {
        header('Location: ' . root . 'error404');
        exit;
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
            <div class="form card card-body">
                <form id="recipe_form" enctype="multipart/form-data" action="update?editname=<?php echo $recipeName;?>&username=<?php echo $userName;?>" method="POST">

                    <div class="input-group mb-3">
                        <label class="input-group-text is-required" for="newRecipeName">Nombre: </label>
                        <input type="text" name="newRecipeName" value="<?php echo $recipeName;?>" class="form-control" id="newRecipeName" pattern="[a-zA-Z áéíóúÁÉÍÓÚñÑ]+" maxlength="50" minlength="7" required>
                    </div>

                    <div class="input-group mb-3">
                        <label class="input-group-text" for="imageUrl">Url de la imagen</label>
                        <input class="form-control"  accept=".png, .jpeg, .jpg, .gif" type="url" name="imageUrl" id="imageUrl" placeholder="Formatos: jpg, png y gif" maxlength="150" minlength="20">
                    </div>

                    <div class="frame">
                        <div class="dropzone">
                            <img src="http://100dayscss.com/codepen/upload.svg" class="upload-icon" />
                            <input type="file" name="recipeImage" accept=".png, .jpeg, .jpg, .gif" class="upload-input form-control" id="recipeImage"/>
                        </div>                       
                    </div>   
                    
                    <div id="imgMessage" class="text-center"></div>

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
                        <label for="ingredients" class="form-label is-required">Ingredientes: </label>
                        <textarea class="form-control" name="ingredients" id="ingredients" cols="10" rows="10" required><?php echo $ingredients;?></textarea>
                    </div>     
                            
                    <div class="mb-3 text-center">
                        <label  class="form-label is-required" for="preparation">Preparación: </label>
                        <textarea name="preparation"  cols="10" rows="10" class="form-control" id="preparation" required><?php echo $preparation;?></textarea>
                    </div>
                                      
                    <div class="mb-3 text-center">
                        <input class='btn btn-primary' type="submit" name="edit" value="Actualizar"> 
                        <a href="<?php echo root;?>" class='btn btn-secondary' title="Regresar"><i class="fa-solid fa-right-from-bracket"></i></a>  
                    </div>
                </form>
            </div>
        </div>                 
    </div> 
    <script>
    pictureData();
    imgValidation();   

 //Format for the message showing the name of the picture uploaded
    function pictureData() {
        var recipeImage = document.getElementById('recipeImage');
        var imgMessage = document.getElementById('imgMessage');

        recipeImage.onchange = function () {    
            imgMessage.innerHTML = recipeImage.files[0].name;
        }
//Margin for the name of the image to be uploaded                    
        imgMessage.style.marginBottom = "15px";
        imgMessage.style.marginTop = "5px";
    }

//Image format validation
    function imgValidation(){

        var form = document.getElementById("recipe_form");    

        form.addEventListener("submit", function(event) { 
//Accepted formats            
            var ext=/(.jpg|.JPG|.jpeg|.JPEG|.png|.PNG)$/i;
            var regExp = /[a-zA-Z\t\h]+|(^$)/;
            var imageUrlInput = document.getElementById('imageUrl');
            var imageUrl = imageUrlInput.value;
            var recipeImage = document.getElementById('recipeImage');            
            var cookingTimeInput = document.getElementById('cookingTime');
            var cookingTime = cookingTimeInput.value;
            var ingredients = document.getElementById("ingredients").value;
            var preparation = document.getElementById("preparation").value;
            var newRecipeName = document.getElementById("newRecipeName").value;               

            if(newRecipeName == "" || preparation == "" || ingredients == ""){
                event.preventDefault();                
                confirm("Completar los campos requeridos");                     
                return false;
            }

            if(!newRecipeName.match(regExp)){ 
                event.preventDefault();                
                confirm ("¡Nombre de receta incorrecto!");                                
                return false;
            }

            if(cookingTime <= 5 && cookingTime >= 180) {
                event.preventDefault();
                confirm("¡Tiempo de cocción incorrecto!");
                cookingTimeInput.focus();                
                return false;                
            }

            if (imageUrl != "") {
//Weight of the file                        
                var weight = imageUrl.size;
//Size in Bytes     
                if(weight > 300000) {
                    event.preventDefault();
                    confirm("¡El tamaño de la imagen debe ser menor que 300 KB!");  
                    return false;
                }     
                
                if (!ext.exec(imageUrl)){
                    event.preventDefault();
                    confirm("¡Formatos de imagen admitidos: jpg, png y gif!");
                    return false;
                }
            } else if (recipeImage.value != "") {
//File type                
                var file = recipeImage.files[0]; 
                var fileType = file.type;
//Weight of the file                        
                var weight = file.size;                
//Size in Bytes     
                if(weight > 300000) {
                    event.preventDefault();
                    confirm("¡El tamaño de la imagen debe ser menor que 300 KB!");  
                    return false;
                }       
//Image format validation
                if(!ext.exec(fileType)){
                    event.preventDefault();
                    confirm("¡Formatos de imagen admitidos: jpg, png y gif!");
                    return false;
                }
//No image added               
            } else {
                alert("¡Ninguna imagen agregada para esta receta!");  
            }
            
            return true;
        })
    }
    </script>    
</main>
<?php
    } else {
        header('Location: ' . root . 'error404');
        die();
    }
}
/************************************************************************************************/
/********************************************USER EDITION CODE***********************************/
/************************************************************************************************/


if(isset($_GET['userid'])) {
$userId = $_GET['userid'];

$sql = "SELECT userid FROM users WHERE type = 'Admin' AND userid = " . $_SESSION["userid"] . ";";
$result = $conn -> query($sql);
$num_rows  = $result -> num_rows;

    if($num_rows > 0) {
    $sql = "SELECT * FROM users WHERE userid = '$userId';";
    $result = $conn -> query($sql);
    $num_rows = $result -> num_rows;

        if($num_rows > 0){
            $row = $result -> fetch_assoc();

            $userName = $row["username"];
            $firstName=  $row["firstname"];
            $lastName=  $row["lastname"];
            $type = $row["type"];
            $state = $row["state"];
            $email = $row["email"];
            $currentPassword = $row["password"];
            $sex = $row["sex"];

            if($num_rows == 1 && $_SESSION['username'] == $userName){
                $userNameState = "hidden";
                $userNameLabelState = "display: none;";    
            } else {
                $userNameState = $userNameLabelState = "";
            }            

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
        <h3 class="text-center">Editar Usuario</h3>     
        <div class="mt-3 col-auto">
            <form id="user_form" class="form card card-body" enctype="multipart/form-data" action="update?userid=<?php echo $userId; ?>" method="POST">

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
                    <input class="form-control" type="password" id="current_password" name="current_password"  maxlength="50" minlength="8">
                </div>      
                
                <div class="input-group mb-3">
                    <label class="input-group-text" for="new_password">Nueva contraseña: </label>
                    <input class="form-control" type="password" id="new_password" name="new_password"  maxlength="50" minlength="8">
                </div>   

                <div class="input-group mb-3">
                    <label class="input-group-text" for="repite_password">Repite nueva contraseña: </label>
                    <input class="form-control" type="password" id="repite_password" name="repite_password" maxlength="50" minlength="8">
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

                <div class="frame-edit mb-2">
                    <label class="form-label" for="profile">Foto</label>                    
                    <div class="dropzone">                                     
                        <img src="http://100dayscss.com/codepen/upload.svg" class="upload-icon"/>                        
                        <input  class="upload-input form-control" id="profile" type="file" name="profile" accept=".png, .jpeg, .jpg, .gif"/>
                    </div>                       
                </div>     

                <div class="text-center" id="imgMessage"></div>

                <div class="input-group mb-3">
                    <label class="input-group-text is-required" for="useremail">Email: </label>
                    <input class="form-control" value="<?php echo $email; ?>"  type="email" id="useremail" name="useremail" minlength="15" maxlength="70" required>
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
                        <a class="btn btn-secondary" href="<?php echo $_SESSION["location"];?>">Regresar</a>
                    </div>
                </div>       
            </form>
            <script>
            pictureData(); 
            formValidation();   

//Format for the message showing the name of the picture uploaded
                function pictureData() {
                    var profile = document.getElementById('profile');
                    var imgMessage = document.getElementById('imgMessage');

                    profile.onchange = function () {    
                        imgMessage.innerHTML = profile.files[0].name;
                    }
//Margin for the name of the image to be uploaded                    
                    imgMessage.style.marginBottom = "15px";
                    imgMessage.style.marginTop = "5px";
                }

//Image format validation
                function formValidation(){

                    var form = document.getElementById("user_form");    

                    form.addEventListener("submit", function(event) { 
//Accepted formats            
                        var ext=/(.jpg|.JPG|.jpeg|.JPEG|.png|.PNG)$/i;
                        var regExp = /[a-zA-Z\t\h]+|(^$)/;
                        var firstname = document.getElementById("firstname").value;
                        var lastname = document.getElementById("lastname").value;
                        var username = document.getElementById("username").value;
                        var current_password = document.getElementById("current_password").value;
                        var new_password = document.getElementById("new_password").value;
                        var repite_password = document.getElementById("repite_password").value;
                        var profile = document.getElementById("profile");
                        var sex = document.getElementsByName("sex");    
                        var useremail = document.getElementById("useremail").value;  

//Verify if an option of the radio input has been chosen                        
                        for (var s of sex) {
                            if (s.checked) {
                                sex = s.value;
                            }
                        }

                    if (profile.value != "") {
//File type                
                        var file = profile.files[0]; 
                        var fileType = file.type;
//Weight of the file                        
                        var weight = file.size;                
//Size in Bytes     
                        if(weight > 300000) {
                            event.preventDefault();
                            confirm("¡El tamaño de la imagen debe ser menor que 300 KB!");  
                            return false;
                        }       
//Image format validation
                        if(!ext.exec(fileType)){
                            event.preventDefault();
                            confirm("¡Formatos de imagen admitidos: jpg, png y gif!");
                            return false;
                        }             
                    }

                    if(firstname == "" || lastname == "" || username == "" || sex == "" || useremail == "") {
                        event.preventDefault();                        
                        confirm ("¡Completar los campos requeridos!");             
                        return false;
                    }

                    if(current_password != "" && new_password != "" && repite_password != ""){
                        if(current_password.length < 8 || current_password.length > 50){
                            event.preventDefault();
                            confirm ("¡La contraseña debe tener de 8 a 50 caracteres!");                 
                            return false;
                        }

                        if(new_password.length < 8 || new_password.length > 50){
                            event.preventDefault();
                            confirm ("¡La contraseña debe tener de 8 a 50 caracteres!");                 
                            return false;
                        }

                        if(repite_password.length < 8 || repite_password.length > 50){
                            event.preventDefault();
                            confirm ("¡La contraseña debe tener de 8 a 50 caracteres!");                 
                            return false;
                        }
                        if(new_password !== repite_password){
                            event.preventDefault();
                            confirm ("¡Contraseñas nuevas no coinciden!");
                            return false;
                        }                     
                    }
//Regular Expression    
                    if(!firstname.match(regExp) || !lastname.match(regExp) || !username.match(regExp)){
                        event.preventDefault();
                        confirm ("¡Nombre, apellido o usuario incorrecto!");                 
                        return false;
                    }

                    if(firstname.length < 2 || firstname.length > 30){
                        event.preventDefault();
                        confirm ("¡El nombre debe tener de 2 a 30 caracteres!");                 
                        return false;
                    } 

                    if(lastname.length < 2 || lastname.length > 40){
                        event.preventDefault();
                        confirm ("¡El apellido debe tener de 2 a 40 caracteres!");                 
                        return false;
                    }

                    if(username.length < 2 || username.length > 30){
                        event.preventDefault();
                        confirm ("¡El usuario debe tener de 2 a 30 caracteres!");                 
                        return false;
                    }                   
                    
                    if(useremail.length < 15 || useremail.length > 70){
                        event.preventDefault();                        
                        confirm ("¡El email debe tener de 15 a 70 caracteres!");                 
                        return false;
                    }                
                    return true;          
                    })
                }
            </script>
        </div>                   
    </div>     
</main>
<?php
        } else {
            header('Location: ' . root . 'error404');
            die();
        }
    } else {
            header('Location: ' . root . 'error404');
            die();
    }
}
$conn -> close();    

//Footer of the page.
require_once ("views/partials/footer.php");
?>