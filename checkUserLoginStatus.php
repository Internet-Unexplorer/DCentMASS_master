<?php
session_start();
include_once("php_includes/db_connect.php");
// Files that inculde this file at the very top would NOT require 
// connection to database or session_start(), be careful.
// Initialize some vars
$user_ok = false;
$log_id = "";
$log_username = "";
$log_password = "";

$user_ok = "";
$siteName = "DCentMASS";
$siteAddress = "http://localhost/DCentMASS_master";
// User Verify function
function evalLoggedUser($connect_db, $id,$u,$type){
	$sql = "SELECT ip FROM login_info WHERE id='$id' AND userName='$u' AND userType='$type' LIMIT 1";
    $query = mysqli_query($connect_db, $sql);
    $numrows = mysqli_num_rows($query);
	if($numrows > 0){
		return true;
	} else {
		return false;
	}
}
// FIXME: This is very important for security reasons
// Use bcrypt method to have 'remember me' option.
if(isset($_SESSION["id"]) && isset($_SESSION["username"]) && isset($_SESSION["userType"])) {
	$log_id = preg_replace('#[^0-9]#', '', $_SESSION['id']);
	$log_username = preg_replace('#[^a-zA-Z0-9_-]#i', '', $_SESSION['username']);
	$log_user_type = preg_replace('#[^a-z0-9]#i', '', $_SESSION['userType']);
	// Verify the user
	$user_ok = evalLoggedUser($connect_db,$log_id,$log_username,$log_user_type);
} else if(isset($_COOKIE["id"]) && isset($_COOKIE["username"]) && isset($_COOKIE["userType"])){
	$_SESSION['id'] = preg_replace('#[^0-9]#', '', $_COOKIE['id']);
    $_SESSION['username'] = preg_replace('#[^a-zA-Z0-9_-]#i', '', $_COOKIE['username']);
    $_SESSION['userType'] = preg_replace('#[^a-z0-9]#i', '', $_COOKIE['userType']);
	$log_id = $_SESSION['id'];
	$log_username = $_SESSION['username'];
	$log_user_type = $_SESSION['userType'];
	// Verify the user
	$user_ok = evalLoggedUser($connect_db,$log_id,$log_username,$log_user_type);	
	if($user_ok == true){
		// Update their loginDateTime field
		$sql = "UPDATE login_info SET loginDateTime=now() WHERE id='$log_id' LIMIT 1";
        $query = mysqli_query($connect_db, $sql);
	}
}
?>