<?php
session_start();

require "../php/config.php";

if(is_logged_in() == false){
    header("Location: ../index.php");
}

include "../php/dbConnection.php";
include "header.html";

// Get Profile Data
$sql = "SELECT  `email`, `name` FROM `users`";
$result = mysqli_query($connect,$sql);
if($result){
    while($row = mysqli_fetch_assoc($result)){
        $email = $row["email"];
        $name = $row["name"];
    }
}
//update profile data
if(isset($_POST["btnSave"])){
    $id = $_POST["id"];
    $newName = $_POST["name"];
    $newEmail = $_POST["email"];
    $sql = "UPDATE `users` SET `name`='$newName',`email`='$newEmail' WHERE `id` = '$id'";
    $res = mysqli_query($connect,$sql);
    if($res){
        echo "<script>alert('User Updated!');window.location.href='../htmls/profile.php'</script>";
    }else{
        $message = "Data Not Updated";
    }
}
//change password
if(isset($_POST["changePwd"])){
    $id = $_POST["id"];
    $oldpwd = $_POST["password"];
    $newpwd = $_POST["newpassword"];
    $newrepwd = $_POST["renewpassword"];
    $oldpass;
    //confirm pwd
    $sqlpwd = "SELECT  `password` FROM `users` ";
    $pwdRes = mysqli_query($connect,$sqlpwd);
    if($pwdRes){
        if(mysqli_num_rows($pwdRes) > 0){
            while($prow = mysqli_fetch_assoc($pwdRes)){
                $oldpass = $prow["password"];
                $GLOBALS['oldpass'] = $prow["password"];
               
            }
        }else{
            $message ="No data found";
        }
        
    }else{
        $message ="Not Committed";
    }
    // check if old passwd == entered pwd
    if(password_verify($GLOBALS['oldpwd'],$GLOBALS['oldpass'])){
        //check if current passwords are equal
        // $message = "Passwords Are Equal ".$oldpass." ".$GLOBALS['oldpass'];
        if($newpwd == $newrepwd){
            $hashed_password = password_hash($newpwd, PASSWORD_DEFAULT);
            $sql = "UPDATE `users` SET `password`= '$hashed_password'";
            $res = mysqli_query($connect,$sql);
            if($res){
                echo "<script>alert('Password Updated');</script>";
                // header("Location:../htmls/profile.php");
            }
        }else{
            $message = "Password Don't Match!";
        }

    }else{
        $message = "Old Password Don't Match!";
        // header("Location:../htmls/profile.php#changePassword");
    }
   
    }




?>

<div style=" width: 52pc;
    margin-left: 11pc;
    box-shadow: -1px 11px 9px 5px lightgrey;
    padding: 40px;">
    <span style="color:red;"><?php echo $message;?></span>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="viewProfile-tab" data-toggle="tab" href="#viewProfile" role="tab" aria-controls="viewProfile" aria-selected="true">Profile</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="editProfile-tab" data-toggle="tab" href="#editProfile" role="tab" aria-controls="editProfile" aria-selected="false">Edit Profile</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="changePassword-tab" data-toggle="tab" href="#changePassword" role="tab" aria-controls="changePassword" aria-selected="false">Change Password</a>
    </li>
    </ul>
    <div class="tab-content" id="myTabContent" style="padding-top: 1pc;">
        <div class="tab-pane fade show active" id="viewProfile" role="tabpanel" aria-labelledby="home-tab">

        <!-- Overview -->
        
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo $GLOBALS['name'];?></td>
                    <td><?php echo $GLOBALS['email'];?></td>
                </tr>
            </tbody>
        </table>

        </div>

        <!-- Edit Profile -->
        <div class="tab-pane fade" id="editProfile" role="tabpanel" aria-labelledby="profile-tab">
            <form action="?" method="post">
                <input type="hidden" value="1" name="id">
                <div class="row mb-3">
                    <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Full Name</label>
                    <div class="col-md-8 col-lg-9">
                    <input name="name" type="text" class="form-control" id="fullName" value="<?php echo $GLOBALS['name']?>">
                    </div>
                </div>


                <div class="row mb-3">
                    <label for="Email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                    <div class="col-md-8 col-lg-9">
                    <input name="email" type="email" class="form-control" id="Email" value="<?php echo $GLOBALS['email']?>">
                    </div>
                </div>


                <div class="text-center">
                    <button type="submit" name="btnSave" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div> 
        <!-- Change Password -->
        <div class="tab-pane fade" id="changePassword" role="tabpanel" aria-labelledby="contact-tab">
            <form action="?" method="post">
                <input type="hidden" value="1" id="id">

                <div class="row mb-3">
                <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                <div class="col-md-8 col-lg-9">
                    <input name="password" type="password" class="form-control" id="currentPassword">
                </div>
                </div>

                <div class="row mb-3">
                <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                <div class="col-md-8 col-lg-9">
                    <input name="newpassword" type="password" class="form-control" id="newPassword">
                </div>
                </div>

                <div class="row mb-3">
                <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Re-enter New Password</label>
                <div class="col-md-8 col-lg-9">
                    <input name="renewpassword" type="password" class="form-control" id="renewPassword">
                </div>
                </div>

                <div class="text-center">
                <button type="submit" name="changePwd" class="btn btn-primary">Change Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>