<?php
session_start();

include_once "dbConnection.php";
require('config.php');

$email = $password = "";
$emailErr = $passwordErr = $error = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    }else{
        $email = test_input($_POST["email"]);
        // check if name only contains letters and whitespace
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }
    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
    }else{
        $password = test_input($_POST["password"]);

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
    }

    // Query now
    $sql = "SELECT email,password FROM users WHERE email = '$email'";
    $commit = mysqli_query($connect,$sql);
    if($commit == true){
        if(mysqli_num_rows($commit) > 0){
            while($rows = mysqli_fetch_assoc($commit)){

                //confirm password
                if(password_verify($rows["password"],$hashed_password)){
        
                    //set sessions
                    $_SESSION['loggedin_time'] = time(); 
                    $_SESSION["user"] = $email;
        
                    //success login
                    header("Location:htmls/mainpage.php");

                }else{
                    $error = "Wrong Credentials";
                }
            }
        }else{
            $error = "No user found";
        }
    }else{
       die("ERROR could not connect. Try again. ".mysqli_connect_error());
    }
}
