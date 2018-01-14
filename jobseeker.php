<?php
require_once("checkUserLoginStatus.php");
if($user_ok){
	header("location: $siteAddress/user/$log_user_type/$log_username");
    exit();
}
?><?php
// Ajax calls this EMAIL CHECK code to execute
if(isset($_POST["emailcheck"])){
	$email = $_POST['emailcheck'];
	$sql = "SELECT id FROM login_info WHERE email='$email' LIMIT 1";
    $query = mysqli_query($connect_db, $sql);
    $email_check = mysqli_num_rows($query);
    
    if ($email_check < 1) {
	    exit();
    } else {
	    echo '<strong style="color:#F00;">' . $email . ' is taken</strong>';
	    exit();
    }
}
if(isset($_POST["unamecheck"])){
      $uname = $_POST['unamecheck'];

$sql = "SELECT id FROM login_info WHERE userName='$uname' LIMIt 1";
$query = mysqli_query($connect_db, $sql);
$uname_check = mysqli_num_rows($query);

if($uname_check < 1)
{
	exit();
}else {
	echo '<strong style="color:#F00;">' . $uname . ' is taken</strong>';
	exit();
   }
 }      
?><?php
// Ajax calls this REGISTRATION code to execute
if(isset($_POST["uname"])){
	// CONNECT TO THE DATABASE
	
	// GATHER THE POSTED DATA INTO LOCAL VARIABLES
    $uname = preg_replace('#[^a-z0-9A-Z_-]#i', '', $_POST['uname']);
	$dname = preg_replace('#[^a-z0-9A-Z ._-]#i', '', $_POST['dname']);

    
	$emai = mysqli_real_escape_string($connect_db, $_POST['emai']);
    $pas1 = $_POST['pas1'];
	$pas2 = $_POST['pas2'];
	$loc = preg_replace('#[^a-zA-Z]#i', '', $_POST['loc']);
	$con = preg_replace('#[^a-zA-Z 0-9]#i', '', $_POST['con']);
	$dte = preg_replace('#[^a-zA-Z0-9 /-]#i', '', $_POST['dte']);
    
	// GET USER IP ADDRESS
    $ip = preg_replace('#[^0-9.]#', '', getenv('REMOTE_ADDR'));
	// -------------------------------------------
	$sql = "SELECT id FROM login_info WHERE email='$emai' LIMIT 1";
    $query = mysqli_query($connect_db, $sql);
	$e_check = mysqli_num_rows($query);
	// FORM DATA ERROR HANDLING
	if($uname == "" || $dname == "" ||  $emai == "" ||  $pas1 == "" || $pas2 == "" || $loc == "" || $con == "" || $dte == ""){
		echo "The form submission is missing values.";
        exit();
	}
	else if($pas1 != $pas2){
		echo "Password Not Matching";
		exit();
	} else if ($e_check > 0){	
        echo "That email address is already in use.";
        exit();
	} else if (strlen($uname) < 3 || strlen($uname) > 30) {
        echo "Username must be between 3 and 30 characters";
        exit();
    } else if (is_numeric($uname[0])) {
        echo 'Username cannot begin with a number';
        exit();
    } else {
	// END FORM DATA ERROR HANDLING
	    // Begin Insertion of data into the database
		// Hash the password and apply your own mysterious unique salt
		$mmd5 = md5(md5($pas1));
        // include_once("php_includes/randomStringGen.php");
        function randStrGen($len){
            $result = "";
            $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
            $charArray = str_split($chars);
            for($i = 0; $i < $len; $i++){
                $randItem = array_rand($charArray);
                $result .= "".$charArray[$randItem];
            }
            return $result;
        }
       
		$p_hash = randStrGen(20)."$mmd5".randStrGen(20);
		// Add user info into the database table for the main site table

		$sql = "INSERT INTO `jobseeker` (`userName`, `displayName`, `email`, `location`, `pNumber`, `dob`)
		VALUES ('$uname', '$dname', '$emai', '$loc', '$con', '$dte');";
		$query = mysqli_query($connect_db, $sql);
		$uid = mysqli_insert_id($connect_db);


		$sql = "INSERT INTO `login_info` (`userName`, `email`, `pNumber`, `password`, `signe_up_dateTime`, `loginDateTime`, `active`, `userType`, `ip`) 
		VALUES ('$uname', '$emai', '$con', '$p_hash', now(), now(), '0', 'jobseeker', '$ip');";
		$query = mysqli_query($connect_db, $sql);
		$uid = mysqli_insert_id($connect_db);


	
		// Email the user their activation link
		// $to = "$e";
		// $from = "auto_responder@yoursitename.com";
		// $subject = 'yoursitename Account Activation';
		// $message = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>yoursitename Message</title></head><body style="margin:0px; font-family:Tahoma, Geneva, sans-serif;"><div style="padding:10px; background:#333; font-size:24px; color:#CCC;"><a href="http://www.yoursitename.com"><img src="http://www.yoursitename.com/images/logo.png" width="36" height="30" alt="yoursitename" style="border:none; float:left;"></a>yoursitename Account Activation</div><div style="padding:24px; font-size:17px;">Hello '.$u.',<br><br>Click the link below to activate your account when ready:<br><br><a href="http://www.yoursitename.com/activation.php?id='.$uid.'&u='.$u.'&e='.$e.'&key='.$p_hash.'">Click here to activate your account now</a><br><br>Login after successful activation using your:<br>* E-mail Address: <b>'.$e.'</b></div></body></html>';
		// $headers = "From: $from\n";
        // $headers .= "MIME-Version: 1.0\n";
        // $headers .= "Content-type: text/html; charset=iso-8859-1\n";
		// //mail($to, $subject, $message, $headers);
		echo "signup_success";
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
    <title>Sign Up</title>
	<?php include_once "stylesAndFont.php"; ?>    
</head>
<body>
<form name="signupform" id="signupform" onSubmit="return false;" style="padding:20px;">
  <h2 style="margin-left:5px;">Job Seeker</h2><i>All the fields are required.</i>
  <br>
  
  <div class="input-group input-group-lg">
  <label>user Name</label>
    <input id="username" type="text" onFocus="emptyElement('status')" onBlur="checkuname()" onKeyUp="restrict('username')" maxlength="40" class="form-control" placeholder=" Enter Username" style="border-radius: 0px; margin:5px;">
   </div>
   <span id="unamestatus"></span>
   
   <div class="input-group input-group-lg">
  <label>Display Name</label>
   
    <input id="displayname" type="text" onFocus="emptyElement('status')" maxlength="30" class="form-control" placeholder="Dispay name" style="border-radius: 0px; margin:5px;">
   </div>

   <div class="input-group input-group-lg">
  <label>Email Addres</label>
   
    <input class="form-control" placeholder="E-mail address" onBlur="checkemail()" id="email" type="text" onFocus="emptyElement('status')" onKeyUp="restrict('email')" maxlength="88" style="border-radius: 0px; margin:5px;">
   </div>
   <span id="emailstatus"></span>

    <div class="input-group input-group-lg">
	<label>Password </label>
	
    <input class="form-control" placeholder="Create password" id="pass1" type="password" onFocus="emptyElement('status')" maxlength="100" style="border-radius: 0px; margin:5px;">
    </div>

    <div class="input-group input-group-lg">
	<label>Confirm password</label>
	
    <input class="form-control" placeholder="confirm password" id="pass2" type="password" onFocus="emptyElement('status')" maxlength="100" style="border-radius: 0px; margin:5px;">
    </div>
    
    <div class="input-group input-group-lg">
	<label>Location</label>
	
    <input class="form-control" placeholder="Enter your city" id="location" type="text" onFocus="emptyElement('status')" maxlength="100" style="border-radius: 0px; margin:5px;">
    </div>
    
    <div class="input-group input-group-lg">
	<label>contact Number</label>
	
    <input class="form-control" placeholder="Enter your contact number" id="contact" type="text" onFocus="emptyElement('status')" maxlength="100" style="border-radius: 0px; margin:5px;">
    </div>
    
    <div class="input-group input-group-lg">
	<label>Date Of Birth</label>
	
    <input class="form-control" placeholder="Date Of Birth" id="birthdate" type="date" onFocus="emptyElement('status')" maxlength="100" style="border-radius: 0px; margin:5px;">
    </div>
    <button class="btn btn-success btn-lg" id="signupbtn" onClick="signup()" style="border-radius: 0px; margin-left:5px;">Create Account
    </button>
  </form>


  <div class="alert alert-danger" role="alert" id="status" style="display:none; width:300px;"></div>

<!-- scripts -->
<?php include_once("script.php"); ?>

  <script>
    var siteAddress = "http://localhost/DCentMASS_master";
    function restrict(elem){
	var tf = _(elem);
	var rx = new RegExp;
	if(elem == "email"){
		rx = /[' "\\]/gi;
	} else if(elem == "username"){
		rx = /[^a-z0-9_-]/gi;
	}
	tf.value = tf.value.replace(rx, "");
}
    function emptyElement(x){
	_(x).innerHTML = "";
	var status = _("status");
	status.style.display = "none";
}
function checkemail(){
	var u = _("email").value;
	if(u != ""){
		_("emailstatus").innerHTML = 'checking ...';
		var ajax = ajaxObj("POST", "<?php echo $siteAddress; ?>/jobseeker.php");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
	            _("emailstatus").innerHTML = ajax.responseText;
	        }
        }
        ajax.send("emailcheck="+u);
	}
}


function checkuname(){
	var t = _("username").value;
	if(t != "")
	{
		_("unamestatus").innerHtml = 'checking...';
		var ajax = ajaxObj("POST", "<?php echo $siteAddress; ?>/jobseeker.php");
		ajax.onreadystatechange = function()
	{
		if(ajaxReturn(ajax)== true){
			_("unamestatus").innerHTML = ajax.responseText;
		}
	}
		ajax.send("unamecheck="+t);
	}
	
}


function signup(){
	var uname = _("username").value;
    var dname = _("displayname").value;

	var emai = _("email").value;
    var pas1 = _("pass1").value;
    var pas2 = _("pass2").value;
    var loc = _("location").value;
    var con = _("contact").value;
    var dte = _("birthdate").value;
    var status = _("status");
	
	_("username").style.border = "1px solid #ccc";
	_("displayname").style.border = "1px solid #ccc";
    
	_("email").style.border = "1px solid #ccc";

	_("pass1").style.border = "1px solid #ccc";
	_("pass2").style.border = "1px solid #ccc";

	_("location").style.border = "1px solid #ccc";
	_("contact").style.border = "1px solid #ccc";
	_("birthdate").style.border = "1px solid #ccc";
    
	if (uname == "") {
		_("username").style.border = "2px solid #f60";
	}
    if (dname == "") {
		_("displayname").style.border = "2px solid #f60";
	}
	if (emai == "") {
		_("email").style.border = "2px solid #f60";
	}
	if (pas1 == "") {
		_("pass1").style.border = "2px solid #f60";
	}
    if (pas2 == "") {
		_("pass2").style.border = "2px solid #f60";
	}
    if (loc == "") {
		_("location").style.border = "2px solid #f60";
	}
    if (con == "") {
		_("contact").style.border = "2px solid #f60";
	}if (dte == "") {
		_("birthdate").style.border = "2px solid #f60";
	}
  if (pas1 != pas2){
		status.innerHTML = "Password not matching please enter valid password";
		status.style.display = "block";
		return 0;
    }
	if(uname == "" || dname == "" || emai == "" || pas1 == "" || pas2 == "" || loc == "" || con == "" || dte == ""){
		status.innerHTML = "Fill out all of the form data";
		status.style.display = "block";
		return 0;
	} else {
		_("signupbtn").style.display = "none";
		status.innerHTML = 'please wait ...';
		var ajax = ajaxObj("POST", "<?php echo $siteAddress; ?>/jobseeker.php");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
	            if(ajax.responseText != "signup_success"){
					status.innerHTML = ajax.responseText;
					status.style.display = "block";
					_("signupbtn").style.display = "block";
				} else {
					alert("Sign Up successful! You may Login now.");
					window.location = siteAddress+"/login.php";
					//_("signupform").innerHTML = "OK "+u+", check your email inbox and junk mail box at <u>"+e+"</u> in a moment to complete the sign up process by activating your account. You will not be able to do anything on the site until you successfully activate your account.";
				}
	        }
        }
        ajax.send("uname="+uname+"&dname="+dname+"&emai="+emai+"&pas1="+pas1+"&pas2="+pas2+"&loc="+loc+"&con="+con+"&dte="+dte);
	}
}
    </script>
</body>
</html>