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

$registros = isset($_POST["registros"]) ? $conn -> real_escape_string($_POST["registros"]) : 10;
$pagina = isset($_POST["pagina"]) ? $conn -> real_escape_string($_POST["pagina"]) : 0;

if(!$pagina) {
    $start = 0;
    $pagina = 1;
} else {
    $start = ($pagina - 1) * $registros;
}   

$limit = " LIMIT $start, $registros";

//Store the messages
$output = [];   
$output['pagination'] = '';
$output['data'] = '';
$output['totalRegister'] = '';

//Array of the columns to be querried from the database.
$columns = ["id", "name", "type", "date", "elementid"];

//Query with limit
$sql = "SELECT SQL_CALC_FOUND_ROWS ". implode(", ", $columns) . "
FROM recycle WHERE username = '" . $_SESSION["username"] . "' ORDER BY id desc" . $limit .";";
$result = $conn -> query($sql); 
$num_rows = $result -> num_rows;

//filtered register query
$sqlFilter = "SELECT FOUND_ROWS()";
$resFilter = $conn->query($sqlFilter);
$rowFilter = $resFilter->fetch_array();
$totalFilter = $rowFilter[0];

//filtered register query
$sqlTotal = "SELECT count(id) FROM recycle WHERE username = '" . $_SESSION["username"] . "';";   
$resTotal = $conn->query($sqlTotal);
$rowTotal = $resTotal->fetch_array();

$totalRegister = $rowTotal[0]; 

//Total registers
$output['totalRegister'] = $totalRegister;

if($totalFilter > 0) {
    while($row = $result->fetch_assoc()){
        switch ($row ["type"]) {
            case "Receta":
                $table = "recipe";
                $title = "RECETA";
                $color = "warning";
                break;
            case "Ingrediente":
                $table = "ingredients";
                $title = "INGREDIENTE";
                $color = "success";
                break;                    
            default:
                $table = "categories";
                $title = "CATEGORÍA";
                $color = "secondary";
        }                   

        $output['data'] .= '<div class="py-2 col-auto">'; 
        $output['data'] .= '<div class="card">';
        $output['data'] .= '<h5 class="card-header text-center text-light bg-'. $color . '">' . $row['date'] . '</h5>';
        $output['data'] .= '<div class="card-body">';
        $output['data'] .= '<h6 class="card-title text-center text-'. $color . '">' . $title . '</h6>';
        $output['data'] .= '<p class="card-text">' . $row['name'] . '</p>';
        $output['data'] .= '<div class="btn-group" role="group">';
        $output['data'] .= '<a href="' . root . 'delete?id='. $row['elementid'] .'&table=' . $table . '" class="btn btn-outline-danger" onclick="deleteMessageLoop()">Eliminar</a>';
        $output['data'] .= '<a href="' . root . 'update?id='. $row['elementid'] .'&table=' . $table . '" class="btn btn-primary">Restaurar</a>';
        $output['data'] .= '</div>'; 
        $output['data'] .= '</div>'; 
        $output['data'] .= '</div>';  
        $output['data'] .= '</div>';  
        $output['data'] .= '</div>';              
    } 
} else {
    $output['data'] .= '<div class="mt-4">';
    $output['data'] .= '<h3 class="text-secondary text-center">Ningún elemento encontrado...</h3>';
    $output['data'] .= '</div>';
}  


if($totalRegister > 0) {
    $totalRegister = ceil($totalRegister / $registros);

    $output['pagination'] .= "<nav>";
    $output['pagination'] .= "<ul class='pagination'>";        

    $start = 1;
//Total Tabs
    if(($pagina - 7) > 1) {
        $start = $pagina - 7;
    }

    $end = $start + 8;

    if($end > $totalRegister) {
        $end = $totalRegister;
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
}


//Json file is encoded and echoed excluding especial characters.
    echo json_encode($output, JSON_UNESCAPED_UNICODE);

//Closing the connection.
    $conn -> close();    
?>
