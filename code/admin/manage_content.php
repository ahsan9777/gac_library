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
	if(isset($_REQUEST['btnAdd']))
	{
			$MaxID = getMaximum("contents","cnt_id");
			$strQRY="INSERT INTO contents (cnt_id, cnt_heading, cnt_title, cnt_keywords, cnt_metades, cnt_details) VALUES(".$MaxID.", '".dbStr(trim($_REQUEST['cnt_heading']))."', '".dbStr(trim($_REQUEST['cnt_title']))."',  '".dbStr(trim($_REQUEST['cnt_keywords']))."', '".dbStr(trim($_REQUEST['cnt_metades']))."', '".dbStr(trim($_REQUEST['cnt_details']))."')";
			$nRst=mysqli_query($GLOBALS['conn'], $strQRY) or die(mysqli_error($GLOBALS['conn']));
			header("Location: ".$_SERVER['PHP_SELF']."?".$qryStrURL."op=1");
	}
	elseif(isset($_REQUEST['btnUpdate']))
	{
		mysqli_query($GLOBALS['conn'], "UPDATE contents SET cnt_heading='".dbStr(trim($_REQUEST['cnt_heading']))."', cnt_title='".dbStr(trim($_REQUEST['cnt_title']))."', cnt_keywords='".dbStr(trim($_REQUEST['cnt_keywords']))."', cnt_metades='".dbStr(trim($_REQUEST['cnt_metades']))."', cnt_details='".dbStr(trim($_REQUEST['cnt_details']))."'  WHERE cnt_id=".$_REQUEST['cnt_id']) or die(mysqli_error($GLOBALS['conn']));
		header("Location: ".$_SERVER['PHP_SELF']."?".$qryStrURL."op=2");
	}
	elseif($_REQUEST['action']==2){
		$rsM = mysqli_query($GLOBALS['conn'], "SELECT * FROM contents WHERE cnt_id=".$_REQUEST['cnt_id']);
		if(mysqli_num_rows($rsM)>0){
			$rsMem = mysqli_fetch_object($rsM);
			$cnt_heading = $rsMem->cnt_heading;
			$cnt_title=$rsMem->cnt_title;
			$cnt_keywords=$rsMem->cnt_keywords;
			$cnt_metades=$rsMem->cnt_metades;
            $cnt_details=$rsMem->cnt_details;
           $formHead = "Update Info";
		}
	}
	else{
		$cnt_id = "";
		$cnt_heading='';
        $cnt_details='';
        $cnt_keywords='';
        $cnt_title='';
        $cnt_metades='';
		
		$formHead = "Add New";
	}
}
if(isset($_REQUEST['show'])){
	$rsM = mysqli_query($GLOBALS['conn'], "SELECT * From  contents WHERE cnt_id=".$_REQUEST['cnt_id']);
	if(mysqli_num_rows($rsM)>0){
		$rsMem = mysqli_fetch_object($rsM);
		$cnt_id = $rsMem->cnt_id;
        $cnt_heading = $rsMem->cnt_heading;
		$cnt_title=$rsMem->cnt_title;
		$cnt_keywords=$rsMem->cnt_keywords;
        $cnt_metades=$rsMem->cnt_metades;
        $cnt_details=$rsMem->cnt_details;
        $formHead = "Update Info";
	}
}
include("includes/messages.php");
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
									<li>Contents Management</li>
								</ul>
								<!-- /BREADCRUMBS -->
								<div class="clearfix">
									<h3 class="content-title pull-left">Contents Management</h3>
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
							<h4 class="panel-title">
								<i class="fa fa-bars"></i><?php print($formHead);?>
								
							</h4>
						</div>
						<div class="box-body">
                        <form name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']);?>" class="form-horizontal" role="form" enctype="multipart/form-data">
                        <div class="form-group">
							<label  class="col-lg-2 col-md-3 control-label">Heading</label>
								<div class="col-lg-8 col-md-8">
									<input type="text" class="form-control form-cascade-control  required " name="cnt_heading" id="cnt_heading" value="<?php print($cnt_heading);?>" placeholder="Heading">
								</div>
						</div>
                             <div class="form-group">
							<label  class="col-lg-2 col-md-3 control-label">Title</label>
								<div class="col-lg-8 col-md-8">
									<input type="text" class="form-control form-cascade-control  required " name="cnt_title" id="grp_name" value="<?php print($cnt_title);?>" placeholder="Title">
								</div>
						</div>

                        <div class="form-group">
                              <label for="mem_fname" class="col-lg-2 col-md-3 control-label">Meta Keyword</label>
									<div class="col-lg-8 col-md-8"> 
										<input type="text" class="form-control form-cascade-control " name="cnt_keywords" id="grp_end" value="<?php print($cnt_keywords);?>" placeholder="Keyword">
									</div>
						</div>
                            <div class="form-group">
                            <label for="mem_login" class="col-lg-2 col-md-3 control-label">Meta Description</label>
                            <div class="col-lg-8 col-md-8">
                                <textarea type="text" class="form-control form-cascade-control  " name="cnt_metades" id="cnt_metades" placeholder="Meta Description"  rows="5"><?php print($cnt_metades); ?></textarea>
                              
                            </div>
                        </div>
                        <div class="form-group">
                             <label for="mem_fname" class="col-lg-2 col-md-3 control-label">Details</label>
                              	<div class="col-lg-8 col-md-8">
                                        <textarea class="form-control form-cascade-control ckeditor" name="cnt_details" rows="8"><?php print($cnt_details);?></textarea>
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
										<button type="button" name="btnCancel" class="btn btn-default btn-animate-demo" onClick="javascript: window.location='<?php print($_SERVER['PHP_SELF']."?".$qryStr);?>';">Cancel</button>
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
									<label for="inputEmail1" class="col-lg-2 col-md-3 control-label">Heading</label>
									<div class="col-lg-10 col-md-9 padTop7"><?php print($cnt_heading);?></div>
								</div>
                            	<div class="form-group">
									<label for="inputEmail1" class="col-lg-2 col-md-3 control-label">Title</label>
									<div class="col-lg-10 col-md-9 padTop7"><?php print($cnt_title);?></div>
								</div>
                                <div class="form-group">
									<label for="inputEmail1" class="col-lg-2 col-md-3 control-label">Meta Keyword</label>
									<div class="col-lg-10 col-md-9 padTop7"><?php print($cnt_keywords);?></div>
								</div>
                                <div class="form-group">
									<label for="inputEmail1" class="col-lg-2 col-md-3 control-label">Meta Description</label>
									<div class="col-lg-10 col-md-9 padTop7"><?php print($cnt_metades);?></div>
								</div>
                                <div class="form-group">
									<label for="inputEmail1" class="col-lg-2 col-md-3 control-label">Details</label>
									<div class="col-lg-10 col-md-9 padTop7"><?php print($cnt_details);?></div>
								</div>
                                
                                <div class="form-group">
									<label for="inputEmail1" class="col-lg-2 col-md-3 control-label">&nbsp;</label>
									<div class="col-lg-10 col-md-9">
										<button type="button" name="btnBack" class="btn btn-default btn-animate-demo" onClick="javascript: window.location='<?php print($_SERVER['PHP_SELF']."?".$qryStr);?>';">Back</button>
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
							<h4 ><i class="fa fa-bars"></i> Contents</h4>
								    <!--<div class="tools" style="color:white">
								
									<div>
										<a href="<?php print($_SERVER['PHP_SELF']."?".$qryStrURL."action=1");?>" title="Add New"><i class="fa fa-plus"></i> Add New</a>
									
									</div>
								</div> -->
							
						</div>
						<div class="box-body">
						<form name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']);?>" class="form-horizontal" role="form">
                        <table class="table users-table table-condensed table-hover table-striped table-bordered" >
								<thead>
									<tr>
<!--										<th class="visible-xs visible-sm visible-md visible-lg"><input type="checkbox" name="chkAll" onClick="setAll();"></th>-->
										<th class="visible-xs visible-sm visible-md visible-lg">Heading</th>
										
										<th class="visible-lg">Keyword</th>
							
										<th width="70">Action</th>
									</tr>
								</thead>
								<tbody>
								<?php
									$Query = "Select * From contents";
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
<!--                                    <td class="visible-xs visible-sm visible-md visible-lg"><input type="checkbox" name="chkstatus[]" value="<?php print($row->cnt_id); ?>" /></td>-->
                                    <td class="visible-xs visible-sm visible-md visible-lg"><?php print($row->cnt_heading); ?> </td>
    								<td class="visible-lg"><?php print($row->cnt_keywords); ?></td>
									<td>
									<button type="button" class="btn btn-xs btn-info" title="View Details" onClick="javascript: window.location='<?php print($_SERVER['PHP_SELF'] . "?show=1&" . $qryStrURL . "cnt_id=" . $row->cnt_id); ?>';"><i class="fa fa-eye"></i></button>
									<button type="button" class="btn btn-xs btn-warning" title="Edit" onClick="javascript: window.location='<?php print($_SERVER['PHP_SELF'] . "?action=2&" . $qryStrURL . "cnt_id=" . $row->cnt_id); ?>';"><i class="fa fa-edit"></i></button>
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
													$pageList = $p->pageList($_GET['page'], $pages, '&' . $qryStrURL);
													print($pageList);
													?>
												</ul>
											</td>
										</tr>
									</table>
                                         <?php } ?>
                                         <?php if ($counter > 0) { ?>

                                           <!--<input type="submit" name="btnDelete" value="Delete" class="btn btn-danger btn-animate-demo">-->
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