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
                    <button type="button" onclick="viewusers()" id="viewbtn" class="btn btn-info btn-sm pull-right">View Users</button>
                </div>
                <hr>
                <form method="post" action="<?= base_url(); ?>User/CreateUser" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="username">User Name</label>
                                        <input type="text" required name="username" required class="form-control form-control-rounded" id="username" placeholder="Enter User Name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" required name="password" required class="form-control form-control-rounded" id="password" placeholder="Enter Password">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" required name="email" required class="form-control form-control-rounded" id="email" placeholder="Enter Email ID">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="contact">Contact</label>
                                    <div class="input-group">
                                        <input type="tel" required name="contact" required class="form-control form-control-rounded" id="contact" placeholder="Enter Contact No.">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="type">User Type</label>
                                    <select class="form-control form-control-rounded" name="type" id="basic-select">
                                        <option value="" selected disabled>Select Type</option>
                                        <option value="Admin">Admin</option>
                                        <option value="SuperAdmin">SuperAdmin</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="location">Location</label>
                                    <select class="form-control form-control-rounded" name="location" id="basic-select">
                                        <option value="" selected disabled>Select Location</option>
                                        <?php
                                        foreach ($location->result() as $loc) {
                                            ?>
                                            <option value="<?= $loc->LOCID; ?>"><?= $loc->locationname . " (" . $loc->locationcode . ")"; ?></option>
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
                                    <input type="checkbox" name="product[]" value="1" class="js-switch" data-color="#008cff" data-size="small" />
                                    <label for="secondary">Create</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="checkbox" name="product[]" value="2" class="js-switch" data-color="#0dceec" data-size="small" />
                                    <label for="secondary">View</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="checkbox" name="product[]" value="3" class="js-switch" data-color="#75808a" data-size="small" />
                                    <label for="info">Update</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="checkbox" name="product[]" value="4" class="js-switch" data-color="#fd3550" data-size="small" />
                                    <label for="danger">Delete</label>
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
                                    <input type="checkbox" name="consignee[]" value="1" class="js-switch" data-color="#008cff" data-size="small" />
                                    <label for="secondary">Create</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="checkbox" name="consignee[]" value="2" class="js-switch" data-color="#0dceec" data-size="small" />
                                    <label for="secondary">View</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="checkbox" name="consignee[]" value="3" class="js-switch" data-color="#75808a" data-size="small" />
                                    <label for="info">Update</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="checkbox" name="consignee[]" value="4" class="js-switch" data-color="#fd3550" data-size="small" />
                                    <label for="danger">Delete</label>
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
                                    <input type="checkbox" name="user[]" value="1" class="js-switch" data-color="#008cff" data-size="small" />
                                    <label for="secondary">Create</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="checkbox" name="user[]" value="2" class="js-switch" data-color="#0dceec" data-size="small" />
                                    <label for="secondary">View</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="checkbox" name="user[]" value="3" class="js-switch" data-color="#75808a" data-size="small" />
                                    <label for="info">Update</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="checkbox" name="user[]" value="4" class="js-switch" data-color="#fd3550" data-size="small" />
                                    <label for="danger">Delete</label>
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
                                    <input type="checkbox" name="label[]" value="1" class="js-switch" data-color="#008cff" data-size="small" />
                                    <label for="secondary">Create</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="checkbox" name="label[]" value="2" class="js-switch" data-color="#0dceec" data-size="small" />
                                    <label for="secondary">View</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="checkbox" name="label[]" value="3" class="js-switch" data-color="#75808a" data-size="small" />
                                    <label for="info">Update</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="checkbox" name="label[]" value="4" class="js-switch" data-color="#fd3550" data-size="small" />
                                    <label for="danger">Delete</label>
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
                                    <input type="checkbox" name="batch[]" value="1" class="js-switch" data-color="#008cff" data-size="small" />
                                    <label for="secondary">Create</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="checkbox" name="batch[]" value="2" class="js-switch" data-color="#0dceec" data-size="small" />
                                    <label for="secondary">View</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="checkbox" name="batch[]" value="3" class="js-switch" data-color="#75808a" data-size="small" />
                                    <label for="info">Update</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="checkbox" name="batch[]" value="4" class="js-switch" data-color="#fd3550" data-size="small" />
                                    <label for="danger">Delete</label>
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
                                    <input type="checkbox" name="print[]" value="1" class="js-switch" data-color="#008cff" data-size="small" />
                                    <label for="secondary">Create</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="checkbox" name="print[]" value="2" class="js-switch" data-color="#0dceec" data-size="small" />
                                    <label for="secondary">View</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="checkbox" name="print[]" value="3" class="js-switch" data-color="#75808a" data-size="small" />
                                    <label for="info">Update</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="checkbox" name="print[]" value="4" class="js-switch" data-color="#fd3550" data-size="small" />
                                    <label for="danger">Delete</label>
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
                                    <input type="checkbox" name="reprint[]" value="1" class="js-switch" data-color="#008cff" data-size="small" />
                                    <label for="secondary">Create</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="checkbox" name="reprint[]" value="2" class="js-switch" data-color="#0dceec" data-size="small" />
                                    <label for="secondary">View</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="checkbox" name="reprint[]" value="3" class="js-switch" data-color="#75808a" data-size="small" />
                                    <label for="info">Update</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="checkbox" name="reprint[]" value="4" class="js-switch" data-color="#fd3550" data-size="small" />
                                    <label for="danger">Delete</label>
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
                                    <input type="checkbox" name="reports[]" value="1" class="js-switch" data-color="#008cff" data-size="small" />
                                    <label for="secondary">Create</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="checkbox" name="reports[]" value="2" class="js-switch" data-color="#0dceec" data-size="small" />
                                    <label for="secondary">View</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="checkbox" name="reports[]" value="3" class="js-switch" data-color="#75808a" data-size="small" />
                                    <label for="info">Update</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="checkbox" name="reports[]" value="4" class="js-switch" data-color="#fd3550" data-size="small" />
                                    <label for="danger">Delete</label>
                                </div>
                                <div class="col-md-1"></div>
                            </div>
                            <hr>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary shadow-primary pull-right btn-round px-5"><i class="icon-plus"></i> Add</button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<div class="row hidden" id="viewusersrow">
    <div class="col-lg-12 mx-auto">
        <div class="card">
            <div class="card-header">
                <i class="fa fa-table"></i> Users
                <button type="button" onclick="addusers()" id="addbtn" class="btn btn-info btn-sm pull-right">Add Users</button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="" class="table default-datatable table-bordered">
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>User Type</th>
                                <th>Location Code</th>
                                <th>Location Name</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $c = 1;
                                foreach($users->result() as $user){
                                    ?>
                                        <tr>
                                            <td><?= $c; ?></td>
                                            <td><?= $user->username; ?></td>
                                            <td><?= $user->emailid; ?></td>
                                            <td><?= $user->usertype; ?></td>
                                            <td><?= $user->locationcode; ?></td>
                                            <td><?= $user->locationname; ?></td>
                                            <td>
                                                <a href="<?= base_url(); ?>User/EditUser/<?= $user->UID; ?>"><button type="button" class="btn btn-info"><i class="fa fa-edit"></i></button></a>
                                            </td>
                                        </tr>
                                    <?php
                                    $c++;
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div><!-- End Row-->

<script>

    $("#viewbtn").click(function(e){
        $("#adduserrow").addClass("hidden");
        $("#viewusersrow").removeClass('hidden');
    });

    $("#addbtn").click(function(e){
        $("#viewusersrow").addClass("hidden");
        $("#adduserrow").removeClass('hidden');
    });

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