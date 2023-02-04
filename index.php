<?php
//Head of the page.
require_once ("modules/head.php");

//Navigation panel of the page
require_once ("modules/nav.php");

require_once ("models/models.php");
?>
<main>
    <?php
//Messages that are shown in the index page
        if(isset($_SESSION['message'])){
        buttonMessage($_SESSION['message'], $_SESSION['message_alert']);        

//Unsetting the messages variables so the message fades after refreshing the page.
        unset($_SESSION['message_alert'], $_SESSION['message']);
        }
    ?>

    <div>
        <h3>Recetas</h3>
<!--Form for filtering the database info-->
        <div>
            <div>
                <label for="search">Buscar: </label>
                <input type="text" id="search" name="search">
            </div>
        </div>
    </div>
    <div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <td>Receta</td>
                    <td>Categoría</td>
                    <td>Acciones</td>
                </tr>
            </thead>
            <tbody id="content">
            </tbody>
        </table>
    </div>
</main>
<script>
getData();

//Adding event to the searching input.
document.getElementById("search").addEventListener("keyup", function() {
        getData()
}, false)

function getData(){
    let content = document.getElementById("content")
    let input = document.getElementById("search").value

    let url = "load.php";
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
//Footer of the page.
require_once ("modules/footer.php");
?>