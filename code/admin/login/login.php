<?php
ob_start();
include("../lib/openCon.php");
session_start();
//print(md5("admin"));
//DIE();
$strMSG="";
if (isset($_REQUEST['btnLogin'])){
	$rs = mysqli_query($GLOBALS['conn'], "SELECT * FROM user WHERE user_password='".md5($_REQUEST['mem_password'])."' AND user_name='".$_REQUEST['mem_login']."'") or die(mysqli_error($GLOBALS['conn']));
	if(mysqli_num_rows($rs)>0){
		$row = mysqli_fetch_object($rs);
		if($row->utype_id == 1){
			$_SESSION["isAdmin"] = 1;
		}
		else{
			$_SESSION["isAdmin"] = 0;
		}
			$_SESSION["UserID"] = $row->user_id;
			$_SESSION["UserName"] = $row->user_name;
			$_SESSION["UType"] = $row->utype_id;
			/*if($_SESSION["UType"] == 3){
				$_SESSION['member_id'] = $row->mem_id;
			}
			else{
				$_SESSION['member_id'] = 0;
			}
			$_SESSION["FName"] = $row->mem_fname;
			$_SESSION["LName"] = $row->mem_lname;*/
		header("location: ../index.php");
	}
	else{
		$strMSG = '<div class="alert alert-danger" style="width:90%; margin-left:5%;">Invalid Login / Password</div>';
	}
}
ob_end_flush();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Kaamkaaj Manager</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="../favicon.ico">

  <!-- Loading Bootstrap -->
  <link href="css/bootstrap.css" rel="stylesheet">

  <!-- Loading Stylesheets -->    
  <link href="css/bootstrap.css" rel="stylesheet">
  <link href="css/font-awesome.css" rel="stylesheet">
  <link href="css/login.css" rel="stylesheet">
</head>
<body >
  <div class="login-box">
  	<!--<div align="center"><img src="../images/logo2_BW.png" width="150" /></div>
	<div align="center"><img src="../images/logo_beaconswatcher.png" width="240" /></div>-->
    <h1><!--<i class='fa fa-bookmark'></i>--><img src="../images/logo.png" width="150" />&nbsp;Ninja Lead Manager </h1>
    <hr>
    <h5>LOGIN</h5>
	<?php //print(md5("admin"));?>
	<?php print($strMSG); ?>
    <div class="input-box">
      <div class="row">
        <div class="col-md-8 col-md-offset-2 col-xs-10 col-xs-offset-1">
          <form role="form" method="post" action="<?php print($_SERVER['PHP_SELF']);?>">
            <div class="input-group form-group">
              <span class="input-group-addon"><i class='fa fa-envelope'></i></span>
              <input type="text" class="form-control" placeholder="Email" name="mem_login">
            </div>
            <div class="input-group form-group">
              <span class="input-group-addon"><i class='fa fa-key'></i></span>
              <input type="Password" class="form-control" placeholder="Password" name="mem_password">
            </div>
            <div class="form-group" style="position:relative; float:left; width:100%;">
              <button type="submit" name="btnLogin" class="btn  btn-block  btn-submit pull-right">Submit</button>
            </div>
          </form>
			<div style="clear:both;"></div>
			<div style="height:20px; line-height:20px; margin:0px 0px 10px 0px;"><a href="forget_password.php">Forgot Password</a></div>
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