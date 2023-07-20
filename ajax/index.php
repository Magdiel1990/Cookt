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
    
//Array of the columns to be querried from the database.
    $columns = ["r.recipename", "c.category", "r.cookingtime"];
    
//Table to be querried from the database.
    $table = "recipe r JOIN categories c ON r.categoryid = c.categoryid";

//If the variable search is set it's received, else it's null. 
    $search = isset($_POST["search"]) ? $conn -> real_escape_string($_POST["search"]) : null; 

//Filter where
    $where = " WHERE username = '" . $_SESSION['username'] . "' AND r.state = 1";

//If the variable search isn't null, the query is done with the where.
    if($search != null){
        $where = "WHERE (";

        $count = count($columns);
        for($i = 0; $i < $count; $i++){
            $where .= $columns[$i] . " LIKE '%" . $search . "%' OR ";
        }

//The final where delection.
        $where = substr_replace($where, "", -3);
        $where .= "AND username = '" . $_SESSION['username'] . "' AND r.state = 1)";
    }        

// Limit
    $registros = isset($_POST["registros"]) ? $conn -> real_escape_string($_POST["registros"]) : 5;
    $pagina = isset($_POST["pagina"]) ? $conn -> real_escape_string($_POST["pagina"]) : 0;
    
    if(!$pagina) {
        $start = 0;
        $pagina = 1;
    } else {
        $start = ($pagina - 1) * $registros;
    }
    
    $limit = "LIMIT $start, $registros";

//Query with limit
    $sql = "SELECT SQL_CALC_FOUND_ROWS ". implode(", ", $columns) . " 
    FROM $table 
    $where ORDER BY recipename $limit;";

//Count of the number of rows of the query
    $result = $conn->query($sql);
    $num_rows = $result-> num_rows;

//filtered register query
    $sqlFilter = "SELECT FOUND_ROWS()";
    $resFilter = $conn->query($sqlFilter);
    $rowFilter = $resFilter->fetch_array();
    $totalFilter = $rowFilter[0];

//filtered register query
    $sqlTotal = "SELECT count(*) FROM recipe WHERE username = '" . $_SESSION['username'] . "' AND state = 1";
    $resTotal = $conn->query($sqlTotal);
    $rowTotal = $resTotal->fetch_array();

    $totalRegister = $rowTotal[0];

    $output = [];    
    $output['totalFilter'] = $totalFilter; 
    $output['totalRegister'] = $totalRegister;
    $output['data'] = '';
    $output['pagination'] = '';
//If there are results for the query , they are shown.
    if($num_rows > 0) {
        while($row = $result->fetch_assoc()){            
            $output['data'] .= "<tr>";                    
           // $output['data'] .= "<td class='p-3'>" . $row['count'] . "</td>";
            $output['data'] .= "<td><a href='" . root . "recipes?recipe=" . $row['recipename'] . "&username=" . $_SESSION['username'] . "' title='receta' class='tlink'>" . $row['recipename'] . "</a></td>";
            $output['data'] .= "<td>" . $row['cookingtime'] . "</td>";
            $output['data'] .= "<td>" .ucfirst($row['category']). "</td>";            
            $output['data'] .= "<td>";
            $output['data'] .= "<div class='btn-group' role='group'>";
            $output['data'] .= "<a href='" . root . "edit?recipename=" . $row['recipename'] . "&username=" . $_SESSION['username'] . "'" . "class='btn btn-outline-secondary' title='Editar'><i class='fa-solid fa-pen'></i></a>";
            $output['data'] .= "<a href='" . root . "delete?recipename=" . $row['recipename'] . "' " . "class='btn btn-outline-danger' title='Eliminar' onclick='deleteMessage()' id='recipe'><i class='fa-solid fa-trash'></i></a>";
            $output['data'] .= "</div>";
            $output['data'] .= "</td>";                     
            $output['data'] .= "</tr>";
            
        }
    } else {
//Else this message is shown.
        $output['data'] .= "<tr>";
        $output['data'] .= "<td colspan = '3'>No hay resultados</td>";
        $output['data'] .= "</tr>";
    }

    if($output['totalRegister'] > 0) {
        $pageTotal = ceil($output['totalRegister'] / $registros);
        $filterTotal = ceil($output['totalFilter'] / $registros);

        $output['pagination'] .= "<nav>";
        $output['pagination'] .= "<ul class='pagination'>";
        
        $start = 1;
//Total Tabs
        if(($pagina - 7) > 1) {
            $start = $pagina - 7;
        }

        $end = $start + 8;

        if($end > $filterTotal) {
            $end = $filterTotal;
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
