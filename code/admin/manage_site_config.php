<?php include("includes/header-top.php"); ?>
<body>
<!-- HEADER -->
<?php include("includes/header.php"); 
$formHead = "Add New";
$strMSG = "";
$class = "";
$qryStrURL = "";
$qryStr = "";



if (isset($_REQUEST['action'])) {
    if (isset($_REQUEST['btnAdd'])) {
        if (IsExist("config_id", "site_config", "config_sitename", $_REQUEST['config_sitename'])) {
            $strMSG = "site_config Name is  already exist";
        } else {
            $config_id = getMaximum("site_config", "config_id");
            mysqli_query($GLOBALS['conn'], "INSERT INTO site_config
			(
			config_id,
			config_sitename, 
			config_sitetitle,	
			config_metakey,
			config_metades,
			config_upload_limit,
			status_id,
			config_leadtime_available,
			config_allow_rentals,
			config_accept_leads,
			config_send_nl_after,
			config_bg_music,
            config_lead_time)
			VALUES(" . $config_id . ", 
			'" . $_REQUEST['config_sitename'] . "',
			'" . $_REQUEST['config_sitetitle'] . "', 
			'" . $_REQUEST['config_metakey'] . "',
			'" . $_REQUEST['config_metades'] . "',
			'" . $_REQUEST['config_upload_limit'] . "',
			'" . $_REQUEST['status_id'] . "',
                        '" . $_REQUEST['config_leadtime_available'] . "',
			'" . $_REQUEST['config_allow_rentals'] . "',
			'" . $_REQUEST['config_accept_leads'] . "',
			'" . $_REQUEST['config_send_nl_after'] . "',
            '" . $_REQUEST['config_lead_time'] . "',
			'" . $_REQUEST['mfileName'] . "'
			
			)") or die(mysqli_error($GLOBALS['conn']));
            header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=1");
        }
    } elseif (isset($_REQUEST['btnUpdate'])) {
            $udtQuery = "UPDATE site_config SET 
			config_phone='".$_REQUEST['config_phone']."',	
			config_sitename='" . $_REQUEST['config_sitename'] . "',
			config_sitetitle='" . $_REQUEST['config_sitetitle'] . "', 
			config_metakey='" . $_REQUEST['config_metakey'] . "',
			config_metades='" . $_REQUEST['config_metades'] . "',
                        config_email='".$_REQUEST['config_email']."',
			config_upload_limit='" . $_REQUEST['config_upload_limit'] . "',
			status_id='" . $_REQUEST['status_id'] . "'
			WHERE config_id=" . $_REQUEST['config_id'];

        mysqli_query($GLOBALS['conn'], $udtQuery) or die(mysqli_error($GLOBALS['conn']));
        header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=2");
    } elseif ($_REQUEST['action'] == 2) {
		 $rsM = mysqli_query($GLOBALS['conn'], "SELECT * FROM site_config WHERE config_id=" . $_REQUEST['config_id']);
        if (mysqli_num_rows($rsM) > 0) {
            $rsMem = mysqli_fetch_object($rsM);

            $config_id = $rsMem->config_id;
            $config_sitename = $rsMem->config_sitename;
            $config_sitetitle = $rsMem->config_sitetitle;
            $config_metakey = $rsMem->config_metakey;
            $config_metades = $rsMem->config_metades;
            $config_upload_limit = $rsMem->config_upload_limit;
            $status_id = $rsMem->status_id;
            $config_email=$rsMem->config_email;
            $config_phone=$rsMem->config_phone;
            $formHead = "Update Info";
        }
    } else {
        $config_id = "";
		$umfileName="";
        $config_sitename = "";
        $config_sitetitle = "";
        $config_metakey = "";
        $config_metades = "";
        $config_upload_limit = "";
        $status_id = "";
        $config_email="";
        $config_phone="";
        $config_mobile="";
        $config_leadtime_available = "";
        $config_allow_rentals = "";
        $config_accept_leads = "";
        $config_send_nl_after = "";
        $config_lead_time = "";
        $config_lead_time_unit="";
        $config_queue_time="";
        $config_queue_time_unit="";
        $config_survey_time="";
        $config_survey_time_unit="";
		$formHead = "Add New";
    }
}
if (isset($_REQUEST['show'])) {
    $rsM = mysqli_query($GLOBALS['conn'], "SELECT * From  site_config WHERE config_id=" . $_REQUEST['config_id']);
    if (mysqli_num_rows($rsM) > 0) {
        $rsMem = mysqli_fetch_object($rsM);
        $config_id = $rsMem->config_id;
        $config_sitename = $rsMem->config_sitename;
        $config_sitetitle = $rsMem->config_sitetitle;
        $config_metakey = $rsMem->config_metakey;
        $config_metades = $rsMem->config_metades;
        $config_email=$rsMem->config_email;
        $config_upload_limit = $rsMem->config_upload_limit;
        $status_id = $rsMem->status_id;
	//$mfileName=$rsMem->config_bg_music;
        $formHead = "Update Info";
    }
}
//--------------Button Active--------------------
if (isset($_REQUEST['btnActive'])) {
    for ($i = 0; $i < count($_REQUEST['chkstatus']); $i++) {
        mysqli_query($GLOBALS['conn'], "UPDATE members SET com_id = 1 WHERE mem_id = " . $_REQUEST['chkstatus'][$i]);
    }
    $class = "alert alert-success";
    $strMSG = "Record(s) updated successfully";
}
//--------------Button InActive--------------------
if (isset($_REQUEST['btnInactive'])) {
    for ($i = 0; $i < count($_REQUEST['chkstatus']); $i++) {
        mysqli_query($GLOBALS['conn'], "UPDATE members SET com_id = 0 WHERE mem_id = " . $_REQUEST['chkstatus'][$i]);
    }
    $class = "alert alert-success";
    $strMSG = "Record(s) updated successfully";
}
//--------------Button Confirm--------------------
if (isset($_REQUEST['btnConfirm'])) {
    for ($i = 0; $i < count($_REQUEST['chkstatus']); $i++) {
        mysqli_query($GLOBALS['conn'], "UPDATE members SET mem_confirm = 1 WHERE mem_id = " . $_REQUEST['chkstatus'][$i]);
    }
    $class = "alert alert-success";
    $strMSG = "Record(s) updated successfully";
}
//--------------Button Not Confirm--------------------
if (isset($_REQUEST['btnNotConfirm'])) {
    for ($i = 0; $i < count($_REQUEST['chkstatus']); $i++) {
        mysqli_query($GLOBALS['conn'], "UPDATE members SET mem_confirm = 0 WHERE mem_id = " . $_REQUEST['chkstatus'][$i]);
    }
    $class = "alert alert-success";
    $strMSG = "Record(s) updated successfully";
}
//--------------Button Delete--------------------
if (isset($_REQUEST['btnDelete'])) {
    if (isset($_REQUEST['chkstatus'])) {
        for ($i = 0; $i < count($_REQUEST['chkstatus']); $i++) {
            mysqli_query($GLOBALS['conn'], "DELETE FROM site_config WHERE config_id = " . $_REQUEST['chkstatus'][$i]);
            //mysqli_query($GLOBALS['conn'], "UPDATE members SET mem_del=1 WHERE mem_id=".$_REQUEST['chkstatus'][$i]) or die(mysqli_query($GLOBALS['conn'], ));
        }
        $class = "alert alert-success";
        $strMSG = "Record(s) deleted successfully";
    } else {
        $class = " alert alert-danger ";
        $strMSG = "Please check atleast one checkbox";
    }
}
if (isset($_REQUEST['op'])) {
    switch ($_REQUEST['op']) {
        case 1:
            $class = "alert alert-success";
            $strMSG = "Record Added Successfully";
            break;
        case 2:
            $strMSG = " Record Updated Successfully";
            $class = "alert alert-success";
            break;
        case 3:

            $strMSG = "Please check atleast one checkbox";
            $class = " alert alert-danger ";
            break;
        case 4:
            $class = "notification success";
            $strMSG = "Please Select Checkbox to Add or Subtract Credits";
            break;
    }
}
?>
<!--/HEADER --> 

<!-- PAGE -->
<section id="page"> 
	<!-- SIDEBAR -->
	<?php include("includes/side_bar.php");?>
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
									<li>Site Config Management</li>
								</ul>
								<!-- /BREADCRUMBS -->
								<div class="clearfix">
									<h3 class="content-title pull-left">Site Config Management</h3>
									<!-- DATE RANGE PICKER --> 
									<!--<span class="date-range pull-right">
									<div class="btn-group"> <a class="js_update btn btn-default" href="#">Today</a> <a class="js_update btn btn-default" href="#">Last 7 Days</a> <a class="js_update btn btn-default hidden-xs" href="#">Last month</a> <a id="reportrange" class="btn reportrange"> <i class="fa fa-calendar"></i> <span></span> <i class="fa fa-angle-down"></i> </a> </div>
									</span> -->
									<!-- /DATE RANGE PICKER --> 
								</div>
								<div class="description">Overview, Statistics and more</div>
							</div>
						</div>
					</div>
                                        <?php if ($class != "") { ?>
                                            <div class="<?php print($class); ?>"><?php print($strMSG); ?><a class="close" data-dismiss="alert">×</a></div>
                                        <?php } ?>
					<!-- /PAGE HEADER --> 
                                        <?php if(isset($_REQUEST['action'])){ ?>
			<div class="row">
				<div class="col-md-12">
					<div class="box border primary">
						  <div class="box-title">
							<h4 class="panel-title">
								<i class="fa fa-bars"></i><?php print($formHead);?>
								
							</h4>
						</div>
						<div class="box-body">
							<form name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']);?>" class="form-horizontal" role="form" enctype="multipart/form-data">
                                                            
                                                            <div class="form-group">
                            <label for="mem_login" class="col-lg-2 col-md-3 control-label">Site Name</label>
                            <div class="col-lg-4 col-md-5">
                                <input type="text" class="form-control  " name="config_sitename" id="grp_name" value="<?php print($config_sitename); ?>" placeholder="Title">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="mem_password" class="col-lg-2 col-md-3 control-label">Site Title</label>
                            <div class="col-lg-4 col-md-5">
                                <textarea type="text" class="form-control form-cascade-control " name="config_sitetitle" id="prop_detaisls" placeholder="Details"  rows="5"><?php print($config_sitetitle); ?></textarea>
                            </div>
                        </div>

                             <div class="form-group">
                            <label for="mem_login" class="col-lg-2 col-md-3 control-label">Site Email</label>
                            <div class="col-lg-4 col-md-5">
                                <input type="text" class="form-control  " name="config_email" id="config_email" value="<?php print($config_email); ?>" placeholder="Email">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="mem_login" class="col-lg-2 col-md-3 control-label">Meta keywords</label>
                            <div class="col-lg-4 col-md-5">
                                <textarea type="text" class="form-control form-cascade-control " name="config_metakey" id="config_metakey" placeholder="Owner" rows="5"><?php print($config_metakey); ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="mem_login" class="col-lg-2 col-md-3 control-label">Meta Description</label>
                            <div class="col-lg-4 col-md-5">
                                <textarea type="text" class="form-control form-cascade-control  " name="config_metades" id="config_metades" placeholder="lconfig_metades"  rows="5"><?php print($config_metades); ?></textarea>
                                
                              
                            </div>
                        </div>
                                                            
                        <div class="form-group">
                            <label for="mem_login" class="col-lg-2 col-md-3 control-label">Phone</label>
                            <div class="col-lg-4 col-md-5">
                                <input type="text" class="form-control  " name="config_phone" id="config_phone" value="<?php print($config_phone); ?>" placeholder="Phone">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="mem_login" class="col-lg-2 col-md-3 control-label">Upload Limit</label>
                            <div class="col-lg-4 col-md-5">
                                   <input type="text" class="form-control form-cascade-control required " name="config_upload_limit" id="config_upload_limit" value="<?php print($config_upload_limit); ?>" placeholder="config upload limit">
                                   
                                </div>
                 
                        </div>
                        <div class="form-group">
                            <label for="mem_login" class="col-lg-2 col-md-3 control-label">Status</label>
                            <div class="col-lg-4 col-md-5">
                                <select  name="status_id" id="status_id" class="form-control input-sm required"  tabindex="2">
                                        <?php FillSelected("status", "status_id", "status_name", @$status_id); ?>
                                    </select>
                               
                            </div>
                        </div>
                                                             <input type="hidden" name="mfileName" id="mfileName" style="float:left !important" />
<!--                        <div class="form-group">
                            <label for="mfileName" class="col-lg-2 col-md-3 control-label">Music File</label>
                            <div class="col-lg-3">
                                <input type="hidden" name="mfileName" id="mfileName" style="float:left !important" />
                            </div>
                        </div>-->
                        
                        <div class="form-group">
                            <label for="inputEmail1" class="col-lg-2 col-md-3 control-label">&nbsp;</label>
                            <div class="col-lg-10 col-md-9">
                                <?php if ($_REQUEST['action'] == 1) { ?>
                                    <button type="submit" name="btnAdd" class="btn btn-primary btn-animate-demo">Submit</button>
                                <?php } else { ?>
                                    <button type="submit" name="btnUpdate" class="btn btn-primary btn-animate-demo">Submit</button>
                                <?php } ?>
                                <button type="button" name="btnCancel" class="btn btn-default btn-animate-demo" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?" . $qryStr); ?>';">Cancel</button>
                            </div>
                        </div>
					</form>
						</div>
					</div>
				</div>
			</div>
		<?php } elseif(isset($_REQUEST['show'])){ ?>
			<div class="row">
				<div class="col-md-12">
					<div class="box border primary">
						<div class="box-title">
							<h4>
								Details
							</h4>
						</div>
						<div class="box-body">
							<form name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']);?>" class="form-horizontal" role="form">
                                                            
                                                             <div class="form-group">
                            <label for="mem_login" class="col-lg-2 col-md-3 control-label">Site Name</label>
                            <div class="col-lg-10 col-md-9 padTop7">
                                <?php print($config_sitename); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="mem_password" class="col-lg-2 col-md-3 control-label">Site Title</label>
                            <div class="col-lg-10 col-md-9 padTop7">
                               <?php print($config_sitetitle); ?>
                            </div>
                        </div>
						<div class="form-group">
                            <label for="mem_login" class="col-lg-2 col-md-3 control-label">Meta Key</label>
                            <div class="col-lg-10 col-md-9 padTop7">
                               <?php print($config_metakey); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="mem_login" class="col-lg-2 col-md-3 control-label">Meta Data</label>
                            <div class="col-lg-10 col-md-9 padTop7">
                               <?php print($config_metades); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="mem_login" class="col-lg-2 col-md-3 control-label">Upload Limit</label>
                            <div class="col-lg-10 col-md-9 padTop7">
                                  <?php print($config_upload_limit); ?>
                                   
                                </div>
                 
                        </div>
                        <div class="form-group">
                            <label for="mem_login" class="col-lg-2 col-md-3 control-label">Status</label>
                            <div class="col-lg-10 col-md-9 padTop7">
                                <!--<select data-placeholder="Choose a Status..." name="lconfig_metades" id="lconfig_metades" class="chosen-select required" style="width:350px;" tabindex="2">
                                        <option value=""></option>
                                    </select>-->
                                    <?php 
                                        if(@$status_id == 0){
                                                echo "<span class='btn btn-danger btn-animate-demo padMsgs'> In Active </span>";
                                        } else {
                                                echo "<span class='btn btn-success btn-animate-demo padMsgs'> Active </span>";
                                        }
                                    ?>
                            </div>
                        </div>
                        

			<div class="form-group">
                            <label for="inputEmail1" class="col-lg-2 col-md-3 control-label">&nbsp;</label>
                            <div class="col-lg-10 col-md-9">
                                <button type="button" name="btnBack" class="btn btn-default btn-animate-demo" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?" . $qryStr); ?>';">Back</button>
                            </div>
                        </div>		
                         </form>
						</div>
					</div>
				</div>
			</div>
		<?php } else{ ?>
                                        <div class="row">
				<div class="col-md-12">
					<div class="box border blue">
						<div class="box-title">
							<h4 ><i class="fa fa-bars"></i> Site Config</h4>
								    <div class="tools" style="color:white">
								</div> 
							
						</div>
						<div class="box-body">
						<form name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']);?>" class="form-horizontal" role="form">
                                                    
                                                    <table class="table users-table table-condensed table-hover table-striped" >
                            <thead>
                                <tr>
                                    <th class="visible-xs visible-sm visible-md visible-lg"><input type="checkbox" name="chkAll" onClick="setAll();"></th>
                                    <th class="visible-xs visible-sm visible-md visible-lg">Nameee</th>
                                    <th class="visible-lg">Title</th>
                                    <th class="visible-lg">Meta Key</th>
                                    <th class="visible-lg">Meta Data</th>
                                    <th class="visible-lg">Status</th>
                                    <th width="140">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $Query = "Select * From site_config";
							    $counter = 0;
                                $limit = 25;
                                $start = $p->findStart($limit);
                                $count = mysqli_num_rows(mysqli_query($GLOBALS['conn'], $Query));
                                $pages = $p->findPages($count, $limit);
                                $rs = mysqli_query($GLOBALS['conn'], $Query . " LIMIT " . $start . ", " . $limit);
                                if (mysqli_num_rows($rs) > 0) {
                                    while ($row = mysqli_fetch_object($rs)) {
                                        $counter++;
                                        ?>
                                        <tr>
                                            <td class="visible-xs visible-sm visible-md visible-lg"><input type="checkbox" name="chkstatus[]" value="<?php print($row->config_id); ?>" /></td>
                                            <td class="visible-xs visible-sm visible-md visible-lg"><?php print($row->config_sitename); ?> </td>
                                            <td class="visible-lg"><?php print($row->config_sitetitle); ?></td>
                                            <td class="visible-lg"><?php print($row->config_metakey); ?></td>
                                            <td class="visible-lg"><?php print($row->config_metades); ?></td>
                                            <td class="visible-lg">
                                                <?php 
                                                    if($row->status_id == 0){
                                                            echo "<span class='btn btn-danger btn-animate-demo padMsgs'> In Active </span>";
                                                    } else {
                                                            echo "<span class='btn btn-success btn-animate-demo padMsgs'> Active </span>";
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-info" title="View Details" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?show=1&" . $qryStrURL . "config_id=" . $row->config_id); ?>';"><i class="fa fa-eye"></i></button>
                                                <button type="button" class="btn btn-warning" title="Edit" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?action=2&" . $qryStrURL . "config_id=" . $row->config_id); ?>';"><i class="fa fa-edit"></i></button></td>
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
                                        <?php
                                        $next_prev = $p->nextPrev($_GET['page'], $pages, '&' . $qryStr);
                                        print($next_prev);
                                        ?>
                                    </td>
                                </tr>
                            </table>
                        <?php } ?>
                        <?php if ($counter > 0) { ?>

                            <input type="submit" name="btnDelete" value="Delete" class="btn btn-danger btn-animate-demo">
                        <?php } ?>
                              </form>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
					<!-- DASHBOARD CONTENT -->
					
					<!-- /DASHBOARD CONTENT --> 
					<div class="footer-tools"> <span class="go-top"> <i class="fa fa-chevron-up"></i> Top </span> </div>
				</div>
				<!-- /CONTENT--> 
			</div>
		</div>
	</div>
</section>
<!--/PAGE -->
<?php include("includes/bottom_js.php");?>
</body>
</html>