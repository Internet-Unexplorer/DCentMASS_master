<?php

require_once("checkUserLoginStatus.php");

if(!$user_ok) {
    header("location: $siteAddress/login.php");
}

echo "This is Employer profile";
?>