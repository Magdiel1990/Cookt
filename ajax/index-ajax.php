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
    $filter = new Filter ($_POST["search"], FILTER_SANITIZE_STRING, $conn);
    $field = $filter -> sanitization(); 

//Filter where
    $where = " WHERE username = '" . $_SESSION['username'] . "'";

//If the variable search isn't null, the query is done with the where.
    if($field != ""){
        $where = "WHERE (";

        $count = count($columns);
        for($i = 0; $i < $count; $i++){
            $where .= $columns[$i] . " LIKE '%" . $field . "%' OR ";
        }
//The final where delection.
        $where = substr_replace($where, "", -3);
        $where .= "AND username = '" . $_SESSION['username'] . "')";
    }        

    $sql = "SELECT DISTINCT SQL_CALC_FOUND_ROWS ". implode(", ", $columns) . " 
    FROM $table 
    $where ORDER BY rand();";

//Count of the number of rows of the query
    $result = $conn->query($sql);
    $num_rows = $result-> num_rows;

    $output = [];
    $output['data'] = '';
//If there are results for the query , they are shown.
    if($num_rows > 0) {
        while($row = $result->fetch_assoc()){
            
            $output['data'] .= "<tr>";                    
            $output['data'] .= "<td class='px-2'><a href='" . root . "recipes?recipe=" . $row['recipename'] . "&username=" . $_SESSION['username'] . "' title='receta' class='tlink'>" . $row['recipename'] . "</a></td>";
            $output['data'] .= "<td class='text-center px-2'>" . $row['cookingtime'] . "</td>";
            $output['data'] .= "<td class='px-2'>" .ucfirst($row['category']). "</td>";            
            $output['data'] .= "<td class='px-2'>";
            $output['data'] .= "<div class='btn-group' role='group'>";
            $output['data'] .= "<a href='" . root . "edit?recipename=" . $row['recipename'] . "&username=" . $_SESSION['username'] . "'" . "class='btn btn-outline-secondary' title='Editar'><i class='fa-solid fa-pen'></i></a>";
            $output['data'] .= "<a href='" . root . "delete?recipename=" . $row['recipename'] . "' " . "class='btn btn-outline-danger' title='Eliminar'><i class='fa-solid fa-trash'></i></a>";
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
//Json file is encoded and echoed excluding especial characters.
    echo json_encode($output, JSON_UNESCAPED_UNICODE);

//Closing the connection.
    $conn -> close();    
?>
