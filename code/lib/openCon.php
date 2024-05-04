<?php
/*$sessTime = 24*60*60;
ini_set('session.gc_maxlifetime', $sessTime);
ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 1);*/
//ini_set('display_errors', 1);
//error_reporting(E_ALL);
error_reporting(E_ALL ^ E_DEPRECATED);
if($_SERVER['HTTP_HOST']=='localhost:82'){
	$dbServer   = "localhost";
	$dbDatabase = "gac_2024_gac_library";
	$dbUserName = "root";
	$dbPassword = "";
    $GLOBALS['siteURL'] = "http://localhost:82/gaclibrary/";
}
elseif($_SERVER['HTTP_HOST']=='localhost'){
	$dbServer   = "localhost";
	$dbDatabase = "gac_2024_gac_library";
	$dbUserName = "root";
	$dbPassword = "";
    $GLOBALS['siteURL'] = "http://localhost/gaclibrary/";
}
else{
    $dbServer   = "localhost";
    $dbDatabase = "";
	$dbUserName = "";
    $dbPassword = "";
    $GLOBALS['siteURL'] = "";
}
$GLOBALS['conn'] = new mysqli($dbServer, $dbUserName, $dbPassword, $dbDatabase);
mysqli_set_charset($GLOBALS['conn'], 'utf8');

date_default_timezone_set("Asia/Karachi");
define('date_time', date('Y-m-d H:i:s'));
?>
