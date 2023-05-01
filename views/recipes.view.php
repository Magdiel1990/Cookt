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
    if($_SESSION["location"] == "/user-recipes") {
        $_SESSION["location"] = "/user-recipes?username=" . $username;
    }

//Recipe image directory
    $imageDir = "imgs/recipes/" . $username . "/";

//Recipe image file    
    $files = new Directories($imageDir, $recipe);
    $recipeImageDir = $files -> directoryFiles();
    
    $sql = "SELECT r.recipeid, 
    r.recipename,
    r.ingredients, 
    r.cookingtime,
    r.created_at, 
    r.preparation, 
    c.category, 
    r.username
    from recipe r     
    join categories c 
    on r.categoryid = c.categoryid    
    WHERE r.recipename = '". $recipe . "' 
    AND r.username = '" . $username . "';";

    $result = $conn -> query($sql);
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
    $day = $date = date ("d", strtotime($row["created_at"]));     
    $month = date ("M", strtotime($row["created_at"]));
    $year = $date = date ("Y", strtotime($row["created_at"]));

//Object to convert to spanish months
    $timeConvertor = new TimeConvertor ($month);
    $spanishMonth = $timeConvertor -> spanishMonth();     

//Date format
    $date = $day . "/" . $spanishMonth . "/" .  $year;

//Category images location
    $categoryDir = "imgs/categories/";

//Object to get the image directory from the category
    $files = new Directories($categoryDir , $category);
    $categoryImgDir = $files -> directoryFiles();
?>
<main class="container mt-4">
    <div class="my-5" style="background: url('<?php echo $categoryImgDir; ?>') center; background-size: auto;">
        <div class="row m-auto">
            <div class="d-flex flex-column justify-content-center align-items-center jumbotron">

                <div class="bg-form p-2 my-4 col-lg-9 col-xl-9">
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
                                $html .= "<li>" . $arrayIngredients[$i] . "</li>";
                            }
                            $html .= "</ul>";
                            echo $html;                         
                        ?> 
                        </div>
                    </div>
                    <hr class="my-3">
                </div>

                <div class="p-2 col-lg-9 col-xl-9">
                    <div class="lead text-center">
                        <a class="btn btn-primary" data-toggle="collapse" href="#collapse" role="button" aria-expanded="false" aria-controls="collapseExample">
                        Preparación
                        </a>
                        <a class="btn btn-secondary" href="<?php echo $_SESSION["location"];?>">Regresar</a>
                    </div>
                    <div class="py-4">
                        <div class="collapse bg-form" id="collapse">
                            <div class="card card-body text-dark" style="font-size: 1.5rem;" title="preparación"> <?php echo ucfirst($preparation); ?> 
                                <span class="text-info mt-4 text-center" title="fecha"> <?php echo $date; ?> </span>            
                            </div>
                        </div>        
                    </div> 
                </div>       
            </div>
        </div>
    </div>
</main>
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