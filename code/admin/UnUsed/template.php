<?php include("includes/header-top.php"); ?>
<body>
<!-- HEADER -->
<?php include("includes/header.php"); 
$formHead = "Add New";
$strMSG = "";
$class = "";
$qryStrURL = "";
$qryStr = "";
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
									<h3 class="content-title pull-left">Dashboard Management</h3>
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
                                                            eeeeeeeee
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
                                                            
                                                            kkkkk
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
							<h4 ><i class="fa fa-user"></i> Companies</h4>
								    <div class="tools" style="color:white">
								
									<div>
										<a href="<?php print($_SERVER['PHP_SELF']."?".$qryStrURL."action=1");?>" title="Add New"><i class="fa fa-plus"></i> Add New</a>
									
									</div>
								</div> 
							
						</div>
						<div class="box-body">
						<form name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']);?>" class="form-horizontal" role="form">
                                                    sdsafsafdsafssdsfdsffsf
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