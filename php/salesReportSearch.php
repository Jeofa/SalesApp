<?php
include "dbConnection.php";

$comp = $_POST["coname"];
$froDated = $_POST["froDate"];
$toDated = $_POST["toDate"];

 echo "SELECT `Company`, `Product`, `Quantity`,`TotalCost` FROM `saleReport` WHERE `Company`=Ajab";
$sql = "SELECT `Product`, `Quantity`, `Cost`,`Date` FROM `saleReport` WHERE `Company` = '$comp'";
$results = mysqli_query($connect,$sql);
if($results){
    if(mysqli_num_rows($results) > 0){
        while($row = mysqli_fetch_assoc($results)){
               
            if($froDated <= $row["Date"] && $row["Date"] <= $toDated){
                echo $row["Product"];echo $row["Quantity"];echo $row["Cost"];
            
            }
        }
    }
}

?>