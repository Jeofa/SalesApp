<?php

// include "php/dbConnection.php";


$CompProds = "SELECT cp.id As Id, co.Name As Companies , pr.Name As Products FROM CompaniesProducts cp INNER JOIN Companies co ON cp.companyId = co.id INNER JOIN Products pr ON pr.id = cp.productId";

$resultCP = mysqli_query($connect,$CompProds);
$listOfCompaniesAndProducts = array(array());
$idCP;
$countCP = 0;
if($resultCP){
    if(mysqli_num_rows($resultCP) > 0){
        while($rowCP = mysqli_fetch_assoc($resultCP)){
            $idCP = $rowCP["Id"];
            $listOfCompaniesAndProducts[$countCP][$countCP] = $rowCP["Companies"];
            $listOfCompaniesAndProducts[$countCP][$countCP+1]= $rowCP["Products"];
            $CP++;
        }
    }
}

for($xV = 0 ;$xV <= count($listOfCompaniesAndProducts) ; $xV++){
   
    $CompanyNames = $listOfCompaniesAndProducts[$xV][$xV];
    $ProductsNames = $listOfCompaniesAndProducts[$xV][$xV+1];
        
}

?>