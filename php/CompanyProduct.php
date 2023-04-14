<?php
include 'dbConnection.php';

$companyId = $_POST["companyId"];

$lstofProductId = array();
$count =0;
foreach ($_POST['productId'] as $product){
    $lstofProductId[$count] = $product;
    $count++;
}



for($x = 0; $x <= count($lstofProductId); $x++){
    $sql = "INSERT INTO `CompaniesProducts`( `companyId`, `productId`) VALUES ('$companyId','$lstofProductId[$x]')";

    $result = mysqli_query($connect,$sql);

    if(!$result){
        //sweat alerts should be included in....
        echo "error inserting product.. ".mysqli_error($connect);
    }else{
        header("Location: ../htmls/sales.php");
    }
}


