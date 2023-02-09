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
        <form class="mt-3 col-auto" id="randomform" method="POST" action="../actions/create.php" autocomplete="off">
            <div class="input-group mb-3">
                <label class="input-group-text" for="add_units">Unidad: </label>
                <input class="form-control" type="text" id="add_units" name="add_units">
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
                    $sql = "SELECT unit FROM units";

                    $result = $conn -> query($sql);

                    while($row = $result -> fetch_assoc()){
                        $html = "<tr>";
                        $html .= "<td>" . $row['unit'] . "</td>";
                        $html .= "<td>";
                        $html .= "<a href='../actions/edit.php?unitname=" . $row['unit'] . "' " . "class='btn btn-outline-secondary' title='Editar'><i class='fa-solid fa-pen'></i></a>";
                        $html .= "<a href='../actions/delete.php?unitname=" . $row['unit'] . "' " . "class='btn btn-outline-danger' title='Eliminar'><i class='fa-solid fa-trash'></i></a>";
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