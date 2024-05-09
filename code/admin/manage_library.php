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

    if (isset($_REQUEST['auth_id']) && $_REQUEST['auth_id'] > 0) {
        if (!empty($_REQUEST['auth_title'])) {

            $qryStrURL .= "auth_id=" . $_REQUEST['auth_id'] . "&";
            $qryStrURL .= "auth_title=" . urlencode($_REQUEST['auth_title']) . "&";
        }
    }
    if (isset($_REQUEST['sub_auth_id']) && $_REQUEST['sub_auth_id'] > 0) {
        if (!empty($_REQUEST['sub_auth_title'])) {

            $qryStrURL .= "sub_auth_id=" . $_REQUEST['sub_auth_id'] . "&";
            $qryStrURL .= "sub_auth_title=" . urlencode($_REQUEST['sub_auth_title']) . "&";
        }
    }
    if (isset($_REQUEST['pub_id']) && $_REQUEST['pub_id'] > 0) {
        if (!empty($_REQUEST['pub_name'])) {

            $qryStrURL .= "pub_id=" . $_REQUEST['pub_id'] . "&";
            $qryStrURL .= "pub_name=" . urlencode($_REQUEST['pub_name']) . "&";
        }
    }
    if (isset($_REQUEST['sub_id']) && $_REQUEST['sub_id'] > 0) {
        if (!empty($_REQUEST['sub_title'])) {

            $qryStrURL .= "sub_id=" . $_REQUEST['sub_id'] . "&";
            $qryStrURL .= "sub_title=" . urlencode($_REQUEST['sub_title']) . "&";
        }
    }
    if (isset($_REQUEST['lb_title']) && !empty($_REQUEST['lb_title'])) {

        $qryStrURL .= "lb_title=" . urlencode($_REQUEST['lb_title']) . "&";
    }

    if (isset($_REQUEST['btnAdd'])) {
        $lb_id = getMaximum("library_books", "lb_id");
        
        mysqli_query($GLOBALS['conn'], "INSERT INTO library_books (lb_id, sub_id, auth_id, sub_auth_id, pub_id, lb_title, lb_subtitle, lb_accno, lb_dccno, lb_entrydate, lb_price, lb_place, lb_year, lb_source, lb_edition, lb_volume, lb_page, lb_series, lb_language, lb_isbn, lb_note) VALUES (" . $lb_id . ",'" . dbStr(trim($_REQUEST['sub_id'])) . "', '" . dbStr(trim($_REQUEST['auth_id'])) . "', '".dbStr(trim($_REQUEST['sub_auth_id']))."', '".dbStr(trim($_REQUEST['pub_id']))."', '".dbStr(trim($_REQUEST['lb_title']))."', '".dbStr(trim($_REQUEST['lb_subtitle']))."', '".dbStr(trim($_REQUEST['lb_accno']))."', '".dbStr(trim($_REQUEST['lb_dccno']))."', '".dbStr(trim($_REQUEST['lb_entrydate']))."', '".dbStr(trim($_REQUEST['lb_price']))."', '".dbStr(trim($_REQUEST['lb_place']))."', '".dbStr(trim($_REQUEST['lb_year']))."', '".dbStr(trim($_REQUEST['lb_source']))."', '".dbStr(trim($_REQUEST['lb_edition']))."', '".dbStr(trim($_REQUEST['lb_volume']))."', '".dbStr(trim($_REQUEST['lb_page']))."', '".dbStr(trim($_REQUEST['lb_series']))."', '".dbStr(trim($_REQUEST['lb_language']))."', '".dbStr(trim($_REQUEST['lb_isbn']))."', '".dbStr(trim($_REQUEST['lb_note']))."')") or die(mysqli_error($GLOBALS['conn']));
        header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=1");
    } elseif (isset($_REQUEST['btnUpdate'])) {
        
        mysqli_query($GLOBALS['conn'], "UPDATE library_books SET sub_id='" . dbStr(trim($_REQUEST['sub_id'])) . "', auth_id = '" . dbStr(trim($_REQUEST['auth_id'])) . "', sub_auth_id='".dbStr(trim($_REQUEST['sub_auth_id']))."', pub_id = '".dbStr(trim($_REQUEST['pub_id']))."', lb_title = '".dbStr(trim($_REQUEST['lb_title']))."', lb_subtitle = '".dbStr(trim($_REQUEST['lb_subtitle']))."', lb_accno = '".dbStr(trim($_REQUEST['lb_accno']))."', lb_dccno = '".dbStr(trim($_REQUEST['lb_dccno']))."', lb_entrydate = '".dbStr(trim($_REQUEST['lb_entrydate']))."', lb_price = '".dbStr(trim($_REQUEST['lb_price']))."', lb_place = '".dbStr(trim($_REQUEST['lb_place']))."', lb_year = '".dbStr(trim($_REQUEST['lb_year']))."', lb_source = '".dbStr(trim($_REQUEST['lb_source']))."', lb_edition = '".dbStr(trim($_REQUEST['lb_edition']))."', lb_volume = '".dbStr(trim($_REQUEST['lb_volume']))."', lb_page = '".dbStr(trim($_REQUEST['lb_page']))."', lb_series = '".dbStr(trim($_REQUEST['lb_series']))."', lb_language = '".dbStr(trim($_REQUEST['lb_language']))."', lb_isbn = '".dbStr(trim($_REQUEST['lb_isbn']))."', lb_note = '".dbStr(trim($_REQUEST['lb_note']))."' WHERE lb_id=" . $_REQUEST['lb_id']) or die(mysqli_error($GLOBALS['conn']));
        header("Location: " . $_SERVER['PHP_SELF'] . "?" . $qryStrURL . "op=2");
    } elseif (isset($_REQUEST['btnImport_books'])) {
        //print_r($_REQUEST);die();
        $file = $_FILES['mFile_excel']['tmp_name'];
        $ext = pathinfo($_FILES['mFile_excel']['name'], PATHINFO_EXTENSION);
        if (in_array($ext, array("xlsx", "xls")) && !empty($file)) {
            // echo "xlsx = ".$ext;die();
            require('PHPExcel/PHPExcel.php');
            require('PHPExcel/PHPExcel/IOFactory.php');

            $obj = PHPExcel_IOFactory::load($file);
            foreach ($obj->getWorksheetIterator() as $sheet) {
                $getHighestRow = $sheet->getHighestRow();
                //print_r($getHighestRow);die();
                for ($i = 2; $i <= $getHighestRow; $i++) {
                    $sub_id = 0;
                    $auth_id = 0;
                    $sub_auth_id = 0;
                    $pub_id = 0;
                    $subject  = dbStr(trim($sheet->getCellByColumnAndRow(0, $i)->getValue()));
                    $sub_id = datacheck("sub_id", "sub_title", $subject, "subject", 0);
                    $lb_title  = dbStr(trim($sheet->getCellByColumnAndRow(1, $i)->getValue()));
                    $lb_subtitle  = dbStr(trim($sheet->getCellByColumnAndRow(2, $i)->getValue()));
                    $author  = dbStr(trim($sheet->getCellByColumnAndRow(3, $i)->getValue()));
                    $auth_id = datacheck("auth_id", "auth_name", $author, "author", 0);
                    $sub_author  = dbStr(trim($sheet->getCellByColumnAndRow(4, $i)->getValue()));
                    $sub_auth_id = datacheck("auth_id", "auth_name", $sub_author, "author", 1);
                    $lb_accno  = dbStr(trim($sheet->getCellByColumnAndRow(5, $i)->getValue()));
                    $lb_dccno  = dbStr(trim($sheet->getCellByColumnAndRow(6, $i)->getValue()));
                    $lb_entrydate  = dbStr(trim($sheet->getCellByColumnAndRow(7, $i)->getValue()));
                    $lb_price  = dbStr(trim($sheet->getCellByColumnAndRow(8, $i)->getValue()));
                    $publisher  = dbStr(trim($sheet->getCellByColumnAndRow(9, $i)->getValue()));
                    $pub_id = datacheck("pub_id", "pub_name", $publisher, "publisher", 0);
                    $lb_place  = dbStr(trim($sheet->getCellByColumnAndRow(10, $i)->getValue()));
                    $lb_year  = dbStr(trim($sheet->getCellByColumnAndRow(11, $i)->getValue()));
                    $lb_source  = dbStr(trim($sheet->getCellByColumnAndRow(12, $i)->getValue()));
                    $lb_edition  = dbStr(trim($sheet->getCellByColumnAndRow(13, $i)->getValue()));
                    $lb_volume  = dbStr(trim($sheet->getCellByColumnAndRow(14, $i)->getValue()));
                    $lb_page  = dbStr(trim($sheet->getCellByColumnAndRow(15, $i)->getValue()));
                    $lb_series  = dbStr(trim($sheet->getCellByColumnAndRow(16, $i)->getValue()));
                    $lb_language  = dbStr(trim($sheet->getCellByColumnAndRow(17, $i)->getValue()));
                    $lb_isbn  = dbStr(trim($sheet->getCellByColumnAndRow(18, $i)->getValue()));
                    $lb_note  = dbStr(trim($sheet->getCellByColumnAndRow(19, $i)->getValue()));
                    //print($lb_dccno);die();

                    if (!empty($lb_title) && !empty($lb_accno)) {
                        $Query = "SELECT * FROM library_books WHERE lb_title = '" . $lb_title . "' AND lb_accno = '" . $lb_accno . "' ";
                        $rs = mysqli_query($GLOBALS['conn'], $Query);
                        if (mysqli_num_rows($rs) == 0) {
                            $lb_id = getMaximum("library_books", "lb_id");
                            mysqli_query($GLOBALS['conn'], "INSERT INTO library_books (lb_id, sub_id, auth_id, sub_auth_id, pub_id, lb_title, lb_subtitle, lb_accno, lb_dccno, lb_entrydate, lb_price, lb_place, lb_year, lb_source, lb_edition, lb_volume, lb_page, lb_series, lb_language, lb_isbn, lb_note, lb_importby, lb_addedby, lb_cdate) VALUES ('" . $lb_id . "', '" . $sub_id . "', '" . $auth_id . "', '" . $sub_auth_id . "', '" . $pub_id . "', '" . $lb_title . "', '" . $lb_subtitle . "', '" . $lb_accno . "', '" . $lb_dccno . "', '" . $lb_entrydate . "', '" . $lb_price . "', '" . $lb_place . "', '" . $lb_year . "', '" . $lb_source . "', '" . $lb_edition . "', '" . $lb_volume . "', '" . $lb_page . "', '" . $lb_series . "', '" . $lb_language . "', '" . $lb_isbn . "', '" . $lb_note . "', '1', '" . $_SESSION["UserID"] . "', '" . date_time . "')") or die(mysqli_error($GLOBALS['conn']));
                        }
                    }
                }
            }
            header("Location: " . $_SERVER['PHP_SELF'] . "?file&" . $qryStrURL . "op=1");
            //print("<br>Completed");die();
        } elseif (empty($file)) {
            $class = "alert alert-info";
            $strMSG = "Please Select the file";
        } else {
            $class = "alert alert-danger";
            $strMSG = "Plz select the correct file ";
        }
    } elseif (isset($_REQUEST['action'])) {
        if ($_REQUEST['action'] == 2) {
            $rsM = mysqli_query($GLOBALS['conn'], "SELECT lb.*, sub.sub_title, auth.auth_name, subauth.auth_name AS subauthor_name, pub.pub_name FROM library_books AS lb LEFT OUTER JOIN subject AS sub ON sub.sub_id = lb.sub_id LEFT OUTER JOIN author AS  auth ON auth.auth_id = lb.auth_id LEFT OUTER JOIN author AS  subauth ON subauth.auth_id = lb.sub_auth_id LEFT OUTER JOIN publisher AS pub ON pub.pub_id = lb.pub_id WHERE lb.lb_id = " . $_REQUEST['lb_id']);
            if (mysqli_num_rows($rsM) > 0) {
                $rsMem = mysqli_fetch_object($rsM);
                $sub_id = $rsMem->sub_id;
                $sub_title = $rsMem->sub_title;
                $auth_id = $rsMem->auth_id;
                $auth_name = $rsMem->auth_name;
                $sub_auth_id = $rsMem->sub_auth_id;
                $subauthor_name = $rsMem->subauthor_name;
                $pub_id = $rsMem->pub_id;
                $pub_name = $rsMem->pub_name;
                $lb_title = $rsMem->lb_title;
                $lb_subtitle = $rsMem->lb_subtitle;
                $lb_accno = $rsMem->lb_accno;
                $lb_dccno = $rsMem->lb_dccno;
                $lb_entrydate = $rsMem->lb_entrydate;
                $lb_price = $rsMem->lb_price;
                $lb_place = $rsMem->lb_place;
                $lb_year = $rsMem->lb_year;
                $lb_source = $rsMem->lb_source;
                $lb_edition = $rsMem->lb_edition;
                $lb_volume = $rsMem->lb_volume;
                $lb_page = $rsMem->lb_page;
                $lb_series = $rsMem->lb_series;
                $lb_language = $rsMem->lb_language;
                $lb_isbn = $rsMem->lb_isbn;
                $lb_note = $rsMem->lb_note;
                //$formHead = "Update Info";
                $formHead = "View Detail";
            }
        } else {
            $sub_id = "";
            $sub_title = "";
            $auth_id = "";
            $auth_name = "";
            $sub_auth_id = "";
            $subauthor_name = "";
            $pub_id = "";
            $pub_name = "";
            $lb_title = "";
            $lb_subtitle = "";
            $lb_accno = "";
            $lb_dccno = "";
            $lb_entrydate = "";
            $lb_price = "";
            $lb_place = "";
            $lb_year = "";
            $lb_source = "";
            $lb_edition = "";
            $lb_volume = "";
            $lb_page = "";
            $lb_series = "";
            $lb_language = "";
            $lb_isbn = "";
            $lb_note = "";
            $formHead = "Add New";
        }
    }

    //--------------Button Active--------------------
    if (isset($_REQUEST['btnActive'])) {
        if (isset($_REQUEST['chkstatus'])) {
            for ($i = 0; $i < count($_REQUEST['chkstatus']); $i++) {
                mysqli_query($GLOBALS['conn'], "UPDATE library_books SET lb_status = '1' WHERE lb_id = " . $_REQUEST['chkstatus'][$i]);
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
                mysqli_query($GLOBALS['conn'], "UPDATE library_books SET lb_status = '0' WHERE lb_id = " . $_REQUEST['chkstatus'][$i]);
            }
            $class = "alert alert-success";
            $strMSG = "Record(s) updated successfully";
        } else {
            $class = "alert alert-info";
            $strMSG = "Please Select Alteast One Checkbox";
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
                                        <li>Library Management</li>
                                    </ul>
                                    <!-- /BREADCRUMBS -->
                                    <div class="clearfix">
                                        <h3 class="content-title pull-left">Library Management </h3>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- /PAGE HEADER -->

                        <!-- DASHBOARD CONTENT -->
                        <?php if ($class != "") { ?>
                            <div class="<?php print($class); ?>"><?php print($strMSG); ?><a class="close" data-dismiss="alert">×</a></div>
                        <?php } ?>

                        <?php if (isset($_REQUEST['action']) && in_array($_REQUEST['action'], array(1, 2))) { ?>
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
                                                    <label class="col-lg-2 col-md-2 control-label">Title</label>
                                                    <div class="col-lg-9 col-md-9">
                                                        <input type="text" class="form-control form-cascade-control required" name="lb_title" id="lb_title" value="<?php print($lb_title); ?>" placeholder="Title">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-lg-2 col-md-2 control-label">Sub Title</label>
                                                    <div class="col-lg-9 col-md-9">
                                                        <input type="text" class="form-control form-cascade-control required" name="lb_subtitle" id="lb_subtitle" value="<?php print($lb_subtitle); ?>" placeholder="Sub Subject">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-lg-2 col-md-2 control-label">Subject</label>
                                                    <div class="col-lg-9 col-md-9">
                                                        <input type="hidden" name="sub_id" id="sub_id" value="<?php print($sub_id); ?>">
                                                        <input type="text" class="form-control form-cascade-control subject required" name="sub_title" id="sub_title" value="<?php print($sub_title); ?>" placeholder="Subject">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-lg-2 col-md-2 control-label">Author</label>
                                                    <div class="col-lg-4 col-md-4">
                                                        <input type="hidden" name="auth_id" id="auth_id" value="<?php print($auth_id); ?>">
                                                        <input type="text" class="form-control form-cascade-control required author" name="auth_name" id="auth_name" value="<?php print($auth_name); ?>" placeholder="Author">
                                                    </div>
                                                    <label class="col-lg-1 col-md-1 control-label">Subauthor</label>
                                                    <div class="col-lg-4 col-md-4">
                                                        <input type="hidden" name="sub_auth_id" id="sub_auth_id" value="<?php print($sub_auth_id); ?>">
                                                        <input type="text" class="form-control form-cascade-control subauthor" name="subauthor_name" id="subauthor_name" value="<?php print($subauthor_name); ?>" placeholder="Subauthor">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-lg-2 col-md-2 control-label">Acc No</label>
                                                    <div class="col-lg-4 col-md-4">
                                                        <input type="text" class="form-control form-cascade-control required" name="lb_accno" id="lb_accno" value="<?php print($lb_accno); ?>" placeholder="Acc No">
                                                    </div>
                                                    <label class="col-lg-1 col-md-1 control-label">DDC No</label>
                                                    <div class="col-lg-4 col-md-4">
                                                        <input type="text" class="form-control form-cascade-control required" name="lb_dccno" id="lb_dccno" value="<?php print($lb_dccno); ?>" placeholder="DDC No">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-lg-2 col-md-2 control-label">Entry Date</label>
                                                    <div class="col-lg-4 col-md-4">
                                                        <input type="date" class="form-control form-cascade-control required" name="lb_entrydate" id="lb_entrydate" value="<?php print($lb_entrydate); ?>" placeholder="Entry Date">
                                                    </div>
                                                    <label class="col-lg-1 col-md-1 control-label">Price</label>
                                                    <div class="col-lg-4 col-md-4">
                                                        <input type="text" class="form-control form-cascade-control required" name="lb_price" id="lb_price" value="<?php print($lb_price); ?>" placeholder="Price">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-lg-2 col-md-2 control-label">Publisher</label>
                                                    <div class="col-lg-4 col-md-4">
                                                        <input type="hidden" name="pub_id" id="pub_id" value="<?php print($pub_id); ?>">
                                                        <input type="text" class="form-control form-cascade-control publisher required" name="pub_name" id="pub_name" value="<?php print($pub_name); ?>" placeholder="Publisher">
                                                    </div>
                                                    <label class="col-lg-1 col-md-1 control-label">Place</label>
                                                    <div class="col-lg-4 col-md-4">
                                                        <input type="text" class="form-control form-cascade-control required" name="lb_place" id="lb_place" value="<?php print($lb_place); ?>" placeholder="Place">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-lg-2 col-md-2 control-label">Year</label>
                                                    <div class="col-lg-4 col-md-4">
                                                        <input type="number" maxlength="4" class="form-control form-cascade-control required" name="lb_year" id="lb_year" value="<?php print($lb_year); ?>" placeholder="Year">
                                                    </div>
                                                    <label class="col-lg-1 col-md-1 control-label">Source</label>
                                                    <div class="col-lg-4 col-md-4">
                                                        <input type="text" class="form-control form-cascade-control required" name="lb_source" id="lb_source" value="<?php print($lb_source); ?>" placeholder="Source">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-lg-2 col-md-2 control-label">Edition</label>
                                                    <div class="col-lg-4 col-md-4">
                                                        <input type="text" class="form-control form-cascade-control required" name="lb_edition" id="lb_edition" value="<?php print($lb_edition); ?>" placeholder="Edition">
                                                    </div>
                                                    <label class="col-lg-1 col-md-1 control-label">Volume</label>
                                                    <div class="col-lg-4 col-md-4">
                                                        <input type="text" class="form-control form-cascade-control required" name="lb_volume" id="lb_volume" value="<?php print($lb_volume); ?>" placeholder="Volume">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-lg-2 col-md-2 control-label">Page</label>
                                                    <div class="col-lg-4 col-md-4">
                                                        <input type="number" class="form-control form-cascade-control required" name="lb_page" id="lb_page" value="<?php print($lb_page); ?>" placeholder="Page">
                                                    </div>
                                                    <label class="col-lg-1 col-md-1 control-label">Series</label>
                                                    <div class="col-lg-4 col-md-4">
                                                        <input type="text" class="form-control form-cascade-control required" name="lb_series" id="lb_series" value="<?php print($lb_series); ?>" placeholder="Series">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-lg-2 col-md-2 control-label">Language</label>
                                                    <div class="col-lg-4 col-md-4">
                                                        <input type="text" class="form-control form-cascade-control required" name="lb_language" id="lb_language" value="<?php print($lb_language); ?>" placeholder="Language">
                                                    </div>
                                                    <label class="col-lg-1 col-md-1 control-label">ISBN</label>
                                                    <div class="col-lg-4 col-md-4">
                                                        <input type="text" class="form-control form-cascade-control required" name="lb_isbn" id="lb_isbn" value="<?php print($lb_isbn); ?>" placeholder="ISBN">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-lg-2 col-md-2 control-label">Note</label>
                                                    <div class="col-lg-9 col-md-9">
                                                        <textarea type="text" class="form-control form-cascade-control required" rows="5" name="lb_note" id="lb_note" placeholder="Note"><?php print($lb_note); ?></textarea>
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
                        <?php } elseif (isset($_REQUEST['action']) && $_REQUEST['action'] == 3) { ?>
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
                                                    <label for="cnt_pcode" class="col-lg-2 col-md-3 control-label" style="padding-top:0px;">Excel Sheet</label>
                                                    <div class="col-lg-2">
                                                        <input type="file" name="mFile_excel" style="float:left !important" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputEmail1" class="col-lg-2 col-md-3 control-label">&nbsp;</label>
                                                    <div class="col-lg-10 col-md-9">

                                                        <button type="submit" name="btnImport_books" class="btn btn-primary btn-animate-demo">Submit</button>
                                                        <button type="button" name="btnCancel" class="btn btn-default btn-animate-demo" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?" . $qryStr); ?>';">Cancel</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php } else {

                            $auth_id = 0;
                            $auth_title = "";
                            $sub_auth_id = 0;
                            $sub_auth_title = "";
                            $pub_id = 0;
                            $pub_name = "";
                            $sub_id = 0;
                            $sub_title = "";
                            $lb_id = 0;
                            $lb_title = "";
                            $searchQuery = "WHERE 1 = 1";

                            if (isset($_REQUEST['auth_id']) && $_REQUEST['auth_id'] > 0) {
                                if (!empty($_REQUEST['auth_title'])) {
                                    $auth_id = $_REQUEST['auth_id'];
                                    $auth_title = $_REQUEST['auth_title'];
                                    $searchQuery .= " AND lb.auth_id =" . $_REQUEST['auth_id'];
                                }
                            }
                            if (isset($_REQUEST['sub_auth_id']) && $_REQUEST['sub_auth_id'] > 0) {
                                if (!empty($_REQUEST['sub_auth_title'])) {
                                    $sub_auth_id = $_REQUEST['sub_auth_id'];
                                    $sub_auth_title = $_REQUEST['sub_auth_title'];
                                    $searchQuery .= " AND lb.sub_auth_id =" . $_REQUEST['sub_auth_id'];
                                }
                            }
                            if (isset($_REQUEST['pub_id']) && $_REQUEST['pub_id'] > 0) {
                                if (!empty($_REQUEST['pub_name'])) {
                                    $pub_id = $_REQUEST['pub_id'];
                                    $pub_name = $_REQUEST['pub_name'];
                                    $searchQuery .= " AND lb.pub_id =" . $_REQUEST['pub_id'];
                                }
                            }
                            if (isset($_REQUEST['sub_id']) && $_REQUEST['sub_id'] > 0) {
                                if (!empty($_REQUEST['sub_title'])) {
                                    $auth_id = $_REQUEST['sub_id'];
                                    $sub_title = $_REQUEST['sub_title'];
                                    $searchQuery .= " AND lb.sub_id =" . $_REQUEST['sub_id'];
                                }
                            }
                            if (isset($_REQUEST['lb_title']) && !empty($_REQUEST['lb_title'])) {

                                $lb_title = $_REQUEST['lb_title'];
                                $searchQuery .= " AND lb.lb_title LIKE '%" . $_REQUEST['lb_title'] . "%'";
                            }

                        ?>
                            <div class="row">
                                <div class="col-md-12" style="margin-bottom: 12px;">
                                    <form name="frmCat" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?"); ?>">
                                        <div class="form-group">
                                            <!--<label for="inputEmail1" class="col-lg-1 col-md-1 control-label padTop7" style="text-align: right;">Publishers: </label>-->
                                            <div class="col-lg-4 col-md-4">
                                                <label for="inputEmail1" class=" control-label " style="text-align: right;">Author: </label>
                                                <input type="hidden" name="auth_id" id="search_auth_id" value="<?php print($auth_id); ?>">
                                                <input type="text" name="auth_title" id="search_auth_title" value="<?php print($auth_title); ?>" class="form-control form-cascade-control search_author" autocomplete="off" onchange="javascript: frmCat.submit();">
                                            </div>
                                            <div class="col-lg-4 col-md-4">
                                                <label for="inputEmail1" class=" control-label " style="text-align: right;">Sub Author: </label>
                                                <input type="hidden" name="sub_auth_id" id="search_sub_auth_id" value="<?php print($sub_auth_id); ?>">
                                                <input type="text" name="sub_auth_title" id="search_sub_auth_title" value="<?php print($sub_auth_title); ?>" class="form-control form-cascade-control search_sub_author" autocomplete="off" onchange="javascript: frmCat.submit();">
                                            </div>
                                            <div class="col-lg-4 col-md-4">
                                                <label for="inputEmail1" class=" control-label " style="text-align: right;">Publisher: </label>
                                                <input type="hidden" name="pub_id" id="search_pub_id" value="<?php print($pub_id); ?>">
                                                <input type="text" name="pub_name" id="search_pub_name" value="<?php print($pub_name); ?>" class="form-control form-cascade-control search_publisher" autocomplete="off" onchange="javascript: frmCat.submit();">
                                            </div>
                                            <div class="col-lg-4 col-md-4">
                                                <label for="inputEmail1" class=" control-label " style="text-align: right;">Subject: </label>
                                                <input type="hidden" name="sub_id" id="search_sub_id" value="<?php print($sub_id); ?>">
                                                <input type="text" name="sub_title" id="search_sub_title" value="<?php print($sub_title); ?>" class="form-control form-cascade-control search_subject" autocomplete="off" onchange="javascript: frmCat.submit();">
                                            </div>
                                            <div class="col-lg-8 col-md-8">
                                                <label for="inputEmail1" class=" control-label " style="text-align: right;">Title: </label>
                                                <input type="text" name="lb_title" id="search_lb_title" value="<?php print($lb_title); ?>" class="form-control form-cascade-control search_title" autocomplete="off" onchange="javascript: frmCat.submit();">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="box border blue">
                                        <div class="box-title">
                                            <?php
                                            $Query = "SELECT lb.*, COUNT(*) OVER() AS TotalRecCount, sub.sub_title, auth.auth_name, subauth.auth_name AS subauthor_name, pub.pub_name FROM library_books AS lb LEFT OUTER JOIN subject AS sub ON sub.sub_id = lb.sub_id LEFT OUTER JOIN author AS  auth ON auth.auth_id = lb.auth_id LEFT OUTER JOIN author AS  subauth ON subauth.auth_id = lb.sub_auth_id LEFT OUTER JOIN publisher AS pub ON pub.pub_id = lb.pub_id " . $searchQuery . " ORDER BY lb.sub_id ASC ";
                                            //print($Query);
                                            $counter = 0;
                                            $limit = 25;
                                            $start = $p->findStart($limit);
                                            $rs = mysqli_query($GLOBALS['conn'], $Query . " LIMIT " . $start . ", " . $limit);
                                            $row = mysqli_fetch_object($rs);
                                            $count = $row->TotalRecCount;
                                            $pages = $p->findPages($count, $limit);

                                            ?>
                                            <h4><i class="fa fa-bars"></i> Library ( <strong> <?php print($count); ?> </strong> ) </h4>
                                            <div class="tools" style="color:white">

                                                <div>
                                                    <a href="<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL . "action=3"); ?>" title="Import Book"><i class="fa fa-upload"></i> Import Book</a>
                                                    <a href="<?php print($_SERVER['PHP_SELF'] . "?" . $qryStrURL . "action=1"); ?>" title="Add New"><i class="fa fa-plus"></i> Add New</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="box-body">
                                            <form name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" class="form-horizontal" role="form">
                                                <table class="table users-table table-condensed table-hover table-striped table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th class="visible-xs visible-sm visible-md visible-lg" width="40"><input type="checkbox" name="chkAll" onClick="setAll();"></th>
                                                            <th class="visible-xs visible-sm visible-md visible-lg">ACC NO </th>
                                                            <th class="visible-xs visible-sm visible-md visible-lg">Subject </th>
                                                            <th class="visible-xs visible-sm visible-md visible-lg">Author </th>
                                                            <th class="visible-xs visible-sm visible-md visible-lg">Publisher </th>
                                                            <th class="visible-xs visible-sm visible-md visible-lg">Title </th>
                                                            <th width="70">Status</th>
                                                            <th width="50">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php

                                                        if (mysqli_num_rows($rs) > 0) {
                                                            do {
                                                                $counter++;
                                                                $strClass = 'label  label-danger';
                                                                $author_name = "<strong>Author: </strong> " . $row->auth_name;
                                                                if (!empty($row->subauthor_name)) {

                                                                    $author_name .= "<br><strong>Sub Author: </strong> " . $row->subauthor_name;
                                                                }
                                                        ?>
                                                                <tr>
                                                                    <td class="visible-xs visible-sm visible-md visible-lg"><input type="checkbox" name="chkstatus[]" value="<?php print($row->lb_id); ?>" /></td>
                                                                    <td class="visible-xs visible-sm visible-md visible-lg"><?php print($row->lb_accno); ?> </td>
                                                                    <td class="visible-xs visible-sm visible-md visible-lg"><?php print($row->sub_title); ?> </td>
                                                                    <td class="visible-xs visible-sm visible-md visible-lg"><?php print($author_name); ?> </td>
                                                                    <td class="visible-xs visible-sm visible-md visible-lg"><?php print($row->pub_name); ?> </td>
                                                                    <td class="visible-xs visible-sm visible-md visible-lg"><?php print($row->lb_title); ?> </td>

                                                                    <td class="visible-lg">
                                                                        <?php
                                                                        if ($row->lb_status == 0) {
                                                                            echo "<span class='btn btn-xs btn-warning btn-animate-demo padMsgs'> In Active </span>";
                                                                        } else {
                                                                            echo "<span class='btn btn-xs btn-success btn-animate-demo padMsgs'> Active </span>";
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <td>
                                                                        <button type="button" class="btn btn-xs btn-warning" title="Edit" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?action=2&" . $qryStrURL . "lb_id=" . $row->lb_id); ?>';"><i class="fa fa-edit"></i></button>
                                                                        <!--<button type="button" class="btn btn-xs btn-primary" title="View Detail" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?action=2&" . $qryStrURL . "lb_id=" . $row->lb_id); ?>';"><i class="fa fa-eye"></i></button>-->
                                                                    </td>
                                                                </tr>
                                                        <?php
                                                            } while ($row = mysqli_fetch_object($rs));
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
                                                                        $pageList = $p->pageList($_GET['page'], $pages, '&');
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
    $('input.author').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: 'ajax_calls.php?action=search_author&auth_type=0',
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
            //frmCat.submit();
            //return false;
        }
    });

    $('input.sub_author').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: 'ajax_calls.php?action=search_author&auth_type=1',
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
            var sub_auth_id = $("#sub_auth_id");
            var sub_auth_title = $("#sub_auth_title");
            $(sub_auth_id).val(ui.item.auth_id);
            $(sub_auth_title).val(ui.item.value);
            //frmCat.submit();
            //return false;
        }
    });

    $('input.publisher').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: 'ajax_calls.php?action=publisher',
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
            var pub_id = $("#pub_id");
            var pub_name = $("#pub_name");
            $(pub_id).val(ui.item.pub_id);
            $(pub_name).val(ui.item.value);
            //frmCat.submit();
            //return false;
        }
    });

    $('input.subject').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: 'ajax_calls.php?action=search_subject',
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
            var sub_id = $("#sub_id");
            var sub_title = $("#sub_title");
            $(sub_id).val(ui.item.sub_id);
            $(sub_title).val(ui.item.value);
            //frmCat.submit();
            //return false;
        }
    });
</script>
<!-- Search filter  -->
<script>
    $('input.search_author').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: 'ajax_calls.php?action=search_author&auth_type=0',
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
            var auth_id = $("#search_auth_id");
            var auth_title = $("#search_auth_title");
            $(auth_id).val(ui.item.auth_id);
            $(auth_title).val(ui.item.value);
            frmCat.submit();
            //return false;
            //console.log( "Selected: " + ui.item.value + " aka " + ui.item.id );
        }
    });

    $('input.search_sub_author').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: 'ajax_calls.php?action=search_author&auth_type=1',
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
            var sub_auth_id = $("#search_sub_auth_id");
            var sub_auth_title = $("#search_sub_auth_title");
            $(sub_auth_id).val(ui.item.auth_id);
            $(sub_auth_title).val(ui.item.value);
            frmCat.submit();
            //return false;
            //console.log( "Selected: " + ui.item.value + " aka " + ui.item.id );
        }
    });

    $('input.search_publisher').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: 'ajax_calls.php?action=publisher',
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
            var pub_id = $("#search_pub_id");
            var pub_name = $("#search_pub_name");
            $(pub_id).val(ui.item.pub_id);
            $(pub_name).val(ui.item.value);
            frmCat.submit();
            //return false;
            //console.log( "Selected: " + ui.item.value + " aka " + ui.item.id );
        }
    });

    $('input.search_subject').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: 'ajax_calls.php?action=search_subject',
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
            var sub_id = $("#search_sub_id");
            var sub_title = $("#search_sub_title");
            $(sub_id).val(ui.item.sub_id);
            $(sub_title).val(ui.item.value);
            frmCat.submit();
            //return false;
            //console.log( "Selected: " + ui.item.value + " aka " + ui.item.id );
        }
    });

    $('input.search_title').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: 'ajax_calls.php?action=search_title',
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
            var lb_title = $("#search_lb_title");
            $(lb_title).val(ui.item.value);
            frmCat.submit();
            //return false;
            //console.log( "Selected: " + ui.item.value + " aka " + ui.item.id );
        }
    });
</script>

</html>