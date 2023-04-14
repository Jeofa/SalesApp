<?php
include "dbConnection.php";
$id =  filter_var($_POST["id"],FILTER_SANITIZE_STRING);
$name = filter_var($_POST["name"],FILTER_SANITIZE_STRING);
$email = filter_var($_POST["email"],FILTER_SANITIZE_STRING);
$contact = filter_var($_POST["contact"],FILTER_SANITIZE_STRING);
$gender = filter_var($_POST["gender"],FILTER_SANITIZE_STRING);



//if id is true //update else insert
if(!empty($id)){
    $sql = "UPDATE `Agents` SET `Name`='$name',`Email`='$email',`Contact`='$contact',`Gender`='$gender' WHERE `id`='$id'";
    $result = mysqli_query($connect,$sql);

    if(!$result){
        //sweat alerts should be included in....
        echo "error updating agent.. ".mysqli_error($connect);
    }else{
        header("Location: ../htmls/agent.php");
    }
}else{ 

    $sql = "INSERT INTO`Agents`(`Name`, `Email`, `Contact`, `Gender`) VALUES ('$name','$email','$contact','$gender')";

    $result = mysqli_query($connect,$sql);

    if(!$result){
        //sweat alerts should be included in....
        echo "error inserting agent.. ".mysqli_error($connect);
    }else{
        header("Location: ../htmls/agent.php");
    }
}
