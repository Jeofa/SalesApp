<?php
require_once "vendor/autoload.php";
require "php/dbConnection.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

$_GET['send'] = true;
$my_email;
if (isset($_GET['send'])) {
    $query = "SELECT `email` FROM  `users`";
    $commitEmail  = mysqli_query($connect,$query);
    if($commitEmail){
        while($rowdata = mysqli_fetch_assoc($commitEmail)){
            $GLOBALS['my_email'] = $rowdata['email'];
        }
    }
    

    $new_password = generate_password(16);

    // Hash the new password
    $hashed_password = password_hash($new_password,PASSWORD_DEFAULT);
    echo "My password: ".$hashed_password;
    $sql = "UPDATE `users` SET `password`='$hashed_password' WHERE `email`='$my_email'";
    $result = mysqli_query($connect,$sql);
    if($result){
       
        $mail = new PHPMailer;
        $mail->isSMTP();                                // Set mailer to use SMTP
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;   
        $mail->Host = 'smtp.gmail.com';                 // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                         // Enable SMTP authentication
        $mail->Username = 'maiworks999@gmail.com';      // SMTP username
        $mail->Password = 'Mai@999Works!';              // SMTP password
        $mail->SMTPSecure = 'tls';                      // Enable encryption, 'ssl' also accepted
    
        $mail->From = 'maiworks999@gmail.com';
        $mail->FromName = 'Sales App';
        $mail->addAddress($GLOBALS['my_email']);               
        $mail->addReplyTo('maiworks999@gmail.com', 'Information');
    
        $mail->WordWrap = 50;                                 // Set word wrap to 50 characters
        $mail->isHTML(true);                                  // Set email format to HTML
    
        $body =  $new_password;
        $mail->Subject = 'New Password';
        $mail->Body    = "Hello this is ua password: <b>".$body."</b> Got it.";
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
    
        if(!$mail->send()) {
            echo '<script>alert("Message could not be sent.");</script>';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
            // header("Location: ../index.php");

        } else {
            echo '<script>alert("Message has been sent");</script>';
            header("Location: ../index.php");
        }
    }
    
  }

// Get new password
function generate_password( $length = 8 ) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
    $password = substr( str_shuffle( $chars ), 0, $length );
    return $password;
}


