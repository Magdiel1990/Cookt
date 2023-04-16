<?php
//Including the database connection.
require_once ("config/db_Connection.php");

//Head of the page.
require_once ("views/partials/head.php");

//Navigation panel of the page
require_once ("views/partials/nav.php");

//Models.
require_once ("models/models.php");

if(isset($_GET["recipe"]) && isset($_GET["username"]) && isset($_GET["path"])){
    $recipe = $_GET["recipe"];
    $username = $_GET["username"];
    
    if($_GET["path"] == "index"){
        $pathToReturn = "/cookt/";        
    } else if (isset($_GET["ingredients"])) {
        $ingArray = $_GET["ingredients"];
        $pathToReturn = unserialize(base64_decode($_GET["path"])) . "?ingredients=". $ingArray ."&username=" . $username;

    } else {
        $pathToReturn = unserialize(base64_decode($_GET["path"])) . "?username=" . $username;
    }

    $imageDir = "imgs/recipes/" . $username . "/";

    $files = new Directories($imageDir, $recipe);
    $recipeImageDir = $files -> directoryFiles();
    
    $sql = "SELECT r.recipeid, 
    r.recipename,
    concat_ws(' ', ri.quantity, ri.unit, 'de' , i.ingredient, ri.detail) as indications, 
    r.cookingtime,
    r.date, 
    r.preparation, 
    c.category, 
    r.username
    from recipe r 
    join recipeinfo ri 
    on ri.recipeid = r.recipeid
    join categories c 
    on r.categoryid = c.categoryid
    join ingredients i 
    on i.id = ri.ingredientid
    WHERE r.recipename = '$recipe' 
    AND r.username = '$username';";

    $result = $conn -> query($sql);
    $num_rows = $result -> num_rows;
    $row = $result->fetch_assoc();
}
?>
<main class="container mt-4">
    <?php
    if(isset($row["category"]) && isset($row["recipename"]) && isset($row["cookingtime"]) && isset($row["preparation"]) && isset($row["date"])) {
    $category = $row["category"];
    $recipeName = $row["recipename"];
    $cookingTime = $row["cookingtime"];
    $preparation = $row["preparation"];
    $day = $date = date ("d", strtotime($row["date"]));     
    $month = date ("M", strtotime($row["date"]));

    //Function to convert to spanish months
    $timeConvertor = new TimeConvertor ($month);
    $spanishMonth = $timeConvertor -> spanishMonth();  
    
    $year = $date = date ("Y", strtotime($row["date"]));

    $date = $day . "/" . $spanishMonth . "/" .  $year;

    $categoryDir = "imgs/categories/";

    //Function to get the image directory from the category
    $files = new Directories($categoryDir , $category);
    $categoryImgDir = $files -> directoryFiles();

    ?>
    <div class="my-5" style="background: url('<?php echo $categoryImgDir; ?>') center; background-size: auto;">
        <div class="row m-auto">
            <div class="d-flex flex-column justify-content-center align-items-center jumbotron">

                <div class="bg-form p-2 my-4 col-lg-9 col-xl-9">
                    <div class="text-center">
                        <h1 class="display-4 text-info"> <?php echo $recipeName; ?> </h1>
                        <h5 class="text-warning" style='font-size: 1.5rem;'> (<?php echo $cookingTime; ?> minutos)</h5>
                    </div>
                    <div class="my-4">
                        <div class="text-center">
                            <img src="<?php echo $recipeImageDir?>" alt="Imangen de la receta" style="width:auto;height:11rem;">
                        </div> 
                        <ul class="lead py-4"> 
                        <?php            
                        $result = $conn -> query($sql);
                        
                        while($row = $result->fetch_assoc()){
                            echo "<li class='text-success' style='font-size: 1.5rem;'>" . $row["indications"]. ".</li>";
                        }        
                        ?>   
                        </ul>
                        <?php
                            if(file_exists($recipeImageDir)) {
                        ?>                         
                        <?php
                            }
                        ?>
                    </div>
                    <hr class="my-4">
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
                            <div class="card card-body text-dark" style="font-size: 1.5rem;"> <?php echo ucfirst($preparation); ?> 
                                <span class="text-info mt-4 text-center"> <?php echo $date; ?> </span>            
                            </div>
                        </div>        
                    </div> 
                </div>       
            </div>
        </div>
    </div>
    <?php
    } else {
        http_response_code(404);

        require "views/error_pages/error.php";
    } 
    ?>
</main>
<?php
$conn -> close();
//Footer of the page.
require_once ("views/partials/footer.php");
?>
