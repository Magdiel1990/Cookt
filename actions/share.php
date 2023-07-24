<?php
//Name the session
session_name("Login");

//Iniciating session. 
session_start();

//Models.
require_once ("models/models.php");

//Including the database connection.
$conn = DatabaseConnection::dbConnection();

if(isset($_GET["recipe"]) && isset($_GET["username"]) && isset($_POST["email"])){  
    $recipe = unserialize(base64_decode($_GET["recipe"]));
    $username = $_GET["username"];

    $filter = new Filter ($_POST["email"], FILTER_SANITIZE_EMAIL, $conn);
    $email = $filter -> sanitization();

    if($recipe == "" || $username == "" || $email == "") {
        $_SESSION['message'] = '¡Complete todos los campos por favor!';
        $_SESSION['message_alert'] = "danger";

        header('Location: ' . root . 'recipes?recipe=' . $recipe . '&username=' . $_GET["username"]); 
        exit;
    } else {
        if(strlen($email) < 15 || strlen($email) > 70) {
            $_SESSION['message'] = '¡Cantidad de caracteres no aceptada!';
            $_SESSION['message_alert'] = "danger";

            header('Location: ' . root . 'recipes?recipe=' . $recipe . '&username=' . $_GET["username"]); 
            exit;
        } else {
            $sql = "SELECT email, username, shares FROM users WHERE email = ?;";
            $stmt = $conn -> prepare($sql); 
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt -> get_result(); 

            if($result -> num_rows == 0) {
                $_SESSION['message'] = '¡Este usuario no existe!';
                $_SESSION['message_alert'] = "danger";

                header('Location: ' . root . 'recipes?recipe=' . $recipe. '&username=' . $_GET["username"]); 
                exit;               
            } else {
                $row = $result -> fetch_assoc();
                $destination = $row["username"];
                $shares = $row["shares"];

                if ($destination == $_SESSION["username"] || $shares == 0) {
                    $_SESSION['message'] = '¡No puedes compartir la receta con este usuario!';
                    $_SESSION['message_alert'] = "danger";

                    header('Location: ' . root . 'recipes?recipe=' . $recipe . '&username=' . $_GET["username"]); 
                    exit; 
                } else {
                    $sql = "SELECT recipeid FROM recipe WHERE recipename = '$recipe' AND username = '$username' AND state = 1;";
                    $result = $conn -> query($sql); 
                    $row = $result -> fetch_assoc();
                    $recipeid = $row["recipeid"];

                    $sql = "SELECT id FROM shares WHERE share_from = '$username' AND share_to = '$destination' AND recipeid = '$recipeid';";
                    $result = $conn -> query($sql); 
                    if ($result -> num_rows > 0) {
                        $_SESSION['message'] = '¡Esta receta ya ha sido compartida!';
                        $_SESSION['message_alert'] = "danger";

                        header('Location: ' . root . 'recipes?recipe=' . $recipe. '&username=' . $_GET["username"]); 
                        exit;  
                    } else {
                        $sql = "INSERT INTO shares (share_from, share_to, recipeid) VALUES ('$username', '$destination', '$recipeid');";
                    
                        if($conn -> query($sql)) {
// Image path (sender)
                            $recipeImagesDirSender = "imgs/recipes/". $_SESSION['username'] ."/";

                            if (!file_exists($recipeImagesDirSender)) {
                                mkdir($recipeImagesDirSender, 0777, true);
                            }   

                            $files = new Directories($recipeImagesDirSender, $recipe);
                            $ext = $files -> directoryFiles();
//Image complete path (sender)
                            if($ext !== null) {
                                $imageDirSender = $recipeImagesDirSender . $recipename . "." . $ext;
// Image path (receiver)
                                $recipeImagesDirReceiver = "imgs/recipes/". $username ."/";

                                if (!file_exists($recipeImagesDirReceiver)) {
                                    mkdir($recipeImagesDirReceiver, 0777, true);
                                }  
//Image complete path (receiver)                           

                                $imageDirReceiver = $recipeImagesDirReceiver . $recipename . "." . $ext;
//Moving the file
                                rename($imageDirSender, $imageDirReceiver);
                            }

//Notification message            
                            if($_SESSION['notification'] == 1) {
                                $log_message_receiver = "El usuario " . $username . " te ha compartido la receta \"" . $recipe . "\".";
                                $log_message_sender = "Has compartido la receta \"" . $recipe . "\" con el usuario " . $destination . ".";
                            
                                $type = "share";
                                $sql = "INSERT INTO `log` (username, log_message, type, state) VALUES ('$destination', '$log_message_receiver', '$type', 0);";
                                $sql .= "INSERT INTO `log` (username, log_message, type, state) VALUES ('$username', '$log_message_sender', '$type', 0);";
                                $conn -> multi_query($sql);                     
                            }
                            
                            $_SESSION['message'] = '¡Receta ha sido compartida con ' . $email . '!';
                            $_SESSION['message_alert'] = "success";

                            header('Location: ' . root . 'recipes?recipe=' . $recipe . '&username=' . $_GET["username"]); 
                            exit;                           
                        } else {
                            $_SESSION['message'] = '¡Error al compartir receta!';
                            $_SESSION['message_alert'] = "danger";

                            header('Location: ' . root . 'recipes?recipe=' . $recipe . '&username=' . $_GET["username"]); 
                            exit;  
                        }
                    }
                }
            }
        }
    }
}
//Exiting connection
$conn -> close();

//Verify that data comes
if(empty($_POST) || empty($_GET)) {
    header('Location: ' . root);
    exit;  
}
?>