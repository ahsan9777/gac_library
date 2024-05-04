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

    if (isset($_REQUEST['action'])) {
        if (isset($_REQUEST['btnAdd'])) {
            $faq_id = getMaximum("faqs", "faq_id");

            mysqli_query($GLOBALS['conn'], "INSERT INTO faqs (faq_id, faq_question, faq_answer) VALUES (" . $faq_id . ",'" . dbStr(trim($_REQUEST['faq_question'])) . "','" .dbStr(trim($_REQUEST['faq_answer'])) . "')") or die (mysqli_error($GLOBALS['conn']));
            header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=1");
        } elseif (isset($_REQUEST['btnUpdate'])) {

            mysqli_query($GLOBALS['conn'], "UPDATE faqs SET faq_question='" . dbStr(trim($_REQUEST['faq_question'])) . "',faq_answer='" .dbStr(trim($_REQUEST['faq_answer'])). "' WHERE faq_id=" . $_REQUEST['faq_id'])or die (mysqli_error($GLOBALS['conn']));
            header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=2");
        } elseif ($_REQUEST['action'] == 2) {
            $rsM = mysqli_query($GLOBALS['conn'], "SELECT * FROM faqs WHERE faq_id = " . $_REQUEST['faq_id']);
            if (mysqli_num_rows($rsM) > 0) {
                $rsMem = mysqli_fetch_object($rsM);
                $faq_question = $rsMem->faq_question;
                $faq_answer = $rsMem->faq_answer;
                $formHead = "Update Info";
            }
        } else {
            $faq_question = "";
            $faq_answer = "";
            $formHead = "Add New";
        }
    }
    //--------------Button Inactive--------------------
    if (isset($_REQUEST['Inactive'])) {
        if (isset($_REQUEST['chkstatus'])) {
            for ($i = 0; $i < count($_REQUEST['chkstatus']); $i++) {
                mysqli_query($GLOBALS['conn'], "UPDATE faqs SET faq_status= '0' WHERE faq_id  = " . $_REQUEST['chkstatus'][$i]) or die(mysqli_error($GLOBALS['conn']));
            }
            $class = "alert alert-success";
            $strMSG = "Record(s) updated successfully";
        } else {
            $class = "alert alert-info";
            $strMSG = "Please Select Alteast One Checkbox";
        }
    }
    //--------------Button active--------------------
    if (isset($_REQUEST['active'])) {
        if (isset($_REQUEST['chkstatus'])) {
            for ($i = 0; $i < count($_REQUEST['chkstatus']); $i++) {
                mysqli_query($GLOBALS['conn'], "UPDATE faqs SET faq_status = '1' WHERE faq_id  = " . $_REQUEST['chkstatus'][$i]) or die(mysqli_error($GLOBALS['conn']));
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
                mysqli_query($GLOBALS['conn'], "delete from faqs WHERE faq_id = " . $_REQUEST['chkstatus'][$i])or die(mysqli_error($GLOBALS['conn']));
            }
            $class = "alert alert-success";
            $strMSG = "Record(s) deleted successfully";
        } else {
            $class = " alert alert-info ";
            $strMSG = "Please check atleast one checkbox";
        }
    }
    //--------------Button Update order--------------------
    if (isset($_REQUEST['btnUpdateOrder'])) {
    for ($i = 0; $i < count($_REQUEST['faq_id']); $i++) {
        $faq_id  = $_REQUEST['faq_id'][$i];
        mysqli_query($GLOBALS['conn'], "UPDATE faqs SET faq_orderby = " . $_REQUEST['faq_orderby'][$i] . " WHERE faq_id  = " . $faq_id ) or die(mysqli_error($GLOBALS['conn']));

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
                                        <li>FAQs Management</li>
                                    </ul>
                                    <!-- /BREADCRUMBS -->
                                    <div class="clearfix">
                                        <h3 class="content-title pull-left">FAQs Management </h3>
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
                                                    <label  class="col-lg-2 col-md-3 control-label">Question</label>
                                                    <div class="col-lg-4 col-md-9">
                                                        <input type="text" class="form-control form-cascade-control  required " name="faq_question" id="faq_question" value="<?php print($faq_question); ?>" placeholder="Question">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="mem_login" class="col-lg-2 col-md-2 control-label">Answer </label>
                                                    <div class="col-lg-8 col-md-8">
                                                        <textarea class="form-control form-cascade-control" name="faq_answer" id="apost_intro" rows="5"><?php print($faq_answer); ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputEmail1" class="col-lg-2 col-md-3 control-label">&nbsp;</label>
                                                    <div class="col-lg-10 col-md-9">
                                                      <?php if ($_REQUEST['action'] == 1) { ?>
                                                            <button type="submit" name="btnAdd" class="btn btn-primary btn-animate-demo">Submit</button>
                                                                   <?php } else { ?>
                                                            <button type="submit" name="btnUpdate" class="btn btn-primary btn-animate-demo">Submit</button>
                                                                   <?php } ?>
                                                        <button type="button" name="btnCancel" class="btn btn-default btn-animate-demo" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL); ?>';">Cancel</button>
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
                                            <h4 ><i class="fa fa-bars"></i> FAQs</h4>
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
                                                            <th class="visible-xs visible-sm visible-md visible-lg">Question</th>
                                                            <th class="visible-xs visible-sm visible-md visible-lg">Answer</th>
                                                            <th class="visible-xs visible-sm visible-md visible-lg" width="70">Order By </th>
                                                            <th class="visible-xs visible-sm visible-md visible-lg">Status</th>
                                                            <th width="50">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                            <?php
                                                            $Query = "SELECT * FROM faqs ORDER BY faq_orderby ASC";
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
                                                                    <td class="visible-xs visible-sm visible-md visible-lg"><input type="checkbox" name="chkstatus[]" value="<?php print($row->faq_id); ?>" /></td>
                                                                    <td class="visible-xs visible-sm visible-md visible-lg"><?php print($row->faq_question); ?> </td>
                                                                    <td class="visible-xs visible-sm visible-md visible-lg"><?php print($row->faq_answer); ?> </td>
                                                                    <td class="visible-xs visible-sm visible-md visible-lg">
                                                                    <input type="hidden" name="faq_id[]"  value="<?php print($row->faq_id);?>">
                                                                    <input type="number" name="faq_orderby[]" style="width: 80px;" min="0" onkeypress="return event.charCode >= 48" autocomplete="off"  value="<?php print($row->faq_orderby);?>">
                                                                    </td>
                                                                    <td class="visible-lg">
                                                                        <?php
                                                                        if ($row->faq_status == 0) {
                                                                            echo "<span class='btn btn-xs btn-warning btn-animate-demo padMsgs'> In Active </span>";
                                                                        } else    {
                                                                            echo "<span class='btn btn-xs btn-success btn-animate-demo padMsgs'> Active </span>";
                                                                        }

                                                                        ?>
                                                                    </td>
                                                                    <td>
                                                                        <button type="button" class="btn btn-xs btn-warning" title="Edit" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?action=2&" . $qryStrURL . "faq_id=" . $row->faq_id); ?>';"><i class="fa fa-edit"></i></button>
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
                                                                    $pageList = $p->pageList($_GET['page'], $pages, '&' . rmPageFromURL($qryStrURL));
                                                                    print($pageList);
                                                                    ?>
                                                                </ul>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                <?php } ?>
                                                <?php if ($counter > 0) { ?>
                                                    <input type="submit" name="active" value="Active" class="btn btn-success btn-animate-demo">
                                                    <input type="submit" name="Inactive" value="In Active" class="btn btn-warning btn-animate-demo">
                                                    <input type="submit" name="btnUpdateOrder" value="Update" class="btn btn-primary btn-animate-demo">
                                                    <input type="submit" name="btnDelete" onclick="return confirm('Are you sure you want to delete selected item(s)?');" value="Delete" class="btn btn-danger btn-animate-demo">
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

