<?php
//Head
require_once ("views/partials/head.php");

//Nav
require_once ("views/partials/nav.php");

//Reseting the message counter
    $sql = "UPDATE `log` SET `state` = 1 WHERE `state` = 0;";
    $conn->query($sql);

//Messages
    if(isset($_SESSION['message'])){
    $message = new Messages ($_SESSION['message'], $_SESSION['message_alert']);
    echo $message -> buttonMessage();           

//Unsetting the messages
    unset($_SESSION['message_alert'], $_SESSION['message']);
    }
?>
<main class="container row py-4">    
    <div class="col-auto">    
        <select class="form-select" id="num_registros" name="num_registros">
            <option value="5">5</option>
            <option value="10">10</option>
            <option value="20">20</option>
            <option value="30">30</option>
            <option value="50">50</option>
        </select>
    </div>

    <div class="col-5">
        <label for="num_registros" class="col-form-label">registros</label>
    </div>

    <div class="mt-2 col-auto">
        <h3>Notificaciones</h3>
    </div>

    <div class="col-auto" id="content">
    </div>        

    <div class="col-8" id="nav-pagination">            
    </div> 
</main>
<!-- Ajax script-->
<script>
let paginaActual = 1;

getData(paginaActual);

document.getElementById("num_registros").addEventListener("change", function() {
        getData(paginaActual)
}, false);

//Function for getting the data
function getData(pagina){
    let content = document.getElementById("content");
    let num_registros = document.getElementById("num_registros").value;

//When filtering and searching the page doesn't start from the begging    
    if(pagina != null){
        paginaActual = pagina;
    }

    let url = "ajax/notification-ajax.php";
    let formaData = new FormData();
    formaData.append("registros", num_registros);
    formaData.append("pagina", pagina);

    fetch(url, {
        method: "POST",
        body: formaData            
    }).then(response => response.json())
    .then(data => {   
        content.innerHTML = data.data;  
        document.getElementById("nav-pagination").innerHTML = data.pagination;
//If there's an error.  
    }).catch(err => console.log(err));
}
</script>
<?php
//Exiting connection
$conn -> close();

//Footer
require_once ("views/partials/footer.php");
?>