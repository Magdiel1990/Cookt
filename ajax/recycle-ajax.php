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
    
//Array for the deleted items
    $recycle = [];  
    
    $recycle ['category'] = ''; 
    $recycle ['recipe'] = ''; 
    $recycle ['ingredients'] = ''; 

    $sql = "SELECT categoryid, category, date FROM categories WHERE state = 0;"; 
    $result = $conn -> query($sql); 

    if($result -> num_rows != 0) {
        while ($row = $result -> fetch_assoc()) {
            $recycle ['category']['id'] = $row["categoryid"];
            $recycle ['category']['category'] = $row["category"];
            $recycle ['category']['date'] = $row["date"];
        }
    }

    var_dump($recycle);
   
    
    $sql = "SELECT id FROM ingredients WHERE state = 0 AND username = '" . $_SESSION['username'] . "';";
    $sql = "SELECT recipeid FROM recipe WHERE state = 0 AND username = '" . $_SESSION['username'] . "';";
    $result= $conn -> query($sql); 

    $output = [];   
    $output['data'] = ''; 
    $output['pagination'] = '';

    if($result -> num_rows == 0){
        $output['data'] .= '<div class="mt-4">';
        $output['data'] .= '<h3 class="text-secondary text-center">No hay notificaciones...</h3>';
        $output['data'] .= '</div>';
    } else {  
        while($row = $result -> fetch_assoc()){
//Time ago calculation                
        $timeAgo = new DateCalculation($row["date"]); 
        $timeAgo = $timeAgo -> timeAgo(); 

        $output['data'] .= '<div class="py-2 col-auto">'; 
        $output['data'] .= '<div class="card">';
        $output['data'] .= '<div class="card-header">' . $timeAgo . '</div>';
        $output['data'] .= '<div class="card-body">';
        $output['data'] .= '<p class="card-text">' . $row ["log_message"] . '</p>';
        $output['data'] .= '<a href="' . root . 'delete?messageid=' . $row['id'] . '&type=' . $row["type"] . '" class="btn btn-danger" onclick="deleteMessageLoop()">Eliminar</a>';
                    
        if($row["type"] == "share"){
            $output['data'] .= ' <a href="' . root . 'create?messageid=' . $row['id'] . '&type=' . $row["type"] .'" class="btn btn-primary">Aceptar</a>';
        }

        $output['data'] .= '</div>'; 
        $output['data'] .= '</div>';  
        $output['data'] .= '</div>';   
        }
        $pageTotal = ceil($num_rows / $registros);

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
    }      

//Json file is encoded and echoed excluding especial characters.
    echo json_encode($output, JSON_UNESCAPED_UNICODE);

//Closing the connection.
    $conn -> close();    
?>
