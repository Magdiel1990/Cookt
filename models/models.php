<?php
//Method for the message.
function buttonMessage($message, $message_alert) {
    $html = "";
    if(isset($message_alert)){
        $html .= "<div class='row justify-content-center'>";
        $html .= "<div class='col-auto'>";
        $html .= "<div class='alert alert-" . $message_alert . " alert-dismissible fade show' role='alert'>";
        $html .= "<span>" . $message . "</span>";
        $html .= "<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
        <i class='fa-regular h6 text-secondary fa-circle-xmark'></i></button>";
        $html .= "</div>"; 
        $html .= "</div>";      
        $html .= "</div>";   
        echo $html;             
    }
}

function sanitization($input, $type) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    $input = filter_var($input, $type);
    return $input;
  }
?>