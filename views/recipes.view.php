<?php
//Head
    require_once ("views/partials/head.php");
    
//Nav
    require_once ("views/partials/nav.php");

//Recipe and username    
if(isset($_GET["recipe"]) && isset($_GET["username"])){  
    $recipe = $_GET["recipe"];
    $username = $_GET["username"];  

//If the page requesting the recipes is user-recipes, return the parameter username too    
    if($_SESSION["location"] == root . "user-recipes") {
        $_SESSION["location"] = root . "user-recipes?username=" . $username;
    }

//Recipe image directory
    $imageDir = "imgs/recipes/" . $username . "/";

//Recipe image file    
    $files = new Directories($imageDir, $recipe);
    $ext = $files -> directoryFiles();

    if($ext !== null) {
        $recipeImageDir = $imageDir . $recipe . "." . $ext;
    } else {
        $recipeImageDir = "";
    }

    $sql = "SELECT r.recipeid, 
    r.recipename,
    r.ingredients, 
    r.cookingtime,
    r.date, 
    r.preparation, 
    c.category, 
    r.username
    from recipe r     
    join categories c 
    on r.categoryid = c.categoryid    
    WHERE r.recipename = ?
    AND r.username = ? AND r.state = 1;";

    $stmt = $conn -> prepare($sql); 
    $stmt->bind_param("ss", $recipe, $username);
    $stmt->execute();

    $result = $stmt -> get_result(); 
    $num_rows = $result -> num_rows;   

    if($num_rows > 0){
    $row = $result->fetch_assoc();

    $category = $row["category"];
    $ingredients = $row["ingredients"];

//Split the ingredients separated by semicolon
    $arrayIngredients = explode(".rn", $ingredients);

    $recipeName = $row["recipename"];
    $cookingTime = $row["cookingtime"];
    $preparation = $row["preparation"];

//Replace the rn (salto de línea) by ""
    $preparation = str_replace('rn', ' ', $preparation);

//Day, month and year of the day the recipe was added
    $day = $date = date ("d", strtotime($row["date"]));     
    $month = date ("M", strtotime($row["date"]));
    $year = $date = date ("Y", strtotime($row["date"]));

//Object to convert to spanish months
    $timeConvertor = new TimeConvertor ($month);
    $spanishMonth = $timeConvertor -> spanishMonth();     

//Date format
    $date = $day . "/" . $spanishMonth . "/" .  $year;

//Category images location
    $categoryDir = "imgs/categories/";

//Object to get the image directory from the category
    $files = new Directories($categoryDir , $category);
    $ext = $files -> directoryFiles();
    
    if($ext !== null) {
        $categoryImgDir = $categoryDir . $category . "." . $ext;
    } else {
        $categoryImgDir = "";
    }
?>
<main class="container mt-4">
    <?php
//Messages
        if(isset($_SESSION['message'])){
        echo "<h2 class='my-1 text-center'>";

        $message = new Messages ($_SESSION['message'], $_SESSION['message_alert']);
        echo $message -> textMessage();  

//Unsetting the messages variables so the message fades after refreshing the page.
        unset($_SESSION['alert'], $_SESSION['message']);       

        echo "</h2>";
        } else {
            echo "<div class='mt-4'></div>";
        }
    ?>   
    <div class="my-5" style="background: url('<?php echo $categoryImgDir; ?>') center; background-size: auto;">
        <div class="row m-auto">
            <div class="d-flex flex-column justify-content-center align-items-center jumbotron">
<!-- Ingredients-->
                <div class="form p-3 my-4 col-11">
                    <div class="text-center">
                        <h1 class="display-4 text-info"> <?php echo $recipeName; ?> </h1>
                        <h5 class="text-warning" style='font-size: 1.5rem;' title="duración"> (<?php echo $cookingTime; ?> minutos)</h5>
                    </div>
                    <div class="mt-4">
                        <div class="text-center">
                            <img src="<?php echo $recipeImageDir;?>" alt="Imangen de la receta" style="width:auto;height:11rem;">
                        </div> 
                        <div class="pt-4">                           
                        <?php 
//Ingredients list                        
                            $html = "<ul>";
                            for($i = 0; $i<count($arrayIngredients); $i++){
                                $html .= "<li id='ingredient'>" . $arrayIngredients[$i] . "</li>";
                            }
                            $html .= "</ul>";
                            echo $html;                         
                        ?> 
                        </div>
                    </div>
                    <hr class="my-3">
                </div>
<!-- Link-->                 
                <div class="row justify-content-center">                   
                    <div class="col-auto btn-group" role="group">
                        <a href='<?php echo root . "edit?recipename=" . $row['recipename'] . "&username=" . $_SESSION['username']; ?>' class='btn btn-secondary' title='Editar'>Editar</a>
                        <a href='<?php echo root . "delete?recipename=" . $row['recipename']; ?>' class='btn btn-danger' title='Eliminar' onclick='deleteMessage()' id='recipe'>Eliminar</a>
                        <a href="<?php echo $_SESSION["location"];?>" class="btn btn-info"  title='Regresar'>Regresar</a>
                    </div>
                     <div class="col-auto btn-group" role="group">
                        <a class="btn btn-primary dropdown-toggle" data-toggle="collapse" href="#collapse" role="button" aria-expanded="false" aria-controls="collapse" title='Preparación'>Preparación</a>
                        <a class='btn btn-warning dropdown-toggle' data-toggle="collapse" href="#share" role="button"  aria-expanded="false" aria-controls="share" title='Compartir'>Compartir</a>
                    </div>
                </div>
<!-- Email box-->                 
                <div class="row mt-4">
                    <div class="col-12 collapse" id="share">
                        <div class="recovery-form">                            
                            <form method="POST" action="<?php echo root . 'share?recipe=' . base64_encode(serialize($recipe)) . '&username=' . $username;?>" class="text-center" id="share_form">
                                <label class="form-label mb-2" for="email">Email:</label>
                                <div class="input-group mb-3">                                
                                    <input type="email" id="email" class="form-control" name="email" placeholder="Escribe el correo electrónico" minlength="15" maxlength="70" required/>
                                    <button type="submit" form="share_form" name="share" class="btn btn-primary"><i class="fa-solid fa-share"></i></button>
                                </div>
                            </form>                               
                        </div>
                    </div> 
                </div>
<!-- Preparation-->                        
                <div class="col-11 py-4">
                    <div class="collaps" id="collapse">
                        <div class="card card-body text-dark p-4" style="font-size: 1.5rem; text-align: justify;" title="preparación"> <?php echo ucfirst($preparation); ?> 
                            <span class="text-info mt-4 text-center" title="fecha"> <?php echo $date; ?> </span>            
                        </div>
                    </div>        
                </div>        
            </div>
        </div>
    </div>   
</main>
<script>
mailValidation(); 

//Mail format validation
function mailValidation(){

    var form = document.getElementById("share_form");    

    form.addEventListener("submit", function(event) {
        var email = document.getElementById("email");

        if(email.value == "") {
            event.preventDefault();                
            confirm ("¡Escriba el email!");                                
            return false;
        }

        if(email.length < 15 || email.length > 70) {
            event.preventDefault();
            confirm("¡Longitud de email incorrecta!");               
            return false;                
        }     
        return true;               
    })
}

//Delete message
function deleteMessage(){
    var deleteButtons = document.getElementById("recipe");
  
    if(confirm("¿Desea eliminar esta receta?")) {
        return true;
    } else {
        event.preventDefault();
        return false;
    }
}
</script>
<?php
//Exiting connection
    $conn -> close();

//Footer
    require_once ("views/partials/footer.php");

    } else {
        http_response_code(404);

        require "views/error_pages/404.php";
    } 
}
?>