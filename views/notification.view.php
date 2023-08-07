<?php
//Head
require_once ("views/partials/head.php");

//Nav
require_once ("views/partials/nav.php");

//Current location in order to come back
$_SESSION['location'] = $_SERVER["REQUEST_URI"];

//Messages
    if(isset($_SESSION['message'])){
    $message = new Messages ($_SESSION['message'], $_SESSION['message_alert']);
    echo $message -> buttonMessage();           

//Unsetting the messages
    unset($_SESSION['message_alert'], $_SESSION['message']);
    }

    $sql = "SELECT id FROM `log` WHERE username = '" . $_SESSION["username"] . "';";
    $result= $conn -> query($sql);
    $num_rows = $result -> num_rows;

    if($num_rows == 0) {
        $display = "style='display:none;'";
    } else {
        $display = "";
    }
?>
<main class="container-fluid py-4">
    <div class="row" <?php echo $display;?>>  
        <div class="col-auto">    
            <select class="form-select" id="num_registros" name="num_registros">
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="40">40</option>
            </select>
        </div>

        <div class="col-auto">
            <label for="num_registros" class="col-form-label">registros</label>
            <label id="lbl-total" class="col-form-label"></label>
        </div>

        <div class="col-auto">
            <a href="<?php echo root . "delete?not_del=" . base64_encode("yes"); ?>" class='btn btn-outline-danger' title='Eliminar todas las notificaciones' onclick='deleteMessage()' id='notification'><i class='fa-solid fa-trash'></i></a>
        </div>      
    </div>  
    
    <div class="row my-3 justify-content-start" id="content">
    </div>        

    <div class="col-8 mt-4" id="nav-pagination">            
    </div> 
</main>
<!-- Ajax script-->
<script>
let paginaActual = 1;

getData(paginaActual);
deleteMessage();

document.getElementById("num_registros").addEventListener("change", function() {
        getData(paginaActual);
}, false);

//Function for getting the data
function getData(pagina){
    let content = document.getElementById("content");
    let pagination = document.getElementById("nav-pagination");
    let num_registros = document.getElementById("num_registros").value;
    let totalRegister = document.getElementById("lbl-total");

//When filtering and searching the page doesn't start from the begging    
    if(pagina != null){
        paginaActual = pagina;
    }

    let url = "ajax/notification.php";
    let formaData = new FormData();
    formaData.append("registros", num_registros);
    formaData.append("pagina", pagina);

    fetch(url, {
        method: "POST",
        body: formaData            
    }).then(response => response.json())
    .then(data => {   
        content.innerHTML = data.data;  
        pagination.innerHTML = data.pagination;
        totalRegister.innerHTML = "de " + data.totalRegister;
//If there's an error.  
    }).catch(err => console.log(err));
}

//Delete message
function deleteMessage(){
var deleteButton = document.getElementById("notification");

    deleteButton.addEventListener("click", function(event){    
        if(confirm("¿Desea eliminar todas las notificaciones?")) {
            return true;
        } else {
            event.preventDefault();
            return false;
        }
    })   
}

//Delete messeage for loop
function deleteMessageLoop(){  
    if(confirm("¿Desea eliminar esta notificación?")) {
        return true;
    } else {
        event.preventDefault();
        return false;
    }
}
</script>
<?php
require_once ("views/partials/footer.php");

//Reseting the message 
if($_SERVER["REQUEST_URI"] == root . "notifications") {
    $conn-> query ("UPDATE `log` SET `state` = 1 WHERE `state` = 0;");
}

//Exiting connection
$conn -> close();
?>