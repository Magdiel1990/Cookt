<!--Cabecera las páginas-->
<div class="container-fluid">
    <div class="banner">
        <div class="logo">
            <img src="./img/Logo.png" alt="Logo">
        </div>
    </div>
    <!--Enlaces del menú de navegación-->
    <div class="nav-bar">
        <ul class="nav justify-content-around">
            <li class="nav-item"><a class="nav-link" href="index.php">Inicio</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php?action=indicadores">Evaluación</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php?action=registro">Registro Anecdótico</a></li>
            <li class="nav-item">

                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Más</a>
                <!--Enlaces del submenú desplegable-->
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="index.php?action=listado_estudiantes">Estudiantes</a>
                    </li>
                    <li <?php if ($_SESSION['username'] !== 'Admin') {
                            echo "style = 'display: none'";
                        } ?>><a class="dropdown-item" href="index.php?action=backup_view">Respaldos</a>
                    </li>
                    <li><a class="dropdown-item" href="index.php?action=examenes">Exámenes</a></li>
                    <li><a class="dropdown-item" href="index.php?action=resumen">Resumen</a></li>
                    <li><a class="dropdown-item" href="index.php?action=ver_anotaciones">Ver Anotaciones</a></li>
                    <li><a class="dropdown-item" href="index.php?action=participaciones">Participaciones</a></li>
                </ul>
            </li>
            <li class="nav-item" <?php if ($_SESSION['username'] !== 'Admin') {
                                        echo "style = 'display: none'";
                                    } ?>>
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href=" #"><i class="fas fa-cog"></i></a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="index.php?action=newuser">Agregar
                            Usuario</a></li>
                    <li><a class="dropdown-item" href="index.php?action=passchange">Cambiar
                            Contraseña</a>
                    </li>
                    <li><a class="dropdown-item" href="index.php?action=userdelete">Eliminar
                            Usuario</a>
                    </li>
                    <li><a class="dropdown-item" href="index.php?action=userconfig">Actualizar
                            Usuario</a>
                    </li>
                    <li><a class="dropdown-item" href="index.php?action=admin_files">Admin. de
                            Archivos</a>
                    </li>
                    <li><a class="dropdown-item" href="index.php?action=admin_students">Admin.
                            de
                            Estudiantes</a></li>
                    <li><a class="dropdown-item" href="index.php?action=add_indicadores">Agregar
                            Indicadores</a></li>
                    <li><a class="dropdown-item" href="index.php?action=evaluationSetting">Crear
                            Criterio de Evaluación</a></li>
                    <li><a class="dropdown-item" href="index.php?action=reset">Reiniciar</a>
                    </li>
                </ul>
            </li>
            <li class="nav-item"><a class="nav-link" href="Logout.php"><i class="fas fa-arrow-right"></i></a></li>
        </ul>
    </div>
</div>