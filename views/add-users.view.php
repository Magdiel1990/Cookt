<?php
//Including the database connection.
require_once ("../config/db_Connection.php");

//Models.
require_once ("../models/models.php");

//Head of the page.
require_once ("partials/head.php");

if($_SESSION['type'] != 'Admin') { 
    require_once ("/Cookt/error/error.php");
    exit;
}

//Navigation panel of the page
require_once ("partials/nav.php");
?>

<main class="container p-4">
    <div class="row justify-content-center">
    <?php
//Messages that are shown in the add_units page
        if(isset($_SESSION['message'])){
        $message = new Messages ($_SESSION['message'], $_SESSION['message_alert']);
        echo $message -> buttonMessage();         

//Unsetting the messages variables so the message fades after refreshing the page.
        unset($_SESSION['message_alert'], $_SESSION['message']);
        }
    ?>  <div class="col-auto order-last my-4">
            <h3 class="text-center mb-3">Agregar Usuarios</h3>
        <!--Form for filtering the database info-->
            <form method="POST" action="../actions/create.php">         
                
                <div class="input-group mb-3">
                    <label class="input-group-text is-required" for="firstname">Nombre: </label>
                    <input class="form-control" type="text" id="firstname" name="firstname"  pattern="[a-zA-Z áéíóúÁÉÍÓÚñÑ]+" minlength="2" maxlength="30">
                </div>

                <div class="input-group mb-3">
                    <label class="input-group-text is-required" for="lastname">Apellido: </label>
                    <input class="form-control" type="text" id="lastname" name="lastname"  pattern="[a-zA-Z áéíóúÁÉÍÓÚñÑ]+" minlength="2" maxlength="40">
                </div>

                <div class="input-group mb-3">
                    <label class="input-group-text is-required" for="username">Usuario: </label>
                    <input class="form-control" type="text" id="username" name="username"  pattern="[a-zA-Z áéíóúÁÉÍÓÚñÑ]+" minlength="2" maxlength="30">
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
                        $sql = "SELECT type FROM type ORDER BY rand();";
                        $result = $conn -> query($sql);

                        while($row = $result -> fetch_assoc()){
                            echo "<option value='" . $row["type"] . "'>" . $row["type"] . "</option>";
                        }
                    ?>               
                    </select>
                </div>

                <div class="input-group mb-3">
                    <label class="input-group-text" for="useremail">Email: </label>
                    <input class="form-control" type="email" id="useremail" name="useremail" minlength="15" maxlength="70">
                </div>

                <input type="hidden" name="session_user" value = "<?php echo $_SESSION['username']?>">
                <div class="text-center">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="sex" id="M" value="M" required>
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

                <input type="hidden" id="url" name="url" value = "<?php echo $_SERVER["PHP_SELF"];?>"/>

                <div class="text-center form-switch mt-2">
                    <input class="form-check-input" type="checkbox" id="activeuser" name="activeuser" value="yes" checked>
                    <label class="form-check-label" for="activeuser">Activo</label>
                    <input class="btn btn-success mx-4" name="usersubmit" type="submit" value="Agregar">
                </div>       

            </form>
        </div>

        <div class="col-lg-9 col-xl-9 col-md-12 col-sm-12 my-4">
            <h3 class="text-center">Lista de Usuarios</h3>
            <table class="table table-sm mt-3">
                <thead>
                    <tr class="bg-primary">
                        <th>Usuario</th>
                        <th>Rol</th>
                        <th>Estado</th> 
                        <th>Recetas</th>             
                        <th>Acciones</th>                     
                    </tr>
                </thead>
                <tbody>                
                    <?php
                        $sql = "SELECT type, username, state, userid FROM users ORDER BY type;";

                        $result = $conn -> query($sql);                    

                        while($row = $result -> fetch_assoc()){
                            $type = $row['type'];
                            $username = $row['username'];
                            $state = $row['state'];
                            $userid = $row['userid'];

                            //Recipes of each user
                            $sql = "SELECT count(recipeid) as `count` FROM recipe WHERE username = '$username';";
                            $row = $conn -> query($sql) -> fetch_assoc();   
                            $recipeCount = $row ['count'];
                            
                            if($state == 1) {
                                $state = "activo";
                                $color = "rgb(22, 182, 4)";
                            } else {
                                $state = "inactivo";
                                $color = "#aaa";
                            }

                            if($type == "Admin" && $username == $_SESSION['username']) {
                                $display = "style = 'display: none;'";
                                $display_2 = "";
                            } else if ($type == "Admin"){
                                $display = ""; 
                                $display_2 = "style = 'display: none;'";
                            } else {
                                $display = "";
                                $display_2 = "";
                            }

                            if($username == $_SESSION['username']) {
                                $recipeList = "";
                            } else {
                                $recipeList = "href='../views/recipes-list.php?username=" . $username . "'";
                            }
                            
                            $html = "<tr>";                        
                            $html .= "<td>";
                            $html .="<a style='color:" . $color . ";' $recipeList>";
                            $html .= $username;
                            $html .="</a>";
                            $html .= "</td>";
                            $html .= "<td style='color:" . $color . ";'>" . $type . "</td>";
                            $html .= "<td style='color:" . $color . ";'>" . $state . "</td>";
                            $html .= "<td style='color:" . $color . ";'>" . $recipeCount . "</td>";
                            $html .= "<td>";
                            $html .= "<a $display href='../actions/edit.php?userid=" . $userid . "' " . "class='btn btn-outline-secondary m-1' title='Editar'><i class='fa-solid fa-pen'></i></a>";
                            $html .= "<a $display $display_2 href='../actions/delete.php?userid=" . $userid . "' " . "class='btn btn-outline-danger' title='Eliminar'><i class='fa-solid fa-trash'></i></a>";
                            $html .= "<a $display_2 href='../actions/delete.php?user_id=" . $userid . "&reset=1' class='btn btn-outline-warning' title='Resetear'><i class='fa-solid fa-eraser'></i></a>";
                            $html .= "</td>";
                            $html .= "</tr>";
                            echo $html;
                        }                               
                    ?>                
                </tbody>
            </table>
        </div>
    </div>    
</main>
<?php
$conn -> close();
//Footer of the page.
require_once ("partials/footer.php");
?>