<?php
//Head
require_once ("views/partials/head.php");

//Nav
require_once ("views/partials/nav.php");

//Only Admin users can access
if($_SESSION['type'] != 'Admin') { 
    require_once ("views/error_pages/404.php");
    exit;
}

//Current location in order to come back 
$_SESSION["location"] = $_SERVER["REQUEST_URI"];
?>

<main class="container p-4">
    <div class="row justify-content-center">
    <?php
//Messages
        if(isset($_SESSION['message'])){
        $message = new Messages ($_SESSION['message'], $_SESSION['message_alert']);
        echo $message -> buttonMessage();         

//Unsetting the messages
        unset($_SESSION['message_alert'], $_SESSION['message']);
        }
    ?>  <div class="col-auto order-last my-4">
<!--Form for adding the users-->
            <h3 class="text-center mb-3">Agregar Usuarios</h3>
            <form method="POST" action="<?php echo root?>create" id="user_form">         
                
                <div class="input-group mb-3">
                    <label class="input-group-text is-required" for="firstname">Nombre: </label>
                    <input class="form-control" type="text" id="firstname" name="firstname"  pattern="[a-zA-Z áéíóúÁÉÍÓÚñÑ,;:]+" minlength="2" maxlength="30" required>
                </div>

                <div class="input-group mb-3">
                    <label class="input-group-text is-required" for="lastname">Apellido: </label>
                    <input class="form-control" type="text" id="lastname" name="lastname"  pattern="[a-zA-Z áéíóúÁÉÍÓÚñÑ,;:]+" minlength="2" maxlength="40" required>
                </div>

                <div class="input-group mb-3">
                    <label class="input-group-text is-required" for="username">Usuario: </label>
                    <input class="form-control" type="text" id="username" name="username"  pattern="[a-zA-Z áéíóúÁÉÍÓÚñÑ,;:]+" minlength="2" maxlength="30" required>
                </div>

                <div class="input-group mb-3">
                    <label class="input-group-text is-required" for="userpassword">Contraseña: </label>
                    <input class="form-control" type="password" id="userpassword" name="userpassword" minlength="8" maxlength="50">
                </div>

                <div class="input-group mb-3">
                    <label class="input-group-text is-required" for="passrepeat">Repetir contraseña: </label>
                    <input class="form-control" type="password" id="passrepeat" name="passrepeat" minlength="8" maxlength="50">
                </div>

                <div class="input-group mb-3">
                    <label class="input-group-text is-required" for="userrol">Rol: </label>
                    <select class="form-select" name="userrol" id="userrol">
                    <?php
//Types of users
                        $sql = "SELECT type FROM type ORDER BY rand();";
                        $result = $conn -> query($sql);

                        while($row = $result -> fetch_assoc()){
                            echo "<option value='" . $row["type"] . "'>" . $row["type"] . "</option>";
                        }
                    ?>               
                    </select>
                </div>

                <div class="input-group mb-3">
                    <label class="input-group-text is-required" for="useremail">Email: </label>
                    <input class="form-control" type="email" id="useremail" name="useremail" minlength="15" maxlength="70" required>
                </div>
<!-- Current user is sent-->
                <input type="hidden" name="session_user" value = "<?php echo $_SESSION['username']?>">
                <div class="text-center">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="sex" id="M" value="M" checked required>
                        <label class="form-check-label" for="M">M</label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="sex" id="F" value="F">
                        <label class="form-check-label" for="F">F</label>
                    </div>      

                    <div class="form-check form-check-inline my-2">
                        <input class="form-check-input" type="radio" name="sex" id="O" value="O">
                        <label class="form-check-label" for="O">O</label>
                    </div> 
                </div>    

                <div id="alert_message"></div> 

                <div class="text-center form-switch mt-2">
                    <input class="form-check-input" type="checkbox" id="activeuser" name="activeuser" value="yes" checked>
                    <label class="form-check-label" for="activeuser">Activo</label>
                    <input class="btn btn-success mx-4" name="usersubmit" type="submit" value="Agregar">
                </div>       

            </form>
            <script>
                userValidation(); 

//Form validation
                function userValidation(){
                var form = document.getElementById("user_form");    

                form.addEventListener("submit", function(event){ 
                    var regExp = /[a-zA-Z,;:\t\h]+|(^$)/;
                    var firstname = document.getElementById("firstname").value;
                    var lastname = document.getElementById("lastname").value;
                    var username = document.getElementById("username").value;
                    var password = document.getElementById("password").value;
                    var passrepeat = document.getElementById("passrepeat").value;
                    var sex = document.getElementsByName("sex");    
                    var email = document.getElementById("email").value; 
                    var message = document.getElementById("message");    
                    
//Verify if an option of the radio input has been chosen    
                    for (var s of sex) {
                        if (s.checked) {
                            sex = s.value;
                        }
                    }

                    if(firstname == "" || lastname == "" || username == "" || password == ""  || sex =="") {
                        event.preventDefault();
                        message.innerHTML = "¡Completar los campos requeridos!";             
                        return false;
                    }

                    if(password != passrepeat) {
                        event.preventDefault();
                        message.innerHTML = "¡Contraseñas no coinciden!";        
                        return false;
                    }

//Regular Expression    
                    if(!firstname.match(regExp) || !lastname.match(regExp) || !username.match(regExp)){
                        event.preventDefault();
                        message.innerHTML = "¡Nombre, apellido o usuario incorrecto!";                 
                        return false;
                    }

                    if(firstname.length < 2 || firstname.length > 30){
                        event.preventDefault();
                        message.innerHTML = "¡El nombre debe tener de 2 a 30 caracteres!";                 
                        return false;
                    } 

                    if(lastname.length < 2 || lastname.length > 40){
                        event.preventDefault();
                        message.innerHTML = "¡El apellido debe tener de 2 a 40 caracteres!";                 
                        return false;
                    }

                    if(username.length < 2 || username.length > 30){
                        event.preventDefault();
                        message.innerHTML = "¡El usuario debe tener de 2 a 30 caracteres!";                 
                        return false;
                    }

                    if(password.length < 8 || password.length > 50){
                        event.preventDefault();
                        message.innerHTML = "¡La contraseña debe tener de 8 a 50 caracteres!";                 
                        return false;
                    }
                    
                    if(email.length < 15 || email.length > 70){
                        event.preventDefault();                        
                        message.innerHTML = "¡El email debe tener de 15 a 70 caracteres!";                 
                        return false;
                    }                
                    return true;
                })
                }
            </script>
        </div>
<!-- List of users -->
        <div class="col-lg-9 col-xl-9 col-md-12 col-sm-12 my-4">
            <h3 class="text-center">Lista de Usuarios</h3>
            <div class="table-responsive-md mt-3">
                <table class="table table-bordered table-md shadow">
                    <thead>
                        <tr class="table_header">
                            <th scope="col">Usuario</th>
                            <th scope="col">Rol</th>
                            <th scope="col">Estado</th> 
                            <th scope="col">Recetas</th>             
                            <th scope="col">Acciones</th>                     
                        </tr>
                    </thead>
                    <tbody>                
                        <?php
                        $sql = "SELECT email_code, type, username, state, userid FROM users ORDER BY type;";

                        $result = $conn -> query($sql);                    

                        while($row = $result -> fetch_assoc()){
                            $type = $row['type'];
                            $username = $row['username'];
                            $state = $row['state'];
                            $userid = $row['userid'];
                            $email_code = $row['email_code'];

//Recipes of each user
                            $sql = "SELECT count(recipeid) as `count` FROM recipe WHERE username = '$username' AND state = 1;";
                            $row = $conn -> query($sql) -> fetch_assoc();   
                            $recipeCount = $row ['count'];
//Active users are colored green and inactive, gray                            
                            if($state == 1 && $email_code == null) {
                                $state = "activo";
                                $color = "rgb(22, 182, 4)";
                            } else if ($email_code != null) {
                                $state = "desactivado";
                                $color = "rgb(234, 169, 247)";
                            } else {
                                $state = "inactivo";
                                $color = "#aaa";
                            }
//If the user is Admin and is logged in, delete and edit options are unavailable
                            if($type == "Admin" && $username == $_SESSION['username']) {
                                $display = "style = 'display: none;'";
                                $display_2 = "";
//If the user is Admin, only edit option is available for other Admin users                               
                            } else if ($type == "Admin"){
                                $display = ""; 
                                $display_2 = "style = 'display: none;'";
//If the user is not Admin, all options are available   
                            } else {
                                $display = "";
                                $display_2 = "";
                            }
//The logged-in user can't clicked to see his recipe from the user section
                            if($username == $_SESSION['username']) {
                                $recipeList = "";
                            } else {
                                $recipeList = "href='user-recipes?username=" . $username . "'";
                            }
                            
                            $html = "<tr>";                        
                            $html .= "<td>";
                            $html .="<a class='tlink' style='color:" . $color . ";' $recipeList>";
                            $html .= $username;
                            $html .="</a>";
                            $html .= "</td>";
                            $html .= "<td style='color:" . $color . ";'>" . $type . "</td>";
                            $html .= "<td style='color:" . $color . ";'>" . $state . "</td>";
                            $html .= "<td style='color:" . $color . ";'>" . $recipeCount . "</td>";
                            $html .= "<td class='btn-group d-block' role='group'>";
                            $html .= "<a $display href='" . root . "edit?userid=" . $userid . "' " . "class='btn btn-outline-secondary' title='Editar'><i class='fa-solid fa-pen'></i></a>";
                            $html .= "<a $display $display_2 href='" . root . "delete?userid=" . $userid . "&type=" . urlencode($type) . "' " . "class='btn btn-outline-danger' title='Eliminar'><i class='fa-solid fa-trash'></i></a>";
                            $html .= "<a $display_2 href='" . root . "reset?user_id=" . $userid . "&reset=1' class='btn btn-outline-warning' title='Resetear'><i class='fa-solid fa-eraser'></i></a>";
                            $html .= "</td>";
                            $html .= "</tr>";
                            echo $html;
                        }                               
                        ?>                
                    </tbody>
                </table>
            </div>
        </div>
    </div>    
</main>
<script>
deleteMessage("btn-outline-danger", "usuario");   
resetMessage("btn-outline-warning", "usuario");

//Delete message
function deleteMessage(button, pageName){
var deleteButtons = document.getElementsByClassName(button);

    for(var i = 0; i<deleteButtons.length; i++) {
        deleteButtons[i].addEventListener("click", function(event){    
            if(confirm("¿Desea eliminar este " + pageName + "?")) {
                return true;
            } else {
                event.preventDefault();
                return false;
            }
        })
    }
}

//Reset message
function resetMessage(button, pageName){
var deleteButtons = document.getElementsByClassName(button);

    for(var i = 0; i<deleteButtons.length; i++) {
        deleteButtons[i].addEventListener("click", function(event){    
            if(confirm("¿Desea reiniciar este " + pageName + "?")) {
                return true;
            } else {
                event.preventDefault();
                return false;
            }
        })
    }
}
</script>
<?php
//Exiting connection
$conn -> close();

//Footer.
require_once ("views/partials/footer.php");
?>