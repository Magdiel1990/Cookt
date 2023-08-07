<?php
//Head
require_once ("views/partials/head.php");

//Only Admin users can access
if($_SESSION['type'] != 'Admin') { 
    require_once ("views/error_pages/404.php");
    exit;
}

//Nav of the page
require_once ("views/partials/nav.php");
?>

<main class="container p-4">
    <div class="row mt-2 text-center justify-content-center">
    <?php
//Messages 
        if(isset($_SESSION['message'])){
        $message = new Messages ($_SESSION['message'], $_SESSION['message_alert']);
        echo $message -> buttonMessage();           

//Unsetting the messages
        unset($_SESSION['message_alert'], $_SESSION['message']);
        }
    ?>
<!--Category form-->
    <h3>Agregar Categorías</h3>

        <form id="category_form" class="mt-3 col-auto"  enctype="multipart/form-data" method="POST" action="<?php echo root;?>create" autocomplete="on">
            
            <div class="input-group mb-3">
                <label class="input-group-text is-required" for="add_categories">Categoría: </label>
                <input class="form-control" type="text" id="add_categories" name="add_categories"  pattern="[a-zA-Z áéíóúÁÉÍÓÚñÑ,;:]+" minlength="2" maxlength="50" autofocus required>
            </div>

            <div class="mb-3">
                <label class="form-label is-required" for="categoryImage">Foto de la categoría</label>
                <input type="file" name="categoryImage" accept=".jpg" class="form-control" id="categoryImage" required>
            </div> 

            <div class="mb-3">
                <input class="btn btn-success" name="categorySubmit" type="submit" value="Agregar">
            </div>

        </form>
    </div>
<!-- Category list -->
    <div class="table-responsive-sm mt-4">
        <table class="table table-sm shadow">
            <thead>
                <tr class="table_header">
                    <th class='px-2' scope="col">Categorías</th>
                    <th class='px-2' scope="col">Imagen</th>
                    <th class='px-2' scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>                
                <?php
                    $sql = "SELECT * FROM categories WHERE state = 1 ORDER BY category;";
                    $result = $conn -> query($sql);
//Category images main directory
                    $categoryImg = "imgs/categories/";

                    if($result -> num_rows > 0){
                        while($row = $result -> fetch_assoc()){
                            $html = "<tr>";
                            $html .= "<td class='px-2'>" . ucfirst($row['category']) . "</td>";
                            $html .= "<td><img id='categoryImg' src='" . $categoryImg . $row['category'] . ".jpg' alt='Ninguna imagen'></td>";
                            $html .= "<td class='px-2'>";
                            $html .= "<div class='btn-group' role='group'>";
//Delete and edit buttons
                            $html .= "<a href='" . root . "delete?categoryname=" . $row['category'] . "' " . "class='btn btn-outline-danger' title='Eliminar'><i class='fa-solid fa-trash'></i></a>";
                            $html .= "<a href='" . root . "edit?categoryid=" . $row['categoryid'] . "' " . "class='btn btn-outline-secondary' title='Editar'><i class='fa-solid fa-pen'></i></a>";
                            $html .= "</div>";
                            $html .= "</td>";
                            $html .= "</tr>";
                            echo $html;
                        }
                    } else {
//Text when there is no categories                        
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
<script>
deleteMessage("btn-outline-danger", "categoría");
formValidation();    

//Delete message
function deleteMessage(button, pageName){
var deleteButtons = document.getElementsByClassName(button);

    for(var i = 0; i<deleteButtons.length; i++) {
        deleteButtons[i].addEventListener("click", function(event){    
            if(confirm("¿Desea eliminar esta " + pageName + "?")) {
                return true;
            } else {
                event.preventDefault();
                return false;
            }
        })
    }
}                  
               
//Image format validation
function formValidation(){

    var form = document.getElementById("category_form");    

    form.addEventListener("submit", function(event) { 
//Accepted formats            
        var regExp = /[a-zA-Z,;:\t\h]+|(^$)/;
        var categoryImageInput = document.getElementById('categoryImage');
        var categoryImage = categoryImageInput.value;                            
        var categoryNameInput = document.getElementById('add_categories');
        var categoryName = categoryNameInput.value;
        var allowedImageTypes = ["jpg"];  

        if(categoryName == "") {
            event.preventDefault();                
            confirm ("¡Escriba el nombre de la categoría!");                                
            return false;
        }

        if(categoryName.length < 2 || categoryName.length > 20) {
            event.preventDefault();
            confirm("¡Longitud de categoría incorrecta!");               
            return false;                
        }
        
        if(!categoryName.match(regExp)){ 
            event.preventDefault();                
            confirm ("¡Nombre de categoría incorrecto!");                                
            return false;
        }

        if (categoryImage != "") {
            var file = categoryImageInput.files[0];                   
            var fileType = file.type;  
//Weight of the file                        
            var weight = file.size;
//Size in Bytes     
            if(weight > 300000) {
                event.preventDefault();
                confirm ("¡El tamaño de la imagen debe ser menor que 300 KB!");  
                return false;
            }       
//Image format validation
            if(allowedImageTypes.includes(fileType)){
                event.preventDefault();
                confirm ("¡Solo jpg admitido!");
                return false;
            }                            
        }
        return true;               
    })
}
</script>
<?php
//Close db connection
$conn -> close();

//Footer
require_once ("views/partials/footer.php");
?>