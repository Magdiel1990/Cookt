<?php
function dd($value){
    echo "<pre>";
    var_dump($value);
    echo "</pre>";

}


function urlIs($value){
    return $_SERVER["REQUEST_URI"] === $value;
}
?>