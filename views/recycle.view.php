<?php
//Head
require_once ("views/partials/head.php");

//Nav
require_once ("views/partials/nav.php");

//Messages
    if(isset($_SESSION['message'])){
    $message = new Messages ($_SESSION['message'], $_SESSION['message_alert']);
    echo $message -> buttonMessage();           

//Unsetting the messages
    unset($_SESSION['message_alert'], $_SESSION['message']);
    }

//total registers
    $sql = "SELECT id FROM recycle WHERE username = '" . $_SESSION['username'] . "';";
    $count =  $conn -> query($sql) -> num_rows;      

    if($count > 0) {
        $display = "";        
    } else {
        $display = "style='display:none;'";
    }
?>
<main class="container py-4">
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

        <div class="col-6">
            <a href="<?php echo root . "delete?empty=" . base64_encode("yes"); ?>" class='btn btn-outline-danger' title='Vaciar papelera' onclick='deleteMessage()' id='recycle'><i class='fa-solid fa-trash'></i></a>
        </div>
    </div>  
    <div class="row my-3" id="content">
    </div>        

    <div class="col-8" id="nav-pagination">            
    </div> 
</main>
<!-- Ajax script-->
<script>
let paginaActual = 1;

getData(paginaActual);
deleteMessage();

document.getElementById("num_registros").addEventListener("change", function() {
        getData(paginaActual)
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

    let url = "ajax/recycle.php";
    let formaData = new FormData();
    formaData.append("registros", num_registros);
    formaData.append("pagina", pagina);

    console.log()

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
var deleteButton = document.getElementById("recycle");

    deleteButton.addEventListener("click", function(event){    
        if(confirm("¿Desea vaciar la papelera?")) {
            return true;
        } else {
            event.preventDefault();
            return false;
        }
    })   
}

//Delete messeage for loop
function deleteMessageLoop(){  
    if(confirm("¿Desea eliminar este elemento?")) {
        return true;
    } else {
        event.preventDefault();
        return false;
    }
}
</script>
<?php
//Reseting the message counter
$conn-> query ("UPDATE `log` SET `state` = 1 WHERE `state` = 0;");

//Exiting connection
$conn -> close();

//Footer
require_once ("views/partials/footer.php");
?>