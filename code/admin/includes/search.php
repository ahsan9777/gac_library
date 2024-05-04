<?php
include("../../lib/openCon.php");
$str= $_GET['srchStr'];
if($_REQUEST['inactive']=='false'){
	
$query="SELECT * FROM members WHERE status_id='1' AND (mem_fname LIKE '%". $str. "%' OR mem_lname LIKE '%". $str. "%' OR mem_login LIKE '%". $str. "%')";
}else{
	$query="SELECT * FROM members WHERE status_id='0' AND (mem_fname LIKE '%". $str. "%' OR mem_lname LIKE '%". $str. "%' OR mem_login LIKE '%". $str. "%')";
	}
 $running_query=mysqli_query($GLOBALS['conn'], $query) or die(mysqli_error($GLOBALS['conn']));
 //echo $running_query." running query";
 $fetchQuery=mysqli_fetch_object($running_query);
 if(mysqli_num_rows($running_query)>0){
 echo "success"; }
 else {
	 echo "failed";
	 }
 ?>