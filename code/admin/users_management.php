<?php include("includes/header-top.php"); ?>
<body>
<!-- HEADER -->
<?php
include("includes/header.php");
$formHead = "Add New";
$strMSG = "";
$class = "";
$qryStrURL = "";
$qryStr = "";
$readonly = "";
$user_full_name = "";
$searchQuery ="";
$ref = "users_management.php";
if (isset($_SERVER['HTTP_REFERER'])) {
    $ref = $_SERVER['HTTP_REFERER'];
}

if(isset($utype_id) && in_array($utype_id, array(1,2,3))){
	$utype_id = $utype_id;
    $searchQuery = " AND u.utype_id IN (2,3)";
} else {
    $utype_id = "";
    $searchQuery = " AND u.utype_id IN (4)";
	$pHead = "Investor Management";
    $table_Head = "Investor";
}

if (isset($_REQUEST['user_id']) && $_REQUEST['user_id'] > 0) {
    if(!empty($_REQUEST['user_fname']) || !empty($_REQUEST['user_lname'])) {
        $qryStrURL .= "user_id=".$_REQUEST['user_id']."&";
        $user_full_name .=  $_REQUEST['user_fname']; 
        if(!empty($_REQUEST['user_lname'])){ $user_full_name .=  " ".$_REQUEST['user_lname'];}
        $qryStrURL .= "user_full_name=".urlencode($user_full_name)."&";

    }

}
if (isset($_REQUEST['user_email_id']) && $_REQUEST['user_email_id'] > 0) {
    if(!empty($_REQUEST['user_name'])) {

        $qryStrURL .= "user_email_id=".$_REQUEST['user_email_id']."&";
        $qryStrURL .= "user_name=".urlencode($_REQUEST['user_name'])."&";

    }

}
if (isset($_REQUEST['utype_id']) && $_REQUEST['utype_id'] > 0) {

    $qryStrURL .= "utype_id=".$_REQUEST['utype_id']."&";
}

if (isset($_REQUEST['btnAdd'])) {
    $user_id = getMaximum("users", "user_id");
    $mfileName = "";
    /*$dirName = "images/manufacturers/";*/
    if(!file_exists("../files/customer/".$user_id))
    {
        mkdir("../files/customer/".$user_id, 0777, true);
        mkdir("../files/customer/".$user_id. "/th/", 0777, true);
    }
    $dirName = "../files/customer/".$user_id."/";
    if (!empty($_FILES["mFile"]["name"])) {
        $mfileName = $user_id . "_" . $_FILES["mFile"]["name"];
        $mfileName = str_replace(" ", "_", strtolower($mfileName));
        if (move_uploaded_file($_FILES['mFile']['tmp_name'], $dirName . $mfileName)) {
            createThumbnail2($dirName, $mfileName, $dirName . "th/", "200", "200");
        }
    }
    if(isset($_REQUEST['user_name']) && !empty($_REQUEST['user_name']))
    {

        $rsM = mysqli_query($GLOBALS['conn'], "SELECT * FROM users WHERE user_name = '".dbStr($_REQUEST['user_name'])."' AND utype_id = '".dbStr($_REQUEST['utype_id'])."'");
        if (mysqli_num_rows($rsM) > 0) {
            /*$rsMem = mysqli_fetch_object($rsM);*/
            header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=10");
        }
        else
            {
                mysqli_query($GLOBALS['conn'], "INSERT INTO users (`user_id`, `user_name`, `user_password`, `utype_id`, `user_fname`, `user_lname`,  `gen_id`, `user_phone`, `user_address`, `user_img`) VALUES (" . $user_id . ",'" . dbStr(trim($_REQUEST['user_name'])) . "', '" . dbStr(md5(trim($_REQUEST['user_password']))) . "','".dbStr($_REQUEST['utype_id'])."', '".dbStr(trim($_REQUEST['user_fname']))."', '" . dbStr(trim($_REQUEST['user_lname'])) . "','" . dbStr($_REQUEST['gen_id']) . "','" . dbStr(trim($_REQUEST['user_phone'])) . "', '".dbStr(trim($_REQUEST['user_address']))."','". $mfileName ."')") or die(mysqli_error($GLOBALS['conn']));
                header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=1");

        }

    }

} elseif (isset($_REQUEST['btnUpdate'])) {
    $date=date_create($_REQUEST['user_dob']);
    $user_dob=date_format($date,"Y-m-d");
    if(!file_exists("../files/customer/".$_REQUEST['user_id']))
    {
        mkdir("../files/customer/".$_REQUEST['user_id'], 0777, true);
        mkdir("../files/customer/".$_REQUEST['user_id']. "/th/", 0777, true);
    }
    $dirName = "../files/customer/".$_REQUEST['user_id']."/";
    $mfileName = $_REQUEST['mFile'];
    if (!empty($_FILES["mFile"]["name"])) {
        DeleteFileWithThumb("user_img", "users", "user_id", $_REQUEST['user_id'], "../files//customer/" . $_REQUEST["user_id"] . "/", "EMPTY");
        DeleteFileWithThumb("user_img", "users", "user_id", $_REQUEST['user_id'], "../files//customer/" . $_REQUEST["user_id"] . "/th/", "EMPTY");
        $mfileName = $_REQUEST['user_id'] . "_" . $_FILES["mFile"]["name"];
        $mfileName = str_replace(" ", "_", strtolower($mfileName));
        if (move_uploaded_file($_FILES['mFile']['tmp_name'], $dirName . $mfileName)) {
            createThumbnail2($dirName, $mfileName, $dirName . "th/", "200", "200");
        }
    }
    mysqli_query($GLOBALS['conn'], "UPDATE users SET user_fname='" . dbStr(trim($_REQUEST['user_fname'])) . "', user_lname='" . dbStr(trim($_REQUEST['user_lname'])) . "', gen_id='" . dbStr($_REQUEST['gen_id']) . "', user_phone='" . dbStr(trim($_REQUEST['user_phone'])) . "', user_address='" . dbStr(trim($_REQUEST['user_address'])) . "',  user_img='" . $mfileName . "', utype_id='".dbStr($_REQUEST['utype_id'])."' WHERE user_id=" . $_REQUEST['user_id'])  or die(mysqli_error($GLOBALS['conn']));
    header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=2");


} elseif (isset($_REQUEST['btnResetPassword'])) {
    $Query = "SELECT * FROM users WHERE user_password = '".md5(trim($_REQUEST['old_password']))."' AND user_id ='".$_REQUEST['user_id']."'";
    $rsM = mysqli_query($GLOBALS['conn'], $Query);
    if (mysqli_num_rows($rsM) > 0) {
        if (trim($_REQUEST['new_password']) == trim($_REQUEST['reconfirm_password'])) {
            mysqli_query($GLOBALS['conn'], "UPDATE users SET user_password='".md5(trim($_REQUEST['reconfirm_password']))."' WHERE user_id=".$_REQUEST["user_id"]) or die(mysqli_error($GLOBALS['conn']));
            //$mailer->change_password($_SESSION["FullName"], $_SESSION["UName"],trim($_REQUEST['reconfirm_password']));
            header("Location: " . $_SERVER['PHP_SELF'] . "?op=9");
        }
        else
        {
            header("Location: " . $_SERVER['PHP_SELF'] . "?action=3&user_id=".$_REQUEST['user_id']."&op=7");
        }
    }
    else
    {
        header("Location: " . $_SERVER['PHP_SELF'] . "?action=3&user_id=".$_REQUEST['user_id']."&op=8");

    }
} elseif (isset($_REQUEST['action'])) {
    if ($_REQUEST['action'] == 2) {
        $rsM = mysqli_query($GLOBALS['conn'], "SELECT * FROM users WHERE user_id = " . $_REQUEST['user_id']);
        if (mysqli_num_rows($rsM) > 0) {
            $rsMem = mysqli_fetch_object($rsM);
            $user_fname = $rsMem->user_fname;
            $user_lname = $rsMem->user_lname;
            $user_name = $rsMem->user_name;
            $user_password = $rsMem->user_password;
            $utype_id = $rsMem->utype_id;
            $gen_id = $rsMem->gen_id;
            $user_phone = $rsMem->user_phone;
            $user_address = $rsMem->user_address;
            $user_img = $rsMem->user_img;
            $user_image_path = "../files/no_img_1.jpg";
            if(!empty($rsMem->user_img)){
                $user_image_path = "../files/customer/".$rsMem->user_id."/".$rsMem->user_img;
            }
            $formHead = "Update Info";
            $readonly = "readonly";

        }
    } elseif($_REQUEST['action'] == 3){
        $formHead = "Reset Password";
    } else {
        $user_fname = "";
        $user_lname = "";
        $user_name = "";
        $user_password = "";
        $utype_id = "";
        $gen_id = "";
        $user_phone = "";
        $user_address = "";
        $formHead = "Add New";
    }
}
elseif (isset($_REQUEST['show'])) {

    $rsM = mysqli_query($GLOBALS['conn'], "SELECT u.*, ut.utype_name FROM `users` AS u LEFT OUTER JOIN user_type AS ut ON ut.utype_id=u.utype_id WHERE  u.user_id = " . $_REQUEST['user_id']);
    if (mysqli_num_rows($rsM) > 0) {
        $rsMem = mysqli_fetch_object($rsM);
        $user_fname = $rsMem->user_fname;
        $user_lname = $rsMem->user_lname;
        $user_name = $rsMem->user_name;
        $user_password = $rsMem->user_password;
        $utype_id = $rsMem->utype_id;
        $utype_name = $rsMem->utype_name;
        $gen_id = $rsMem->gen_id;
        $user_phone = $rsMem->user_phone;
        $user_dob = $rsMem->user_dob;
        $user_address = $rsMem->user_address;
        $user_house_no = $rsMem->user_house_no;
        $user_street = $rsMem->user_street;
        $user_town = $rsMem->user_town;
        $user_countrie = $rsMem->user_countrie;
        $user_state = $rsMem->user_state;
        $user_city = $rsMem->user_city;
        $user_image_path = $GLOBALS['siteURL']."files/no_img_1.jpg";
        if(isset($_REQUEST['proj_id']) && $_REQUEST['proj_id'] > 0){
            $user_image_path = $GLOBALS['siteURL']."files/projects/".$_REQUEST['proj_id']."/".returnImage("proj_logo","projects", "proj_id", $_REQUEST['proj_id']);
        }elseif(!empty($rsMem->user_img)){
            $user_image_path = $GLOBALS['siteURL']."files/customer/".$rsMem->user_id."/".$rsMem->user_img;
        }
        $formHead = "Update Info";
    }
}
//--------------Button Active--------------------
if (isset($_REQUEST['btnActive'])) {
    if (isset($_REQUEST['chkstatus'])) {
        for ($i = 0; $i < count($_REQUEST['chkstatus']); $i++) {
            mysqli_query($GLOBALS['conn'], "UPDATE users SET status_id=1 WHERE user_id = " . $_REQUEST['chkstatus'][$i]);
        }
        $class = "alert alert-success";
        $strMSG = "Record(s) updated successfully";
    } else {
        $class = "alert alert-info";
        $strMSG = "Please Select Alteast One Checkbox";
    }
}
//--------------Button InActive--------------------
if (isset($_REQUEST['btnInactive'])) {
    if (isset($_REQUEST['chkstatus'])) {
        for ($i = 0; $i < count($_REQUEST['chkstatus']); $i++) {
            mysqli_query($GLOBALS['conn'], "UPDATE users SET status_id=0 WHERE user_id = " . $_REQUEST['chkstatus'][$i]);
        }
        $class = "alert alert-success";
        $strMSG = "Record(s) updated successfully";
    } else {
        $class = "alert alert-info";
        $strMSG = "Please Select Alteast One Checkbox";
    }
}
//--------------Button Delete--------------------
/*if (isset($_REQUEST['btnDelete'])) {
    if (isset($_REQUEST['chkstatus'])) {
        for ($i = 0; $i < count($_REQUEST['chkstatus']); $i++) {
            $getID = $_REQUEST['chkstatus'][$i];
            //@unlink("../files/manufacturers/" . $_REQUEST['imgFile_' . $getID]);
            //@unlink("../files/manufacturers/th/" . $_REQUEST['imgFile_' . $getID]);
            //mysqli_query($GLOBALS['conn'], "DELETE FROM manufacturers WHERE man_id = " . $_REQUEST['chkstatus'][$i]);
            mysqli_query($GLOBALS['conn'], "UPDATE users SET user_deleted='1' WHERE user_id = " . $_REQUEST['chkstatus'][$i]);
        }
        $class = "alert alert-success";
        $strMSG = "Record(s) deleted successfully";
    } else {
        $class = " alert alert-info ";
        $strMSG = "Please check atleast one checkbox";
    }
}*/
include("includes/messages.php");

?>
<!--/HEADER -->

<!-- PAGE -->
<section id="page">
    <!-- SIDEBAR -->
    <?php include("includes/side_bar.php"); ?>
    <!-- /SIDEBAR -->
    <div id="main-content">
        <!-- SAMPLE BOX CONFIGURATION MODAL FORM-->
        <div class="modal fade" id="box-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Box Settings</h4>
                    </div>
                    <div class="modal-body"> Here goes box setting content. </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- /SAMPLE BOX CONFIGURATION MODAL FORM-->
        <div class="container">
            <div class="row">
                <div id="content" class="col-lg-12">
                    <!-- PAGE HEADER-->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="page-header">
                                <!-- STYLER -->

                                <!-- /STYLER -->
                                <!-- BREADCRUMBS -->
                                <ul class="breadcrumb">
                                    <li> <i class="fa fa-home"></i> <a href="index.php">Home</a> </li>
                                    <li><?php print($pHead); ?> </li>
                                </ul>
                                <!-- /BREADCRUMBS -->
                                <div class="clearfix">
                                    <h3 class="content-title pull-left"><?php print($pHead); ?></h3>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- /PAGE HEADER -->

                    <!-- DASHBOARD CONTENT -->
                    <?php if ($class != "") { ?>
                        <div class="<?php print($class); ?>"><?php print($strMSG); ?><a class="close" data-dismiss="alert">×</a></div>
                    <?php } ?>

                    <?php if (isset($_REQUEST['action'])) { ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box border primary">
                                    <div class="box-title">
                                        <h4 class="panel-title">
                                            <i class="fa fa-bars"></i><?php print($formHead); ?>
                                        </h4>
                                    </div>
                                    <div class="box-body">
                                        <form name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" class="form-horizontal" role="form" enctype="multipart/form-data">

                                            <?php if (isset($_REQUEST['action']) && $_REQUEST['action'] == 2) { ?>
                                                <div class="form-group">
                                                    <label for="inputEmail1" class="col-lg-2 col-md-3 control-label"></label>
                                                    <div class="col-lg-10 col-md-9 padTop7"><img src="<?php print($user_image_path); ?>" style="border-radius:30%; width: 150px;" alt="" /></div>
                                                </div>

                                            <?php } if($_REQUEST['action'] == in_array($_REQUEST['action'], array('1', '2'))) { ?>
                                            <div class="form-group">
                                                <label  class="col-lg-2 col-md-2 control-label">User name</label>
                                                <div class="col-lg-4 col-md-4">
                                                    <input type="email" <?php print($readonly);?> class="form-control form-cascade-control"  title="Use valid pattern like ( abc@gmail.com )"  name="user_name" id="user_name" value="<?php print($user_name); ?>" required pattern="^[\w]{1,}[\w.+-]{0,}@[\w-]{1,}([.][a-zA-Z]{2,}|[.][\w-]{2,}[.][a-zA-Z]{2,})$" autofocus required placeholder="User Name..">
                                                    <!---->
                                                </div>
                                            </div>
                                            <?php if($_REQUEST['action'] == 1) { ?>
                                            <div class="form-group">
                                                <label  class="col-lg-2 col-md-2 control-label">Password</label>
                                                <div class="col-lg-4 col-md-4">
                                                    <input type="password" class="form-control form-cascade-control required " name="user_password" id="user_password" value="<?php print($user_password); ?>" placeholder="Password..">
                                                </div>
                                            </div>
                                            <?php }?>
                                            <div class="form-group">
                                                <label  class="col-lg-2 col-md-3 control-label">User</label>
                                                <div class="col-lg-4 col-md-9">
                                                    <select  name="utype_id" id="utype_id" class="form-control required"  tabindex="2">
                                                        <?php FillSelected2("user_type", "utype_id", "utype_name", $utype_id, "utype_id IN (3)"); ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label  class="col-lg-2 col-md-2 control-label">First name</label>
                                                <div class="col-lg-4 col-md-4">
                                                    <input type="text" class="form-control form-cascade-control required " name="user_fname" id="user_fname" value="<?php print($user_fname); ?>" required pattern="([A-Za-z ]+)" autofocus required title="Use Only upper and lower case characters like ( Abdullah )" placeholder="First Name">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label  class="col-lg-2 col-md-2 control-label">Last name</label>
                                                <div class="col-lg-4 col-md-4">
                                                    <input type="text" class="form-control form-cascade-control " name="user_lname" id="user_lname" value="<?php print($user_lname); ?>" required pattern="([A-Za-z ]+)" autofocus required title="Use Only upper and lower case characters like ( Khalid )" placeholder="Last Name">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label  class="col-lg-2 col-md-3 control-label">Gender</label>
                                                <div class="col-lg-4 col-md-9">
                                                    <select  name="gen_id" id="gen_id" class="form-control input-sm"  tabindex="2">
                                                        <option value="1" <?php echo (($gen_id == '1')?'selected':'');?>>Male</option>
                                                        <option value="2" <?php echo (($gen_id == '2')?'selected':'');?>>Female</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label  class="col-lg-2 col-md-2 control-label">Phone no</label>
                                                <div class="col-lg-4 col-md-4">
                                                    <input type="text" class="form-control form-cascade-control required" name="user_phone" id="user_phone" value="<?php print($user_phone); ?>" autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label  class="col-lg-2 col-md-2 control-label">Address</label>
                                                <div class="col-lg-4 col-md-4">
                                                    <textarea type="text" rows="5" cols="50"  name="user_address" id="user_address"  placeholder=""><?php print($user_address); ?></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="cnt_pcode" class="col-lg-2 col-md-3 control-label">Picture</label>
                                                <div class="col-lg-3">
                                                    <input type="file" name="mFile" style="float:left !important" />
                                                </div>
                                            </div>
                                            <?php } else { ?>
                                            <div class="form-group">
                                                <label  class="col-lg-2 col-md-2 control-label">Old Password</label>
                                                <div class="col-lg-4 col-md-4">
                                                    <input type="password" class="form-control form-cascade-control required " name="old_password" id="old_password" value="" placeholder="Old Password">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label  class="col-lg-2 col-md-2 control-label">New Password</label>
                                                <div class="col-lg-4 col-md-4">
                                                    <input type="password" class="form-control form-cascade-control required " name="new_password" id="new_password" value="" placeholder="New Password">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label  class="col-lg-2 col-md-2 control-label">Reconfirm Password</label>
                                                <div class="col-lg-4 col-md-4">
                                                    <input type="password" class="form-control form-cascade-control required " name="reconfirm_password" id="reconfirm_password" value="" placeholder="Reconfirm Password">
                                                </div>
                                            </div>
                                            <?php } ?>
                                            <div class="form-group">
                                                <label for="inputEmail1" class="col-lg-2 col-md-3 control-label">&nbsp;</label>
                                                <div class="col-lg-10 col-md-9">
                                                    <?php if ($_REQUEST['action'] == 1) { ?>
                                                        <button type="submit" name="btnAdd" class="btn btn-primary btn-animate-demo">Submit</button>
                                                    <?php } elseif ($_REQUEST['action'] == 3) { ?>
                                                        <button type="submit" name="btnResetPassword" class="btn btn-primary btn-animate-demo">Submit</button>
                                                    <?php } else { ?>
                                                        <button type="submit" name="btnUpdate" class="btn btn-primary btn-animate-demo">Submit</button>
                                                        <input type="hidden" name="mFile" value="<?php print($user_img); ?>" />
                                                    <?php } ?>
                                                    <button type="button" name="btnCancel" class="btn btn-default btn-animate-demo" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF']); ?>';">Cancel</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php } elseif (isset($_REQUEST['show'])) { ?>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="box border primary">
                                    <div class="box-title">
                                        <h4>
                                            Details
                                        </h4>
                                    </div>
                                    <?php if(isset($_REQUEST['show']) && $_REQUEST['show'] == 2) {?>
                                    <div class="box-body">
                                        <form name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" class="form-horizontal" role="form">
                                            <div class="form-group">
                                                
                                                <div class="col-lg-10 col-md-9 padTop7"><img src="<?php print($user_image_path); ?>" style="width: 300px; border-radius: 30px;" alt="" /></div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail1" class="col-lg-2 col-md-2 control-label">Customer Name:</label>
                                                <div class="col-lg-8 col-md-8 padTop7"><?php print($user_fname." ".$user_lname); ?></div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail1" class="col-lg-2 col-md-2 control-label">Phone no:</label>
                                                <div class="col-lg-8 col-md-8 padTop7"><?php print($user_phone); ?></div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail1" class="col-lg-2 col-md-2 control-label">Email:</label>
                                                <div class="col-lg-8 col-md-8 padTop7"><?php print($user_name); ?></div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail1" class="col-lg-2 col-md-2 control-label">User Type:</label>
                                                <div class="col-lg-8 col-md-8 padTop7"><?php print($utype_name); ?></div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail1" class="col-lg-2 col-md-2 control-label">Address:</label>
                                                <div class="col-lg-8 col-md-8 padTop7"><?php print($user_address); ?></div>
                                            </div>
                                            <div class="form-group">
                                                <label for="inputEmail1" class="col-lg-2 col-md-3 control-label">&nbsp;</label>
                                                <div class="col-lg-10 col-md-9">
                                                    <button type="button" name="btnBack" class="btn btn-default btn-animate-demo" onClick="javascript: window.location = '<?php print($ref); ?>';">Back</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <?php } else { ?>
                                    <div class="row">
                                        <div class="col-md-12">
                                        <div class="box border blue">
                                        <div class="box-title">
                                            <h4><i class="fa fa-columns"></i> <span class="hidden-inline-mobile">Profile Summary</span></h4>																
                                        </div>
                                        <div class="box-body">
                                            <div class="tabbable">
                                                <ul class="nav nav-tabs">
                                                    <li class="active"><a href="#personal_info" data-toggle="tab"><i class="fa fa-info"></i> <span class="hidden-inline-mobile">Personal Info</span></a></li>
                                                    <li class=""><a href="#address" data-toggle="tab"><i class="fa fa-map-marker"></i> <span class="hidden-inline-mobile">Address</span></a></li>
                                                    <li class=""><a href="#investment" data-toggle="tab"><i class="fa fa-money"></i> <span class="hidden-inline-mobile">Investment</span></a></li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane active" id="personal_info">
                                                        <table class="table table-striped table-bordered table-hover">
                                                            <thead>
                                                            <tr>
                                                                <th>Name</th>
                                                                <th> Email</th>
                                                                <th> Contact No</th>
                                                                <th> Date of Birth</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr>
                                                                <td><?php print($user_fname." ".$user_lname); ?></td>
                                                                <td ><?php print($user_name); ?></td>
                                                                <td><?php print($user_phone); ?></td>
                                                                <td><?php print(((!empty($user_dob))?date('D F j, Y', strtotime($user_dob)):'')); ?></td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="tab-pane" id="address">
                                                        <table class="table table-striped table-bordered table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th Width="150">Title</th>
                                                                    <th>Detail</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <tr>
                                                                    <td>House No</td>
                                                                    <td ><?php print($user_house_no); ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Street Name</td>
                                                                    <td ><?php print($user_street); ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Town</td>
                                                                    <td ><?php print($user_town); ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Country</td>
                                                                    <td ><?php print($user_countrie); ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>State</td>
                                                                    <td ><?php print($user_state); ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>City</td>
                                                                    <td ><?php print($user_city); ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Address</td>
                                                                    <td ><?php print($user_address); ?></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="tab-pane" id="investment">
                                                    <table class="table table-striped table-bordered table-hover">
                                                            <thead>
                                                            <tr>
                                                                <th>Date</th>
                                                                <th>Project</th>
                                                                <th>Referance Code</th>
                                                                <th>Amount</th>
                                                                <th>Payment Method</th>
                                                                <th>Status</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php 
                                                                $Query = "SELECT pi.*, proj.proj_name FROM `projects_investments` AS pi LEFT OUTER JOIN projects AS proj ON proj.proj_id = pi.proj_id WHERE pi.user_id = '".$_REQUEST['user_id']."' ORDER BY pi_id DESC";
                                                                $rs = mysqli_query($GLOBALS['conn'], $Query);
                                                                if(mysqli_num_rows($rs) > 0){
                                                                    while($row = mysqli_fetch_object($rs)){
                                                                ?>
                                                            <tr>
                                                                <td><?php print(date('D F j, Y', strtotime($row->pi_cdate))); ?></td>
                                                                <td><a href="manage_projects.php?action=2&proj_id=<?php print($row->proj_id); ?>"><?php print($row->proj_name); ?></td>
                                                                <td ><?php print($row->pi_referance_code); ?></td>
                                                                <td><?php print($row->pi_payment); ?></td>
                                                                <td><?php print($row->pi_type); ?></td>
                                                                <td class="visible-lg">
                                                                    <?php
                                                                    if ($row->pi_status == 0) {
                                                                        echo "<span class='label label-success label-sm'> Pending </span>";
                                                                    } else {
                                                                        echo '<span class="label label-success label-sm">Paid</span>';
                                                                    }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <?php 
                                                                }

                                                            }   else {
                                                                print('<tr><td colspan="100%" align="center">No record found!</td></tr>');
                                                            }
                                                            ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                </div>
                                        </div>
                                        </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        

                    <?php } else { ?>
                        <div class="row">
                            <?php

                            $user_id = "";
                            $user_full_name = "";
                            $user_email_id = "";
                            $user_name="";
                            
                            if (isset($_REQUEST['user_id']) && $_REQUEST['user_id'] > 0) {
                                if(!empty($_REQUEST['user_full_name'])) {
                                    $user_id = $_REQUEST['user_id'];
                                    $user_full_name = $_REQUEST['user_full_name'];
                                    $searchQuery .= " AND u.user_id =" . $_REQUEST['user_id'];
                                }

                            }
                            if (isset($_REQUEST['user_email_id']) && $_REQUEST['user_email_id'] > 0) {
                                if(!empty($_REQUEST['user_name'])) {
                                    $user_email_id = $_REQUEST['user_email_id'];
                                    $user_name = $_REQUEST['user_name'];
                                    $searchQuery .= " AND u.user_id =" . $_REQUEST['user_email_id'];
                                }

                            }
                            if (isset($_REQUEST['utype_id']) && $_REQUEST['utype_id'] > 0) {

                                $searchQuery .= " AND u.utype_id =" . $_REQUEST['utype_id'];
                                $qryStrURL .= "utype_id=".$_REQUEST['utype_id']."&";
                                $utype_id = $_REQUEST['utype_id'];
                            }
                            if(!in_array($utype_id, array(1,2,3))){
                            ?>
                            <div class="col-md-12" style="margin-bottom: 12px;">
                                <form name="frmCat" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL); ?>">
                                    <div class="form-group">
                                        <label for="inputEmail1" class="col-lg-1 col-md-1 control-label padTop7" style="text-align: right;">Name: </label>
                                        <div class="col-lg-3 col-md-3">
                                            <input type="hidden" name="user_id" id="user_id_0" value="<?php print($user_id);?>" >
                                            <input type="text" name="user_full_name" id="user_full_name_0" value="<?php print ($user_full_name);?>" data-id="0" class="form-control form-cascade-control user_full_name" autocomplete="off" onchange="javascript: frmCat.submit();">
                                         </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail1" class="col-lg-1 col-md-1 control-label padTop7" style="text-align: right;">Email: </label>
                                        <div class="col-lg-3 col-md-3">
                                            <input type="hidden" name="user_email_id" id="user_email_id_0" value="<?php print($user_email_id);?>" >
                                            <input type="text" name="user_name" id="user_name_0" value="<?php print ($user_name);?>" data-id="0" class="form-control form-cascade-control user_name" autocomplete="off" onchange="javascript: frmCat.submit();">
                                    </div>
                                    </div>
                                    <!--<div class="form-group">
                                        <label for="inputEmail1" class="col-lg-1 col-md-1 control-label padTop7" style="text-align: right;">Member: </label>
                                        <div class="col-lg-3 col-md-3">
                                            <select name="utype_id" id="utype_id " class="form-control form-cascade-control" onchange="javascript: frmCat.submit();">
                                                <option value="0">N/A</option>
                                                <?php FillSelected2("user_type", "utype_id", "utype_name", $_REQUEST['utype_id'], "utype_id IN (1,2)"); ?>
                                            </select>
                                        </div>
                                    </div>-->
                                </form>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="col-md-12" style="margin-bottom: 12px;">

                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box border blue">
                                    <div class="box-title">
                                        <h4 ><i class="fa fa-bars"></i> <?php print($table_Head); ?> </h4>
                                        <?php if(in_array($utype_id, array(1,2,3))){ ?>
                                        <div class="tools" style="color:white">

                                            <div>
                                                <a href="<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL . "action=1"); ?>" title="Add New"><i class="fa fa-plus"></i> Add New</a>

                                            </div>
                                        </div>
                                        <?php } else {?>
                                        <div class="tools" style="color:white">
                                            <div>
                                                <a href="<?php print("export_report_details.php?action=investor_export_data&user_id=0"); ?>" title="Add New"><i class="fa fa-download"></i> Export all data</a>
                                            </div>
                                        </div>
                                        <?php }?>
                                    </div>
                                    <div class="box-body">
                                        <form name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" class="form-horizontal" role="form">
                                            <table class="table users-table table-condensed table-hover table-striped table-bordered" >
                                                <thead>
                                                <tr>
                                                    <th class="visible-xs visible-sm visible-md visible-lg" width="30"><input type="checkbox" name="chkAll" onClick="setAll();"></th>
                                                    <th class="visible-xs visible-sm visible-md visible-lg" width="100">Image</th>
                                                    <th class="visible-xs visible-sm visible-md visible-lg">Name</th>
                                                    <th class="visible-xs visible-sm visible-md visible-lg">Email</th>
                                                    <th class="visible-xs visible-sm visible-md visible-lg">User Type</th>
                                                    <th class="visible-md visible-lg" width="50">Status</th>
                                                    <th width="70">Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                //$Query = "SELECT u.* FROM users AS u LEFT OUTER JOIN user_type AS t ON t.utype_id=u.utype_id WHERE t.utype_id= '2' OR t.utype_id = '3' AND t.user_deleted = '0' ORDER BY t.user_id";
                                               $Query = "SELECT u.*, t.utype_name FROM users AS u LEFT OUTER JOIN user_type AS t ON t.utype_id=u.utype_id WHERE u.user_deleted = '0' ".$searchQuery." ORDER BY u.user_id";
                                                //print($Query);
                                                $counter = 0;
                                                $limit = 25;
                                                $start = $p->findStart($limit);
                                                $count = mysqli_num_rows(mysqli_query($GLOBALS['conn'], $Query));
                                                $pages = $p->findPages($count, $limit);
                                                $rs = mysqli_query($GLOBALS['conn'], $Query . " LIMIT " . $start . ", " . $limit);
                                                if (mysqli_num_rows($rs) > 0) {
                                                    while ($row = mysqli_fetch_object($rs)) {
                                                        $counter++;
                                                        $strClass = 'label  label-danger';
                        
                                                        $user_image_path = $GLOBALS['siteURL']."files/no_img_1.jpg";
                                                        if(!empty($row->user_img)){
                                                            $user_image_path = $GLOBALS['siteURL']."files/customer/".$row->user_id."/".$row->user_img;
                                                        }
                                                        ?>
                                                        <tr>
                                                            <td class="visible-xs visible-sm visible-md visible-lg"><input type="checkbox" name="chkstatus[]" value="<?php print($row->user_id); ?>" /></td>
                                                            <td><img src="<?php print($user_image_path);?>" height="100"></td>
                                                            <td class="visible-xs visible-sm visible-md visible-lg"><?php print($row->user_fname." ".$row->user_lname); ?> </td>
                                                            <td class="visible-xs visible-sm visible-md visible-lg"><?php print($row->user_name); ?> </td>
                                                            <td class="visible-xs visible-sm visible-md visible-lg"><?php print($row->utype_name); ?> </td>
                                                            <td class="visible-lg">
                                                                <?php
                                                                if ($row->status_id == 0) {
                                                                    echo "<span class='btn btn-xs btn-warning btn-animate-demo padMsgs'> In Active </span>";
                                                                } else {
                                                                    echo "<span class='btn btn-xs btn-success btn-animate-demo padMsgs'> Active </span>";
                                                                }
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <?php if($row->utype_id == 2){ ?>
                                                                    <button type="button" class="btn btn-xs btn-success" title="Reset Password" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?action=3&" . $qryStrURL . "user_id=" . $row->user_id); ?>';"><i class="fa fa-key"></i></button>   
                                                                <?php } elseif($row->utype_id == 3){?>
                                                                    <button type="button" class="btn btn-xs btn-info" title="View Details" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?show=2&" . $qryStrURL . "user_id=" . $row->user_id); ?>';"><i class="fa fa-eye"></i></button>
                                                                    <button type="button" class="btn btn-xs btn-warning" title="Edit" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?action=2&" . $qryStrURL . "user_id=" . $row->user_id); ?>';"><i class="fa fa-edit"></i></button>
                                                                <?php } else {?>
                                                                    <button type="button" class="btn btn-xs btn-info" title="View Details" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?show=1&" . $qryStrURL . "user_id=" . $row->user_id); ?>';"><i class="fa fa-eye"></i></button>
                                                                    <button type="button" class="btn btn-xs btn-primary" title="Export Investor Data" onClick="javascript: window.location = '<?php print("export_report_details.php?action=investor_export_data&user_id=" . $row->user_id); ?>';"><i class="fa fa-download"></i></button>
                                                                    <?php } ?>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                } else {
                                                    print('<tr><td colspan="100%" align="center">No record found!</td></tr>');
                                                }
                                                ?>

                                                </tbody>
                                            </table>
                                            <?php if ($counter > 0) { ?>
                                                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <td><?php print("Page <b>" . $_GET['page'] . "</b> of " . $pages); ?></td>
                                                        <td align="right">
                                                            <ul class="pagination" style="margin: 0px;">
                                                                <?php
                                                                $pageList = $p->pageList($_GET['page'], $pages, '&' . $qryStr);
                                                                print($pageList);
                                                                ?>
                                                            </ul>
                                                        </td>
                                                    </tr>
                                                </table>
                                            <?php } ?>

                                            <?php if ($counter > 0) { ?>
                                                <input type="submit" name="btnActive" value="Active" class="btn btn-success btn-animate-demo">
                                                <input type="submit" name="btnInactive" value="In Active" class="btn btn-warning btn-animate-demo">
                                                <!--<input type="submit" name="btnDelete" onclick="return confirm('Are you sure you want to delete selected item(s)?');" value="Delete" class="btn btn-danger btn-animate-demo">-->
                                            <?php } ?>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <!-- /DASHBOARD CONTENT -->
                    <div class="footer-tools"> <span class="go-top"> <i class="fa fa-chevron-up"></i> Top </span> </div>
                </div>
                <!-- /CONTENT-->
            </div>
        </div>
    </div>
</section>
<script>
    $(":input").inputmask();
</script>
<?php include("includes/bottom_js.php"); ?>
<script>


    $( function() {
        $( "#datepicker" ).datepicker();
    } );

    $('input.user_full_name').autocomplete({
        source: function( request, response ) {
            $.ajax( {
                url: 'ajax_calls.php?action=user_full_name',
                dataType: "json",
                data: {
                    term: request.term
                },
                success: function( data ) {
                    response( data );

                }
            } );
        },
        minLength: 1,
        select: function( event, ui ) {
            var user_id = $("#user_id_"+$(this).attr("data-id"));
            var user_full_name = $("#user_full_name_"+$(this).attr("data-id"));
            console.log("#user_id_"+$(this).attr("data-id"));
            console.log(ui.item.user_id);
            $(user_id).val(ui.item.user_id);
            $(user_full_name).val(ui.item.value);
            frmCat.submit();
            //return false;
            //console.log( "Selected: " + ui.item.value + " aka " + ui.item.id );
        }
    });
    $('input.user_name').autocomplete({
        source: function( request, response ) {
            $.ajax( {
                url: 'ajax_calls.php?action=user_name',
                dataType: "json",
                data: {
                    term: request.term
                },
                success: function( data ) {
                    response( data );

                }
            } );
        },
        minLength: 1,
        select: function( event, ui ) {
            var user_email_id = $("#user_email_id_"+$(this).attr("data-id"));
            var user_name = $("#user_name_"+$(this).attr("data-id"));
            console.log("#user_email_id_"+$(this).attr("data-id"));
            console.log(ui.item.user_id);
            $(user_email_id).val(ui.item.user_id);
            $(user_name).val(ui.item.value);
            frmCat.submit();
            //return false;
            //console.log( "Selected: " + ui.item.value + " aka " + ui.item.id );
        }
    });
</script>
<!--/PAGE -->

</body>
</html>

