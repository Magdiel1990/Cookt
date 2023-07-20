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

    $sql = "SELECT categoryid FROM categories WHERE state = 0 UNION SELECT id FROM ingredients WHERE state = 0 AND username = '" . $_SESSION['username'] . "' UNION SELECT recipeid FROM recipe WHERE state = 0 AND username = '" . $_SESSION['username'] . "';";
    $result = $conn -> query($sql);
    $count =  $result -> num_rows;
 
    if($count == 0) {
        $display = "style='display:none;'";
    } else {
        $display = "";
    }
?>
<main class="container py-4">
    <div class="row" <?php echo $display;?>>  
        <div class="col-auto">    
            <select class="form-select" id="num_registros" name="num_registros">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="30">30</option>
            </select>
        </div>

        <div class="col-auto">
            <label for="num_registros" class="col-form-label">registros</label>
        </div>

        <div class="col-6">
            <a href="<?php echo root . "delete?empty=" . base64_encode("yes"); ?>" class='btn btn-outline-danger' title='Vaciar papelera' onclick='deleteMessage()' id='recycle'><i class='fa-solid fa-trash'></i></a>
        </div>

        <div class="mt-2 col-auto">
            <h3>Papelera de reciclaje</h3>
        </div>
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
deleteMessage();

document.getElementById("num_registros").addEventListener("change", function() {
        getData(paginaActual)
}, false);

//Function for getting the data
function getData(pagina){
    let content = document.getElementById("content");
    let pagination = document.getElementById("nav-pagination");
    let num_registros = document.getElementById("num_registros").value;

//When filtering and searching the page doesn't start from the begging    
    if(pagina != null){
        paginaActual = pagina;
    }

    let url = "ajax/recycle.php";
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