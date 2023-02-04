<?php
    //Including the database connection.
    require_once ("config/db_Connection.php");
    
    //Array of the columns to be querried from the database.
    $columns = ["recipeid","recipename", "category"];
    
    //Table to be querried from the database.
    $table = "recipeinfoview";

    //If the variable search is set it's received, else it's null.
    $field = isset($_POST["search"]) ? $conn -> real_escape_string($_POST["search"]) : null;
    
    /*Filter where*/
    $where = "";

    //If the variable search isn't null, the query is done with the where.
    if($field != null){
        $where = "WHERE (";

        $count = count($columns);
        for($i = 0; $i < $count; $i++){
            $where .= $columns[$i] . " LIKE '%" . $field . "%' OR ";
        }
    //The final where delection.
        $where = substr_replace($where, "", -3);
        $where .= ")";
    }        
    

    $sql = "SELECT SQL_CALC_FOUND_ROWS ". implode(", ", $columns) . " 
    FROM $table 
    $where";

    //Count of the number of rows of the query
    $result = $conn->query($sql);
    $num_rows = $result-> num_rows;

    $output = [];
    $output['data'] = '';

    //If there are results for the query , they are shown.
    if($num_rows > 0) {
        while($row = $result->fetch_assoc()){
            $output['data'] .= "<tr>";
            $output['data'] .= "<td ><a href='./actions/recipe_viewer.php?recipe=" . $row['recipename'] . "'>" . $row['recipename'] . "</a></td>";
            $output['data'] .= "<td>" .$row['category']. "</td>";
            $output['data'] .= "<td>";
            $output['data'] .= "<a href='actions/edit.php?name=" . $row['recipename'] . "' " . "class='btn btn-secondary' title='Editar'><i class='fa-solid fa-pen'></i></a>";
            $output['data'] .= "<a href='actions/delete.php?name=" . $row['recipename'] . "' " . "class='btn btn-danger' title='Eliminar'><i class='fa-solid fa-trash'></i></a>";
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
