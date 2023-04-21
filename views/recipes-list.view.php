<?php
//Head of the page.
require_once ("views/partials/head.php");

//Navigation panel of the page
require_once ("views/partials/nav.php");

if(isset($_GET["username"])){
    $username = $_GET["username"];
}

$sql = "SELECT recipename FROM recipe WHERE username = '$username';";
$result = $conn -> query($sql);
?>

<main class="container p-4 mt-4">
    <div class="row justify-content-center table form">
        <div class="col-lg-8 col-xl-8 col-md-8 mb-5">
            <div class="text-center">
                <h3>Datos del usuario <?php echo $username;?></h3>                
            </div>
            <div class="table-responsive-md mt-2">
                <table class="table table-sm">
                    <thead>
                        <tr class="bg-primary">
                            <th scope="col">Nombre completo</th>                
                            <th scope="col">Tiempo de uso</th>
                            <th scope="col">Último acceso</th>
                            <th scope="col">Accesos</th>
                            <th scope="col">Imágenes</th>                 
                        </tr>
                    </thead>
                    <tbody>   
                    <?php 
                        $imgDirectory = "../imgs/recipes/" . $username . "/";
                        
                        //Size of the images directory
                        $files = new Directories($imgDirectory, null);                        
                        $size = $files -> directorySize();                       
                        
                        date_default_timezone_set("America/Santo_Domingo");        

                        //Recipes of each user
                        $sql = "SELECT u.created_at  as `time`, max(a.lastlogin) as `lastlogin`, concat_ws(' ', u.firstname, u.lastname) as `fullname`, count(a.userid) as `quantity` FROM users u LEFT JOIN access a on a.userid = u.userid WHERE u.username = '$username';";
                        $row = $conn -> query($sql) -> fetch_assoc();   
                        
                        $time_days = round((strtotime(date("Y-m-d H:i:s")) - strtotime($row ['time'])) / 86400);
                        $fullname = $row ['fullname'];
                        $lastlogin = date("d-m-Y g:i A", strtotime($row ['lastlogin']));
                        $accesses = $row ['quantity'];

                        if($lastlogin == "") {
                            $lastlogin = "Ninguno";
                        }

                        $html = "<tr>";     
                        $html .= "<td>" . $fullname . "</td>";                   
                        $html .= "<td>" . $time_days . " días</td>";
                        $html .= "<td>" . $lastlogin . "</td>";
                        $html .= "<td>" . $accesses . "</td>";
                        $html .= "<td>" . $size . "</td>";
                        $html .= "</tr>";
                        echo $html;
                    ?>
                    </tbody> 
                </table>
            </div>
        </div>
        <div class="col-lg-6 col-xl-6 col-md-6">
            <div class="text-center">
            <?php
                if($result -> num_rows > 0) {
                ?>
                <h3>Lista de Recetas del Usuario <?php echo $username;?></h3>
            </div>
            <ol>
            <?php
                while($row = $result -> fetch_assoc()){
                    $html = "<li>"; 
                    $html .= "<a href='/cookt/recipes?recipe=" . $row['recipename'] . "&username=" . $username . "&path=" . base64_encode(serialize($_SERVER['REQUEST_URI'])) . "'>"; 
                    $html .= $row['recipename'];
                    $html .= "</a>"; 
                    $html .= "</li>"; 
                    echo $html;    
                }
            ?>
            </ol>            
            <?php
            } else {
            ?>
            <div class="text-center col-auto">
                <p>¡El usuario <?php echo $username; ?> no ha agregado recetas aún!</p>
            </div>
            <?php
            }
            ?>
        </div>
           
    </div>
    <div class="text-center mb-4">
        <a class="btn btn-secondary" href="/cookt/user">Usuarios</a>
    </div> 
</main>
<?php
$conn -> close();
//Footer of the page.
require_once ("views/partials/footer.php");
?>
