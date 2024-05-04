<?php
ob_start();
include("../lib/openCon.php");
include("../lib/functions_mail.php");
session_start();
//print(md5("admin"));
//DIE();
$strMSG="";
$strMSG1="";
if (isset($_POST['btnLogin'])){
    
    if(!empty($_POST)){
        
        
        $usernameError=null;
        $passwordError=null;
        $password=md5($_POST['mem_password']);
        //$password=$_POST['mem_password'];
        $username=$_POST['mem_login'];
        $valid = true;
                if (empty($username)) {
                    $usernameError = 'Please enter user Name';
                    $valid = false;
                }
                if($valid){
                        $rs = mysqli_query($GLOBALS['conn'], "SELECT * FROM users WHERE user_password='$password' AND user_name='$username' AND utype_id IN (2,3)") or die(mysqli_error($GLOBALS['conn']));
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
                                header("location:index.php");
                        }
                        else{
                                $strMSG = '<div class="alert alert-danger" style="width:100%; ">Invalid Login / Password</div>';
                        }
                }
        }
    }

if (isset($_POST['btnforgetpass'])) {  
 
    $rs = mysqli_query($GLOBALS['conn'], "SELECT u.*,c.* FROM users As u Left outer join contacts As c on c.user_id=u.user_id WHERE  user_name='" . $_REQUEST['user_name'] . "'");
    if (mysqli_num_rows($rs) > 0) {  
        $row = mysqli_fetch_object($rs);
        $mem_fnmae=$row->cnt_fname;
        $user_name=$row->user_name;
        //$user_pass=$row->user_password;
		 $user_pass = generate_password(8);
               
		mysqli_query($GLOBALS['conn'], "UPDATE users SET user_password='".md5($user_pass)."' WHERE user_id='".$row->user_id."'");
                
        //echo'1';
        forgotPass($mem_fnmae, $user_name, $user_pass);
       
		$strMSG1 = '<div class="alert alert-success" style="width:90%; margin-left:5%;"> Please check your email </div>';
        
    } else {
       //echo'2';
        $strMSG1 = '<div class="alert alert-danger" style="width:90%; margin-left:5%;"> Invalid Email Address </div>';
       
    }
}
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<title>Web Administration Panel</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no">
<meta name="description" content="">
<meta name="author" content="">
<!-- STYLESHEETS --><!--[if lt IE 9]><script src="js/flot/excanvas.min.js"></script><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script><![endif]-->
<link rel="stylesheet" type="text/css" href="css/cloud-admin.css" >
<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">
<!-- DATE RANGE PICKER -->
<link rel="stylesheet" type="text/css" href="js/bootstrap-daterangepicker/daterangepicker-bs3.css" />
<!-- UNIFORM -->
<link rel="stylesheet" type="text/css" href="js/uniform/css/uniform.default.min.css" />
<!-- ANIMATE -->
<link rel="stylesheet" type="text/css" href="css/animatecss/animate.min.css" />
<!-- FONTS -->
<link href='http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700' rel='stylesheet' type='text/css'>
</head>
<body class="login">
<!-- PAGE -->
<section id="page"> 
	<!-- HEADER -->
	<header> 
		<!-- NAV-BAR --> 
		
		<!--/NAV-BAR --> 
	</header>
	<!--/HEADER --> 
	<!-- LOGIN -->
	<section id="login" class="visible">
		<div class="container">
			<div class="row">
				<div class="col-md-4 col-md-offset-4">
					<div class="login-box-plain">
						<div class="container">
							<div class="row">
								<div class="col-md-14" align="center"> 
									<!--<div id="logo" class="logo1" >--> 
									<a href="index.html"><img src="images/logo/logo.png" height="46" alt="logo name" /> </a><!--<h4>&nbsp;Ace Surfing</h4>--> 
									<!--</div>--> 
								</div>
							</div>
						</div>
						<h2 class="bigintro">Sign In</h2>
						<div class="divide-10"></div>
						<?php print($strMSG); ?>
						<form role="form" method="post" action="<?php print($_SERVER['PHP_SELF']);?>">
							<div class="form-group <?php echo!empty($usernameError) ? 'has-error' : ''; ?>">
								<label for="exampleInputEmail1">Email address</label>
								<i class="fa fa-envelope"></i>
								<input type="text" class="form-control" id="exampleInputEmail1" name="mem_login" >
								<?php if (!empty($usernameError)): ?>
								<span class="help-inline"><?php echo $usernameError; ?></span>
								<?php endif; ?>
							</div>
							<div class="form-group ">
								<label for="exampleInputPassword1">Password</label>
								<i class="fa fa-lock"></i>
								<input type="password" class="form-control" id="exampleInputPassword1" name="mem_password">
							</div>
							<div class="form-actions">
								<label class="checkbox">
									<input type="checkbox" class="uniform" value="">
									Remember me</label>
								<button type="submit" name="btnLogin" class="btn btn-danger">Submit</button>
							</div>
						</form>
						<!-- SOCIAL LOGIN -->
						<div class="divide-20"></div>
						
						<!-- /SOCIAL LOGIN --> 
						<!--								<div class="login-helpers">
									<a href="#" onclick="swapScreen('forgot');return false;">Forgot Password?</a> <br>
									Don't have an account with us? <a href="#" onclick="swapScreen('register');return false;">Register
										now!</a>
								</div>--> 
					</div>
				</div>
			</div>
		</div>
	</section>
	<!--/LOGIN --> 
	<!-- REGISTER -->
	<section id="register" >
		<div class="container">
			<div class="row">
				<div class="col-md-4 col-md-offset-4">
					<div class="login-box-plain">
						<h2 class="bigintro">Register</h2>
						<div class="divide-40"></div>
						<form role="form">
							<div class="form-group">
								<label for="exampleInputName">Full Name</label>
								<i class="fa fa-font"></i>
								<input type="text" class="form-control" id="exampleInputName" >
							</div>
							<div class="form-group">
								<label for="exampleInputUsername">Username</label>
								<i class="fa fa-user"></i>
								<input type="text" class="form-control" id="exampleInputUsername" >
							</div>
							<div class="form-group">
								<label for="exampleInputEmail1">Email address</label>
								<i class="fa fa-envelope"></i>
								<input type="email" class="form-control" id="exampleInputEmail1" >
							</div>
							<div class="form-group">
								<label for="exampleInputPassword1">Password</label>
								<i class="fa fa-lock"></i>
								<input type="password" class="form-control" id="exampleInputPassword1" >
							</div>
							<div class="form-group">
								<label for="exampleInputPassword2">Repeat Password</label>
								<i class="fa fa-check-square-o"></i>
								<input type="password" class="form-control" id="exampleInputPassword2" >
							</div>
							<div class="form-actions">
								<label class="checkbox">
									<input type="checkbox" class="uniform" value="">
									I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a></label>
								<button type="submit" class="btn btn-success">Sign Up</button>
							</div>
						</form>
						<!-- SOCIAL REGISTER -->
						<div class="divide-20"></div>
						<div class="center"> <strong>Or register using your social account</strong> </div>
						<div class="divide-20"></div>
						<div class="social-login center"> <a class="btn btn-primary btn-lg"> <i class="fa fa-facebook"></i> </a> <a class="btn btn-info btn-lg"> <i class="fa fa-twitter"></i> </a> <a class="btn btn-danger btn-lg"> <i class="fa fa-google-plus"></i> </a> </div>
						<!-- /SOCIAL REGISTER -->
						<div class="login-helpers"> <a href="#" onclick="swapScreen('login');return false;"> Back to Login</a> <br>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!--/REGISTER --> 
	<!-- FORGOT PASSWORD -->
	<section id="forgot">
		<div class="container">
			<div class="row">
				<div class="col-md-4 col-md-offset-4">
					<div class="login-box-plain">
						<h2 class="bigintro">Reset Password</h2>
						<div class="divide-40"></div>
						<?php print($strMSG1); ?>
						<form role="form" method="post" action="<?php print($_SERVER['PHP_SELF']);?>">
							<div class="form-group">
								<label for="exampleInputEmail1">Enter your Email address</label>
								<i class="fa fa-envelope"></i>
								<input type="email" class="form-control" id="exampleInputEmail1" name="user_name" >
							</div>
							<div class="form-actions">
								<button type="submit" name="btnforgetpass" class="btn btn-info">Send Me Reset Instructions</button>
							</div>
						</form>
						<div class="login-helpers"> <a href="#" onclick="swapScreen('login');return false;">Back to Login</a> <br>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- FORGOT PASSWORD --> 
</section>
<!--/PAGE --> 
<!-- JAVASCRIPTS --> 
<!-- Placed at the end of the document so the pages load faster --> 
<!-- JQUERY --> 
<script src="js/jquery/jquery-2.0.3.min.js"></script> 
<!-- JQUERY UI--> 
<script src="js/jquery-ui-1.10.3.custom/js/jquery-ui-1.10.3.custom.min.js"></script> 
<!-- BOOTSTRAP --> 
<script src="bootstrap-dist/js/bootstrap.min.js"></script> 

<!-- UNIFORM --> 
<script type="text/javascript" src="js/uniform/jquery.uniform.min.js"></script> 
<!-- CUSTOM SCRIPT --> 
<script src="js/script.js"></script> 
<script>
		jQuery(document).ready(function() {		
			App.setPage("login");  //Set current page
			App.init(); //Initialise plugins and elements
		});
	</script> 
<script type="text/javascript">
		function swapScreen(id) {
			jQuery('.visible').removeClass('visible animated fadeInUp');
			jQuery('#'+id).addClass('visible animated fadeInUp');
                       
                       
		}
               
	</script> 
<!-- /JAVASCRIPTS -->
</body>
</html>