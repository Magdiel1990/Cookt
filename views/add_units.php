<?php
//Head of the page.
require_once ("../modules/head.php");

//Including the database connection.
require_once ("../config/db_Connection.php");

//Models.
require_once ("../models/models.php");

//Navigation panel of the page
require_once ("../modules/nav.php");
?>

<link rel="stylesheet" href="../styles/styles.css">

<main class="container p-4">
    <div class="row mt-2 text-center justify-content-center">
    <?php
//Messages that are shown in the add_units page
        if(isset($_SESSION['message'])){
        buttonMessage($_SESSION['message'], $_SESSION['message_alert']);        

//Unsetting the messages variables so the message fades after refreshing the page.
        unset($_SESSION['message_alert'], $_SESSION['message']);
        }
    ?>
    <h3>AGREGAR UNIDADES</h3>
<!--Form for filtering the database info-->
        <form class="mt-3 col-auto" method="POST" action="../actions/create.php" autocomplete="on" onsubmit="return validation('add_units', /[a-zA-Z\t\h]+|(^$)/)">
            <div class="input-group mb-3">
                <label class="input-group-text is-required" for="add_units">Unidad: </label>
                <input class="form-control" type="text" id="add_units" name="add_units"  pattern="[a-zA-Z áéíóúÁÉÍÓÚñÑ]+" minlength="2" maxlength="50" autofocus required>
                <input  class="btn btn-primary" type="submit" value="Agregar">
            </div>
        </form>
    </div>
    <div>
        <table class="table table-sm">
            <thead>
                <tr>
                    <th>Unidades</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>                
                <?php
                    $sql = "SELECT unit FROM units ORDER BY unit;";

                    $result = $conn -> query($sql);
                    if($result -> num_rows > 0){
                        while($row = $result -> fetch_assoc()){
                            $html = "<tr>";
                            $html .= "<td>" . ucfirst($row['unit']) . "</td>";
                            $html .= "<td>";
                            $html .= "<a href='../actions/delete.php?unitname=" . $row['unit'] . "' " . "class='btn btn-outline-danger' title='Eliminar'><i class='fa-solid fa-trash'></i></a>";
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
//Footer of the page.
require_once ("../modules/footer.php");
?>