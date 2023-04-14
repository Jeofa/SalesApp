<?php
session_start();

require "../php/config.php";

if(is_logged_in() == false){
    header("Location: ../index.php");
}


require "header.html";
include "../php/dbConnection.php";

// Pie Chart Data ------------Company Vs Quantity----------------
$sql = "SELECT Companies.Name AS Company, SUM(SalesReports.Quantity) As Quantity FROM SalesReports INNER JOIN Companies ON Companies.id = SalesReports.Company GROUP BY Company";

$company = array();
$quantity = array();
$count = 0;
$myresult =mysqli_query($connect,$sql);
if($myresult){
    if(mysqli_num_rows($myresult) > 0){
        while($row = mysqli_fetch_assoc($myresult)){
          $company[$count] = $row["Company"];
          $quantity[$count] = $row["Quantity"];
          $count++;
      }
   }
   json_encode($company);
   json_encode($quantity);
}

$pie = "";

$sql = "SELECT prod.Name AS Product, SUM(TotalCost) AS TotalCost FROM SalesReports sr INNER JOIN Products prod ON sr.Product= prod.id GROUP BY Product";
$res = mysqli_query($connect,$sql);


$count = 0;

$valuesData = array();
$labels = array();

if($res){
     if(mysqli_num_rows($res) > 0){
          while($row = mysqli_fetch_assoc($res)){
            $labels[$count] = $row["Product"];
            $valuesData[$count] = $row["TotalCost"];
            $count++;
        
        }
     }

    json_encode($valuesData);
    json_encode($labels);

}


// Get Pending details 
$getAll = "SELECT COUNT(`id`) As NumberOfSales FROM SalesReports";
$allSales = mysqli_query($connect,$getAll);
$sold;
if(mysqli_num_rows($allSales) > 0){
    while($sales = mysqli_fetch_assoc($allSales)){
        $sold = $sales['NumberOfSales']; //takes all sales made
        $query = "SELECT COUNT(`Status`) As PendingPayments, SUM(`TotalCost`) As PendingBalance FROM `SalesReports` WHERE `Status` = 'pending'";
        $penders = mysqli_query($connect,$query);
        if($penders){
            if(mysqli_num_rows($penders) > 0){
                while($eachPender = mysqli_fetch_assoc($penders)){
                    $pending = $eachPender['PendingPayments']; //takes number of pending payments
                    $pendingBal = $eachPender['PendingBalance']; // takes the pending balance

                    // now convert into percentage
                    
                    $pendingPaymentsInPer1 = ($pending * 100) / $sold;
                    $pendingPaymentsInPer = number_format((float)$pendingPaymentsInPer1, 2, '.', '');
                    $paidValues1 = (100 - $pendingPaymentsInPer);
                    $paidValues = number_format((float)$paidValues1, 2, '.', '');


                }
            }
        }
    }
}


?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">    
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>    
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>   
<title>Home</title>

</head>
<body>

<div class="container">

    <div class="row" style="margin-bottom: 4pc;">
        <!-- payment analysis -->
        <div class="col-sm-6" style="margin-top: 3pc; width: 30pc;border-radius: 5px;padding-top: 1pc; height: 18pc; box-shadow: 2px 7px 8px 0px grey;">
            
            <h3>Payment Analysis</h3>
            <h6>Pending</h6>
            <div class="progress" id="pr1" style="text-align: center;"> 
            </div> 
            <h6>Paid</h6>
            <div class="progress" id="pr2" style="text-align: center;"> 
            </div> 
            <h4>Pending Balance: <span style="color:red;"><?php echo ' Kshs.'.$pendingBal; ?></span></h4>
       
        </div>
        <!-- Company Vs Quantity Chart -->
        <div class="col-sm-6" style="padding: 2pc 6pc;">
                <canvas id="mypieChart"></canvas>
                <hr style="  border: 0; height: 1px;background-image: linear-gradient(to right, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0));">
        </div>
    </div>
   
    <div class="row">
        
        <div class="col-sm-6">
            <div style="width: 36pc;margin-left: -4pc;">
                <canvas id="myChart" ></canvas>
            </div>
        </div>
        <div class="col-sm-6">
            <h4>Leading Products </h4>
            <table class="table">
                <tr>
                    <th>Company</th>
                    <th>Product</th>
                    <th>Sold Quantity</th>
                </tr>
                <tr>
                    <td>Ajab</td>
                    <td>Wheat</td>
                    <td>30</td>
                </tr>
                <tr>
                    <td>Dola</td>
                    <td>Maize</td>
                    <td>40</td>
                </tr>
                <tr>
                    <td>Shamba</td>
                    <td>sugar</td>
                    <td>94</td>
                </tr>
            </table>
        </div>
    </div>
</div>

<script>
      $(document).ready(function(){    
        var progressBarVal1=<?php echo $pendingPaymentsInPer; ?>;    
        var progressBarVal2=<?php echo $paidValues; ?>;   
        var html1="<div class='progress-bar progress-bar' role='progressbar' aria-valuenow="+progressBarVal1+" aria-valuemin='0' aria-valuemax='100' style=' width:"+progressBarVal1+"%'>"+progressBarVal1+"%    </div>";    
        var html2="<div class='progress-bar progress-bar' role='progressbar' aria-valuenow="+progressBarVal2+" aria-valuemin='0' aria-valuemax='100' style=' width:"+progressBarVal2+"%'>"+progressBarVal2+"%    </div>";    
        $("#pr1").append(html1);    
        $("#pr2").append(html2);  
        
        // for graph actions
        // $("#hitData").hide();
        // $('#ajabHit').click(function(){
        //     $("#hitData").fadeToggle();
        // });

        });
</script>
</body>

<script>



    // For Product And Costs---------
        var tempValData = <?php echo json_encode($valuesData); ?>;
        var tempLabData = <?php echo json_encode($labels); ?>;
        // console.log(tempValData);
        var labelData = [];
        var valueData = [];

        for (var i in tempValData){
            valueData.push(tempValData[i]);
            labelData.push(tempLabData[i]);
        }

        const ctx = document.getElementById('myChart').getContext('2d');
        const myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labelData,
            datasets: [{
                label: 'Costs Vs Products',
                data: valueData,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            animations: {
                tension: {
                    duration: 1000,
                    easing: 'easeInOutSine',
                    from: 1,
                    to: 0,
                    loop: true
                }
                },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    // For Companies  Vs Products---------

    var tempCompanyData = <?php echo json_encode($company); ?>;
    var QuantityData = <?php echo json_encode($quantity); ?>;
    console.log(tempCompanyData);
    console.log(QuantityData);
    var labelCompanyData = [];
    var QuantityDataValues = [];

    for (var i in tempCompanyData){
        labelCompanyData.push(tempCompanyData[i]);
    }
    for (var j in QuantityData){
        QuantityDataValues.push(QuantityData[j]);
    }

    const chart = document.getElementById('mypieChart').getContext('2d');
    const myPChart = new Chart(chart, {
        type: 'pie',
        data: {
            labels: labelCompanyData,
            datasets: [{
                label: 'Company Vs Quantity',
                data: QuantityDataValues,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        
       
    });

</script>
</html>

