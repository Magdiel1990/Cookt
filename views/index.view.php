<?php
//Head
require_once ("views/partials/head.php");

//Nav
require_once ("views/partials/nav.php");

$_SESSION["location"] = root;
?>

<main class="container py-4">
<?php
//Messages
    if(isset($_SESSION['message'])){
    $message = new Messages ($_SESSION['message'], $_SESSION['message_alert']);
    echo $message -> buttonMessage();         

//Unsetting the messages
    unset($_SESSION['message_alert'], $_SESSION['message']);
    }
?>

<!--Form for filtering the recipes-->
    <div class="row mt-2 g-3">

        <div class="col-auto">    
            <select class="form-select" id="num_registros" name="num_registros">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="30">30</option>
                <option value="50">50</option>
            </select>
        </div>

        <div class="col-auto">
            <label for="num_registros" class="col-form-label">registros</label>
        </div>

        <div class="col-3"></div>
      
        <div class="col-auto">
            <div class="input-group mb-3">
                <label for="search" class="input-group-text">Buscar: </label>
                <input class="form-control" type="text" id="search" name="search" maxlength="50">
            </div>
        </div>

 <!--         <div class="col-1"></div>

 
    <div class="col-auto">
        <div class="form-check form-check-inline">   
            <input class="form-check-input" type="radio" id="name" name="orderby" value="r.recipename" checked>
            <label class="form-check-label" for="name">Nombre</label><br>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" id="category" name="orderby" value="c.category">
            <label class="form-check-label" for="category">Categoría</label><br>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" id="time" name="orderby" value="r.cookingtime">
            <label class="form-check-label" for="time">Tiempo</label><br>
        </div> 
    </div> 
-->       
    </div>
<!-- Table to show the recipes-->
    <div class="table-responsive-sm mt-2">
        <table class="table table-condensed shadow">
            <thead>
                <tr class="table_header text-center">
                    <!--<th scope="col">#</th>-->
                    <th scope="col">Receta</th>
                    <th scope="col">Tiempo (min)</th>
                    <th scope="col">Categoría</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody id="content">
            </tbody>
        </table>
    </div>
    <div class="row mt-4">
        <div class="col-6">
            <label id="lbl-total"></label>
        </div>
        <div class="col-6" id="nav-pagination">            
        </div>
    </div>
</main>
<!-- Ajax script-->
<script>
let paginaActual = 1;

getData(paginaActual);

//Adding event to the searching input.
document.getElementById("search").addEventListener("keyup", function() {
        getData(1)
}, false);

document.getElementById("num_registros").addEventListener("change", function() {
        getData(paginaActual)
}, false);

//Function for getting the data
function getData(pagina){
    let content = document.getElementById("content");
    let input = document.getElementById("search").value;
    let num_registros = document.getElementById("num_registros").value;

//When filtering and searching the page doesn't start from the begging    
    if(pagina != null){
        paginaActual = pagina;
    }

    let url = "ajax/index-ajax.php";
    let formaData = new FormData();
    formaData.append("search", input);
    formaData.append("registros", num_registros);
    formaData.append("pagina", pagina);

    fetch(url, {
        method: "POST",
        body: formaData            
    }).then(response => response.json())
    .then(data => {
        content.innerHTML = data.data;
        document.getElementById("lbl-total").innerHTML = data.totalFilter + " de " + data.totalRegister;
        document.getElementById("nav-pagination").innerHTML = data.pagination;
//If there's an error.  
    }).catch(err => console.log(err));
}
</script>
<?php
//Footer.
require_once ("views/partials/footer.php");
?>