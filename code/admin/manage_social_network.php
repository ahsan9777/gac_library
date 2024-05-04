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

    if (isset($_REQUEST['btnAdd'])) {
        $sl_id = getMaximum("social_links", "sl_id");
        $mfileName = "";
        $dirName = "../images/social_icon/";
        if (!empty($_FILES["mFile"]["name"])) {
            $mfileName = $sl_id . "_" . $_FILES["mFile"]["name"];
            if (move_uploaded_file($_FILES['mFile']['tmp_name'], $dirName . "/" . $mfileName)) {
                
            }
            mysqli_query($GLOBALS['conn'], "INSERT INTO social_links (sl_id, sl_title, sl_url, sl_icon) VALUES (" . $sl_id . ",'" . $_REQUEST['sl_title'] . "','" . $_REQUEST['sl_url'] . "', '" . $mfileName . "')");
            header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=1");
        }
    } elseif (isset($_REQUEST['btnUpdate'])) {
        mysqli_query($GLOBALS['conn'], "UPDATE social_links SET sl_title='".dbStr($_REQUEST['sl_title'])."', sl_url='".dbStr($_REQUEST['sl_url'])."' WHERE sl_id = " . $_REQUEST['sl_id']);
        header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=2");
    } elseif (isset($_REQUEST['action'])) {
        if ($_REQUEST['action'] == 2) {
            $rsM = mysqli_query($GLOBALS['conn'], "SELECT * FROM social_links WHERE sl_id = " . $_REQUEST['sl_id']);
            if (mysqli_num_rows($rsM) > 0) {
                $rsMem = mysqli_fetch_object($rsM);
                $sl_title = $rsMem->sl_title;
                $sl_url = $rsMem->sl_url;
                $mfileName = $rsMem->sl_icon;
                $formHead = "Update Info";
            }
        } else {
            $sl_title = "";
            $sl_url = '';
            $com_details = '';
            $mfileName = '';
            $formHead = "Add New";
        }
    }
//--------------Button Active--------------------
    if (isset($_REQUEST['btnActive'])) {
        if (isset($_REQUEST['chkstatus'])) {
            for ($i = 0; $i < count($_REQUEST['chkstatus']); $i++) {
                mysqli_query($GLOBALS['conn'], "UPDATE social_links SET sl_status =1 WHERE sl_id = " . $_REQUEST['chkstatus'][$i]);
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
                mysqli_query($GLOBALS['conn'], "UPDATE social_links SET sl_status =0 WHERE sl_id = " . $_REQUEST['chkstatus'][$i]);
            }
            $class = "alert alert-success";
            $strMSG = "Record(s) updated successfully";
        } else {
            $class = "alert alert-info";
            $strMSG = "Please Select Alteast One Checkbox";
        }
    }
//--------------Button Delete--------------------
    if (isset($_REQUEST['btnDelete'])) {
        if (isset($_REQUEST['chkstatus'])) {
            for ($i = 0; $i < count($_REQUEST['chkstatus']); $i++) {
                $getID = $_REQUEST['chkstatus'][$i];
                mysqli_query($GLOBALS['conn'], "DELETE FROM social_links WHERE sl_id = " . $_REQUEST['chkstatus'][$i]);
            }
            header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=5");
        } else {
            $class = "alert alert-info";
            $strMSG = "Please Select Alteast One Checkbox";
        }
    }
 //--------------Button Update order--------------------
    if (isset($_REQUEST['btnUpdateOrder'])) {
        for ($i = 0; $i < count($_REQUEST['sl_id']); $i++) {
            $sl_id  = $_REQUEST['sl_id'][$i];
            mysqli_query($GLOBALS['conn'], "UPDATE social_links SET si_orderby = " . $_REQUEST['si_orderby'][$i] . " WHERE sl_id  = " . $sl_id ) or die(mysqli_error($GLOBALS['conn']));

        }
        $class = "alert alert-success";
        $strMSG = "Record(s) update successfully";
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
                                        <li>Social Networks Management</li>
                                    </ul>
                                    <!-- /BREADCRUMBS -->
                                    <div class="clearfix">
                                        <h3 class="content-title pull-left">Social Networks  Management</h3>
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
                                                <div class="form-group">
                                                    <label  class="col-lg-2 col-md-3 control-label">Title</label>
                                                    <div class="col-lg-4 col-md-9">
                                                        <input type="text" class="form-control form-cascade-control required" name="sl_title" id="sl_title" value="<?php print($sl_title); ?>" placeholder="Title">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label  class="col-lg-2 col-md-3 control-label">URL</label>
                                                    <div class="col-lg-4 col-md-9">
                                                        <input type="text" class="form-control form-cascade-control" name="sl_url" id="sl_url" value="<?php print($sl_url); ?>" placeholder="URL">
                                                    </div>
                                                </div>
                                                <!-- <div class="form-group">
                                                    <label for="cnt_pcode" class="col-lg-2 col-md-3 control-label">File</label>
                                                    <div class="col-lg-3">
                                                        <input type="file" name="mFile" style="float:left !important" />
                                                    </div>
                                                </div> -->
                                                <div class="form-group">
                                                    <label for="inputEmail1" class="col-lg-2 col-md-3 control-label">&nbsp;</label>
                                                    <div class="col-lg-10 col-md-9">
                                                <?php if ($_REQUEST['action'] == 1) { ?>
                                                            <button type="submit" name="btnAdd" class="btn btn-primary btn-animate-demo">Submit</button>
                                                        <?php } else { ?>
                                                            <button type="submit" name="btnUpdate" class="btn btn-primary btn-animate-demo">Submit</button>
                                                            <input type="hidden" name="mfileName" value="<?php //print($mfileName); ?>" />
                                                <?php } ?>
                                                        <button type="button" name="btnCancel" class="btn btn-default btn-animate-demo" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?" . $qryStr); ?>';">Cancel</button>
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
                                            <h4 ><i class="fa fa-user"></i> Socail Links</h4>
                                            <div class="tools" style="color:white">

                                                <div>
                                                    <!-- <a href="<?php //print($_SERVER['PHP_SELF'] . "?" . $qryStrURL . "action=1"); ?>" title="Add New"><i class="fa fa-plus"></i> Add New</a> -->

                                                </div>
                                            </div> 

                                        </div>
                                        <div class="box-body">
                                            <form name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" class="form-horizontal" role="form">
                                                <table class="table users-table table-condensed table-hover table-striped table-bordered" >
                                                    <thead>
                                                        <tr>
                                                            <th class="visible-xs visible-sm visible-md visible-lg"><input type="checkbox" name="chkAll" onClick="setAll();"></th>
                                                            <th class=" visible-md visible-lg" >Icon</th>
                                                            <th class="visible-xs visible-sm visible-md visible-lg">Title</th>
                                                            <th class="visible-lg">URL / Link</th>
                                                            <th class=" visible-md visible-lg" width="70">Order By</th>
                                                            <th class="visible-lg" width="50">Status</th>
                                                            <th width="40" class="visible-xs visible-sm visible-md visible-lg">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                            <?php
                                                            $Query = "SELECT * FROM social_links ORDER BY si_orderby ASC";

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
                                                                    <td class="visible-xs visible-sm visible-md visible-lg"><input type="checkbox" name="chkstatus[]" value="<?php print($row->sl_id); ?>" /></td>
                                                                    <td class=" visible-md visible-lg" align="center"><?php print($row->sl_icon); ?> </td>
                                                                    <td class="visible-xs visible-sm visible-md visible-lg"><?php print($row->sl_title); ?> </td>
                                                                    <td class="visible-lg"><?php print($row->sl_url); ?></td>
                                                                    <td class="visible-xs visible-sm visible-md visible-lg">
                                                                    <input type="hidden" name="sl_id[]"  value="<?php print($row->sl_id);?>">
                                                                    <input type="number" name="si_orderby[]" style="width: 80px;" min="0" onkeypress="return event.charCode >= 48" autocomplete="off"  value="<?php print($row->si_orderby);?>">
                                                                    </td>
                                                                    <td class="visible-lg">
                                                                    <?php
                                                                    if ($row->sl_status == 0) {
                                                                        echo "<span class='btn btn-xs btn-warning btn-animate-demo padMsgs'> In Active </span>";
                                                                    } else {
                                                                        echo "<span class='btn btn-xs btn-success btn-animate-demo padMsgs'> Active </span>";
                                                                    }
                                                                    ?>
                                                                    </td>
                                                                    <td class="visible-xs visible-sm visible-md visible-lg">
                                                                        <!-- <button type="button" class="btn btn-xs btn-info" title="View Details" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?show=1&" . $qryStrURL . "sl_id=" . $row->sl_id); ?>';"><i class="fa fa-eye"></i></button> -->
                                                                        <button type="button" class="btn btn-xs btn-warning" title="Edit" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?action=2&" . $qryStrURL . "sl_id=" . $row->sl_id); ?>';"><i class="fa fa-edit"></i></button>
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
                                                    <input type="submit" name="btnUpdateOrder" value="Update" class="btn btn-primary btn-animate-demo">
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
</body>
</html>
