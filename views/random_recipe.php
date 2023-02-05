<?php
//Head of the page.
require_once ("../modules/head.php");

//Including the database connection.
require_once ("../config/db_Connection.php");

//Models.
require_once ("../models/models.php");
?>
<main>
    <div>
        <h3>Sugerencias</h3>
        <form action="" method="POST">
            <div>
                <label for="category">Categoría: </label>
                
                <select name="category" id="category">
                    <?php
                    $sql = "SELECT category FROM categories";

                    $result = $conn -> query($sql);

                    while($row = $result -> fetch_assoc()) {
                        echo '<option value="' . $row["category"] . '">' . $row["category"] . '</option>';
                    }
                    ?>
                </select>

                <input type="submit" value="Sugerir">
            </div>
        </form>
    </div>
</main>
<?php
//Footer of the page.
require_once ("../modules/footer.php");
?>