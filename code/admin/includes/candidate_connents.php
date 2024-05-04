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

                                                
                                                  <div class="form-group">
                                                    <label  class="col-lg-2 col-md-3 control-label">User Type</label>
                                                    <div class="col-lg-4 col-md-9">
                                                        <select  name="utype_id" id="utype_id" class="form-control input-sm"  tabindex="2">
                                                                <option value="">Choose a User Type...</option>
                                                                <?php FillSelected("user_type Where utype_id>4", "utype_id", "utype_name", @$utype_id); ?>
                                                         </select>
                                                     
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group ">
                                                    <label  class="col-lg-2 col-md-3 control-label">User Name</label>
                                                    <div class="col-lg-4 col-md-9">
                                                        <input class="form-control form-cascade-control" name="user_name" type="text"  placeholder="User Name" value="<?php print($user_name); ?>">
                                                       
                                                    </div>
                                                </div>
                                                <?php
                                                    if($_GET['action']==1){
                                                ?>
                                                <div class="form-group">
                                                    <label  class="col-lg-2 col-md-3 control-label">Password</label>
                                                    <div class="col-lg-4 col-md-9">
                                                        <input type="text" class="form-control form-cascade-control" name="user_password"   placeholder="Password" value="">
                                                      
                                                    </div>
                                                </div>
                                                    <?php }?>
                                                 <div class="form-group">
                                                    <label  class="col-lg-2 col-md-3 control-label">First Name</label>
                                                    <div class="col-lg-4 col-md-9">
                                                        <input type="text" class="form-control form-cascade-control" name="user_fname"  id="user_fname"  placeholder="First Name" value="<?php print($user_fname);?>">
                                                      
                                                    </div>
                                                </div>
<!--                                                 <div class="form-group">
                                                    <label  class="col-lg-2 col-md-3 control-label">Title</label>
                                                    <div class="col-lg-4 col-md-9">
                                                        <select  name="user_fname" id="user_fname" class="form-control input-sm"  tabindex="2">
                                                                <option value="">Choose a Title...</option>
                                                                <?php //FillSelected("title", "user_fname", "title_name", @$user_fname); ?>
                                                         </select>
                                                     
                                                    </div>
                                                </div>-->
                                                 <div class="form-group">
                                                    <label  class="col-lg-2 col-md-3 control-label">Last Name</label>
                                                    <div class="col-lg-4 col-md-9">
                                                        <input type="text" class="form-control form-cascade-control" name="user_lname"   placeholder="First Name" value="<?php print($user_lname);?>">
                                                      
                                                    </div>
                                                </div>
                                                 <div class="form-group">
                                                    <label  class="col-lg-2 col-md-3 control-label">Father Name</label>
                                                    <div class="col-lg-4 col-md-9">
                                                        <input type="text" class="form-control form-cascade-control" name="user_father_name"   placeholder="Father Name" value="<?php print($user_father_name);?>">
                                                      
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label  class="col-lg-2 col-md-3 control-label">NIC</label>
                                                    <div class="col-lg-4 col-md-9">
                                                        <input type="text" class="form-control form-cascade-control" name="user_nic"  id="user_nic"  placeholder="NIC" value="<?php print($user_nic);?>">
                                                      
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label  class="col-lg-2 col-md-3 control-label">Gender :</label>
                                                    <div class="col-lg-4 col-md-9">
                                                        <input type="radio" name="user_gender" value="0" <?php print((@$user_gender == 0) ? 'checked="checked"' : ''); ?> />
                                                        Male &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <input type="radio" name="user_gender" value="1" <?php print((@$user_gender == 1) ? 'checked="checked"' : ''); ?> />
                                                        Female &nbsp;&nbsp;
                                                    </div>
                                                </div>
                                               <div class="form-group">
                                                    <label  class="col-lg-2 col-md-3 control-label">Date of Birth:</label>
                                                    <div class="col-lg-4 col-md-9">
                                                        <input type="text" class="form-control form-cascade-control  datepicker " name="user_dob" id="user_dob" value="<?php print(@$user_dob); ?>" placeholder="Date Of Birth">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label  class="col-lg-2 col-md-3 control-label">Email</label>
                                                    <div class="col-lg-4 col-md-9">
                                                        <input type="text" class="form-control form-cascade-control" name="user_email" id="user_email"   placeholder="Email" value="<?php print($user_email);?>">
                                                      
                                                    </div>
                                                </div>
                                            <div class="form-group">
                                                    <label  class="col-lg-2 col-md-3 control-label">Mobile</label>
                                                    <div class="col-lg-4 col-md-9">
                                                        <input type="text" class="form-control form-cascade-control" name="user_mobile"   placeholder="Mobile" value="<?php print($user_mobile);?>">
                                                      
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label  class="col-lg-2 col-md-3 control-label">Phone</label>
                                                    <div class="col-lg-4 col-md-9">
                                                        <input type="text" class="form-control form-cascade-control" name="user_phone"   placeholder="Phone" value="<?php print($user_phone);?>">
                                                      
                                                    </div>
                                                </div>
                                                 <div class="form-group">
                                                    <label  class="col-lg-2 col-md-3 control-label">Address</label>
                                                    <div class="col-lg-4 col-md-9">
                                                        <textarea class="form-control form-cascade-control" row="4" name="user_address" placeholder="Addess"  ><?php print($user_address);?></textarea>
                                                      
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label  class="col-lg-2 col-md-3 control-label">City</label>
                                                    <div class="col-lg-4 col-md-9">
                                                        <select  name="city_id" id="city_id" class="form-control input-sm"  tabindex="2">
                                                                <option value="">Choose a City...</option>
                                                                <?php FillSelected("cities", "city_id", "city_name", @$city_id); ?>
                                                         </select>
                                                     
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label  class="col-lg-2 col-md-3 control-label">States</label>
                                                    <div class="col-lg-4 col-md-9">
                                                        <select  name="state_id" id="user_fname" class="form-control input-sm"  tabindex="2">
                                                                <option value="">Choose a State...</option>
                                                                <?php FillSelected("states", "state_id", "state_name", @$state_id); ?>
                                                         </select>
                                                     
                                                    </div>
                                                </div>
                                                
                                                 
                                               <div class="form-group">
                                                    <label  class="col-lg-2 col-md-3 control-label">Country</label>
                                                    <div class="col-lg-4 col-md-9">
                                                        <select  name="countries_id" id="user_fname" class="form-control input-sm"  tabindex="2">
                                                                <option value="">Choose a Country...</option>
                                                                <?php FillSelected("countries", "countries_id", "countries_name", @$countries_id); ?>
                                                         </select>
                                                     
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
                                                    <label for="inputEmail1" class="col-lg-2 col-md-3 control-label">User Name:</label>
                                                    <div class="col-lg-10 col-md-9 padTop7"><?php print($user_name); ?></div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputEmail1" class="col-lg-2 col-md-3 control-label">First Name:</label>
                                                    <div class="col-lg-10 col-md-9 padTop7"><?php print($user_fname); ?></div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputEmail1" class="col-lg-2 col-md-3 control-label">Last Name:</label>
                                                    <div class="col-lg-10 col-md-9 padTop7"><?php print($user_lname); ?></div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputEmail1" class="col-lg-2 col-md-3 control-label">Father Name:</label>
                                                    <div class="col-lg-10 col-md-9 padTop7"><?php print($user_father_name); ?></div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputEmail1" class="col-lg-2 col-md-3 control-label">NIC:</label>
                                                    <div class="col-lg-10 col-md-9 padTop7"><?php print($user_nic); ?></div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputEmail1" class="col-lg-2 col-md-3 control-label">Date Of Birth:</label>
                                                    <div class="col-lg-10 col-md-9 padTop7"><?php print($user_dob); ?></div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputEmail1" class="col-lg-2 col-md-3 control-label">Email:</label>
                                                    <div class="col-lg-10 col-md-9 padTop7"><?php print($user_email); ?></div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputEmail1" class="col-lg-2 col-md-3 control-label">Mobile:</label>
                                                    <div class="col-lg-10 col-md-9 padTop7"><?php print($user_mobile); ?></div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputEmail1" class="col-lg-2 col-md-3 control-label">Phone:</label>
                                                    <div class="col-lg-10 col-md-9 padTop7"><?php print($user_phone); ?></div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputEmail1" class="col-lg-2 col-md-3 control-label">Address:</label>
                                                    <div class="col-lg-10 col-md-9 padTop7"><?php print($user_address); ?></div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputEmail1" class="col-lg-2 col-md-3 control-label">City:</label>
                                                    <div class="col-lg-10 col-md-9 padTop7"><?php print($city_name); ?></div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputEmail1" class="col-lg-2 col-md-3 control-label">State:</label>
                                                    <div class="col-lg-10 col-md-9 padTop7"><?php print($state_name); ?></div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputEmail1" class="col-lg-2 col-md-3 control-label">Country:</label>
                                                    <div class="col-lg-10 col-md-9 padTop7"><?php print($countries_name); ?></div>
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
                                            <h4 ><i class="fa fa-bars"></i>Personal Information</h4>
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
                                                            <th class="visible-xs visible-sm visible-md visible-lg">User Name</th>
                                                             <th class="visible-xs visible-sm visible-md visible-lg">Name</th>
                                                             <th class="visible-xs visible-sm visible-md visible-lg">Email</th>
                                                             <th class="visible-xs visible-sm visible-md visible-lg">Mobile</th>
                                                             <th class="visible-xs visible-sm visible-md visible-lg">Status</th>
                                                           
                                                              
                                                          
                                                            <th width="140">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                       $Query = "SELECT u.*,s.status_name FROM user AS u LEFT OUTER JOIN status AS s ON s.status_id=u.status_id WHERE utype_id=".$utype_id." ORDER BY u.user_id DESC" ;
                                                        $counter = 0;
                                                        $limit = 25;
                                                        $start = $p->findStart($limit);
                                                        $count = mysqli_num_rows(mysqli_query($GLOBALS['conn'], $Query));
                                                        $pages = $p->findPages($count, $limit);
                                                        $rs = mysqli_query($GLOBALS['conn'], $Query . " LIMIT " . $start . ", " . $limit);
                                                        if (mysqli_num_rows($rs) > 0) {
                                                            while ($row = mysqli_fetch_object($rs)) {
                                                                $counter++;
                                                                $strClass = 'label  label-warning';
                                                                if($row->status_id==1){
                                                                    $strClass = 'label  label-primary'; 
                                                                }
                                                                ?>
                                                                <tr>
                                                                    <td class="visible-xs visible-sm visible-md visible-lg"><input type="checkbox" name="chkstatus[]" value="<?php print($row->user_id); ?>" /></td>
                                                                    <td class="visible-xs visible-sm visible-md visible-lg"><?php print($row->user_name); ?> </td>
                                                                    <td class="visible-xs visible-sm visible-md visible-lg"><?php print($row->user_lname.' '.$row->user_fname); ?> </td>
                                                                    <td class="visible-xs visible-sm visible-md visible-lg"><?php print($row->user_email); ?> </td>
                                                                    <td class="visible-xs visible-sm visible-md visible-lg"><?php print($row->user_mobile); ?> </td>
                                                                    <td class="visible-xs visible-sm visible-md visible-lg"><span class="<?php print($strClass) ?>"><?php print($row->status_name); ?> </span></td>
                                                                  
                                                                  
                                                                    <td>
                                                                        <button type="button" class="btn btn-info" title="View Details" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?show=1&" . $qryStrURL . "userid=" . $row->user_id); ?>';"><i class="fa fa-eye"></i></button>
                                                                        <button type="button" class="btn btn-warning" title="Edit" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?action=2&" . $qryStrURL . "userid=" . $row->user_id); ?>';"><i class="fa fa-edit"></i></button>
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
<!--                                                   <input type="hidden" name="btnDelete" id="btnDelete">-->
                                                    <input type="submit" name="btnDelete" value="Delete" class="btn btn-danger btn-animate-demo">
                                                     <input type="submit" name="btnActive" value="Active" class="btn btn-primary btn-animate-demo">
                                                    <input type="submit" name="btnInactive" value="In Active" class="btn btn-warning btn-animate-demo">
                                                <?php } ?>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>