<?php
//Method for the message.
function buttonMessage($message, $message_alert) {
    if(isset($message_alert)){
        $html = "<div class='row justify-content-center'>";
        $html .= "<div class='col-auto alert alert-" . $message_alert . " alert-dismissible fade show' role='alert'>";
        $html .= "<button type='button' class='close border-0' data-dismiss='alert' aria-label='Close'>";
        $html .= "<span>" . $message . "</span>";        
        $html .= "</button>";
        $html .= "</div>"; 
        $html .= "</div>";   
        echo $html;             
    }
}

function sanitization($input, $type, $conn) {
    $input = mysqli_real_escape_string($conn, $input);   
    $input = htmlspecialchars($input);
    $input = filter_var($input, $type);
    $input = trim($input);
    $input = stripslashes($input);
    return $input;
}

function directoryFiles($directory, $fileName){

$dir_handle = opendir($directory);

while(($file = readdir($dir_handle)) !== false) {
  $path = $directory . '/' . $file;
    if(is_file($path)) {
    $name = pathinfo($path, PATHINFO_FILENAME);      
        if($name == $fileName) {
        $ext = pathinfo($path, PATHINFO_EXTENSION); 
        } 
    } else {
        $ext = "";
    }
}
closedir($dir_handle);

$imgDir = $directory . $fileName . "." . $ext;
return $imgDir;
}
?>