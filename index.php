<?php
session_start();

include_once "php/dbConnection.php";
require('php/config.php');
require_once "vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

$email = $password = "";
$emailErr = $passwordErr = $error = "";
$my_email;

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
        $password = $_POST["password"];
   
    }

    // Query now
    if(isset($password) && isset($email)){
      
      $loginQ = "SELECT   `email`, `password` FROM `users` WHERE email = '$email'";
      $commit = mysqli_query($connect,$loginQ);
      if($commit){
          if(mysqli_num_rows($commit) > 0){
              while($rows = mysqli_fetch_assoc($commit)){

                  //confirm password
                  if(password_verify($password,$rows["password"])){
          
                      //set sessions
                      $_SESSION['loggedin_time'] = time(); 
                      $_SESSION["usermail"] = $email;
          
                      //success login
                      header("Location:htmls/mainpage.php");

                  }else{
                      $error = "Wrong Credentials";
                  }
              }
          }else{
              $error = "User not found";
          }
    }else{
        die("ERROR could not connect. Try again. ".mysqli_connect_error());
    }
  }else{
    echo "Data not captured";
  }
}


// Get user email from db
// We do this coz only one user is expected.
$query = "SELECT `email` FROM  `users`";
$commitEmail  = mysqli_query($connect,$query);
if($commitEmail){
    while($rowdata = mysqli_fetch_assoc($commitEmail)){
      $GLOBALS["my_email"] = $rowdata['email'];
    }
}

// For sending mail
if (isset($_GET['send'])) {


// Get new password

  $new_password = generate_password(16);

  // Hash the new password
  $hashed_password = password_hash($new_password,PASSWORD_DEFAULT);
  echo "My password: ".$hashed_password;
  $sender = "UPDATE `users` SET `password`='$hashed_password' WHERE `email`='$my_email'";
  $sendhere = mysqli_query($connect,$sender);
  if($sendhere){
    
      $mail = new PHPMailer;
      $mail->isSMTP();                                    // Set mailer to use SMTP
      $mail->SMTPDebug = SMTP::DEBUG_SERVER;   
      $mail->Host = 'smtp.gmail.com';                     // Specify main and backup SMTP servers
      $mail->SMTPAuth = true;                             // Enable SMTP authentication
      $mail->Username = 'maiworks999@gmail.com';          // SMTP username
      $mail->Password = 'Mai@999Works!';                  // SMTP password
      $mail->SMTPSecure = 'tls';                          // Enable encryption, 'ssl' also accepted

      $mail->From = 'maiworks999@gmail.com';
      $mail->FromName = 'Sales APP';                         // Name is optional
      $mail->addAddress($my_email);        
      $mail->addReplyTo('maiworks999@gmail.com', 'Information Sales APP');

      $mail->WordWrap = 50;                                 // Set word wrap to 50 characters
      $mail->isHTML(true);                                  // Set email format to HTML

      $body =  $new_password;
      $mail->Subject = 'New Password';
      $mail->Body    = "This is your new password: <b>".$body."</b> Got it.";
      $mail->AltBody = 'We are sorry we couldn\'t make your request at this moment please try later.';

      if(!$mail->send()) {
          echo '<script>alert("Message could not be sent.\n Please check your internet connection");window.location.href="index.php";</script>';
      } else {
          echo '<script>alert("Message has been sent.\n After this kindly change your password"); window.location.href="index.php";</script>';
      }
  }else{
    echo '<script>alert("Could not connect");</script>';

  }

}
function generate_password( $length = 8 ) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
    $password = substr( str_shuffle( $chars ), 0, $length );
    return $password;
}

?>

<!DOCTYPE html>
<head>
    <title>
        LOGIN PAGE
    </title>
    <link rel="icon"  type="image/png" href="imgs/Logo.png" />
    <link rel="stylesheet" href="css/loginme.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script> 
</head>
<body style="background-color: #cfd8d1;">

    <form action="" method="post" style="margin-top: 6pc; width: 22pc;height: 26pc;">
        
      <div style="text-align: center;">
        <span id="toggleError" style="color: red;background-color: azure; padding: 8px 4pc; border-radius: 6px;"><?php echo $error;?></span>
      </div>
      
        <div class="container" style="padding-top: 3pc;">
          <label for="uname"><b>Email</b></label><span style="color: #FF0000;">* <?php echo $emailErr;?></span>
          <input type="email" placeholder="Enter Email" name="email">
      
          <label for="psw"><b>Password</b></label><span style="color: #FF0000;">* <?php echo $passwordErr;?></span>
          <input type="password" placeholder="Enter Password" name="password">
      
          <button type="submit" style="margin-top: 2pc; margin-left: 32px; width: 17pc;">Login</button>
         
        </div>
         <!-- Forgot password -->
        <div class="container" style="background-color:#b9dbe4">
          <a style="color: white;" href="htmls/Registration.html" ><button type="button" style="width: 9pc;">  Sign Up</button></a>
          <span class="psw">Forgot  <button type="button" style="width: auto;" class="btn btn-outline-primary" data-toggle="modal" data-target="#staticBackdrop"> password?</button></span> 
        </div>
      </form>

   
      
<!-- modal for forgot password navigation-->
<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Forgot Password?</h5>
        
      </div>
      <div class="modal-body">
        A new password will be sent to this email address: <span style="color:red;"> <?php echo $GLOBALS['my_email'];?></span>
      </div>
      <div class="modal-footer">
        <button type="button" style="width: auto;" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <a href="index.php?send=true"><button type="button" class="btn btn-primary" > Send</button></a>
      </div>
    </div>
  </div>
</div>

<?php

?>


<script>


  var myError = "<?php echo $error;?>";
  var div = document.getElementById('toggleError'); 
  var elem = document.getElementById('toggleError');
  console.log(myError);
  if(isEmpty(myError)){
    document.getElementById("toggleError").style.display ="none";
  }else if(!isEmpty(myError)){
    document.getElementById("toggleError").style.display ="initial";
  }

  function isEmpty(value){
    return (value == null || value === '');
  }
</script>
</body>
</html>