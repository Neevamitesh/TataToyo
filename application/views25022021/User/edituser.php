<style>
    hr{
        margin-top:1%;
        margin-bottom:2%;
    }
    body{
        overflow: hidden;
    }
</style>
<div class="row" id="adduserrow">
    <div class="col-lg-12 mx-auto">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    Add User
                    <a href="<?= base_url(); ?>User"><button type="button" id="viewbtn" class="btn btn-info btn-sm pull-right">Add Users</button></a>
                </div>
                <hr>
                <form method="post" action="<?= base_url(); ?>User/UpdateUser" enctype="multipart/form-data">
                    <input type="hidden" name="uid" value="<?= $user->UID; ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="username">User Name</label>
                                        <input type="text" required value="<?= $user->username; ?>" name="username" class="form-control form-control-rounded" id="username" placeholder="Enter User Name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" required name="password" value="<?= $user->password; ?>" class="form-control form-control-rounded" id="password" placeholder="Enter Password">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" required name="email" value="<?= $user->emailid; ?>" class="form-control form-control-rounded" id="email" placeholder="Enter Email ID">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="contact">Contact</label>
                                    <div class="input-group">
                                        <input type="tel" required name="contact" value="<?= $user->mobileno; ?>" class="form-control form-control-rounded" id="contact" placeholder="Enter Contact No.">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="type">User Type</label>
                                    <select class="form-control form-control-rounded" name="type" id="basic-select">
                                        <option value="" selected disabled>Select Type</option>
                                        <option <?= $user->usertype == "Admin" ? "selected" : ""; ?> value="Admin">Admin</option>
                                        <option <?= $user->usertype == "SuperAdmin" ? "selected" : ""; ?> value="SuperAdmin">SuperAdmin</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="location">Location</label>
                                    <select class="form-control form-control-rounded" name="location" id="basic-select">
                                        <option value="" selected disabled>Select Location</option>
                                        <?php
                                        foreach ($location->result() as $loc) {
                                            ?>
                                            <option <?= $user->location == $loc->LOCID ? "selected" : ""; ?> value="<?= $loc->LOCID; ?>"><?= $loc->locationname . " (" . $loc->locationcode . ")"; ?></option>
                                        <?php
                                    }
                                    ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <!-- <div class="col-md-1"></div> -->
                                <div class="col-md-3">
                                    <center>
                                        <h5>Product</h5>
                                    </center>
                                </div>
                                <div class="col-md-2">
                                    <div class="icheck-material-primary">
                                    <label for="" >
                                        <input type="checkbox" name="product[]" id="Productcreate" <?= in_array("Productcreate", $rights) ? "checked" : ""; ?> value="1"/> Create
                                    </label>    
                                        
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label for="secondary">
                                    <input type="checkbox" name="product[]" id="Productview" <?= in_array("Productview", $rights) ? "checked" : ""; ?> value="2"/>
                                    View</label>
                                </div>
                                <div class="col-md-2">
                                <label for="info">    
                                <input type="checkbox" name="product[]" id="Productupdate" <?= in_array("Productupdate", $rights) ? "checked" : ""; ?> value="3"/>
                                    Update</label>
                                </div>
                                <div class="col-md-2">
                                <label for="danger">    
                                <input type="checkbox" name="product[]" id="Productdelete" <?= in_array("Productdelete", $rights) ? "checked" : ""; ?> value="4"/>
                                    Delete</label>
                                </div>
                                <div class="col-md-1"></div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-3">
                                    <center>
                                        <h5>Consignee</h5>
                                    </center>
                                </div>
                                <div class="col-md-2">
                                <label for="secondary">    
                                <input type="checkbox" name="consignee[]" id="Consigneecreate" <?= in_array("Consigneecreate", $rights) ? "checked" : ""; ?> value="1"/>
                                    Create</label>
                                </div>
                                <div class="col-md-2">
                                <label for="secondary">    
                                <input type="checkbox" name="consignee[]" id="Consigneeview" <?= in_array("Consigneeview", $rights) ? "checked" : ""; ?> value="2"/>
                                    View</label>
                                </div>
                                <div class="col-md-2">
                                <label for="info">    
                                <input type="checkbox" name="consignee[]" id="Consigneeupdate" <?= in_array("Consigneeupdate", $rights) ? "checked" : ""; ?> value="3"/>
                                    Update</label>
                                </div>
                                <div class="col-md-2">
                                <label for="danger">    
                                <input type="checkbox" name="consignee[]" id="Consigneedelete" <?= in_array("Consigneedelete", $rights) ? "checked" : ""; ?> value="4"/>
                                    Delete</label>
                                </div>
                                <div class="col-md-1"></div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-3">
                                    <center>
                                        <h5>User</h5>
                                    </center>
                                </div>
                                <div class="col-md-2">
                                <label for="secondary">    
                                <input type="checkbox" name="user[]" id="Usercreate" <?= in_array("Usercreate", $rights) ? "checked" : ""; ?> value="1"/>
                                    Create</label>
                                </div>
                                <div class="col-md-2">
                                <label for="secondary">    
                                <input type="checkbox" name="user[]" id="Userview" <?= in_array("Userview", $rights) ? "checked" : ""; ?> value="2"/>
                                    View</label>
                                </div>
                                <div class="col-md-2">
                                <label for="info">    
                                <input type="checkbox" name="user[]" id="Userupdate" <?= in_array("Userupdate", $rights) ? "checked" : ""; ?> value="3"/>
                                    Update</label>
                                </div>
                                <div class="col-md-2">
                                <label for="danger">    
                                <input type="checkbox" name="user[]" id="Userdelete" <?= in_array("Userdelete", $rights) ? "checked" : ""; ?> value="4"/>
                                    Delete</label>
                                </div>
                                <div class="col-md-1"></div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-3">
                                    <center>
                                        <h5>Label Design</h5>
                                    </center>
                                </div>
                                <div class="col-md-2">
                                <label for="secondary">    
                                <input type="checkbox" name="label[]" id="LabelDesigncreate" <?= in_array("LabelDesigncreate", $rights) ? "checked" : ""; ?> value="1"/>
                                    Create</label>
                                </div>
                                <div class="col-md-2">
                                <label for="secondary">    
                                <input type="checkbox" name="label[]" id="LabelDesignview" <?= in_array("LabelDesignview", $rights) ? "checked" : ""; ?> value="2"/>
                                    View</label>
                                </div>
                                <div class="col-md-2">
                                <label for="info">    
                                <input type="checkbox" name="label[]" id="LabelDesignupdate" <?= in_array("LabelDesignupdate", $rights) ? "checked" : ""; ?> value="3"/>
                                    Update</label>
                                </div>
                                <div class="col-md-2">
                                <label for="danger">    
                                <input type="checkbox" name="label[]" id="LabelDesigndelete" <?= in_array("LabelDesigndelete", $rights) ? "checked" : ""; ?> value="4"/>
                                    Delete</label>
                                </div>
                                <div class="col-md-1"></div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-3">
                                    <center>
                                        <h5>Batch</h5>
                                    </center>
                                </div>
                                <div class="col-md-2">
                                <label for="secondary">    
                                <input type="checkbox" name="batch[]" id="Batchcreate" <?= in_array("Batchcreate", $rights) ? "checked" : ""; ?> value="1"/>
                                    Create</label>
                                </div>
                                <div class="col-md-2">
                                <label for="secondary">    
                                <input type="checkbox" name="batch[]" id="Batchview" <?= in_array("Batchview", $rights) ? "checked" : ""; ?> value="2"/>
                                    View</label>
                                </div>
                                <div class="col-md-2">
                                <label for="info">    
                                <input type="checkbox" name="batch[]" id="Batchupdate" <?= in_array("Batchupdate", $rights) ? "checked" : ""; ?> value="3"/>
                                    Update</label>
                                </div>
                                <div class="col-md-2">
                                <label for="danger">    
                                <input type="checkbox" name="batch[]" id="Batchdelete" <?= in_array("Batchdelete", $rights) ? "checked" : ""; ?> value="4"/>
                                    Delete</label>
                                </div>
                                <div class="col-md-1"></div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-3">
                                    <center>
                                        <h5>Printing</h5>
                                    </center>
                                </div>
                                <div class="col-md-2">
                                <label for="secondary">    
                                <input type="checkbox" name="print[]" id="Printingcreate" <?= in_array("Printingcreate", $rights) ? "checked" : ""; ?> value="1"/>
                                    Create</label>
                                </div>
                                <div class="col-md-2">
                                <label for="secondary">    
                                <input type="checkbox" name="print[]" id="Printingview" <?= in_array("Printingview", $rights) ? "checked" : ""; ?> value="2"/>
                                    View</label>
                                </div>
                                <div class="col-md-2">
                                <label for="info">Update    
                                <input type="checkbox" name="print[]" id="Printingupdate" <?= in_array("Printingupdate", $rights) ? "checked" : ""; ?> value="3"/>
                                    </label>
                                </div>
                                <div class="col-md-2">
                                <label for="danger">    
                                <input type="checkbox" name="print[]" id="Printingdelete" <?= in_array("Printingdelete", $rights) ? "checked" : ""; ?> value="4"/>
                                    Delete</label>
                                </div>
                                <div class="col-md-1"></div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-3">
                                    <center>
                                        <h5>Reprint</h5>
                                    </center>
                                </div>
                                <div class="col-md-2">
                                <label for="secondary">    
                                <input type="checkbox" name="reprint[]" id="Reprintcreate" <?= in_array("Reprintcreate", $rights) ? "checked" : ""; ?> value="1"/>
                                    Create</label>
                                </div>
                                <div class="col-md-2">
                                <label for="secondary">    
                                <input type="checkbox" name="reprint[]" id="Reprintview" <?= in_array("Reprintview", $rights) ? "checked" : ""; ?> value="2"/>
                                    View</label>
                                </div>
                                <div class="col-md-2">
                                <label for="info">    
                                <input type="checkbox" name="reprint[]" id="Reprintupdate" <?= in_array("Reprintupdate", $rights) ? "checked" : ""; ?> value="3"/>
                                    Update</label>
                                </div>
                                <div class="col-md-2">
                                <label for="danger">    
                                <input type="checkbox" name="reprint[]" id="Reprintdelete" <?= in_array("Reprintdelete", $rights) ? "checked" : ""; ?> value="4"/>
                                    Delete</label>
                                </div>
                                <div class="col-md-1"></div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-3">
                                    <center>
                                        <h5>Reports</h5>
                                    </center>
                                </div>
                                <div class="col-md-2">
                                <label for="secondary">    
                                <input type="checkbox" name="reports[]" id="Reportscreate" <?= in_array("Reportscreate", $rights) ? "checked" : ""; ?> value="1"/>
                                    Create</label>
                                </div>
                                <div class="col-md-2">
                                <label for="secondary">    
                                <input type="checkbox" name="reports[]" id="Reportsview" <?= in_array("Reportsview", $rights) ? "checked" : ""; ?> value="2"/>
                                    View</label>
                                </div>
                                <div class="col-md-2">
                                <label for="info">    
                                <input type="checkbox" name="reports[]" id="Reportsupdate" <?= in_array("Reportsupdate", $rights) ? "checked" : ""; ?> value="3"/>
                                    Update</label>
                                </div>
                                <div class="col-md-2">
                                <label for="danger">    
                                <input type="checkbox" name="reports[]" id="Reportsdelete" <?= in_array("Reportsdelete", $rights) ? "checked" : ""; ?> value="4"/>
                                    Delete</label>
                                </div>
                                <div class="col-md-1"></div>
                            </div>
                            <hr>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary shadow-primary pull-right btn-round px-5"><i class="icon-plus"></i> Update</button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script>

    <?php
    if (!empty($msg)) {
        ?>
        $(document).ready(function() {
            var type = '<?= $msg['type']; ?>'; 
            if (type == "error") {
                round_error_noti();
            } else if(type == "info"){
                round_info_noti();
            }
            else if(type == "success"){
                round_info_noti();
            }
            $(".lobibox-notify-msg").html('<?= $msg['msg']; ?>');
        });
    <?php
    }
    ?>


</script>