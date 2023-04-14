<?php
include "dbConnection.php";

session_start();

if (count($_POST) > 0) {
    $result = mysqli_query($connect, "SELECT *from users WHERE userId='" . $_SESSION["username"] . "'");
    $row = mysqli_fetch_array($result);
    if ($_POST["currentPassword"] == $row["password"]) {
        mysqli_query($conn, "UPDATE usersinfo set password='" . $_POST["newPassword"] . "' WHERE username='" . $_SESSION["username"] . "'");
        $message = "Password Changed";
    } else
        $message = "Current Password is not correct";
}
?>