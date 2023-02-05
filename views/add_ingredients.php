<?php
//Head of the page.
require_once ("../modules/head.php");

//Including the database connection.
require_once ("../config/db_Connection.php");

//Models.
require_once ("../models/models.php");
?>
<main>
    <div>
    <?php
//Messages that are shown in the add_units page
        if(isset($_SESSION['message'])){
        buttonMessage($_SESSION['message'], $_SESSION['message_alert']);        

//Unsetting the messages variables so the message fades after refreshing the page.
        unset($_SESSION['message_alert'], $_SESSION['message']);
        }
    ?>
    <h3>Agregar Unidades</h3>
<!--Form for filtering the database info-->
        <form method="POST" action="../actions/unit_add.php" autocomplete="off">
            <div>
                <label for="add_ingredient">Ingrediente: </label>
                <input type="text" id="add_ingredient" name="add_ingredient">
                <input type="submit" value="Agregar">
            </div>
        </form>
    </div>
    <div>
        <table class="table">
            <thead>
                <tr>
                    <th>Ingredientes</th>
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