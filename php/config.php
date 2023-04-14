<?php
session_start();

function is_logged_in(){
    if(isset($_SESSION['usermail'])){
        return true;
    }else{
        return false;
    }
}
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}