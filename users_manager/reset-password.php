<?php
//Models.
require_once ("models/models.php");

//Including the database connection.
$conn = DatabaseConnection::dbConnection();

if(isset($_GET["r_code"])){
    $recovery_code = $_GET["r_code"];

    if(strlen($recovery_code) != 32) {
        header('Location: ' . root . 'not-found');
        exit;
    } else {
        $sql = "SELECT userid FROM recovery WHERE forgot_pass_identity = ?;";        
        $stmt = $conn -> prepare($sql); 
        $stmt->bind_param("s", $recovery_code);
        $stmt->execute();

        $result = $stmt -> get_result(); 
        $num_rows  = $result -> num_rows;
        if($num_rows != 0) {
            $row = $result -> fetch_assoc();
            $userid = $row ["userid"];
            header('Location: ' . root . 'recovery-page?id=' . $userid . '&pass=' . $recovery_code);
            exit;
        } else {
            header('Location: ' . root . 'not-found');
            exit;
        }
    } 
}

$conn -> close();

//Verify that data comes
if(empty($_GET) && empty($_POST)) {
    header('Location: ' . root);
    exit;  
}
?>
