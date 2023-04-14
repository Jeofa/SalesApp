<?php
session_start();
include_once "dbConnection.php";

//for all user notifications
$message = "";
if(isset($_POST['btnLogin'])){
   
    $username = filter_var($_POST["username"], FILTER_SANITIZE_STRING);
    $password = filter_var($_POST["password"], FILTER_SANITIZE_STRING);
   

    //check if the user is already logged in
    if(isLoginSessionExpired() == true){
            
        //query for input
        $sql = "SELECT * FROM usersDetails WHERE username = '$username'";
        $result = mysqli_query($connect,$sql);
        if($result){
            if(mysqli_num_rows($result) > 0){
                while($rows = mysqli_fetch_assoc($result)){

                    //confirm password
                if($password == $rows["password"]){

                    //set sessions
                    $_SESSION['loggedin_time'] = time(); 
                    $_SESSION["username"] = $_POST["username"];

                    //success login
                    echo "<script> window.location.href='main.php';</script>";
                    
                    
                }else{
                    $_SESSION['loggedin_time'] = time(); 
                     echo "Wrong Password";
                     echo "the time ".time()."\nThe user time ".$_SESSION['loggedin_time'] ;
                }
               
                }
            }else{
                echo "Invalid Username & Password!";
            }
        }else{
            echo "wrong table";
        }
    }
        
    else{
        //already logged-in
        header("Location : main.php");

    }
     


}

//check in logout timeout to auto logout
function isLoginSessionExpired() {
	$login_session_duration = 5; 
	$current_time = time(); 
	if(isset($_SESSION['loggedin_time']) && isset($_SESSION["username"])){  
        //check if the session is active
		if(((time() - $_SESSION['loggedin_time']) > $login_session_duration)){ 
            echo "This is the function";
			return true; 
		} 
            
	}
	return false;
}

// echo isLoginSessionExpired();

// function logout(){
//     //set session to null
//     unset($_SESSION["username"]);
//     $url = "index.php";

//     //should send GET->index(url) && GET is called to display status --> session expired
//     if(isset($_GET["session_expired"])) {
//         $url .= "?session_expired=" . $_GET["session_expired"];
//     }
//     header("Location:$url");
// }

?>
<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="<?php ?>" method="post">
        <label for="">username</label>
        <input type="text" name="name" id=""><br>
        <label for="">password</label>
        <input type="password" name="password" id=""><br>
        <input type="submit" value="Login" name="btnLogin">


    </form>
</body>
</html> -->