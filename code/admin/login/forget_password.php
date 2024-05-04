<?php

include("../lib/openCon.php");
include("../lib/functions_mail.php");

$strMSG="";
if (isset($_POST['btnLogin'])) {  
 
    $rs = mysqli_query($GLOBALS['conn'], "SELECT u.*,c.* FROM user As u Left outer join contacts As c on c.user_id=u.user_id WHERE  user_name='" . $_REQUEST['user_name'] . "'");
    if (mysqli_num_rows($rs) > 0) {  
        $row = mysqli_fetch_object($rs);
        $mem_fnmae=$row->cnt_fname;
        $user_name=$row->user_name;
        //$user_pass=$row->user_password;
		 $user_pass = generate_password(8);
               
		mysqli_query($GLOBALS['conn'], "UPDATE user SET user_password='".md5($user_pass)."' WHERE user_id='".$row->user_id."'");
                
        //echo'1';
        forgotPass($mem_fnmae, $user_name, $user_pass);
       
		$strMSG = '<div class="alert alert-success" style="width:90%; margin-left:5%;"> Please check your email </div>';
        
    } else {
       //echo'2';
        $strMSG = '<div class="alert alert-danger" style="width:90%; margin-left:5%;"> Invalid Email Address </div>';
       
    }
}
ob_end_flush();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Ninja Lead Manager</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Loading Bootstrap -->
  <link href="css/bootstrap.css" rel="stylesheet">
  <link rel="shortcut icon" href="../favicon.ico">

  <!-- Loading Stylesheets -->    
  <link href="css/bootstrap.css" rel="stylesheet">
  <link href="css/font-awesome.css" rel="stylesheet">
  <link href="css/login.css" rel="stylesheet">
</head>
<body >
  <div class="login-box">
  	<!--<div align="center"><img src="../images/logo2_BW.png" width="150" /></div>
	<div align="center"><img src="../images/logo_beaconswatcher.png" width="240" /></div>-->
    <h1><!--<i class='fa fa-bookmark'></i>--><img src="../images/logo.png" height="30" />&nbsp;Ninja Lead Manager </h1>
    <hr>
    <h5>FORGOT PASSWORD</h5>
	<?php //print(md5("admin"));?>
	<?php print($strMSG); ?>
    <div class="input-box">
      <div class="row">
        <div class="col-md-8 col-md-offset-2 col-xs-10 col-xs-offset-1">
          <form role="form" method="post" action="<?php print($_SERVER['PHP_SELF']);?>">
            <div class="input-group form-group">
              <span class="input-group-addon"><i class='fa fa-envelope'></i></span>
              <input type="text" class="form-control" placeholder="Email" name="user_name">
            </div>
            
            <div class="form-group">
              <button type="submit" name="btnLogin" class="btn  btn-block  btn-submit pull-right">Submit</button>
            </div>
          </form>
        </div>
		<div style="clear:both;"></div>
		<div style="height:15px;">&nbsp;</div>
        <!-- col -->
      </div>
      <!-- row -->
    </div>
    <!-- input-box -->
  </div>
  <!-- lock-box -->
</body>
</html>