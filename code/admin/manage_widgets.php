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

    if (isset($_REQUEST['cnt_id']) && $_REQUEST['cnt_id'] > 0) {
        $qryStrURL .= "cnt_id=".$_REQUEST['cnt_id']."&";
    }

    if (isset($_REQUEST['action'])) {
        if (isset($_REQUEST['btnAdd'])) {
            $wid_id = getMaximum("widgets", "wid_id");
            if(!file_exists("../files/widgets/".$_REQUEST['cnt_id']))
            {
                mkdir("../files/widgets/".$_REQUEST['cnt_id'], 0777, true);
                mkdir("../files/widgets/".$_REQUEST['cnt_id']. "/th/", 0777, true);
            }
            $dirName = "../files/widgets/".$_REQUEST['cnt_id']."/";
            if (!empty($_FILES["mFile"]["name"])) {
                $mfileName = $_REQUEST['cnt_id'] . "_" . $_FILES["mFile"]["name"];
                $mfileName = str_replace(" ", "_", strtolower($mfileName));
                if (move_uploaded_file($_FILES['mFile']['tmp_name'], $dirName . $mfileName)) {
                    createThumbnail2($dirName, $mfileName, $dirName . "th/", "200", "200");
                }
            }
            $wid_params = str_replace(" ", "_", strtolower($_REQUEST['wid_params']));
            $Query = "SELECT * FROM widgets WHERE cnt_id = '".dbStr(trim($_REQUEST['cnt_id']))."' AND wid_params = '".dbStr(trim($wid_params))."'";
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            if(mysqli_num_rows($rs) > 0){
                header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=6");
            } else{
                mysqli_query($GLOBALS['conn'], "INSERT INTO widgets (wid_id, cnt_id, wid_heading, wid_params, wid_details, wid_img) VALUES ('" . $wid_id . "','" . dbStr(trim($_REQUEST['cnt_id'])) . "','" . dbStr(trim($_REQUEST['wid_heading'])) . "', '".dbStr(trim($wid_params))."','" . dbStr(trim($_REQUEST['wid_details'])) . "','" . dbStr(trim($mfileName)) . "')") or die(mysqli_error($GLOBALS['conn']));
                header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=1");
            }
        } elseif (isset($_REQUEST['btnUpdate'])) {
            if(!file_exists("../files/widgets/".$_REQUEST['cnt_id']))
            {
                mkdir("../files/widgets/".$_REQUEST['cnt_id'], 0777, true);
                mkdir("../files/widgets/".$_REQUEST['cnt_id']. "/th/", 0777, true);
            }
            $dirName = "../files/widgets/".$_REQUEST['cnt_id']."/";
            $mfileName = $_REQUEST['mfileName'];
            if (!empty($_FILES["mFile"]["name"])) {
                DeleteFileWithThumb("wid_img", "widgets", "wid_id", $_REQUEST['cnt_id'], "../files//widgets/" . $_REQUEST["cnt_id"] . "/", "EMPTY");
                DeleteFileWithThumb("wid_img", "widgets", "wid_id", $_REQUEST['cnt_id'], "../files//widgets/" . $_REQUEST["cnt_id"] . "/th/", "EMPTY");
                $mfileName = $_REQUEST['cnt_id'] . "_" . $_FILES["mFile"]["name"];
                $mfileName = str_replace(" ", "_", strtolower($mfileName));
                if (move_uploaded_file($_FILES['mFile']['tmp_name'], $dirName . $mfileName)) {
                    createThumbnail2($dirName, $mfileName, $dirName . "th/", "200", "200");
                }
            }
            $wid_params = str_replace(" ", "_", strtolower($_REQUEST['wid_params']));
            mysqli_query($GLOBALS['conn'], "UPDATE widgets SET wid_heading='".dbStr(trim($_REQUEST['wid_heading']))."', wid_details='".dbStr(trim($_REQUEST['wid_details']))."', wid_img = '".dbStr(trim($mfileName))."' WHERE wid_id=" . $_REQUEST['wid_id']) or die(mysqli_error($GLOBALS['conn']));
            header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=2");
        } elseif ($_REQUEST['action'] == 2) {
            $rsM = mysqli_query($GLOBALS['conn'], "SELECT * FROM widgets WHERE wid_id = " . $_REQUEST['wid_id']);
            if (mysqli_num_rows($rsM) > 0) {
                $rsMem = mysqli_fetch_object($rsM);
                $wid_heading = $rsMem->wid_heading;
                $wid_params = $rsMem->wid_params;
                $wid_details = $rsMem->wid_details;
                $cnt_id = $rsMem->cnt_id;
                $mfileName = $rsMem->wid_img;
                $disabled = "disabled";
                $readonly = "readonly";
                $formHead = "Update Info";
            }
        } else {
            $cnt_id = "";
            $wid_heading = "";
            $wid_params = "";
            $wid_details = "";
            $mfileName = "";
            $disabled = "";
            $readonly = "";
            $formHead = "Add New";
        }
    }
    if (isset($_REQUEST['show'])) {

        $rsM = mysqli_query($GLOBALS['conn'], "SELECT w.*, c.cnt_heading FROM widgets AS w LEFT OUTER JOIN contents AS c ON c.cnt_id=w.cnt_id WHERE w.wid_id = " . $_REQUEST['wid_id']);
        if (mysqli_num_rows($rsM) > 0) {
            $rsMem = mysqli_fetch_object($rsM);
            $cnt_heading = $rsMem->cnt_heading;
            $wid_heading = $rsMem->wid_heading;
            $wid_params = $rsMem->wid_params;
			$wid_details = $rsMem->wid_details;
            $mfileName = $rsMem->wid_img;
            $formHead = "Update Info";
        }
    }
    //--------------Button Delete--------------------
    if (isset($_REQUEST['btnDelete'])) {
        if (isset($_REQUEST['chkstatus'])) {
            for ($i = 0; $i < count($_REQUEST['chkstatus']); $i++) {
                mysqli_query($GLOBALS['conn'], "delete from widgets WHERE wid_id = " . $_REQUEST['chkstatus'][$i])or die(mysqli_error($GLOBALS['conn']));
            }
            $class = "alert alert-success";
            $strMSG = "Record(s) deleted successfully";
        } else {
            $class = " alert alert-info ";
            $strMSG = "Please check atleast one checkbox";
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
                                        <li>Widget Management</li>
                                    </ul>
                                    <!-- /BREADCRUMBS -->
                                    <div class="clearfix">
                                        <h3 class="content-title pull-left">Widget Management </h3>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- /PAGE HEADER -->

                        <!-- DASHBOARD CONTENT -->
          <?php if(isset($_REQUEST['action'])) { ?>
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

                                    <?php if (!empty($mfileName)) { ?>
                                        <div class="form-group">
                                            <label for="inputEmail1" class="col-lg-2 col-md-3 control-label"></label>
                                            <div class="col-lg-10 col-md-9 padTop7"><img src="<?php print($GLOBALS['siteURL']); ?>files/widgets/<?php print($mfileName); ?>" style="max-width: 300px;" alt="" /></div>
                                        </div>
                                    <?php } ?>
                                    <div class="form-group">
                                        <label  class="col-lg-2 col-md-3 control-label">Page</label>
                                        <div class="col-lg-4 col-md-9">
                                            <select name="cnt_id" id="cnt_id" class="form-control form-cascade-control  required" <?php print($disabled); ?> >
                                                <?php FillSelected2("contents", "cnt_id", "cnt_heading", $cnt_id, "cnt_id >= 0"); ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label  class="col-lg-2 col-md-3 control-label">Heading</label>
                                        <div class="col-lg-4 col-md-9">
                                            <input type="text" class="form-control form-cascade-control  required " name="wid_heading" id="wid_heading" value="<?php print($wid_heading); ?>" placeholder="Title">
                                        </div>
                                        <label  class="col-lg-1 col-md-3 control-label">Params</label>
                                        <div class="col-lg-4 col-md-9">
                                            <input type="text" <?php print($readonly); ?> class="form-control form-cascade-control  required " name="wid_params" id="wid_params" value="<?php print($wid_params); ?>" placeholder="Params">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="mem_login" class="col-lg-2 col-md-2 control-label">Detail </label>
                                        <div class="col-lg-9 col-md-8">
                                            <textarea class="form-control form-cascade-control ckeditor  required" name="wid_details" id="wid_details" rows="10"><?php print($wid_details); ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="cnt_pcode" class="col-lg-2 col-md-3 control-label">File</label>
                                        <div class="col-lg-3">
                                            <input type="file" name="mFile" style="float:left !important" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail1" class="col-lg-2 col-md-3 control-label">&nbsp;</label>
                                        <div class="col-lg-10 col-md-9">
                                            <?php if ($_REQUEST['action'] == 1) { ?>
                                                <button type="submit" name="btnAdd" class="btn btn-primary btn-animate-demo">Submit</button>
                                            <?php } else { ?>
                                                <button type="submit" name="btnUpdate" class="btn btn-primary btn-animate-demo">Submit</button>
                                                <input type="hidden" name="mfileName" value="<?php print($mfileName); ?>" />
                                            <?php } ?>
                                            <button type="button" name="btnCancel" class="btn btn-default btn-animate-demo" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL); ?>';">Cancel</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
              <?php } else if(isset($_REQUEST['show'])) {?>
                <div class="row">
                    <div class="col-md-12">
                                    <div class="box border primary">
                                        <div class="box-title">
                                            <h4>
                                                Details
                                            </h4>
                                        </div>
                                        <div class="box-body">
                                            <form name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" class="form-horizontal" role="form">
                                                <?php if (!empty($mfileName)) { ?>
                                                    <div class="form-group">
                                                        <label for="inputEmail1" class="col-lg-2 col-md-3 control-label"></label>
                                                        <div class="col-lg-10 col-md-9 padTop7"><img src="<?php print($GLOBALS['siteURL']); ?>files/widgets/<?php print($mfileName); ?>" style="max-width: 300px;" alt="" /></div>
                                                    </div>
                                                <?php } ?>
                                                <div class="form-group">
                                                    <label for="inputEmail1" class="col-lg-2 col-md-3 control-label">Page:</label>
                                                    <div class="col-lg-10 col-md-9 padTop7"><?php print($cnt_heading); ?></div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputEmail1" class="col-lg-2 col-md-3 control-label">Heading:</label>
                                                    <div class="col-lg-10 col-md-9 padTop7"><?php print($wid_heading); ?></div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputEmail1" class="col-lg-2 col-md-3 control-label">Params:</label>
                                                    <div class="col-lg-10 col-md-9 padTop7"><?php print($wid_params); ?></div>
                                                </div>
                                                 <div class="form-group">
                                                    <label for="inputEmail1" class="col-lg-2 col-md-3 control-label">Details:</label>
                                                    <div class="col-lg-10 col-md-9 padTop7"><?php print($wid_details); ?></div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputEmail1" class="col-lg-2 col-md-3 control-label">&nbsp;</label>
                                                    <div class="col-lg-10 col-md-9">
                                                        <button type="button" name="btnBack" class="btn btn-default btn-animate-demo" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL); ?>';">Back</button>
                                                    </div>
                                                </div>					
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } else {
                                    $searchQuery =" WHERE 1=1";
                                        $cnt_id = 0;
                                    if (isset($_REQUEST['cnt_id']) && $_REQUEST['cnt_id'] > 0) {
                                        $cnt_id = $_REQUEST['cnt_id'];
                                        $searchQuery .= " AND w.cnt_id = ".$_REQUEST['cnt_id'];
                                    }
                            ?>
                            <div class="row">
                            <div class="col-md-12" style="margin-bottom: 12px;">
                                <form name="frmCat" id="frmCat" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?"); ?>">
                                    <div class="form-group">
                                        <div class="col-lg-3 col-md-3">
                                            <label for="inputEmail1" class=" control-label " style="text-align: right;">Page : </label>
                                            <select name="cnt_id" id="cnt_id" class="form-control form-cascade-control  required" onchange="javascript: frmCat.submit();">
                                                <option value="0">N/A</option>
                                                <?php FillSelected2("contents", "cnt_id", "cnt_heading", $cnt_id, "cnt_id >= 0"); ?>
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="box border blue">
                                        <div class="box-title">
                                            <h4 ><i class="fa fa-bars"></i>Widgets</h4>
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
                                                            <!--<th class="visible-xs visible-sm visible-md visible-lg"><input type="checkbox" name="chkAll" onClick="setAll();"></th>-->
                                                            <th class="visible-xs visible-sm visible-md visible-lg">Page</th>
                                                            <th class="visible-xs visible-sm visible-md visible-lg">Heading</th>
                                                            <th class="visible-xs visible-sm visible-md visible-lg">Params</th>
                                                            <th width="70">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $Query = "SELECT w.*, c.`cnt_heading` FROM widgets AS w LEFT OUTER JOIN contents AS c ON c.`cnt_id`=w.`cnt_id` ".$searchQuery." ORDER BY w.`cnt_id` ASC";

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
                                                                    <!--<td class="visible-xs visible-sm visible-md visible-lg"><input type="checkbox" name="chkstatus[]" value="<?php /*print($row->wid_id); */?>" /></td>-->
                                                                    <td class="visible-xs visible-sm visible-md visible-lg"><?php print($row->cnt_heading); ?> </td>
                                                                     <td class="visible-xs visible-sm visible-md visible-lg"><?php print($row->wid_heading); ?> </td>
                                                                     <td class="visible-xs visible-sm visible-md visible-lg"><?php print($row->wid_params); ?> </td>
                                                                    <td>
                                                                        <button type="button" class="btn btn-xs btn-info" title="View Details" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?show=1&" . $qryStrURL . "wid_id=" . $row->wid_id); ?>';"><i class="fa fa-eye"></i></button>
                                                                        <button type="button" class="btn btn-xs btn-warning" title="Edit" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?action=2&" . $qryStrURL . "wid_id=" . $row->wid_id); ?>';"><i class="fa fa-edit"></i></button>
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
                                                                    $pageList = $p->pageList($_GET['page'], $pages, '&' . rmPageFromURL($qryStr));
                                                                    print($pageList);
                                                                    ?>
                                                                </ul>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                <?php } ?>

                                                <?php if ($counter > 0) { ?>
                                                 <!-- <input type="submit" name="btnDelete" onclick="return confirm('Are you sure you want to delete selected item(s)?');" value="Delete" class="btn btn-danger btn-animate-demo">-->
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