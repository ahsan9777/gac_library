<?php

 if (isset($_GET['action'])) {
        if (isset($_POST['btnAdd'])) {
           
                    $user_id = getMaximum("user", "user_id");
                    mysqli_query($GLOBALS['conn'], "INSERT INTO user ("
                            . "utype_id,"
                            . "user_id,"
                            . "user_name,"
                            . "user_password,"
                            . "user_fname,"
                            . "user_lname,"
                            . "user_father_name,"
                            . "user_nic,"
                            . "user_gender,"
                            . "user_email,"
                            . "user_mobile,"
                            . "user_phone,"
                            . "user_address,"
                            . "city_id,"
                            . "state_id,"
                            . "countries_id,"
                            . "user_dob,"
                            . "user_created_on"
                            . ") "
                            . "VALUES (".$user_id.","
                            . "'".$_POST['utype_ids']."',"
                            . "'".$_POST['user_name']."',"
                            . "'".$_POST['user_password']."',"
                            . "'".$_POST['user_fname']."',"
                            . "'".$_POST['user_lname']."',"
                            . "'".$_POST['user_father_name']."',"
                            . "'".$_POST['user_nic']."',"
                            . "'".$_POST['user_gender']."',"
                            . "'".$_POST['user_email']."',"
                            . "'".$_POST['user_mobile']."',"
                            . "'".$_POST['user_phone']."',"
                            . "'".$_POST['user_address']."',"
                            . "'".$_POST['city_id']."',"
                            . "'".$_POST['state_id']."',"
                            . "'".$_POST['countries_id']."',"
                            . "'".$_POST['user_dob']."',"
                            . "'".date('y-m-d')."'"
                            . ")");
                    header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=1");
             
        } elseif (isset($_POST['btnUpdate'])) {
             print_r($_REQUEST);
             
            // update data
             
            mysqli_query($GLOBALS['conn'], "UPDATE user SET user_name='".$_POST['user_name']."',"
                            . "utype_id='".$_POST['utype_id']."',"
                            . "user_fname='".$_POST['user_fname']."',"
                            . "user_lname='".$_POST['user_lname']."',"
                            . "user_father_name='".$_POST['user_father_name']."',"
                            . "user_nic='".$_POST['user_nic']."',"
                            . "user_gender='".$_POST['user_gender']."',"
                            . "user_email='".$_POST['user_email']."',"
                            . "user_mobile='".$_POST['user_mobile']."',"
                            . "user_phone='".$_POST['user_phone']."',"
                            . "user_address='".$_POST['user_address']."',"
                            . "city_id='".$_POST['city_id']."',"
                            . "state_id='".$_POST['state_id']."',"
                            . "countries_id='".$_POST['countries_id']."',"
                            . "user_dob='".$_POST['user_dob']."'"
                    . " WHERE user_id=" . $_GET['userid']);
            header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=2");
        } elseif ($_GET['action'] == 2) {
            $rsM = mysqli_query($GLOBALS['conn'], "SELECT * FROM user WHERE user_id = " . $_GET['userid']);
            if (mysqli_num_rows($rsM) > 0) {
                $rsMem = mysqli_fetch_object($rsM);
                $user_name = $rsMem->user_name;
                $user_password=$rsMem->user_password;
                $countries_id=$rsMem->countries_id;
                $user_fname=$rsMem->user_fname;
                $user_lname=$rsMem->user_lname;
                $user_father_name=$rsMem->user_father_name;
                $user_nic=$rsMem->user_nic;
                $user_gender=$rsMem->user_gender;
                $user_email=$rsMem->user_email;
                $user_mobile=$rsMem->user_mobile;
                $user_phone=$rsMem->user_phone;
                $user_address=$rsMem->user_address;
                $city_id=$rsMem->city_id;
                $state_id=$rsMem->state_id;
                $user_dob=$rsMem->user_dob;
                $utype_id=$rsMem->utype_id;
             
                $formHead = "Update Info";
            }
        } else {
           
             $user_id="";
             $user_name="";
             $user_password="";
             $user_fname="";
             $user_lname="";
             $user_father_name="";
             $user_nic="";
             $user_gender="";
             $user_email="";
             $user_mobile="";
             $user_phone="";
             $user_address="";
             $city_id="";
             $state_id="";
             $countries_id="";
             $user_dob="";
             $utype_id="";
                           
            $formHead = "Add New";
        }
    }
    if (isset($_REQUEST['show'])) {

        $rsM = mysqli_query($GLOBALS['conn'], "SELECT u.*,s.state_name,c.city_name,cu.countries_name FROM user AS u LEFT OUTER JOIN countries AS cu ON cu.countries_id=u.countries_id LEFT OUTER JOIN states AS s ON s.state_id=u.state_id LEFT OUTER JOIN cities AS c ON c.city_id=u.city_id WHERE u.user_id = " . $_REQUEST['userid']);
        if (mysqli_num_rows($rsM) > 0) {
            $rsMem = mysqli_fetch_object($rsM);
                $user_name = $rsMem->user_name;
                $user_password=$rsMem->user_password;
                $countries_name=$rsMem->countries_name;
                $user_fname=$rsMem->user_fname;
                $user_lname=$rsMem->user_lname;
                $user_father_name=$rsMem->user_father_name;
                $user_nic=$rsMem->user_nic;
                $user_gender=$rsMem->user_gender;
                $user_email=$rsMem->user_email;
                $user_mobile=$rsMem->user_mobile;
                $user_phone=$rsMem->user_phone;
                $user_address=$rsMem->user_address;
                $city_name=$rsMem->city_name;
                $state_name=$rsMem->state_name;
                $user_dob=$rsMem->user_dob;
            $formHead = "Update Info";
        }
    }
//--------------Button Delete--------------------
    
    
    if(isset($_REQUEST['btnDelete'])){
    
	if(isset($_REQUEST['chkstatus'])){
		for($i=0; $i<count($_REQUEST['chkstatus']); $i++){
			mysqli_query($GLOBALS['conn'], "DELETE FROM user  WHERE user_id = ".$_REQUEST['chkstatus'][$i]);
		}
		$class='alert alert-danger';
		$strMSG = "Record(s) deleted successfully";
		$_REQUEST['op']="";
	}
	else{
		$class='alert alert-info';
		$strMSG = "Please check atleast one checkbox";
		$_REQUEST['op']="";
	}
}

//--------------Button Active--------------------
    if (isset($_REQUEST['btnActive'])) {
        if (isset($_REQUEST['chkstatus'])) {
            for ($i = 0; $i < count($_REQUEST['chkstatus']); $i++) {
                mysqli_query($GLOBALS['conn'], "UPDATE user SET status_id =1 WHERE user_id = " . $_REQUEST['chkstatus'][$i]);
            }
            $class = "alert alert-success";
            $strMSG = "Record(s) updated successfully";
            $_REQUEST['op']="";
        } else {
            $class = "alert alert-info";
            $strMSG = "Please Select Alteast One Checkbox";
            $_REQUEST['op']="";
        }
    }
//--------------Button InActive--------------------
    if (isset($_REQUEST['btnInactive'])) {
        if (isset($_REQUEST['chkstatus'])) {
            for ($i = 0; $i < count($_REQUEST['chkstatus']); $i++) {
                mysqli_query($GLOBALS['conn'], "UPDATE user SET status_id =0 WHERE user_id = " . $_REQUEST['chkstatus'][$i]);
            }
            $class = "alert alert-success";
            $strMSG = "Record(s) updated successfully";
            $_REQUEST['op']="";
        } else {
            $class = "alert alert-info";
            $strMSG = "Please Select Alteast One Checkbox";
            $_REQUEST['op']="";
        }
    }


include("includes/messages.php"); 


?>