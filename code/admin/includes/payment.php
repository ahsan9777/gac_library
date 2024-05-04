<div class="row">
    <div class="col-md-12">
        <div class="box border blue">
            <div class="box-title">
                <h4 ><i class="fa fa-user"></i> Payment</h4>
                <span class="pull-right" style="width:auto;">


                    <div class="tools" style="color:white">

                        <div>

                            <a href="<?php print($_SERVER['PHP_SELF'] . "?payment=1&" . $qryStrURL . "mem_id=" . $_REQUEST['mem_id']); ?>" title="Add New"><i class="fa fa-plus"></i> Add New</a>

                        </div>
                    </div> 
                </span> 
            </div>
            <div class="box-body">
                <form name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" class="form-horizontal" role="form">
                    <table class="table table-striped" >
                        <thead>
                            <tr>
                                <th class="visible-xs visible-sm visible-md visible-lg"><input type="checkbox" name="chkAll" onClick="setAll();"></th>
                                <th class="visible-xs visible-sm visible-md visible-lg">Name</th>
                                <th class="visible-xs visible-sm visible-md visible-lg">Payment Details</th>
                                <th class="visible-xs visible-sm visible-md visible-lg">Payment Amount</th>
                                <th class="visible-xs visible-sm visible-md visible-lg">Start Date</th>
                                <th class="visible-xs visible-sm visible-md visible-lg">End Date</th>

                                <th width="140">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $Query = "SELECT m.*,p.*,p.ptype_id AS ptyid ,pt.ptype_name FROM payment_logs AS p LEFT OUTER JOIN members AS m ON m.mem_id=p.mem_id LEFT OUTER JOIN payment_type AS pt ON pt.ptype_id=p.ptype_id WHERE p.mem_id='" . $_REQUEST['mem_id'] . "'";
                            $counter = 0;
                            $limit = 15;
                            $start = $p->findStart($limit);
                            $count = mysqli_num_rows(mysqli_query($GLOBALS['conn'], $Query));
                            $pages = $p->findPages($count, $limit);
                            $rs = mysqli_query($GLOBALS['conn'], $Query . " LIMIT " . $start . ", " . $limit);

                            if (mysqli_num_rows($rs) > 0) {
                                while ($row = mysqli_fetch_object($rs)) {

                                    $counter++;
                                    $strClass = 'label  label-info';
                                    $date = date_create($row->mem_to_date);
                                    $date2 = date_create($row->mem_from_date);
                                    ?>
                                    <tr>
                                        <td class="visible-lg"><input type="checkbox" name="chkstatus[]" value="<?php print($row->mem_id); ?>" /></td>
                                        <td class="visible-lg"><?php print(@$row->mem_fname); ?> </td>

                                        <td class="visible-lg"><span class="label  label-primary"><?php print($row->ptype_name); ?></span></td>
                                        <td class="visible-lg"><?php print($row->pl_amount); ?></td>
                                        <td class="visible-lg"><span class="<?php print($strClass) ?>"><?php echo date_format($date2, 'M-d-Y'); ?></span></td>
                                        <td class="visible-lg"><span class="<?php print($strClass) ?>"><?php echo date_format($date, 'M-d-Y'); ?></span></td>

                                        <td style="width:150px;">


                                            <button type="button" class="btn btn-primary" title="Edit" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?payment=2&" . $qryStrURL . "mem_id=" . $row->mem_id."&pl_id=".$row->pl_id); ?>';"><i class="fa fa-edit"></i></button>

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
                                    
                                    $next_prev = $p->nextPrev($_GET['page'], $pages, '&pay=1&mem_id=' . $_REQUEST['mem_id']);
                                    print($next_prev);
                                    ?>
                                </td>
                            </tr>
                        </table>
                    <?php } ?>
                    <?php if ($counter > 0) { ?>

<!--                        <input type="submit" name="btnDelete" value="Delete" class="btn btn-danger btn-animate-demo">-->

                    <?php } ?>
                </form>
            </div>
        </div>
    </div>
</div>