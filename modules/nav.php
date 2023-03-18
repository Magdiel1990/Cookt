<script src="https://kit.fontawesome.com/65a5e79025.js" crossorigin="anonymous"></script>
<header>
    <div class="banner text-center py-1">
        <a href="/Cookt/index.php">Recipes23</a>
   </div>
    <nav class="navbar navbar-expand-md navbar-dark bg-secondary px-2">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
            <li class="nav-item px-1">
                <a class="nav-link" href="/Cookt/index.php">Recetas</a>
            </li>
            <li class="nav-item px-1">
                <a class="nav-link" href="/Cookt/views/random-recipe.php">Sugerencias</a>
            </li>
            <li class="nav-item px-1">
                <a class="nav-link" href="/Cookt/views/custom-recipe.php">Elegir por ingredientes</a>
            </li>
            <li class="nav-item dropdown px-1" <?php if($_SESSION['type'] == 'Viewer') { echo "style = 'display : none;'";}?>>
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Agregar
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a <?php if($_SESSION['type'] != 'Admin') { echo "style = 'display : none;'";}?> class="dropdown-item" href="/Cookt/views/add-units.php">Unidades</a>
                    <a class="dropdown-item" href="/Cookt/views/add-ingredients.php">Ingredientes</a>
                    <a class="dropdown-item" href="/Cookt/views/add-recipe.php">Recetas</a>
                    <a <?php if($_SESSION['type'] != 'Admin') { echo "style = 'display : none;'";}?> class="dropdown-item" href="/Cookt/views/add-categories.php">Categorías</a>
                    <a <?php if($_SESSION['type'] != 'Admin') { echo "style = 'display : none;'";}?> class="dropdown-item" href="/Cookt/views/add-users.php">Usuarios</a>
                </div>
            </li>
            <li class="nav-item px-1">
                <a class="nav-link" href="/Cookt/user-handler/logout.php" title="Salir"> <i class="fa-solid fa-right-from-bracket"></i></a>
            </li>
            </ul>
        </div>
        <div class="d-flex justify-content-around">            
            <a href="#" class="px-3" style="text-decoration: none;" title="Usuario">
            <span class="username text-light px-2"><?php echo $_SESSION['fullname']?></span>
            <i class="fa-regular fa-user text-light"></i>
            </a>
        </div>   
    </nav>
</header>