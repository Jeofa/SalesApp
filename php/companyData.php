<?php
include "dbConnection.php";

$name = $_POST["coname"];
$location = $_POST["location"];

$sql = "INSERT INTO `Companies`( `Name`, `Location`) VALUES('$name','$location')";
if(mysqli_query($connect,$sql)){
    header("Location: ../htmls/sales.php");
}else{
    echo "Data Not submitted ".mysqli_error($connect);
}