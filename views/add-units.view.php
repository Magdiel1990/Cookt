<?php
//Including the database connection.
require_once ("config/db_Connection.php");

//Models.
require_once ("models/models.php");

//Head of the page.
require_once ("views/partials/head.php");

?>

<?php
if($_SESSION['type'] != 'Admin') { 
    require_once ("views/error_pages/404.php");
    exit;
}

//Navigation panel of the page
require_once ("views/partials/nav.php");
?>

<main class="container p-4">
    <div class="row mt-2 text-center justify-content-center">
    <?php
//Messages that are shown in the add_units page
        if(isset($_SESSION['message'])){
        $message = new Messages ($_SESSION['message'], $_SESSION['message_alert']);
        echo $message -> buttonMessage();            

//Unsetting the messages variables so the message fades after refreshing the page.
        unset($_SESSION['message_alert'], $_SESSION['message']);
        }
    ?>
    <h3>Agregar Unidades</h3>
<!--Form for filtering the database info-->
        <form class="mt-3 col-auto" method="POST" action="/cookt/create" autocomplete="on" onsubmit="return validation('add_units', /[a-zA-Z\t\h]+|(^$)/)">
            <div class="input-group mb-3">
                <label class="input-group-text is-required" for="add_units">Unidad: </label>
                <input class="form-control" type="text" id="add_units" name="add_units"  pattern="[a-zA-Z áéíóúÁÉÍÓÚñÑ]+" minlength="2" maxlength="50" autofocus required>
                <input  class="btn btn-success" type="submit" value="Agregar">
            </div>
        </form>
    </div>
    <div class="table-responsive-sm mt-4">
        <table class="table table-sm">
            <thead>
                <tr class="bg-primary">
                    <th scope="col">Unidades</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>                
                <?php
                    $result = new Units(null);
                    $result = $result -> unitQuery();

                    $unitCount = new Units(null);
                    $unitCount = $unitCount -> unitCount();

                    if($unitCount > 0){
                        while($row = $result -> fetch_assoc()){
                            $html = "<tr>";
                            $html .= "<td>" . ucfirst($row['unit']) . "</td>";
                            $html .= "<td>";
                            $html .= "<a href='/cookt/delete?unitname=" . $row['unit'] . "' " . "class='btn btn-outline-danger' title='Eliminar'><i class='fa-solid fa-trash'></i></a>";
                            $html .= "</td>";
                            $html .= "</tr>";
                            echo $html;
                        }
                    } else {
                        $html = "<tr>";
                        $html .= "<td colspan='2'>";
                        $html .= "Agrega las unidades...";
                        $html .= "</td>";
                        $html .= "</tr>";
                        echo $html;      
                    }                  
                ?>                
            </tbody>
        </table>
    </div>
</main>
<?php
$conn -> close();
//Footer of the page.
require_once ("views/partials/footer.php");
?>