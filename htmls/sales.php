<?php
session_start();

require "../php/config.php";

if(is_logged_in() == false){
    header("Location: ../index.php");
}

require "header.html";
include "../php/dbConnection.php";
include "../htmls/CompProd.php";

$lstProducts = array(array());
$counter = 0;
$query = "SELECT `id`, `Name` FROM `Products`";
$result = mysqli_query($connect,$query);
if($result){
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_assoc($result)){
            $lstProducts[$counter][$counter] = $row["id"];
            $lstProducts[$counter][$counter+1] = $row["Name"];
            $counter++;
        }
    }
}
$lenOfProducts = count($lstProducts);


// Handle the delete method
if(isset($_GET["idDel"]) ){

    $id = $_GET["idDel"];
    $delete = "DELETE FROM `CompaniesProducts` WHERE `id` = '$id'";
    $DelRes = mysqli_query($connect,$delete);
    if($DelRes){
        echo "<script>alert('Deleted Successfully.');</script>";
        header("Location: sales.php");
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Sales</title>
    <link rel="stylesheet" href="../css/sales.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.js"></script>
    
</head>
<body>
<div class="container">
        <div class="row" style=" margin-top: 5pc;">
            <div class="col-sm-5" id="saleForm" style="margin-right: 7pc;">
                <!-- Form Create Product -->
                <form action="../php/productData.php" method="post">
                    <legend> Product</legend><br>
                    <label for="" class="label">Product Name</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                    <label for="" class="label">Product Description</label>
                    <textarea name="prodDesc" id="" cols="30" rows="5" class="form-control" required></textarea><br><br>           
                    <button type="submit"  style="margin-left: 4pc;margin-bottom: 1pc; width: 14pc;" class="btn btn-outline-success justify-content-end">Create</button>
                </form>
            </div>
            <div class="col-sm-5" id="saleForm" style="margin-right: 1pc;">
                <!-- Form Create Company -->
                <form action="../php/companyData.php" method="post">
                    <legend> Company</legend><br>
                    <label for="" class="label">Company Name</label>
                    <input type="text" name="coname" id="name" class="form-control" required>
                    <label for="" class="label">Location</label>
                    <input type="text" name="location" id="location" class="form-control" required>
                    <button type="submit"  style="margin-left: 4pc;margin-bottom: 1pc; width: 20pc; margin-top: 3pc;" class="btn btn-outline-success justify-content-end">Create</button>
                </form>
            </div>
            <!-- Row to Assign Data -->
            <div class="row">
                <div class="col-sm-12" style="background-color: aliceblue;width: 32pc;margin-left: 13pc;">
                <form action="../php/CompanyProduct.php" method="post">
                    <legend>Assign Products To Company</legend>
                    <label for="" class="label">Select Companies</label>
                    <select name="companyId" id="" class="form-control" required>
                        <?php
                            // Get all companies 
                            $comp = "SELECT `id`, `Name` FROM `Companies`";
                            $resComp = mysqli_query($connect,$comp);
                            if(mysqli_num_rows($resComp) > 0){
                                while($rowComp = mysqli_fetch_assoc($resComp)){
                                ?>
                                    <option value="<?php echo $rowComp['id'];?>"><?php echo $rowComp['Name'];?></option>
                                <?php
                                }
                            }
                        ?>
                    </select>
                    <label for="" class="form-label">Select Products</label>
                    <select name="productId[]" id="" multiple size=4 class="form-control" required>
                    <?php
                            // Get all products
                            $prod = "SELECT `id`, `Name` FROM `Products`";
                            $resProd = mysqli_query($connect,$prod);
                            if(mysqli_num_rows($resProd) > 0){
                                while($rowProd = mysqli_fetch_assoc($resProd)){
                                ?>
                                    <option value="<?php echo $rowProd['id'];?>"><?php echo $rowProd['Name'];?></option>
                                <?php
                                }
                            }
                        ?>
                    </select>
                    <button type="submit" style="margin: 13px;width: 40pc;" class="btn btn-success">Assign</button>
                </form>
                </div>
            </div>


       
        </div>
        <div class="row" style="margin-top: 5pc;">
            <div class="col-sm-12">
                <!-- List Edit Delete Agent -->

            <table id="table" class="table table-striped table-sm" >
                    <thead>
                        <tr>
                            <th>Company</th>
                            <th>Product</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                  
                    <?php
                    $CompProds = "SELECT cp.id As Id, co.Name As Companies , pr.Name As Products FROM CompaniesProducts cp INNER JOIN Companies co ON cp.companyId = co.id INNER JOIN Products pr ON pr.id = cp.productId";

                    $resultCP = mysqli_query($connect,$CompProds);
                    $listOfCompaniesAndProducts = array(array());
                    $countCP = 0;
                    if($resultCP){
                        if(mysqli_num_rows($resultCP) > 0){
                            while($rowCP = mysqli_fetch_assoc($resultCP)){
                                ?>
                                    <tbody>
                                        <tr>
                                            <td> <?php echo $rowCP["Companies"];?></td>
                                            <td><?php echo $rowCP["Products"]; ?></td>
                                            <td>
                                                <a href="sales.php?idDel=<?php echo $rowCP["Id"];;?>" style="color: white;text-decoration: none;"><button onClick="if (!confirm('Confirm Delete')) return false" class="btn btn-danger" >Delete  </button></a>
                                            </td>

                                        </tr>
                                    </tbody>
                                <?php
                            }
                        }
                    }
                    ?>
                </table>
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