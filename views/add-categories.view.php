<?php
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
    <h3>Agregar Categorías</h3>
<!--Form for filtering the database info-->
        <form class="mt-3 col-auto"  enctype="multipart/form-data" method="POST" action="/create" autocomplete="on" onsubmit="return validation('add_categories', /[a-zA-Z\t\h]+|(^$)/)">
            
            <div class="input-group mb-3">
                <label class="input-group-text is-required" for="add_categories">Categoría: </label>
                <input class="form-control" type="text" id="add_categories" name="add_categories"  pattern="[a-zA-Z áéíóúÁÉÍÓÚñÑ]+" minlength="2" maxlength="50" autofocus required>
            </div>

            <div class="mb-3">
                <label class="form-label is-required" for="categoryImage">Foto de la categoría</label>
                <input type="file" name="categoryImage" accept=".png, .jpeg, .jpg, .gif" class="form-control" id="categoryImage" required>
            </div> 
            <div class="mb-3">
                <input  class="btn btn-success" name="categorySubmit" type="submit" value="Agregar">
            </div>

        </form>
    </div>
    <div class="table-responsive-sm mt-4">
        <table class="table table-sm">
            <thead>
                <tr class="bg-primary">
                    <th scope="col">Categorías</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>                
                <?php
                    $sql = "SELECT * FROM categories ORDER BY category;";

                    $result = $conn -> query($sql);
                    if($result -> num_rows > 0){
                        while($row = $result -> fetch_assoc()){
                            $html = "<tr>";
                            $html .= "<td>" . ucfirst($row['category']) . "</td>";
                            $html .= "<td>";
                            $html .= "<div class='btn-group' role='group'>";
                            $html .= "<a href='/delete?categoryname=" . $row['category'] . "' " . "class='btn btn-outline-danger' title='Eliminar'><i class='fa-solid fa-trash'></i></a>";
                            $html .= "<a href='/edit?categoryid=" . $row['categoryid'] . "' " . "class='btn btn-outline-secondary' title='Editar'><i class='fa-solid fa-pen'></i></a>";
                            $html .= "</div>";
                            $html .= "</td>";
                            $html .= "</tr>";
                            echo $html;
                        }
                    } else {
                        $html = "<tr>";
                        $html .= "<td colspan='2'>";
                        $html .= "Agrega las categorías...";
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