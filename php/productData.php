<?php
include "dbConnection.php";

$name = $_POST["name"];
$desc = $_POST["prodDesc"];

$sql = "INSERT INTO `Products`(`Name`, `Description`) VALUES('$name','$desc')";
if(mysqli_query($connect,$sql)){
    header("Location: ../htmls/sales.php");
}else{
    echo "Data Not submitted ".mysqli_error($connect);
}