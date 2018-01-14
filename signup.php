<?php
require_once("checkUserLoginStatus.php");
if($user_ok) {
    header("location: $siteAddress/user/$log_user_type/$log_username");
    exit();
}
if(isset($_GET['as'])) {
    if ($_GET['as']=="student") {
        include_once "student.php"; // Student signup form
    } else if ($_GET['as']=="instructor") {
        include_once "instructor.php"; // Instructor signup form
    } else if ($_GET['as']=="jobseeker") {
        include_once "jobseeker.php"; // jobseeker signup form
    } else if ($_GET['as']=="employer") {
        echo "Display employer form";
    } else {
        echo "Invalid selection. Please press back button.";
    }
} else {
    echo "display options";
}

?>