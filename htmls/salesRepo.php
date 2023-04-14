<?php
session_start();

require "../php/config.php";

if(is_logged_in() == false){
    header("Location: ../index.php");
}

include "header.html";
include "../php/dbConnection.php";
include "../php/repoTry.php";
$lst ="Trial";


// Update Transactions Status

if(isset($_GET["Transcation"])){
    $id = $_GET["Transcation"];
    // echo "<script>var id = $id; alert('Clicked id'+id);</script>";

    $myUpdate = "UPDATE `SalesReports` SET `Status`='paid' WHERE `id` = '$id'";
    $results = mysqli_query($connect,$myUpdate);
    if($results){
        echo "<script>alert('Transaction Updated!!');</script>";
    }


}

//delete transaction
if(isset($_GET["id"]) && isset($_GET["product"])){
    $id = $_GET["id"];

    $sql = "DELETE FROM `SalesReports` WHERE `id` =  '$id'";
    $result = mysqli_query($connect,$sql);
    if($result){
        echo "<script>alert('Transaction Deleted!!');</script>";

    }

}
       ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/salesReport.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <link rel="stylesheet" href="../DataTables/tables.css">
    <script src="../DataTables/tables.js"></script>
    <script type="text/javascript" src="https://raw.githack.com/eKoopmans/html2pdf/master/dist/html2pdf.bundle.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.js"></script>
    <title>Sales Report</title>
</head>
<body>
    <div class="container-fluid">
        <div style="margin-top: 2pc; margin-bottom: 3pc;">

                <!-- Create Report -->
            <h3>Record Daily Sales</h3>
            <form action="../php/SalesReportDB.php" method="post">
                <table class="table">
                    <tr>
                        <th>Date</th>
                        <th>Company</th>
                        <th>Product</th>
                        <th>Kgs</th>
                        <th>Quantity</th>
                        <th>Cost</th>
                        <th>Mode Of Payment</th>
                        <th>Agent</th>
                        <th>Status</th>
                        <th>Client</th>

                    </tr>
                
                    <tr>
                        <td><input type="date"  name="date" id="" class="form-control" style="width: 118px;" required></td>
                        <td>
                            <select name="company" id="" class="form-control" required>
                            <?php
                                for($r=0;$r<$GLOBALS["lenOfCompany"];$r++){
                                    ?>
                                    <option value="<?php echo $lstCompany[$r][$r];?>"><?php echo $lstCompany[$r][$r+1];?></option>
                                    <?php
                                }
                            ?>
                            </select>
                        </td>
                        <td>
                            <select name="product" id="" class="form-control" required>
                            <?php
                                for($r=0;$r<$GLOBALS["lenOfProduct"];$r++){
                                    ?>
                                    <option value="<?php echo $lstProducts[$r][$r];?>"><?php echo $lstProducts[$r][$r+1];?></option>
                                    <?php
                                }
                            ?>
                            </select>
                        </td>
                        <td>
                            <select name="kgs" id="" class="form-control" required>
                                <option value="50">50Kgs</option>
                                <option value="25">25kgs</option>
                                <option value="10">10kgs</option>
                            </select>
                        </td>
                        <td>
                            <input type="number"  name="quantity" id="" class="form-control" style="width: 118px;" required>
                        </td>
                        <td>
                            <input type="number" name="cost" id="" class="form-control" style="width: 118px;" required>
                        </td>
                        <td>
                            <select name="payment" id="" class="form-control" required>
                                <option value="Cash">Cash</option>
                                <option value="Cheque">Cheque</option>
                                <option value="Debt">Debt</option>
                            </select>
                        </td>
                        <td>
                            <select name="agent" id="" class="form-control" required>
                            <?php
                                for($r=0;$r<$GLOBALS["lenOfAgent"];$r++){
                                    ?>
                                    <option value="<?php echo $lstAgents[$r][$r];?>"><?php echo $lstAgents[$r][$r+1];?></option>
                                    <?php
                                }
                            ?>
                            </select>
                        </td>
                        <td>
                            <select name="status" id="" class="form-control" required>
                                <option value="paid">Paid</option>
                                <option value="pending">Pending</option>
                            </select>
                        </td>
                        <td>
                            <input type="tel" name="client" id="" class="form-control" style="width: 118px;" required>
                        </td>
                    </tr>
                    
                </table>
                <button type="submit" class="form-control btn btn-info">submit</button>

            </form>

        </div>

        <!-- Display reports  -->

        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-item nav-link active" id="nav-daily-tab" data-toggle="tab" href="#nav-daily" role="tab" aria-controls="nav-home" aria-selected="true">Weekly</a>
            </div>
        </nav>
            <div class="tab-content" id="nav-tabContent">

                <!-- Daily Sales Structs -->
                <div class="tab-pane fade show active" id="nav-daily" role="tabpanel" aria-labelledby="nav-daily-tab">
            
                    <table class="table" id="table">
                        <thead>
                        <tr>
                       
                            <th>Date</th>
                            <th>Product</th>
                            <th>Kgs</th>
                            <th>Quantity</th>
                            <th>Cost</th>
                            <th>Total Cost</th>
                            <th>Company</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <?php
                        $sql="SELECT repo.id As id, repo.Date As Date,repo.Kgs,repo.Quantity,repo.Cost,repo.TotalCost,repo.Status, prd.Name As Product,comp.Name As Company FROM SalesReports repo LEFT JOIN Products prd ON prd.id = repo.Product INNER JOIN Companies comp ON comp.id = repo.Company";
                        $result = mysqli_query($connect,$sql);
                        if($result){
                            if(mysqli_num_rows($result) > 0){
                                while($row = mysqli_fetch_assoc($result)){
                                 ?>
                              <tbody>                  
                                    <tr>
                                        <td> <?php echo $row["Date"];?></td>
                                        <td><?php echo $row["Product"];?></td>
                                        <td><?php echo $row["Kgs"];?></td>
                                        <td> <?php echo $row["Quantity"];?></td>
                                        <td><?php echo $row["Cost"];?></td>
                                        <td><?php echo $row["TotalCost"];?></td>
                                        <td><?php echo $row["Company"]; ?></td>
                                        <td><?php echo $row["Status"];?></td>
                                        <td>
                                           <a href="salesRepo.php?Transcation=<?php echo $row["id"];?>" style="color: white;text-decoration: none;" ><button onClick="if (!confirm('Confirm Status Change To Paid')) return false" class="btn btn-info" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i>Status  </button></a>
                                           <a href="salesRepo.php?id=<?php echo $row["id"];?>&product=<?php echo $row["Product"];?>" style="color: white;text-decoration: none;"><button onClick="if (!confirm('Confirm Delete')) return false" class="btn btn-danger" ><i class="fa fa-trash" aria-hidden="true"></i>  </button></a>
                                        </td>

                                    </tr>
                                </tbody> 
                        <?php
                                }
                            }
                        }
                        ?>
                    </table>
                    <!-- For Daily Sales Download -->
                    <form action="../php/DownloadSales.php" method="post">
                        <small style=" margin-left: 26pc; color: red; font-family: ui-monospace; margin-top: 1pc;">Select company</small>
                        <select name="company" id="" class="form-control" style="width: 21pc; color: darkred; margin-left: 26pc; margin-bottom: 2pc;">
                             <?php
                                for($r=0;$r<$GLOBALS["lenOfCompany"];$r++){
                                    ?>
                                    <option value="<?php echo $lstCompany[$r][$r+1];?>"><?php echo $lstCompany[$r][$r+1];?></option>
                                    <?php
                                }
                            ?>
                        </select>
                        <button type="submit" name="generate" class="btn btn-success form-control"  style="margin-bottom: 4pc; width: 30pc;background-color: darkcyan; font-size: larger; font-family: none; margin-left: 22pc;">Generate Pdf</button>

                    </form>

                </div>
                <!-- End Daily Sales Structs -->

            </div>
    </div>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>

    <script type="text/javascript" src="libs/jsPDF/jspdf.min.js"></script>

<script type="text/javascript" src="libs/jsPDF-AutoTable/jspdf.plugin.autotable.js"></script>

<script type="text/javascript" src="tableExport.min.js"></script>
</body>
<script>
     $(document).ready( function () {
        var table = $('#table').DataTable();
        $('.myMonthlyform').on('submit', function(event) {
            event.preventDefault();
        }
    );
}); 
var mm = "<?php echo $_SESSION["user"];?>";
console.log(mm);
    
</script>
</html>