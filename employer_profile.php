<?php
require_once("checkUserLoginStatus.php");
if(!$user_ok) { //Check whether user is loggedin or not. If not, header them away.
    header("location: $siteAddress/login.php");
}
//Init variables
$isOwner = FALSE;
$username = "";
$log_username = $_SESSION['username'];
$log_usertype = $_SESSION['userType'];
//Get URL vars
if(isset($_GET['username']) && isset($_GET['usertype'])) {
    $username = $_GET['username'];
    $usertype = $_GET['usertype'];    
} else {
    echo "Invalid GET variables";
    exit();
}
//Get usertype using username from URL var and header them to their proper URL if URL var and SQL var don't match.
$sql = "SELECT userType FROM login_info WHERE userName='$username' LIMIT 1;";
$query = mysqli_query($connect_db, $sql);
$row = mysqli_fetch_row($query);
$sql_user_type = $row[0];
if($sql_user_type != $usertype) {
    header("location: $siteAddress/user/$sql_user_type/$username");
}

if($log_username==$username && $log_usertype==$usertype) { //Check if the visitor is Owner of this page.
    $isOwner = TRUE;    
}
?>
 
<!-- HTML content -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $username; ?> | Profile</title>
    <?php include_once "stylesAndFont.php"; //Include styles ?>
</head>
<body>
<!-- START -->
<?php if ($isOwner) { //Example code to check whether visitor is page owner.
    echo "$log_username is owner of this page";
} else {
    echo "$log_username is not owner of this page. $username is.";
} ?>
<br><br>
<a class="btn btn-danger" href="<?php echo $siteAddress ?>/logout.php">Logout</a>
<!-- END -->
<?php include_once("script.php"); //Include scripts ?>
</body>
</html>