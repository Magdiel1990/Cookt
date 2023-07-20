<?php
//Reviso el estado de la sesión.
    session_name("Login");
    
    session_start(); 

//Models.
    require_once ("../models/models.php");

//Current location
    $_SESSION["location"] = root;

//Including the database connection.
    $conn = DatabaseConnection::dbConnection();    

    $registros = isset($_POST["registros"]) ? $conn -> real_escape_string($_POST["registros"]) : 5;
    $pagina = isset($_POST["pagina"]) ? $conn -> real_escape_string($_POST["pagina"]) : 0;

    if(!$pagina) {
        $start = 0;
        $pagina = 1;
    } else {
        $start = ($pagina - 1) * $registros;
    }   

    $limit = " LIMIT $start, $registros";
    
//Main array
    $recycle = [];  

//Type of query
    $type = "delete";

//Category array
    $sql = "SELECT categoryid, category, date FROM categories WHERE state = 0;"; 
    $result = $conn -> query($sql); 

    if($result -> num_rows != 0) {

        while ($row = $result -> fetch_assoc()) {
            array_push ($recycle, ["categories", $row["categoryid"], $row["category"], $row["date"]]);
        }
    }

//Ingredient array
    $sql = "SELECT id, ingredient, date FROM ingredients WHERE state = 0 AND username = '" . $_SESSION['username'] . "';";
    $result = $conn -> query($sql); 

    if($result -> num_rows != 0) {

        while ($row = $result -> fetch_assoc()) {
            array_push ($recycle, ["ingredients", $row["id"], $row["ingredient"], $row["date"]]);
        }
    }

//Recipe array
    $sql = "SELECT recipeid, recipename, date FROM recipe WHERE state = 0 AND username = '" . $_SESSION['username'] . "';";
    $result = $conn -> query($sql); 

    if($result -> num_rows != 0) {

        while ($row = $result -> fetch_assoc()) {
            array_push ($recycle, ["recipe", $row["recipeid"], $row["recipename"], $row["date"]]);
        }
    }

//Store the messages
$output = [];   
$output['pagination'] = '';
$output['data'] = '';

$count = count($recycle);

    if($count > 0) {
        for($i = 0; $i < count($recycle); $i++){          

            switch ($recycle[$i][0]) {
                case "recipe":
                    $log_message = $recycle[$i][2];
                    $table = "recipe";
                    $title = "RECETA";
                    $color = "text-warning";
                    break;
                case "ingredients":
                    $log_message = $recycle[$i][2];
                    $table = "ingredients";
                    $title = "INGREDIENTE";
                    $color = "text-success";
                    break;                    
                default:
                    $log_message = $recycle[$i][2];
                    $table = "categories";
                    $title = "CATEGORÍA";
                    $color = "text-primary";
            }                   

            $output['data'] .= '<div class="py-2 col-auto">'; 
            $output['data'] .= '<div class="card">';
            $output['data'] .= '<div class="card-header">' . $recycle[$i][3] . '</div>';
            $output['data'] .= '<div class="card-body">';
            $output['data'] .= '<h5 class="card-title ' . $color . '">' . $title . '</h5>';
            $output['data'] .= '<p class="card-text">' . $log_message . '</p>';
            $output['data'] .= '<div class="btn-group" role="group">';
            $output['data'] .= '<a href="' . root . 'delete?id=' .  $recycle[$i][1] . '&table=' . $table . '" class="btn btn-danger" onclick="deleteMessageLoop()">Eliminar</a>';
            $output['data'] .= '<a href="' . root . 'update?id=' .  $recycle[$i][1] . '&table=' . $table . '" class="btn btn-primary">Restaurar</a>';
            $output['data'] .= '</div>'; 
            $output['data'] .= '</div>'; 
            $output['data'] .= '</div>';  
            $output['data'] .= '</div>';  
            $output['data'] .= '</div>';              
        }

        $pageTotal = ceil($count / $registros);

        $output['pagination'] .= "<nav>";
        $output['pagination'] .= "<ul class='pagination'>";        

        $start = 1;
    //Total Tabs
        if(($pagina - 7) > 1) {
            $start = $pagina - 7;
        }

        $end = $start + 8;

        if($end > $pageTotal) {
            $end = $pageTotal;
        }
        
        for($i = $start; $i <= $end; $i++){
            if($pagina == $i){
                $output['pagination'] .= "<li class='active page-item'><a class='page-link' href='#'>" . $i . "</a></li>";
            } else {
                $output['pagination'] .= "<li class='page-item'><a class='page-link' href='#' onclick='getData(" . $i . ")'>" . $i . "</a></li>";
            }
        }
        $output['pagination'] .= "</ul>";
        $output['pagination'] .= "</nav>";  
    } else {
        $output['data'] .= '<div class="mt-4">';
        $output['data'] .= '<h3 class="text-secondary text-center">No hay notificaciones...</h3>';
        $output['data'] .= '</div>';
    }      

//Json file is encoded and echoed excluding especial characters.
    echo json_encode($output, JSON_UNESCAPED_UNICODE);

//Closing the connection.
    $conn -> close();    
?>
