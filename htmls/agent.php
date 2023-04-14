<?php
// function is_logged_in(){
//     if(isset($_SESSION['usermail'])){
//         return true;
//     }else{
//         return false;
//     }
// }

require "header.html";
include "../php/dbConnection.php";

//update agent
if(isset($_GET["id"])){
    $id = $_GET["id"];

    $sql = "SELECT  `name`, `email`, `contact` FROM `Agents` WHERE `id` = '$id'";
    $result = mysqli_query($connect,$sql);
    if($result){
        while($cols = mysqli_fetch_assoc($result)){
            $ColName = $cols["name"];
            $ColEmail = $cols["email"];
            $ColContact = $cols["contact"];
        }
    }

}
//delete agent
if(isset($_GET["id"]) && isset($_GET["name"])){
    $id = $_GET["id"];

    $sql = "DELETE FROM `Agents` WHERE `id` = '$id'";
    $result = mysqli_query($connect,$sql);
    if($result){
        echo "<script>deleteAgentPrompt();</script>";
        header("Location: ../htmls/agent.php");
       
    }

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
   

    <link rel="stylesheet" href="../css/sales.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.js"></script>
    <title>Agent</title>
</head>
<body>
    <div class="container">
        <div class="row" style=" margin-top: 5pc;">
            <div class="col-sm-4"  id="saleForm">
                <!-- Form Create Agent -->
                <form action="../php/agentData.php" method="post">
                    <legend>Create Agent</legend>
                    <input type="hidden" name="id" value="<?php echo $GLOBALS['id'];?>">
                    <label for="" class="label">Name</label>
                    <input type="text" value="<?php echo $GLOBALS['ColName'];?>" name="name" id="name" class="form-control" required>
                    <label for="" class="label">Email</label>
                    <input type="email" value="<?php echo $GLOBALS['ColEmail'];?>" name="email" id="email" class="form-control" required>
                    <label for="" class="label">Contact</label>
                    <input type="tel" value="<?php echo $GLOBALS['ColContact'];?>" name="contact" id="contact" class="form-control" required>
                    <label for="" class="label">Gender</label><br>
                    <input type="radio" name="gender" value="Male" id=""> <label class="label" for="" required>Male</label><br>
                    <input type="radio" name="gender" value="Female" id=""> <label for="" required>Female</label><br><br>
                    <button onclick="createUser()" type="submit" class="btn btn-outline-success" style="margin-left: 4pc;margin-bottom: 1pc; width: 14pc;">Create</button>
                </form>
            </div>
            <div class="col-sm-8">
                <!-- List Edit Delete Agent -->
                <table id="table" class="table table-striped table-sm">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Contact</th>
                        <th>Gender</th>
                        <th>Action</th>
                    </tr>
                    
                    <?php

                   

                    $sql = "SELECT `id` , `name`, `email`, `contact`, `gender` FROM `Agents`";
                    $result = mysqli_query($connect,$sql);
                    if($result){
                        if(mysqli_num_rows($result) > 0){
                            while($row = mysqli_fetch_assoc($result)){
                                ?>
                                            
                                            <tr>
                                                <td> <?php echo $row["name"];?></td>
                                                <td><?php echo $row["email"]; ?></td>
                                                <td><?php echo $row["contact"];?></td>
                                                <td><?php echo $row["gender"];?></td>
                                                <td>
                                                    <button class="btn btn-info"> <a href="agent.php?id=<?php echo $row["id"];?>" style="color: white;text-decoration: none;" > Edit</a></button>
                                                    <a href="agent.php?id=<?php echo $row["id"];?>&name=<?php echo $row["name"];?>" style="color: white;text-decoration: none;"><button onClick="if (!confirm('Delete Agent?')) return false" class="btn btn-danger" id="<?php echo $row["id"];?>" >Delete  </button></a>
                                                </td>

                                            </tr>
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
 
    function createUser(){
        var name = $("#name").val();
        var email = $("#email").val();
        var contact = $("#contact").val();
        if(name != "" && email != "" && contact != ""){
             swal("Poof! Your Agent has been deleted!", {
                icon: "success",
            });
        }
       
    }    
    
    }


      
        
</script>
</html>