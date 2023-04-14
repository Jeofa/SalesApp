<?php



$connect = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if($connect == false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

?>