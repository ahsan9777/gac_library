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

    if (isset($_REQUEST['page']) && $_REQUEST['page'] > 0) {
        $qryStrURL = "page=" . $_REQUEST['page'] . "&";
    } else {
        $qryStrURL = "";
    }

    if (isset($_REQUEST['auth_type']) && $_REQUEST['auth_type'] < 2) {

        $qryStrURL .= "auth_type=" . $_REQUEST['auth_type'] . "&";
    }

    if (isset($_REQUEST['btnAdd'])) {
        $auth_id = getMaximum("author", "auth_id");
        
        mysqli_query($GLOBALS['conn'], "INSERT INTO author (auth_id, auth_name) VALUES (" . $auth_id . ",'" . dbStr(trim($_REQUEST['auth_name'])) . "')") or die(mysqli_error($GLOBALS['conn']));
        header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=1");
    } elseif (isset($_REQUEST['btnUpdate'])) {
        
         mysqli_query($GLOBALS['conn'], "UPDATE author SET auth_name='" . dbStr(trim($_REQUEST['auth_name'])) . "' WHERE auth_id=" . $_REQUEST['auth_id']);
        header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=2");
    } elseif (isset($_REQUEST['action'])) {
        if ($_REQUEST['action'] == 2) {
            $rsM = mysqli_query($GLOBALS['conn'], "SELECT * FROM author WHERE auth_id = " . $_REQUEST['auth_id']);
            if (mysqli_num_rows($rsM) > 0) {
                $rsMem = mysqli_fetch_object($rsM);
                $auth_name = $rsMem->auth_name;
                $formHead = "Update Info";
            }
        } else {
            $auth_name = "";
            $formHead = "Add New";
        }
    }

//--------------Button Active--------------------
    if (isset($_REQUEST['btnActive'])) {
        if (isset($_REQUEST['chkstatus'])) {
            for ($i = 0; $i < count($_REQUEST['chkstatus']); $i++) {
                mysqli_query($GLOBALS['conn'], "UPDATE author SET auth_status='1' WHERE auth_id = " . $_REQUEST['chkstatus'][$i]);
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
                mysqli_query($GLOBALS['conn'], "UPDATE author SET auth_status='0' WHERE auth_id = " . $_REQUEST['chkstatus'][$i]);
            }
            $class = "alert alert-success";
            $strMSG = "Record(s) updated successfully";
        } else {
            $class = "alert alert-info";
            $strMSG = "Please Select Alteast One Checkbox";
        }
    }
//--------------Button Update order--------------------
    if (isset($_REQUEST['btnUpdateOrder'])) {
    for ($i = 0; $i < count($_REQUEST['auth_id']); $i++) {
        $auth_id  = $_REQUEST['auth_id'][$i];
        mysqli_query($GLOBALS['conn'], "UPDATE author SET sub_orderby = " . $_REQUEST['sub_orderby'][$i] . " WHERE auth_id  = " . $auth_id ) or die(mysqli_error($GLOBALS['conn']));

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
                                        <li>Author Management</li>
                                    </ul>
                                    <!-- /BREADCRUMBS -->
                                    <div class="clearfix">
                                        <h3 class="content-title pull-left">Author Management </h3>
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
                                                    <label  class="col-lg-2 col-md-2 control-label">Title</label>
                                                    <div class="col-lg-4 col-md-4">
                                                        <input type="text" class="form-control form-cascade-control required" name="auth_name" id="auth_name" value="<?php print($auth_name); ?>" placeholder="Title">
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
                                                        <button type="button" name="btnCancel" class="btn btn-default btn-animate-demo" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?" . $qryStr); ?>';">Cancel</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } else {
                            $auth_type = 2;
                            $auth_id = 0;
                            $auth_title = "";
                            $searchQuery = "WHERE 1 = 1";
                            if (isset($_REQUEST['auth_type']) && $_REQUEST['auth_type'] < 2) {

                                $auth_type = $_REQUEST['auth_type'];
                                $searchQuery .= " AND auth_type = '" . $_REQUEST['auth_type'] . "'";
                            }
                            if (isset($_REQUEST['auth_id']) && $_REQUEST['auth_id'] > 0) {
                                if (!empty($_REQUEST['auth_title'])) {
                                    $auth_id = $_REQUEST['auth_id'];
                                    $auth_title = $_REQUEST['auth_title'];
                                    $searchQuery .= " AND auth_id =" . $_REQUEST['auth_id'];
                                }
                            }
                            ?>
                            <div class="row">
                                <div class="col-md-12" style="margin-bottom: 12px;">
                                    <form name="frmCat" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?"); ?>">
                                        <div class="form-group">
                                                <!--<label for="inputEmail1" class="col-lg-1 col-md-1 control-label padTop7" style="text-align: right;">Publishers: </label>-->
                                                <div class="col-lg-3 col-md-3">
                                                    <label for="inputEmail1" class=" control-label " style="text-align: right;">Author Type: </label>
                                                    <select  name="auth_type" id="auth_type" class="form-control"  onchange="javascript: frmCat.submit();">
                                                        <option value="" <?php echo (($auth_type == 2)?'selected':'');?>>N/A</option>
                                                        <option value="0" <?php echo (($auth_type == 0)?'selected':'');?>>Main Author</option>
                                                        <option value="1" <?php echo (($auth_type == 1)?'selected':'');?>>Sub Author</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-3 col-md-3">
                                                <label for="inputEmail1" class=" control-label " style="text-align: right;">Author: </label>
                                                <input type="hidden" name="auth_id" id="auth_id" value="<?php print($auth_id); ?>">
                                                <input type="text" name="auth_title" id="auth_title" value="<?php print($auth_title); ?>" class="form-control form-cascade-control search_author" autocomplete="off" onchange="javascript: frmCat.submit();">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="box border blue">
                                        <div class="box-title">
                                            <h4 ><i class="fa fa-bars"></i> Author</h4>
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
                                                            <th class="visible-xs visible-sm visible-md visible-lg" width="40"><input type="checkbox" name="chkAll" onClick="setAll();"></th>
                                                            <th class="visible-xs visible-sm visible-md visible-lg" >Author</th>
                                                            <th class="visible-xs visible-sm visible-md visible-lg" width = "100" >Type</th>
                                                            <th width="70">Status</th>
                                                            <th width="50">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                            <?php
                                                            $Query = "SELECT * FROM author ".$searchQuery." ORDER BY auth_type ASC";
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
                                                                    <td class="visible-xs visible-sm visible-md visible-lg"><input type="checkbox" name="chkstatus[]" value="<?php print($row->auth_id); ?>" /></td>
                                                                    <td class="visible-xs visible-sm visible-md visible-lg"><?php print($row->auth_name); ?> </td>
                                                                    <td class="visible-xs visible-sm visible-md visible-lg"><?php print((($row->auth_type == 1)?'Sub Author':'Main Author')); ?> </td>
                                                                    <td class="visible-lg">
                                                                        <?php
                                                                        if ($row->auth_status == 0) {
                                                                            echo "<span class='btn btn-xs btn-warning btn-animate-demo padMsgs'> In Active </span>";
                                                                        } else {
                                                                            echo "<span class='btn btn-xs btn-success btn-animate-demo padMsgs'> Active </span>";
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <td>
                                                                        <button type="button" class="btn btn-xs btn-warning" title="Edit" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?action=2&" . $qryStrURL . "auth_id=" . $row->auth_id); ?>';"><i class="fa fa-edit"></i></button>
                                                                    </td>
                                                                </tr>
                                                                            <?php
                                                                        }
                                                                    } else {
                                                                        print('<tr><td colspan="100%" align="center">No record found!</td></tr>');
                                                                    }
                                                                    ?>

                                                    </tbody>
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
                                                    <input type="submit" name="btnActive" value="Active" class="btn btn-success btn-animate-demo">
                                                    <input type="submit" name="btnInactive" value="In Active" class="btn btn-warning btn-animate-demo">
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
<script>
    $('input.search_author').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: 'ajax_calls.php?action=search_author&auth_type=<?php print($auth_type); ?>',
                dataType: "json",
                data: {
                    term: request.term
                },
                success: function(data) {
                    response(data);

                }
            });
        },
        minLength: 1,
        select: function(event, ui) {
            var auth_id = $("#auth_id");
            var auth_title = $("#auth_title");
            $(auth_id).val(ui.item.auth_id);
            $(auth_title).val(ui.item.value);
            frmCat.submit();
            //return false;
            //console.log( "Selected: " + ui.item.value + " aka " + ui.item.id );
        }
    });
</script>
</html>

