<!--Ventana Emergente para introducir anotaciones-->
<div class="popup_container">
    <div class = "popup_subcontainer">
        <div class="popup_content">
            <img src="img/closebutton.jpg" alt="close" class="close">
            <h2>Anotación</h2>
<!--Espacio para escribir las anotaciones-->
            <form action="" method="POST">
                <textarea name="anotaciones" class="field" cols="30" rows="6"></textarea>
<!--Botón para guardar las anotaciones-->
                <input type="submit" class="button btn_form" value="Guardar" name="Guardar">
            </form>
        </div>
    </div>
</div>
<div>
<!--Código JavaScript para abrir y cerrar la ventana emergente-->
    <script src="./js/script.js"></script> 
    <div>
    <?php    
//Verifico si el botón de enviar del formulario de la ventana emergente viene con información
    if(isset($_POST['Guardar'])){
//Si viene con información las recibo la variable
        $entrada = $_POST['anotaciones'];
//Verifico si la variable viene con información       
        if ($entrada !== "") {
//Objetos que llaman a la función para eliminar espacios en blanco, quitar la barra de escape de caracteres, convertir caracteres especiales en entidades HTML. 
        $input = new input_cleaning();
        $nota = $input -> test_input($entrada);
//Introduzco las anotaciones en la base de datos 
        $sql = "INSERT INTO notes (anotacion) VALUES ('$nota');";
        $conn -> query($sql);
        }
    }
    ?>
    </div>
</div>    