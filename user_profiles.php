<?php
$siteAddress = "http://localhost/DCentMASS_master";
if (isset($_GET['usertype'])) {
    if ($_GET['usertype'] == "student") {
        if(isset($_GET['username'])) {
            include_once("student_profile.php");
        } else {
            echo "Please specify Username";
        }
    } else if ($_GET['usertype'] == "instructor") {
        if(isset($_GET['username'])) {
            include_once("instructor_profile.php");
        } else {
            echo "Please specify Username";
        }
    } else if ($_GET['usertype'] == "jobseeker") {
        if(isset($_GET['username'])) {
            echo "Here";
        } else {
            echo "Please specify Username";
        }
    } else if ($_GET['usertype'] == "employer") {
        if(isset($_GET['username'])) {
            echo "Here";
        } else {
            echo "Please specify Username";
        }
    } else {
        echo "Please specify valid user type";
    }
} else {
    header("location: $siteAddress");
}
?>