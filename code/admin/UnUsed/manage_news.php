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

    if (isset($_GET['action'])) {
        if (isset($_POST['btnAdd'])) {
            if (!empty($_POST)) {
                // keep track validation errors
                $nameError = null;
                $news_headlineError=null;
                // keep track post values
                $name = $_POST['name'];
                $news_headline=$_POST['news_headline'];
                $news_details=$_POST['news_details'];
                $news_date=date('y-m-d');
               // validate input
                $valid = true;
                if (empty($name)) {
                    $nameError = 'Please enter Short Details';
                    $valid = false;
                }
                if (empty($news_headline)) {
                    $news_headlineError = 'Please Headline of news';
                    $valid = false;
                }

                // insert data
                if ($valid) {
                    $news_id = getMaximum("news", "news_id");
                    mysqli_query($GLOBALS['conn'], "INSERT INTO news (news_id, news_intro,news_headline,news_details,news_date) VALUES (" . $news_id . ",'$name','$news_headline','$news_details','$news_date')");
                    header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=1");
                }
            }
        } elseif (isset($_POST['btnUpdate'])) {
            
            $name = $_POST['name'];
            $news_headline=$_POST['news_headline'];
            $news_details=$_POST['news_details'];
            
            // update data
            mysqli_query($GLOBALS['conn'], "UPDATE news SET news_intro='$name',news_headline='$news_headline' news_details='$news_details' WHERE news_id=" . $_GET['newsid']);
            header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=2");
        } elseif ($_GET['action'] == 2) {
            $rsM = mysqli_query($GLOBALS['conn'], "SELECT * FROM news WHERE news_id = " . $_GET['newsid']);
            if (mysqli_num_rows($rsM) > 0) {
                $rsMem = mysqli_fetch_object($rsM);
                $name = $rsMem->news_intro;
                $news_headline=$rsMem->news_headline;
                $news_details=$rsMem->news_details;
                
            
                $formHead = "Update Info";
            }
        } else {
            $name = "";
            $news_headline="";
            $news_details="";
            $formHead = "Add New";
        }
    }
    if (isset($_REQUEST['show'])) {

        $rsM = mysqli_query($GLOBALS['conn'], "SELECT * FROM news WHERE news_id = " . $_REQUEST['newsid']);
        if (mysqli_num_rows($rsM) > 0) {
            $rsMem = mysqli_fetch_object($rsM);
            $news_headline = $rsMem->news_headline;
            $name = $rsMem->news_intro;
            $news_details=$rsMem->news_details;
            $formHead = "Update Info";
        }
    }
//--------------Button Delete--------------------
    
    
    if(isset($_REQUEST['btnDelete'])){
    
	if(isset($_REQUEST['chkstatus'])){
		for($i=0; $i<count($_REQUEST['chkstatus']); $i++){
			mysqli_query($GLOBALS['conn'], "DELETE FROM news  WHERE news_id = ".$_REQUEST['chkstatus'][$i]);
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
                                        <li>News Management</li>
                                    </ul>
                                    <!-- /BREADCRUMBS -->
                                    <div class="clearfix">
                                        <h3 class="content-title pull-left">News Management </h3>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- /PAGE HEADER -->

                        <!-- DASHBOARD CONTENT -->
                        <?php if ($class != "") { ?>
                            <div class="<?php print($class); ?>"><?php print($strMSG); ?><a class="close" data-dismiss="alert">×</a></div>
                        <?php } ?>

                        <?php if (isset($_GET['action'])) { ?>
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

                                                
                                                 <div class="form-group <?php echo!empty($news_headlineError) ? 'has-error' : ''; ?>">
                                                    <label  class="col-lg-2 col-md-3 control-label">Headline</label>
                                                    <div class="col-lg-4 col-md-9">
                                                        <input class="form-control form-cascade-control" name="news_headline" type="text"  placeholder="Headline" value="<?php echo $news_headline; ?>">
                                                       <?php if (!empty($news_headlineError)): ?>
                                                            <span class="help-inline"><?php echo $news_headlineError; ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group <?php echo!empty($nameError) ? 'has-error' : ''; ?>">
                                                    <label  class="col-lg-2 col-md-3 control-label">Short Details</label>
                                                    <div class="col-lg-4 col-md-9">
                                                        <textarea class="form-control form-cascade-control" name="name"   placeholder="Name"  rows="5" ><?php echo!empty($name) ? $name : ''; ?></textarea>
                                                        <?php if (!empty($nameError)): ?>
                                                            <span class="help-inline"><?php echo $nameError; ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                               
                                                <div class="form-group">
                                                        <label for="mem_login" class="col-lg-2 col-md-3 control-label">Event Details </label>
                                                        <div class="col-lg-8 col-md-9">
                                                            <textarea type="text" class="ckeditor" name="news_details" id="news_details" ><?php print($news_details); ?></textarea>

                                                        </div>
                                                    </div>



                                                <div class="form-group">
                                                    <label for="inputEmail1" class="col-lg-2 col-md-3 control-label">&nbsp;</label>
                                                    <div class="col-lg-10 col-md-9">
                                                        <?php if ($_GET['action'] == 1) { ?>
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

                        <?php } elseif (isset($_REQUEST['show'])) { ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="box border primary">
                                        <div class="box-title">
                                            <h4>
                                                Detailsaaa
                                            </h4>
                                        </div>
                                        <div class="box-body">
                                            <form name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" class="form-horizontal" role="form">
                                                
                                                <div class="form-group">
                                                    <label for="inputEmail1" class="col-lg-2 col-md-3 control-label">Headline:</label>
                                                    <div class="col-lg-10 col-md-9 padTop7"><?php print($news_headline); ?></div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputEmail1" class="col-lg-2 col-md-3 control-label">Short Details:</label>
                                                    <div class="col-lg-10 col-md-9 padTop7"><?php print($name); ?></div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputEmail1" class="col-lg-2 col-md-3 control-label">Details:</label>
                                                    <div class="col-lg-10 col-md-9 padTop7"><?php print($news_details); ?></div>
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

                        <?php } else { ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="box border blue">
                                        <div class="box-title">
                                            <h4 ><i class="fa fa-bars"></i>News</h4>
                                            <div class="tools" style="color:white">

                                                <div>
                                                    <a href="<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL . "action=1"); ?>" title="Add New"><i class="fa fa-plus"></i> Add New</a>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="box-body">
                                            <form name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" class="form-horizontal" role="form">
                                                <table class="table users-table table-condensed table-hover table-striped table-bordered" >
                                                    <thead>
                                                        <tr>
                                                            <th class="visible-xs visible-sm visible-md visible-lg"><input type="checkbox" name="chkAll" onClick="setAll();"></th>
                                                            <th class="visible-xs visible-sm visible-md visible-lg">HeadLine</th>
                                                             <th class="visible-xs visible-sm visible-md visible-lg">Short Details</th>
                                                              <th class="visible-xs visible-sm visible-md visible-lg">Date</th>
                                                              
                                                          
                                                            <th width="140">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $Query = "SELECT * FROM news ORDER BY news_id";

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
                                                                ?>
                                                                <tr>
                                                                    <td class="visible-xs visible-sm visible-md visible-lg"><input type="checkbox" name="chkstatus[]" value="<?php print($row->news_id); ?>" /></td>
                                                                     <td class="visible-xs visible-sm visible-md visible-lg"><?php print($row->news_headline); ?> </td>
                                                                    <td class="visible-xs visible-sm visible-md visible-lg"><?php print($row->news_intro); ?> </td>
                                                                     <td class="visible-xs visible-sm visible-md visible-lg"><?php print($row->news_date); ?> </td>
                                                                  
                                                                    <td>
                                                                        <button type="button" class="btn btn-info" title="View Details" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?show=1&" . $qryStrURL . "newsid=" . $row->news_id); ?>';"><i class="fa fa-eye"></i></button>
                                                                        <button type="button" class="btn btn-warning" title="Edit" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?action=2&" . $qryStrURL . "newsid=" . $row->news_id); ?>';"><i class="fa fa-edit"></i></button>
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
                                                                <?php
                                                                $next_prev = $p->nextPrev($_GET['page'], $pages, '&' . $qryStr);
                                                                print($next_prev);
                                                                ?>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                <?php } ?>

                                                <?php if ($counter > 0) { ?>
<!--                                                    <a class="btn btn-danger btn-animate-demo confirm-dialog2" href="#">Delete</a>-->
                                                   <input type="hidden" name="btnDelete" id="btnDelete">
                                                    <input type="submit" name="btnDelete" value="Delete" class="btn btn-danger btn-animate-demo">
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
    <!--/PAGE -->
    <?php include("includes/bottom_js.php"); ?>
    <script>
    $(".confirm-dialog2").click(function(){
        
			bootbox.confirm("Are you sure?", function(result){
                            $("#btnDelete").click();
                            $("#frm").submit();
                            //return (true);
                        });
		});
    
    </script>
</body>
</html>

