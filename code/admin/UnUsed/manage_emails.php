<?php include("includes/header-top.php"); ?>
<body>
<!-- HEADER -->
<?php include("includes/header.php"); 
$formHead = "Add New";
$strMSG = "";
$class = "";
$qryStrURL = "";
$qryStr = "";

if(isset($_REQUEST['action'])){
	if(isset($_REQUEST['btnAdd'])){
		$eml_id = getMaximum("emails","eml_id");
		mysqli_query($GLOBALS['conn'], "INSERT INTO emails(eml_id, eml_title, eml_contents,eml_subject,eml_from_address) VALUES(".$eml_id.", '".$_REQUEST['eml_title']."', '".$_REQUEST['eml_contents']."', '".$_REQUEST['eml_subject']."', '".$_REQUEST['eml_from_address']."')") or die(mysqli_error($GLOBALS['conn']));
		header("Location: ".$_SERVER['PHP_SELF']."?".$qryStrURL."op=1");
	}
	elseif(isset($_REQUEST['btnUpdate'])){
		echo $udtQuery = "UPDATE emails SET eml_title='".$_REQUEST['eml_title']."',eml_contents='".$_REQUEST['eml_contents']."',eml_subject='".$_REQUEST['eml_subject']."', eml_from_address='".$_REQUEST['eml_from_address']."' WHERE eml_id=".$_REQUEST['eml_id'];
		mysqli_query($GLOBALS['conn'], $udtQuery) or die(mysqli_error($GLOBALS['conn']));
		header("Location: ".$_SERVER['PHP_SELF']."?".$qryStrURL."op=2");
	}
	elseif($_REQUEST['action']==2){
		$rsM = mysqli_query($GLOBALS['conn'], "SELECT * FROM emails WHERE eml_id=".$_REQUEST['eml_id']);
		if(mysqli_num_rows($rsM)>0){
			$rsMem = mysqli_fetch_object($rsM);
			$eml_id = $rsMem->eml_id;
			$eml_title=$rsMem->eml_title;
			$eml_contents=$rsMem->eml_contents;
			$eml_subject=$rsMem->eml_subject;
			$eml_from_address = $rsMem->eml_from_address;
			$formHead = "Update Info";
		}
	}
	else{
		$eml_id = "";
		$eml_title='';
		$eml_contents='';
		$eml_subject='';
		$eml_from_address = "";
		$formHead = "Add New";
	}
}
if(isset($_REQUEST['show'])){
	$rsM = mysqli_query($GLOBALS['conn'], "SELECT * From emails WHERE eml_id=".$_REQUEST['eml_id']);
	if(mysqli_num_rows($rsM)>0){
		$rsMem = mysqli_fetch_object($rsM);
		$eml_id = $rsMem->eml_id;
		$eml_title=$rsMem->eml_title;
		$eml_contents=$rsMem->eml_contents;
		$eml_subject=$rsMem->eml_subject;
		$eml_from_address = $rsMem->eml_from_address;
		$formHead = "Update Info";
	}
}
//--------------Button Active--------------------
/*if(isset($_REQUEST['btnActive'])){
	for($i=0; $i<count($_REQUEST['chkstatus']); $i++){
		mysqli_query($GLOBALS['conn'], "UPDATE members SET eml_from_address = 1 WHERE eml_id = ".$_REQUEST['chkstatus'][$i]);
	}
	$class = "alert alert-success";
	$strMSG = "Record(s) updated successfully";
}*/
//--------------Button InActive--------------------
/*if(isset($_REQUEST['btnInactive'])){
	for($i=0; $i<count($_REQUEST['chkstatus']); $i++){
		mysqli_query($GLOBALS['conn'], "UPDATE members SET eml_from_address = 0 WHERE eml_id = ".$_REQUEST['chkstatus'][$i]);
	}
	$class = "alert alert-success";
	$strMSG = "Record(s) updated successfully";
}*/
//--------------Button Delete--------------------
/*if(isset($_REQUEST['btnDelete'])){
     if (isset($_REQUEST['chkstatus'])) {
	for($i=0; $i<count($_REQUEST['chkstatus']); $i++){
		mysqli_query($GLOBALS['conn'], "DELETE FROM emails WHERE eml_id = ".$_REQUEST['chkstatus'][$i]);
		//mysqli_query($GLOBALS['conn'], "UPDATE members SET mem_del=1 WHERE mem_id=".$_REQUEST['chkstatus'][$i]) or die(mysqli_query($GLOBALS['conn'], ));
     }
        $class = "alert alert-success";
	$strMSG = "Record(s) deleted successfully";
        }else {
        $class = " alert alert-danger ";
        $strMSG = "Please check atleast one checkbox";
    }
	
}*/
if(isset($_REQUEST['op'])){
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
									<li>Dashboard</li>
								</ul>
								<!-- /BREADCRUMBS -->
								<div class="clearfix">
									<h3 class="content-title pull-left">Email Contents Management</h3>
									<!-- DATE RANGE PICKER --> 
									<!--<span class="date-range pull-right">
									<div class="btn-group"> <a class="js_update btn btn-default" href="#">Today</a> <a class="js_update btn btn-default" href="#">Last 7 Days</a> <a class="js_update btn btn-default hidden-xs" href="#">Last month</a> <a id="reportrange" class="btn reportrange"> <i class="fa fa-calendar"></i> <span></span> <i class="fa fa-angle-down"></i> </a> </div>
									</span> --> 
									<!-- /DATE RANGE PICKER --> 
								</div>
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
									<h4 class="panel-title"> <i class="fa fa-bars"></i><?php print($formHead);?> </h4>
								</div>
								<div class="box-body">
									<form name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']);?>" class="form-horizontal" role="form" enctype="multipart/form-data">
										<div class="form-group">
											<label  class="col-lg-2 col-md-3 control-label">Title</label>
											<div class="col-lg-4 col-md-5">
												<input type="text" class="form-control form-cascade-control  required " name="eml_title" id="eml_title" value="<?php print($eml_title);?>" placeholder="Title">
											</div>
										</div>
										<div class="form-group">
											<label for="mem_fname" class="col-lg-2 col-md-3 control-label">Subject</label>
											<div class="col-lg-4 col-md-5">
												<input type="text" class="form-control form-cascade-control  required " name="eml_subject" id="eml_subject" value="<?php print($eml_subject);?>" placeholder="Subject">
											</div>
										</div>
										<div class="form-group">
											<label  class="col-lg-2 col-md-3 control-label">From</label>
											<div class="col-lg-4 col-md-5">
												<input type="text" class="form-control form-cascade-control  required email" name="eml_from_address" id="eml_from_address" value="<?php print($eml_from_address);?>" placeholder="From Email Address">
											</div>
										</div>
										<div class="form-group">
											<label for="mem_fname" class="col-lg-2 col-md-3 control-label">Contents</label>
											<div class="col-lg-8 col-md-9">
												<textarea class="ckeditor" name="eml_contents"><?php print($eml_contents);?></textarea>
											</div>
										</div>
										<div class="form-group">
											<label for="inputEmail1" class="col-lg-2 col-md-3 control-label">&nbsp;</label>
											<div class="col-lg-10 col-md-9">
												<?php if($_REQUEST['action']==1){ ?>
												<button type="submit" name="btnAdd" class="btn btn-primary btn-animate-demo">Submit</button>
												<?php } else{ ?>
												<button type="submit" name="btnUpdate" class="btn btn-primary btn-animate-demo">Submit</button>
												<?php } ?>
												<button type="button" name="btnCancel" class="btn btn-default btn-animate-demo" onclick="javascript: window.location='<?php print($_SERVER['PHP_SELF']."?".$qryStr);?>';">Cancel</button>
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
									<h4> Details </h4>
								</div>
								<div class="box-body">
									<form name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']);?>" class="form-horizontal" role="form">
										<div class="form-group">
											<label for="inputEmail1" class="col-lg-2 col-md-3 control-label">Title</label>
											<div class="col-lg-10 col-md-9 padTop7"><?php print($eml_title);?></div>
										</div>
										<div class="form-group">
											<label for="inputEmail1" class="col-lg-2 col-md-3 control-label">From</label>
											<div class="col-lg-10 col-md-9 padTop7"><?php print($eml_from_address);?></div>
										</div>
										<div class="form-group">
											<label for="inputEmail1" class="col-lg-2 col-md-3 control-label">Subject</label>
											<div class="col-lg-10 col-md-9 padTop7"><?php print($eml_subject);?></div>
										</div>
										<div class="form-group">
											<label for="inputEmail1" class="col-lg-2 col-md-3 control-label">Contents</label>
											<div class="col-lg-10 col-md-9 padTop7"><?php print($eml_contents);?></div>
										</div>
										<div class="form-group">
											<label for="inputEmail1" class="col-lg-2 col-md-3 control-label">&nbsp;</label>
											<div class="col-lg-10 col-md-9">
												<button type="button" name="btnBack" class="btn btn-default btn-animate-demo" onclick="javascript: window.location='<?php print($_SERVER['PHP_SELF']."?".$qryStr);?>';">Back</button>
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
									<h4 ><i class="fa fa-envelope"></i> Emails</h4>
									<div class="tools" style="color:white">
										<!--<div> <a href="<?php //print($_SERVER['PHP_SELF']."?".$qryStrURL."action=1");?>" title="Add New"><i class="fa fa-plus"></i> Add New</a> </div>-->
									</div>
								</div>
								<div class="box-body">
									<form name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']);?>" class="form-horizontal" role="form">
										<table class="table users-table table-condensed table-hover table-striped" >
											<thead>
												<tr>
													<th class="visible-xs visible-sm visible-md visible-lg"><input type="checkbox" name="chkAll" onClick="setAll();"></th>
													<th class="visible-xs visible-sm visible-md visible-lg">Title</th>
													<th class="visible-lg">Subject</th>
													<th width="140">Action</th>
												</tr>
											</thead>
											<tbody>
												<?php
									
									
										$Query = "Select * From emails";

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
													<td class="visible-xs visible-sm visible-md visible-lg"><input type="checkbox" name="chkstatus[]" value="<?php print($row->eml_id); ?>" /></td>
													<td class="visible-xs visible-sm visible-md visible-lg"><?php print($row->eml_title); ?></td>
													<td class="visible-lg"><?php print($row->eml_subject); ?></td>
													<td><button type="button" class="btn btn-info" title="View Details" onclick="javascript: window.location='<?php print($_SERVER['PHP_SELF'] . "?show=1&" . $qryStrURL . "eml_id=" . $row->eml_id); ?>';"><i class="fa fa-eye"></i></button>
														<button type="button" class="btn btn-warning" title="Edit" onclick="javascript: window.location='<?php print($_SERVER['PHP_SELF'] . "?action=2&" . $qryStrURL . "eml_id=" . $row->eml_id); ?>';"><i class="fa fa-edit"></i></button></td>
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
												<td align="right"><?php
                                                                        $next_prev = $p->nextPrev($_GET['page'], $pages, '&' . $qryStr);
                                                                        print($next_prev);
                                                                        ?></td>
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