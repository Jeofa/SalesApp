<?php
include "dbConnection.php";

if(isset($_POST["generate"])){
    $company = $_POST["company"];

    $date;$kgs;$quantity;$cost;$totalCost;$status;$product;
    $sql="SELECT repo.Date As Date,repo.Kgs,repo.Quantity,repo.Cost,repo.TotalCost,repo.Status, prd.Name As Product,comp.Name As Company FROM SalesReports repo LEFT JOIN Products prd ON prd.id = repo.Product INNER JOIN Companies comp ON comp.id = repo.Company WHERE comp.Name='$company'";
    $result = mysqli_query($connect,$sql);


    require "../fpdf/fpdf.php";



    $pdf = new FPDF('l','mm','A4');
    $pdf->AddPage();
    $pdf->setFont('Arial','B',14);

    // Set Pdf Title
  
    $pdf->Cell(240,10, "DAILY SALE FOR: ".$company,0,1,'C',false);
    $pdf->Ln(10);

    $pdf->cell(40,10,"Date",1,0,'C');
    $pdf->cell(40,10,"Kgs",1,0,'C');
    $pdf->cell(40,10,"Quantity",1,0,'C');
    $pdf->cell(40,10,"Total Cost",1,0,'C');
    $pdf->cell(40,10,"Status",1,0,'C');
    $pdf->cell(40,10,"Product",1,1,'C');
    // $pdf->cell(40,10,"Company",1,1,'C');

    $pdf->setFont('Arial','',12);
   
    $sumCost = 0;
    $sumQnty = 0;
    $sumkgs = 0;
    while($row = mysqli_fetch_assoc($result)){
        $pdf->cell(40,10,$row["Date"],1,0,'C');
        $pdf->cell(40,10,$row["Kgs"],1,0,'C');
        $pdf->cell(40,10,$row["Quantity"],1,0,'C');
        $pdf->cell(40,10,$row["TotalCost"],1,0,'C');
        $pdf->cell(40,10,$row["Status"],1,0,'C');
        $pdf->cell(40,10,$row["Product"],1,1,'C');
        $sumCost += $row["TotalCost"];
        $sumQnty += $row["Quantity"];
        $sumkgs += $row["Kgs"];
    }   
    $pdf->cell(80,20,'Total Kgs: '.$sumkgs,1,0,'R');
    $pdf->cell(40,20,'Total Quantity: '.$sumQnty,1,0,'C');
    $pdf->cell(40,20,'Total Cost: '.$sumCost,1,1,'C');

    $pdf->OutPut();
}
?>
