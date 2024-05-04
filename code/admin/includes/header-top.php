<?php 
ob_start();
include('../lib/session_head.php');
// You can easily build the menu with php predefined function written by me (@bootstrapguru). It is located in root folder with file name called menu-builder.php
//include('includes/menu-builder.php'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<!--<link rel="shortcut icon" href="favicon.ico">-->
	<meta charset="utf-8">
	<title> Web Administration Panel</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="stylesheet" type="text/css" href="css/cloud-admin.css" >
	<link rel="stylesheet" type="text/css"  href="css/themes/default.css" id="skin-switcher" >
	<link rel="stylesheet" type="text/css"  href="css/responsive.css" >
	<!-- STYLESHEETS --><!--[if lt IE 9]><script src="js/flot/excanvas.min.js"></script><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script><![endif]-->
	<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">
	<!-- ANIMATE -->
	<link rel="stylesheet" type="text/css" href="css/animatecss/animate.min.css" />
        <!-- COLORBOX -->
	<link rel="stylesheet" type="text/css" href="js/colorbox/colorbox.min.css" />
    <link rel="stylesheet" type="text/css" href="../admin/lib/datatables/css/table_jui.css">
        
        <!-- JQUERY UI-->
	<link rel="stylesheet" type="text/css" href="js/jquery-ui-1.10.3.custom/css/custom-theme/jquery-ui-1.10.3.custom.min.css" />
	<!-- DATE RANGE PICKER -->
	<link rel="stylesheet" type="text/css" href="js/bootstrap-daterangepicker/daterangepicker-bs3.css" />
	<!-- TODO -->
	<link rel="stylesheet" type="text/css" href="js/jquery-todo/css/styles.css" />
	<!-- FULL CALENDAR -->
	<link rel="stylesheet" type="text/css" href="js/fullcalendar/fullcalendar.min.css" />
	<!-- GRITTER -->
<!--	<link rel="stylesheet" type="text/css" href="js/gritter/css/jquery.gritter.css" />-->
        <link rel="stylesheet" type="text/css" media="screen" href="js/jquery.validate.password.css" />
        <!-- BOOTSTRAP SWITCH -->
	<link rel="stylesheet" type="text/css" href="js/bootstrap-switch/bootstrap-switch.min.css" />
	<!-- FONTS -->
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700' rel='stylesheet' type='text/css'>
  	<script src="../admin/js/jquery/jquery-2.0.3.min.js"></script>
   <script language="javascript">
function setAll(){
	if(frm.chkAll.checked == true){
		checkAll("frm", "chkstatus[]");
	}
	else{
		clearAll("frm", "chkstatus[]");
	}
}
function setAll2(objAll, obj){
	if(objAll.checked == true){
		checkAll("frm", obj);
	}
	else{
		clearAll("frm", obj);
	}
}
function checkAll(TheForm, Field){
	var obj = document.forms[TheForm].elements[Field];
	if(obj.length > 0){
		for(var i=0; i < obj.length; i++){
			obj[i].checked = true;
		}
	}
	else{
		obj.checked = true;
	}
}

function clearAll(TheForm, Field){
	var obj = document.forms[TheForm].elements[Field];
	if(obj.length > 0){
		for(var i=0; i < obj.length; i++){
			obj[i].checked = false;
		}
	}
	else{
		obj.checked = false;
	}
}
</script>
<script src="../admin/js/head.load.min.js"></script>

</head>