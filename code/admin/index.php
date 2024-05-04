<?php include("includes/header-top.php");
$qryStrURL = "";
?>
<body>
<!-- HEADER -->
<?php include("includes/header.php"); ?>
<!--/HEADER --> 

<!-- PAGE -->
<section id="page"> 
	<!-- SIDEBAR -->
	<?php include("includes/side_bar.php");?>
        <script src="js/jquery.min.js" type="text/jscript" ></script>
<script>
    $("document").ready(function(e) {

        // $(".team_member").hide();
        $(".team_status").click(function() {
            $(".team_member").slideToggle();
        });

        $(".Queue")
        var green = 0;
        var yellow = 0;
        var red = 0;
        var cTotal = 0;
        var grWidth = 0;
        var yeWidth = 0;
        var reWidth = 0;
        $("ul.Queue li").each(function(index) {
            green = parseInt($(this).find("span.complt").text());
            yellow = parseInt($(this).find("span.in_complt").text());
            red = parseInt($(this).find("span.pend").text());
            cTotal = green + red;
            grWidth = green * 100 / cTotal;
            $(this).find("div.greenq").width(grWidth + "%");
            yeWidth = yellow * 100 / cTotal;
            $(this).find("div.yellowq").width(yeWidth + "%");
            reWidth = red * 100 / cTotal;
            $(this).find("div.redq").width(reWidth + "%");
        });

    });
</script>
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
									<h3 class="content-title pull-left">Dashboard</h3>
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
					<!-- /HERO GRAPH -->
						<!-- NEW ORDERS & STATISTICS -->
						
						
					<div class="footer-tools"> <span class="go-top"> <i class="fa fa-chevron-up"></i> Top </span> </div>
				</div>
				<!-- /CONTENT--> 
			</div>
		</div>
	</div>
</section>
<!--/PAGE -->
<style type="text/css">
   #contentRight {
        float: right;
        width: 260px;
        padding:10px;
        background-color:#336600;
        color:#FFFFFF;
    }
    #contentRight1 {
        float: right;
        width: 260px;
        padding:10px;
        background-color:#336600;
        color:#FFFFFF;
    }
    #contentRight2 {
        float: right;
        width: 260px;
        padding:10px;
        background-color:#336600;
        color:#FFFFFF;
    }
  
</style>
<?php include("includes/bottom_js.php");?>
<?php //include("includes/bottom_sortable_js.php") ?>

</body>
</html>