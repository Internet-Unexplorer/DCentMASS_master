<?php 
/*
Author name: Tejas;

*/

$connect_db = mysqli_connect("localhost","root","","dcentmass");

if(mysqli_connect_errno()) {
	// echo mysqli_connect_errno();
	exit();
} else {
	// echo "Successful Connection.";
}



$user_ok = "";
$siteName = "DCentMASS";
$siteAddress = "http://localhost/DCentMASS_master";




// //mysqli_close($connect_db);
// $mysqli_que = new mysqli("localhost", "root", "", "dcentmass");
// if (mysqli_connect_error())
// {
//     //echo mysqli_connect_error(); 
//     exit();
// }


?>