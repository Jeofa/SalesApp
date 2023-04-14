<?php
include "dbConnection.php";

//list of companies 
$lstCompany = array(array());

//list of products
$lstProducts = array(array());

//list of agents
$lstAgents = array(array());
// y(array());

$count = 0;

// Get All Companies
$companies = "SELECT  `Name` FROM `Companies`";

$sql = "SELECT comp.id As CompanyId, comp.Name As CompanyName,prod.id As ProductId, prod.Name As ProductName FROM Companies comp RIGHT JOIN Products prod ON comp.id = prod.id";
$result = mysqli_query($connect,$sql);
if($result){
    if(mysqli_num_rows($result) > 0){
        
        while($row = mysqli_fetch_assoc($result)){
            if($row["CompanyId"] == null){
                // $lstCompany[$count][$count] = $row["CompanyId"];
                // $lstCompany[$count][$count+1] = $row["CompanyName"];
                $lstProducts[$count][$count] = $row["ProductId"];
                $lstProducts[$count][$count+1] = $row["ProductName"];
            }else{
                $lstProducts[$count][$count] = $row["ProductId"];
                $lstProducts[$count][$count+1] = $row["ProductName"];
                $lstCompany[$count][$count] = $row["CompanyId"];
                $lstCompany[$count][$count+1] = $row["CompanyName"];
            }
            
            
            $count++;
        }
    }else{
        echo "No data Found!!";
    }
}else{
    echo "Error SQL:  ".mysqli_error($connect);
}


//for Agents
$counter = 0;
$sql2 = "SELECT `id`,`Name` FROM `Agents`";
$result2 =  mysqli_query($connect,$sql2);
if($result2){
    if(mysqli_num_rows($result2) > 0){
        while($row = mysqli_fetch_assoc($result2)){
            $lstAgents[$counter][$counter] = $row["id"];
            $lstAgents[$counter][$counter+1] = $row["Name"];
            $counter++;
        }
    }else{
        echo "No data Found!!";
    }
}else{
    echo "Error SQL:  ".mysqli_error();
}


$lenOfCompany = count($lstCompany);
$lenOfProduct = count($lstProducts);
$lenOfAgent = count($lstAgents);
// echo $lstCompany[0][1];







?>
