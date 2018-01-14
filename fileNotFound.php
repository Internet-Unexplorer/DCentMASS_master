<?php
/*
Author name: <Your name>;
E-mail ID:   <Your E-mail ID>
Date:        <dd-mm-yyyy>
*/
/////////////////////////////////////////////////////////
$siteAddress = "http://localhost/DCentMASS_master";

// include_once("variables.php");
//include_once("checkLoginStatus.php");

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Title</title>
    <?php include_once("stylesAndFont.php"); ?>
    <link href="https://fonts.googleapis.com/css?family=Gochi+Hand" rel="stylesheet">
    <style>
        body {
            background: #ccc;
            font-size:30px;
        }
        .textFam {
            font-family: Gochi Hand;

        }
        button {
            font-family: Arial;
            font-weight: bold;
        }
        input {
            font-family: Roboto;
        }
    </style>
</head>
<body>
<div class="container textFam">
    <div class="row">
        <div class="col-md-6" style="text-align:center; padding-top: 10%;">
            <h1>Uhhmm.. 404.</h1>
                <img style="height:300px; display:inline-block" src="http://localhost/DCentMASS_master/images/shrug.png" alt="Shrug" class="img-fluid">
            <h1>SORRY.</h1> 
        </div>
        <div class="col-md-6" style="text-align:center; padding-top: 20%;">
            <h2>What you were looking for, though?</h2>
            <input type="text" class="form-control">
            <button class="btn btn-primary btn-lg">Search</button>
        </div>
        
    </div>
</div>
<?php include_once("script.php"); ?>
</body>
</html>