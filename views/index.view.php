<?php
//Head
require_once ("views/partials/head.php");

//Nav
require_once ("views/partials/nav.php");
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
    <div class="row mt-2 text-center justify-content-center">
        <h3>Recetas</h3>
        <div class="col-auto">
            <div class="input-group mb-3">
                <label for="search" class="input-group-text">Buscar: </label>
                <input class="form-control" type="text" id="search" name="search" maxlength="50">
            </div>
        </div>
    </div>
<!-- Table to show the recipes-->
    <div class="table-responsive-md mt-2">
        <table class="table table-bordered table-sm">
            <thead>
                <tr class="bg-primary">
                    <th scope="col">Receta</th>
                    <th scope="col">Duración</th>
                    <th scope="col">Categoría</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody id="content">
            </tbody>
        </table>
    </div>
</main>
<!-- Ajax script-->
<script>
getData();

//Adding event to the searching input.
document.getElementById("search").addEventListener("keyup", function() {
        getData()
}, false)

//Function for getting the data
function getData(){
    let content = document.getElementById("content")
    let input = document.getElementById("search").value

    let url = "ajax/index-ajax.php";
    let formaData = new FormData()
    formaData.append("search", input)
    fetch(url, {
        method: "POST",
        body: formaData            
    }).then(response => response.json())
    .then(data => {
        content.innerHTML = data.data
//If there's an error.  
    }).catch(err => console.log(err))
}
</script>
<?php
//Footer.
require_once ("views/partials/footer.php");
?>