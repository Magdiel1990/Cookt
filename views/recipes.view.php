<?php
//Head of the page.
require_once ("views/partials/head.php");

//Navigation panel of the page
require_once ("views/partials/nav.php");





//Editar parametros






if(isset($_GET["recipe"]) && isset($_GET["username"]) && isset($_GET["path"])){
    $recipe = $_GET["recipe"];
    $username = $_GET["username"];
    $path = $_GET["path"];
    $decodedPath = unserialize(base64_decode($path));

    if($decodedPath == "index"){
        $pathToReturn = "/";        
    } else if (isset($_GET["ingredients"])) {
        $ingArray = $_GET["ingredients"];
        $pathToReturn = $decodedPath . "?ingredients=". $ingArray ."&username=" . $username;
    } else if ($decodedPath == "/custom" || $decodedPath == "/random" || $decodedPath == "/user"){
        $pathToReturn = $decodedPath . "?username=" . $username;
    } else {
        $pathToReturn = "/";
    }

    $imageDir = "imgs/recipes/" . $username . "/";

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
    $recipeName = $row["recipename"];
    $cookingTime = $row["cookingtime"];
    $preparation = $row["preparation"];
    $day = $date = date ("d", strtotime($row["created_at"]));     
    $month = date ("M", strtotime($row["created_at"]));

    //Function to convert to spanish months
    $timeConvertor = new TimeConvertor ($month);
    $spanishMonth = $timeConvertor -> spanishMonth();  

    $year = $date = date ("Y", strtotime($row["created_at"]));

    $date = $day . "/" . $spanishMonth . "/" .  $year;

    $categoryDir = "imgs/categories/";

    //Function to get the image directory from the category
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
                        <div class="pt-4"><?php echo $ingredients; ?> </div>
                        <?php
                            if(file_exists($recipeImageDir)) {
                        ?>                         
                        <?php
                            }
                        ?>
                    </div>
                    <hr class="my-3">
                </div>

                <div class="p-2 col-lg-9 col-xl-9">
                    <div class="lead text-center">
                        <a class="btn btn-primary" data-toggle="collapse" href="#collapse" role="button" aria-expanded="false" aria-controls="collapseExample">
                        Preparación
                        </a>
                        <a class="btn btn-secondary" href="<?php echo $pathToReturn;?>">Regresar</a>
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
    } else {
        http_response_code(404);

        require "views/error_pages/404.php";
    } 
}

$conn -> close();
//Footer of the page.
require_once ("views/partials/footer.php");
?>