<?php
session_start();

require "../php/config.php";

if(is_logged_in() == false){
    header("Location: ../index.php");
}
require "header.html";

include "../php/dbConnection.php";




?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">    
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>    
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>   
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.js"></script>

<title>Home</title>
</head>
<body>
<div class="container">
        <div class="row" style=" margin-top: 5pc;">
            <div class="col-sm-4" id="saleForm">
                <!-- Form Create Agent -->
                <form action="?" method="post">
                    <legend> Analysis</legend><br>
                    <label for="" class="form-label">Company Name</label>
                    <select name="coname" id="coname" class="form-control">
                        <?php
                            $sqli = "SELECT `id`, `Name` FROM `Companies`";
                            $res = mysqli_query($connect, $sqli);
                            if(mysqli_num_rows($res) > 0){
                                while($cols = mysqli_fetch_assoc($res)){
                                    ?>
                                        <option value="<?php echo $cols["id"];?>"><?php echo $cols["Name"];?></option>
                                    <?php
                                }
                            }
                        ?>
                    </select>
                    <label for="" class="form-label">FROM</label>
                    <input type="date" name="froDate" id="" class="form-control">
                    <label for="" class="form-label">TO</label>
                    <input type="date" name="toDate" id="" class="form-control">
                    <button type="submit" style="width: 17pc; margin: 2pc 2pc;" name="btnSub"class="btn btn-success justify-content-end">Query</button>
                </form>
            </div>
            <div class="col-sm-8">
                <!-- List Edit Delete Agent -->
                <table id="table" class="table table-striped table-sm" >
                    <thead>
                        <tr>
                            <th>From</th>
                            <th>To</th>
                            <th>Product</th>
                            <th>Total Quantity</th>
                            <th>Total Cost</th>
                        </tr>
                    </thead>
                  
                    <!-- <tbody> -->
                        <?php
                           if(isset($_POST["btnSub"]) ){
   

                            $comp = $_POST["coname"];
                            $froDated = $_POST["froDate"];
                            $toDated =$_POST["toDate"];
                               
                           
                            $startDate = date('Y-m-d', strtotime($froDated));
                            $endDate = date('Y-m-d', strtotime($toDated));
                        
                            $sql = "SELECT `Product`,`Date` FROM `SalesReports` WHERE `Company` = '$comp'";
                            $results = mysqli_query($connect,$sql);
                         
                            if($results){
                                if(mysqli_num_rows($results) > 0){
                                    while($row = mysqli_fetch_assoc($results)){
                                        
                                        $currentDate = date($row["Date"]);
                                        $currentDate = date('Y-m-d', strtotime($currentDate));
                                        if(($currentDate >= $startDate) && ($currentDate <= $endDate)){
                                            $product = $row["Product"];
                                            // $sql2 = "SELECT prod.Name As Product, SUM(`Quantity`) As 'TotalQuantity', SUM(`TotalCost`)  As 'TotalCost' FROM SalesReports sr INNER JOIN Products prod ON prod.id = sr.Product WHERE `Company` = GROUP BY Product";
                                            $sql2 = "SELECT prod.Name As Product, SUM(`Quantity`) As 'TotalQuantity', SUM(`TotalCost`) As 'TotalCost'FROM SalesReports sr INNER JOIN Products prod ON prod.id = sr.Product WHERE `Company` = '$comp' GROUP BY Product";
                                            // $sql2 = "SELECT `Product`,SUM(`Quantity`) As 'TotalQuantity', SUM(`TotalCost`) As 'TotalCost' FROM `SalesReports` WHERE `Product` = '$product'AND `Company`='$comp' GROUP BY `Product`";
                                            $results2 = mysqli_query($connect,$sql2);
                                            if(mysqli_num_rows($results2) > 0){
                                                while($row2 = mysqli_fetch_assoc($results2)){
                                                    $product = $row2["Product"];
                                                    $quantity =  $row2["TotalQuantity"];
                                                    $cost = $row2["TotalCost"];
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $startDate;?></td>
                                                        <td><?php echo $endDate;?></td>
                                                        <td><?php echo $row2["Product"];?></td>
                                                        <td><?php echo $row2["TotalQuantity"];?></td>
                                                        <td><?php echo $row2["TotalCost"];?></td>
                                                    </tr>
                                                    <?php
                                                }
                                                break;
                                      
                                            }
                                        
                                        }else{
                                            echo "<script>alert('Your Date Data Is Not Valid');</script>";
                                        }
                                    }
                                }else{
                                    echo "rows";
                                }
                            }else{
                                echo "commit ".mysqli_error($connect);
                            }
                        
                        }
            

                        ?>
                </table>
                <form action="../php/WeeklySalesDownload.php" method="post">
                           
                    <input type="hidden" name="coname" value="<?php echo $comp;?>" >
                    <input type="hidden" name="froDate" value="<?php echo $froDated;?>" >
                    <input type="hidden" name="toDate" value="<?php echo $toDated;?>" >
                     
                    <button type="submit" name="generate" class="btn btn-success form-control"  style="margin-top: 2pc; margin-left: 10pc;margin-bottom: 4pc; width: 30pc;background-color: darkcyan; font-size: larger; font-family: none;">Generate Pdf</button>

                </form>
            </div> 
        </div>
    </div>

</body>
<script>
     $(document).ready( function () {
        $('#table').DataTable();

    } );
</script>
</html>