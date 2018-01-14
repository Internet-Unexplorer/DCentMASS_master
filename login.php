<?php
include_once("checkUserLoginStatus.php");
if($user_ok) {
    header("location: $siteAddress/user/$log_user_type/$log_username");
    exit();
}
?>
<?php
// Ajax calls this EMAIL CHECK code to execute
// if(isset($_POST["emailcheck"])){
// 	$email = $_POST['emailcheck'];
// 	$sql = "SELECT id FROM login_info WHERE email='$email' LIMIT 1";
//     $query = mysqli_query($connect_db, $sql);
//     $email_check = mysqli_num_rows($query);
    
//     if ($email_check < 1) {
// 	    exit();
//     } else {
// 	    echo '<strong style="color:#F00;">' . $email . ' is taken</strong>';
// 	    exit();
//     }
// }

// if(isset($_POST["unamecheck"])){
// 	$uname = $_POST['unamecheck'];

// $sql = "SELECT id FROM login_info WHERE userName='$uname' LIMIT 1";
// $query = mysqli_query($connect_db, $sql);
// $uname_check = mysqli_num_rows($query);

// if($uname_check < 1)
// {
//   exit();
// }else {
//   echo '<strong style="color:#F00;">' . $uname . ' is taken</strong>';
//   exit();
//  }
// } 
?>
<?php
// Ajax calls this REGISTRATION code to execute
if(isset($_POST["uname"])){
	
	// GATHER THE POSTED DATA INTO LOCAL VARIABLES
	// $uname = preg_replace('#[^a-z0-9@.-_]#i', '', $_POST['uname']);
	$uname = mysqli_real_escape_string($connect_db, $_POST['uname']);
    $passw = $_POST['passw'];
    
    if($uname == "" || $passw == ""){
        echo "The form submission is missing values.";
        echo trim(ob_get_clean());
		exit();
	}

	// GET USER IP ADDRESS
    $ip = preg_replace('#[^0-9.]#', '', getenv('REMOTE_ADDR'));
	// -------------------------------------------
	$sql = "SELECT id, userName, password, userType FROM login_info WHERE userName='$uname' OR email='$uname' LIMIT 1";
    $query = mysqli_query($connect_db, $sql);
    $sql_num_rows = mysqli_num_rows($query);

    if($sql_num_rows>0) {
        $row = mysqli_fetch_row($query);
        $db_pass_str = substr($row[2], 20, 32);
    } else {
		echo "Incorrect Username / E mail";
        echo trim(ob_get_clean());
        exit();
	}

    $p_comp = md5(md5($passw));

    if($p_comp == $db_pass_str) {

		$userid = $row[0];
		$username = $row[1];
		$usertype = $row[3];
		$_SESSION['id'] = $userid;
		$_SESSION['username'] = $username;
		// $_SESSION['password'] = $row[2];
		$_SESSION['userType'] = $usertype;
		
		setcookie("id", $userid, strtotime( '+30 days' ), "/", "", "", TRUE);
		setcookie("username", $username, strtotime( '+30 days' ), "/", "", "", TRUE);
		// setcookie("pass", $row[2], strtotime( '+30 days' ), "/", "", "", TRUE);
		setcookie("userType", $usertype, strtotime( '+30 days' ), "/", "", "", TRUE);
		
		// UPDATE THEIR "IP" AND "LASTLOGIN" FIELDS
		$sql = "UPDATE login_info SET ip='$ip', loginDateTime=now() WHERE userName='$username' LIMIT 1";
		$query = mysqli_query($connect_db, $sql);
		//header("location: $site_address/DCentMASS_master/user/$usertype/$username");
		echo "login_success|$usertype|$username";
		exit();
    } else {
        echo "Incorrect password.";
        echo trim(ob_get_clean());
        exit();
    }
  
	exit();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
	<?php include_once "stylesAndFont.php"; ?>

    
</head>
<body>
<form name="studentform" id="studentform" onSubmit="return false;" style="padding:20px;">
  <h2 style="margin-left:5px;">Login Here</h2><i>All the fields are required.</i>
  <br>
  
  
  
  	<div class="input-group input-group-lg">
    	<input id="username" type="text" onFocus="emptyElement('status')" class="form-control" placeholder="Please Enter User name" style="border-radius: 0px; margin:5px;">
	</div>

   <div class="input-group input-group-lg">
    <input class="form-control" placeholder="Enter password" id="pass" type="password" onFocus="emptyElement('status')"  style="border-radius: 0px; margin:5px;">
    </div>

   

   <button class="btn btn-success btn-lg" id="loginbtn" onClick="login()" style="border-radius: 0px; margin-left:5px;"> Login
    </button>
  </form>


  <div class="alert alert-danger" role="alert" id="status" style="display:none; width:300px;"></div>




<!-- scripts -->
<?php include_once("script.php"); ?>

  <script>
  
//     function restrict(elem){
// 	var tf = _(elem);
// 	var rx = new RegExp;
// 	if(elem == "email"){
// 		rx = /[' "]/gi;
// 	} else if(elem == "username"){
// 		rx = /[^a-z0-9_.-]/gi;
// 	}
// 	tf.value = tf.value.replace(rx, "");
// }
    function emptyElement(x){
	_(x).innerHTML = "";
	var status = _("status");
	status.style.display = "none";
}

// function checkemail(){
// 	var u = _("email").value;
// 	if(u != ""){
// 		_("emailstatus").innerHTML = 'checking ...';
// 		var ajax = ajaxObj("POST", "student.php");
//         ajax.onreadystatechange = function() {
// 	        if(ajaxReturn(ajax) == true) {
// 	            _("emailstatus").innerHTML = ajax.responseText;
// 	        }
//         }
//         ajax.send("emailcheck="+u);
// 	}
// }

// function checkuname(){
// 	var t = _("username").value;
// 	if(t != "")
// 	{
// 		_("unamestatus").innerHtml = 'checking...';
// 		var ajax = ajaxObj("POST", "student.php");
// 		ajax.onreadystatechange = function()
// 		{
// 			if(ajaxReturn(ajax)== true){
// 				_("unamestatus").innerHTML = ajax.responseText;
// 			}
// 		}
// 			ajax.send("unamecheck="+t);
// 		}
	
// }

function login(){
    var uname = _("username").value;
	var passw = _("pass").value;
	
    var status = _("status");
	
	_("username").style.border = "1px solid #ccc";

	_("pass").style.border = "1px solid #ccc";
    
	if (uname == "") {
		_("username").style.border = "2px solid #f60";
	}
    if (passw == "") {
		_("pass").style.border = "2px solid #f60";
	}
	

	if( uname == "" || passw == ""){
		status.innerHTML = "Fill out all of the form data";
		status.style.display = "block";
	} else {
		_("loginbtn").style.display = "none";
		status.innerHTML = 'please wait ...';
		var ajax = ajaxObj("POST", "login.php");
        ajax.onreadystatechange = function() {			
	        if(ajaxReturn(ajax) == true) {
				var split_response = ajax.responseText.split("|");
				
	            if(split_response[0] != "login_success"){
					status.innerHTML = ajax.responseText;
					status.style.display = "block";
					_("loginbtn").style.display = "block";
				} else {
					// alert("Successfully Loged In.");
					// window.location = "login.php";
					window.location = "http://localhost/DCentMASS_master/user/"+split_response[1]+"/"+split_response[2];

					
					//_("signupform").innerHTML = "OK "+u+", check your email inbox and junk mail box at <u>"+e+"</u> in a moment to complete the sign up process by activating your account. You will not be able to do anything on the site until you successfully activate your account.";
				}
	        }
        }
        ajax.send("uname="+uname+"&passw="+passw);
	}
}
    </script>
</body>
</html>