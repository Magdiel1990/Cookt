<script src="https://kit.fontawesome.com/65a5e79025.js" crossorigin="anonymous"></script>
<header>
    <div class="banner d-flex justify-content-around pt-2">
        <a href="#">R3CP</a>        
        <a href="#"> <i class="fa-solid fa-right-from-bracket"></i></a>
    </div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="index.php">Recetas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="./views/random-recipe.php">Sugerencias</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="./views/custom-recipe.php">Elegir por ingredientes</a>
            </li>
            <li class="nav-item dropdown" <?php if($_SESSION['type'] == 'Viewer') { echo "style = 'display : none;'";}?>>
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Administración
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                <a <?php if($_SESSION['type'] != 'Admin') { echo "style = 'display : none;'";}?> class="dropdown-item" href="./views/add-units.php">Unidades</a>
                <a class="dropdown-item" href="./views/add-ingredients.php">Ingredientes</a>
                <a class="dropdown-item" href="./views/add-recipe.php">Recetas</a>
                <a <?php if($_SESSION['type'] != 'Admin') { echo "style = 'display : none;'";}?> class="dropdown-item" href="./views/add-categories.php">Categorías</a>
                <a <?php if($_SESSION['type'] != 'Admin') { echo "style = 'display : none;'";}?> class="dropdown-item" href="./views/add-users.php">Usuarios</a>
                </div>
            </li>
            </ul>
        </div>
    </nav>
</header>