<div class="row">
    <div class="col-md-12">
        <div class="box border blue">
            <div class="box-title">
                <h4 ><i class="fa fa-user"></i>Payment Details</h4>

            </div>
            <div class="box-body">
                <form name="frm" id="frm" method="post" action="<?php print($_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']); ?>" class="form-horizontal" role="form">
                    <div class="form-group">
                        <label  class="col-lg-2 col-md-3 control-label">Payment Type</label>
                        <div class="col-lg-4 col-md-9">
                            <select class="form-control" name="ptype_id" >
                                <?php print(FillSelected("payment_type", "ptype_id", "ptype_name", @$ptype_id)); ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label  class="col-lg-2 col-md-3 control-label">Member From Date</label>
                        <div class="col-lg-4 col-md-9">
                            <input type="text" class="form-control form-cascade-control  datepickerfrm " name="mem_from_date" id="mem_from_date" value="<?php print(@$mem_from_date); ?>" placeholder="Member From Date">
                        </div>
                    </div>
                    <div class="form-group">
                        <label  class="col-lg-2 col-md-3 control-label">Payment Amount</label>
                        <div class="col-lg-4 col-md-9">
                            <input type="text" class="form-control form-cascade-control" name="pl_amount" id="pl_amount" value="<?php print(@$pl_amount); ?>" placeholder="Payment Amount">
                        </div>
                    </div>
                    <div class="form-group">
                        <label  class="col-lg-2 col-md-3 control-label">Payment Ref</label>
                        <div class="col-lg-4 col-md-9">
                            <textarea  class="form-control form-cascade-control  datepickerfrm " name="mem_payment_ref" id="mem_payment_ref"  placeholder="Payment Ref" rows="5"><?php print(@$mem_payment_ref); ?></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputEmail1" class="col-lg-2 col-md-3 control-label">&nbsp;</label>
                        <div class="col-lg-10 col-md-9">
                            <?php if ($_REQUEST['payment'] == 1) { ?>
                                <button type="submit" name="AddPayment" class="btn btn-primary btn-animate-demo">Submit</button>
                            <?php } else { ?>
                                <button type="submit" name="EditPayment" class="btn btn-primary btn-animate-demo">Update</button>
                            <?php } ?>
                            <button type="button" name="btnCancel" class="btn btn-default btn-animate-demo" onClick="javascript: window.location = '<?php print($_SERVER['PHP_SELF'] . "?&pay=1&mem_id=" . $_REQUEST['mem_id']); ?>';">Cancel</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>