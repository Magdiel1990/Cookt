<?php
//Head
require_once ("views/partials/head.php");

//Nav
require_once ("views/partials/nav.php");

//Messages
    if(isset($_SESSION['message'])){
    $message = new Messages ($_SESSION['message'], $_SESSION['message_alert']);
    echo $message -> buttonMessage();           

//Unsetting the messages
    unset($_SESSION['message_alert'], $_SESSION['message']);
    }

$result = $conn -> query("SELECT r.recipename, r.recipeid FROM recipe r JOIN categories c ON r.categoryid = c.categoryid WHERE username = '" . $_SESSION['username'] . "' AND r.state = 1 AND c.state = 1 ORDER BY rand() LIMIT 21;"); 
$num_rows = $result -> num_rows;
?>
<main class="container p-4">

<?php
    if($num_rows < 21) {
?>
    <div class="row justify-content-center text-center mt-4">  
        <p>Agregue más recetas para elaborar la dieta</p>
        <a class="btn btn-success col-auto" href="<?php echo root. "add-recipe"; ?>">Agregar</a>
    </div>
<?php
   } else {
    $daysNames = ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo"]
?>
    <div class="row p-4">  
        <div class="table-responsive mt-4">
            <table class="table text-center">
                <thead class="text-light">       
                    <tr>
                    <?php
                        for($i = 0; $i < count($daysNames); $i++) {
                            echo "<th scope='col'><h4>" . $daysNames[$i] . "</h4></th>";
                        }
                    ?>    
                    </tr>
                </thead>
                <tbody>
                    <?php
//Users recipes                     
                        $recipes = [];
                        while($row = $result -> fetch_array()) {
                            $recipes[] = $row [0]; 
                        }                         
//Recipes chunk
                        $sliceRecipes = (array_chunk($recipes,7));
               
                        for($i = 0; $i < count($sliceRecipes); $i++) {
                            echo "<tr>";
                            for($j = 0; $j < count($sliceRecipes[$i]); $j++) {
                                echo "<td><a class='p-2 my-2' id='tlink' href='recipes?recipe=" . $sliceRecipes [$i][$j] . "&username=" . $_SESSION['username']. "'>" . $sliceRecipes [$i][$j] . "</a></td>";
                            } 
                            echo "</tr>"; 
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
<?php
    }
?>
</main>
<?php
require_once ("views/partials/footer.php");

//Exiting connection
$conn -> close();
?>
