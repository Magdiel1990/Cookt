<header>
    <nav class="navbar navbar-expand-md navbar-dark px-4">
        <div class="logo"> 
            <a class="nav-link text-white" href="/cookt"><img src="imgs/logo/logo2.png" alt="Logo"></i></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>  
        </div>   
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
            <li class="nav-item px-1">
                <a class="nav-link text-white" href="/cookt/random">Sugerencias</a>
            </li>
            <li class="nav-item px-1">
                <a class="nav-link text-white" href="/cookt/custom">Elegir por ingredientes</a>
            </li>
            <li class="nav-item dropdown px-1"  <?php if($_SESSION['type'] == 'Viewer') { echo "style = 'display : none;'";}?>>
                <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa-solid fa-gears text-white"></i>
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a <?php if($_SESSION['type'] != 'Admin') { echo "style = 'display : none;'";}?> class="dropdown-item" href="/cookt/units">Unidades</a>
                    <a class="dropdown-item" href="/cookt/ingredients">Ingredientes</a>
                    <a class="dropdown-item" href="/cookt/add-recipe">Recetas</a>
                    <a <?php if($_SESSION['type'] != 'Admin') { echo "style = 'display : none;'";}?> class="dropdown-item" href="/cookt/categories">Categorías</a>
                    <a <?php if($_SESSION['type'] != 'Admin') { echo "style = 'display : none;'";}?> class="dropdown-item" href="/cookt/user">Usuarios</a>
                </div>
            </li>            
            </ul>
        </div>
        <div class="d-flex flex-row">            
            <a class="px-3 text-white"  href="/cookt/profile" style="text-decoration: none;" title="Usuario">
            <?php echo $_SESSION['title'] . $_SESSION['firstname'] . " " .  $_SESSION['lastname'];?> <i class="fa-regular fa-user"></i>
            </a>        
            <a class="nav-link text-white" href="/cookt/logout" title="Salir"> <i class="fa-solid fa-right-from-bracket"></i></a>     
        </div>           
    </nav>
</header>