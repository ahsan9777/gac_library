<div class="row">
        <div class="col-md-12">
            <div class="box border blue">
                <div class="box-title">
                    <h4 ><i class="fa fa-bars"></i>Project</h4>
                </div>
                <div class="clearfix"></div>
                    <div class="box-body">
                        <form name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" class="form-horizontal" role="form">
                            <table class="table users-table table-condensed table-hover table-striped table-bordered" >
                                <thead>
                                    <tr>
                                        <!--<th class="visible-xs visible-sm visible-md visible-lg" width="30"><input type="checkbox" name="chkAll" onClick="setAll();"></th>-->
                                        <th class="visible-xs visible-sm visible-md visible-lg" width="70">Logo</th>
                                        <th class="visible-xs visible-sm visible-md visible-lg">Title</th>
                                        <th class="visible-xs visible-sm visible-md visible-lg">Location</th>
                                        <th class="visible-xs visible-sm visible-md visible-lg" width="50">Status</th>
                                        <th width="70">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        <?php
                                        $Query = "SELECT * FROM projects ORDER BY proj_id ASC";
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
                                                $image_path = $GLOBALS['siteURL']."files/no_img_1.jpg";
                                                if(!empty($row->proj_logo)){
                                                    $image_path = $GLOBALS['siteURL']."files/projects/".$row->proj_id."/th/".$row->proj_logo;
                                                }
                                                ?>
                                            <tr>
                                                <!--<td class="visible-xs visible-sm visible-md visible-lg"><input type="checkbox" name="chkstatus[]" value="<?php print($row->proj_id); ?>" /></td>-->
                                                <td class="visible-xs visible-sm visible-md visible-lg"><img src="<?php print($image_path); ?>" alt="" width="70"> </td>
                                                <td class="visible-xs visible-sm visible-md visible-lg"><?php print($row->proj_name); ?> </td>
                                                <td class="visible-xs visible-sm visible-md visible-lg"><?php print($row->proj_location); ?> </td>
                                                <td class="visible-lg">
                                                    <?php
                                                        if ($row->proj_status == 0) {
                                                            echo "<span class='btn btn-xs btn-primary btn-animate-demo padMsgs'> Past </span>";
                                                        } elseif ($row->proj_status == 1) {
                                                            echo "<span class='btn btn-xs btn-success btn-animate-demo padMsgs'> Current </span>";
                                                        } else {
                                                            echo "<span class='btn btn-xs btn-warning btn-animate-demo padMsgs'> Future </span>";
                                                        }
                                                        ?>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-xs btn-warning" title="Edit" onClick="javascript: window.location = '<?php print("manage_projects.php?action=2&" . $qryStrURL . "proj_id=" . $row->proj_id); ?>';"><i class="fa fa-edit"></i></button>
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
                                <!--<input type="submit" name="btnActive" value="Active" class="btn btn-success btn-animate-demo">
                                <input type="submit" name="btnInactive" value="In Active" class="btn btn-warning btn-animate-demo">-->
                            <?php } ?>
                        </form>
                    </div>
            </div>
        </div>
    </div>