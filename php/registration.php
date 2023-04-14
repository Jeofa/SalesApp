<?php

require "dbConnection.php";

$email = filter_var($_POST["email"],FILTER_SANITIZE_STRING);
$password = filter_var($_POST["password"],FILTER_SANITIZE_STRING);
$name ="jeff";

// Check if user exists
// Only one user is allowed in the system

$query = "SELECT COUNT(`id`) As UsersCount, `email`, `password` FROM `users` GROUP BY `id`";
$commit = mysqli_query($connect,$query);

if($commit){
    if(mysqli_num_rows($commit) > 0){
        // Already exists a user
        echo "<script>alert('User already exists Only one user allowed!');window.location.href='../index.php';</script>";
    }else{
        // Hash the password
        // echo "<script>alert('user allowed!');</script>";
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
echo $hashed_password;
        $sql = "INSERT INTO `users`(`name`, `email`, `password`) VALUES('$name','$email','$hashed_password')";
        $result = mysqli_query($connect,$sql);
        if(!$result){
            echo "<script>alert('User not captured. Try again');window.location.href='../htmls/Registration.html';</script>";
        }
        else{
            echo "<script>alert('Created Successfully');window.location.href='../index.php'</script>";

            // header("Location: ../index.php");
        }
    }
}


?>