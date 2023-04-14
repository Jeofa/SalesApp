<?php

include "dbConnection.php";
$date     =   $_POST["date"];
$company  =   filter_var($_POST["company"],FILTER_SANITIZE_STRING);
$product  =   filter_var($_POST["product"],FILTER_SANITIZE_STRING);
$kgs      =   filter_var($_POST["kgs"],FILTER_SANITIZE_STRING);
$quantity =   filter_var($_POST["quantity"],FILTER_SANITIZE_STRING);
$cost     =   filter_var($_POST["cost"],FILTER_SANITIZE_STRING);
$payment  =   filter_var($_POST["payment"],FILTER_SANITIZE_STRING);
$agent    =   filter_var($_POST["agent"],FILTER_SANITIZE_STRING);
$client   =   filter_var($_POST["client"],FILTER_SANITIZE_STRING);
$status   =   filter_var($_POST["status"],FILTER_SANITIZE_STRING);
$totalCost=   $quantity * $cost;

$sql = "INSERT INTO `SalesReports` (`Date`, `Company`, `Product`, `Kgs`, `Quantity`, `Cost`, `Payment`, `Agent`, `Client`, `Status`, `TotalCost`) 
        VALUES('$date','$company','$product','$kgs','$quantity','$cost','$payment','$agent','$client,','$status','$totalCost')";
$result = mysqli_query($connect,$sql);
if(!$result){
    echo "Error With my query ".mysqli_error($connect);
}
else{
    header("Location: ../htmls/salesRepo.php");
}
?>