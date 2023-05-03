<header>
    <nav class="navbar navbar-expand-md navbar-dark px-4">
<!-- Logo and dropdown button-->
        <div class="logo"> 
            <a class="nav-link text-white" href="/"><img src="imgs/logo/logo2.png" alt="Logo" title="Página principal"></i></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>  
        </div>   
<!-- Nav links -->        
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link text-white" href="/random" title="Sugerencias">Sugerencias</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="/custom" title="Elegir por ingredientes">Elegir por ingredientes</a>
            </li>
            <li class="nav-item dropdown"  <?php if($_SESSION['type'] == 'Viewer') { echo "style = 'display : none;'";}?>>
                <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa-solid fa-gears text-white"></i>
                </a>
<!-- Not Admin users can't access some of the links-->
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">                    
                    <a class="dropdown-item" href="/ingredients" title="Ingredientes">Ingredientes</a>
                    <a class="dropdown-item" href="/add-recipe" title="Recetas">Recetas</a>
                    <a <?php if($_SESSION['type'] != 'Admin') { echo "style = 'display : none;'";}?> class="dropdown-item" href="/categories" title="Categorías">Categorías</a>
                    <a <?php if($_SESSION['type'] != 'Admin') { echo "style = 'display : none;'";}?> class="dropdown-item" href="/user" title="Usuarios">Usuarios</a>
                </div>
            </li>            
            </ul>
        </div>
<!--User identification -->
        <div class="d-flex flex-row">            
            <a class="px-3 text-white"  href="/profile" style="text-decoration: none;" title="Perfil">
            <?php echo $_SESSION['title'] . $_SESSION['firstname'] . " " .  $_SESSION['lastname'];?> 
            <?php
            $target_dir = "imgs/users/";
//User photo
            $files = new Directories($target_dir, $_SESSION["username"]);
            $imgprofileDir = $files -> directoryFiles();
            
            if($imgprofileDir != false){
                echo '<img src="' . $imgprofileDir . '" class="mx-2">';
            } else {
                echo "<i class='fa-regular fa-user'></i>";
            }
            
            ?>
            </a>  
<!-- Logout button -->                  
            <a class="nav-link text-white logout" href="/logout" title="Salir"> <i class="fa-solid fa-right-from-bracket"></i></a>     
        </div>          
    </nav>
</header>
 <script>
deleteMessage("logout");   

//Delete message
function deleteMessage(button){
var deleteButtons = document.getElementsByClassName(button);

    for(var i = 0; i<deleteButtons.length; i++) {
        deleteButtons[i].addEventListener("click", function(event){    
            if(confirm("¿Desea salir?")) {
                return true;
            } else {
                event.preventDefault();
                return false;
            }
        })
    }
}
</script>