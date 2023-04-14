<?php
include "dbConnection.php";

if(empty($_POST["company"])){

    $lstofProducts = array();
    $count =0;
    $compName = filter_var($_POST["coname"],FILTER_SANITIZE_STRING);
    // $product = filter_var($_POST["product"],FILTER_SANITIZE_STRING);
    foreach ($_POST['product'] as $product){
        // echo $product."<br>";
        $lstofProducts[$count] = $product;
        $count++;
    }
    echo "Number ".count($lstofProducts);
    for($x = 0; $x <= count($lstofProducts); $x++){
        // echo "<br>This ".$lstofProducts[$x];
    //    echo "<br>ProdId".$x." ".$lstofProducts[$x]."<br>";
        $sql = "INSERT INTO `Companies`( `Name`, `Product_id`) VALUES ('$compName','$lstofProducts[$x]')";

        $result = mysqli_query($connect,$sql);

        if(!$result){
            //sweat alerts should be included in....
            echo "error inserting product.. ".mysqli_error($connect);
        }else{
            header("Location: ../htmls/sales.php");
        }
    }
    


}else{
    $company = filter_var($_POST["company"],FILTER_SANITIZE_STRING);
    $compName = filter_var($_POST["coname"],FILTER_SANITIZE_STRING);
    $product = filter_var($_POST["product"],FILTER_SANITIZE_STRING);

    $sqa = "UPDATE `Companies` SET `Name`='$compName',`Product_id`='$product' WHERE `Name`='$company'";
    $res = mysqli_query($connect,$sqa);

    if(!$res){
        //sweat alerts should be included in....
        echo "error updating product.. ".mysqli_error($connect);
    }else{
        header("Location: ../htmls/sales.php");
    }
 
}