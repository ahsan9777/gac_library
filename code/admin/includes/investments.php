<div class="row">
    <div class="col-md-12">
        <div class="box border blue">
            <div class="box-title">
                <h4 ><i class="fa fa-bars"></i>Investmonts</h4>
            </div>
            <div class="clearfix"></div>
            <div class="box-body">
                <form name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" class="form-horizontal" role="form">
                    <table class="table users-table table-condensed table-hover table-striped table-bordered" >
                        <thead>
                            <tr>
                                <th class="visible-xs visible-sm visible-md visible-lg">Date</th>
                                <th class="visible-xs visible-sm visible-md visible-lg">Investor Info</th>
                                <th class="visible-xs visible-sm visible-md visible-lg">Project</th>
                                <th class="visible-xs visible-sm visible-md visible-lg">Referance Code</th>
                                <th class="visible-xs visible-sm visible-md visible-lg">Amount</th>
                                <th class="visible-xs visible-sm visible-md visible-lg">Payment Method</th>
                                <th class="visible-xs visible-sm visible-md visible-lg" width="50">Status</th>
                                <!--<th width="70">Action</th>-->
                            </tr>
                        </thead>
                        <tbody>
                                <?php
                                $Query = "SELECT pi.*, u.user_fname, u.user_name, u.user_phone, proj.proj_name FROM `projects_investments` AS pi LEFT OUTER JOIN users AS u ON u.user_id = pi.user_id AND u.utype_id = '4' LEFT OUTER JOIN projects AS proj ON proj.proj_id = pi.proj_id ORDER BY pi_id DESC";
                                $counter = 0;
                                $limit = 5;
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
                                        <td class="visible-xs visible-sm visible-md visible-lg"><?php print(date('D F j, Y', strtotime($row->pi_cdate))); ?> </td>
                                        <td class="visible-xs visible-sm visible-md visible-lg"><a href="users_management.php?show=1&proj_id=<?php print($row->proj_id); ?>&user_id=<?php print($row->user_id); ?>"><?php print($row->user_fname."<br>".$row->user_name."<br>".$row->user_phone); ?></a> </td>
                                        <td class="visible-xs visible-sm visible-md visible-lg"><a href="manage_projects.php?action=2&proj_id=<?php print($row->proj_id); ?>"><?php print($row->proj_name); ?></a></td>
                                        <td class="visible-xs visible-sm visible-md visible-lg"><?php print($row->pi_referance_code); ?> </td>
                                        <td class="visible-xs visible-sm visible-md visible-lg"><?php print($row->pi_payment); ?> </td>
                                        <td class="visible-xs visible-sm visible-md visible-lg"><?php print($row->pi_type); ?> </td>
                                        <td class="visible-lg">
                                        <?php
                                        if ($row->pi_status == 0) {
                                            echo "<span class='btn btn-xs btn-danger btn-animate-demo padMsgs'> Pending </span>";
                                        } else {
                                            echo "<span class='btn btn-xs btn-success btn-animate-demo padMsgs'> Paid </span>";
                                        }
                                        ?>
                                        </td>
                                        <!--<td>
                                            <button type="button" class="btn btn-xs btn-info" title="View Details" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?show=1&" . $qryStrURL . "pi_id=" . $row->pi_id); ?>';"><i class="fa fa-eye"></i></button>
                                            <button type="button" class="btn btn-xs btn-warning" title="Edit" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?action=2&" . $qryStrURL . "pi_id=" . $row->pi_id); ?>';"><i class="fa fa-edit"></i></button>
                                        </td>-->
                                    </tr>
                                                <?php
                                            }
                                        } else {
                                            print('<tr><td colspan="100%" align="center">No record found!</td></tr>');
                                        }
                                        ?>

                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>