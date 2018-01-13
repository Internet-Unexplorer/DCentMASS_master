<?php

require_once("checkUserLoginStatus.php");

if(!$user_ok) {
    header("location: $siteAddress/login.php");
}

echo $id=$_SESSION['id'];
echo "<br>".$_SESSION['username'];
echo "<br>".$_SESSION['userType'];




echo "<br>This is Instructor profile";
?>