<?php
include "dbConnection.php";

if(isset($_POST["generate"])){

    $comp = $_POST["coname"];
    $froDated = $_POST["froDate"];
    $toDated =$_POST["toDate"];

    $startDate = date('Y-m-d', strtotime($froDated));
    $endDate = date('Y-m-d', strtotime($toDated));

    $sql = "SELECT `Product`,`Date` FROM `SalesReports` WHERE `Company` = '$comp'";
    $results = mysqli_query($connect,$sql);
    // get the company name
    $queryComp = "SELECT `Name` FROM `Companies` WHERE `id` = '$comp'";
    $commit = mysqli_query($connect,$queryComp);
    while($col = mysqli_fetch_assoc($commit)){
        $myCompany = $col["Name"];
    }

    require "../fpdf/fpdf.php";


    $pdf = new FPDF('l','mm','A4');
    $pdf->AddPage();
    $pdf->setFont('Arial','B',14);

    // Set Pdf Title
  
    $pdf->Cell(240,10, "SALE FOR: ".$myCompany,0,1,'C',false);
    $pdf->Ln(10);

    $pdf->cell(40,10,"From",1,0,'C');
    $pdf->cell(40,10,"To",1,0,'C');
    $pdf->cell(40,10,"Product",1,0,'C');
    $pdf->cell(40,10,"Total Quantity",1,0,'C');
    $pdf->cell(40,10,"Total Cost",1,1,'C');

    $pdf->setFont('Arial','',12);


    if($results){
        if(mysqli_num_rows($results) > 0){
            while($row = mysqli_fetch_assoc($results)){
                
                $currentDate = date($row["Date"]);
                $currentDate = date('Y-m-d', strtotime($currentDate));
                if(($currentDate >= $startDate) && ($currentDate <= $endDate)){

                    $product = $row["Product"];
                    $sql2 = "SELECT prod.Name As Product, SUM(`Quantity`) As 'TotalQuantity', SUM(`TotalCost`) As 'TotalCost'FROM SalesReports sr INNER JOIN Products prod ON prod.id = sr.Product WHERE `Company` = '$comp' GROUP BY Product";
                    $results2 = mysqli_query($connect,$sql2);

                    if(mysqli_num_rows($results2) > 0){
                        while($row2 = mysqli_fetch_assoc($results2)){
                           
                            $pdf->cell(40,10,$startDate,1,0,'C');
                            $pdf->cell(40,10,$endDate,1,0,'C');
                            $pdf->cell(40,10,$row2["Product"],1,0,'C');
                            $pdf->cell(40,10,$row2["TotalQuantity"],1,0,'C');
                            $pdf->cell(40,10,$row2["TotalCost"],1,1,'C');  
                        
                        }
                        break;
                
                    }
                
                }
            }
        }
    }


    $pdf->OutPut();
}
?>
