<!DOCTYPE html>
<html lang="en">
<head>
  <?php
  include "Modulos/head.php";
  ?>
</head>
<body>
    <header>
        <?php
          include "Modulos/header.php";
        ?>
    </header> 
    <main>
    <?php
     $mvc= new  Mvccontroller();
     $mvc->enlacesPaginasController();
    ?>
    </main>
    <footer>
        <?php
          include "Modulos/footer.php";
        ?>
    </footer>
</body>
</html>