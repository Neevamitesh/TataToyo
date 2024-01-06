<div class="modal fade" id="editmodal" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-star"></i> Edit Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url(); ?>Category/UpdateCategory" method="post">
                    <div class="form-group">
                        <label for="input-7">Category</label>
                        <input type="hidden" name="cid" id="editcid">
                        <input type="text" required name="category" class="form-control form-control-rounded" id="editcategory" placeholder="Enter Category Name">
                    </div>
                    <button type="submit" class="btn btn-info btn-round shadow-info waves-effect pull-right waves-light m-1"><i class="icon-pencil"></i> Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="delmodal" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-star"></i> Delete Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url(); ?>Category/DeleteCategory" method="post">
                    <div class="alert alert-outline-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <div class="alert-icon">
                            <i class="icon-exclamation"></i>
                        </div>
                        <div class="alert-message">
                            <span><strong>Confirm Delete Category ?</strong></span>
                        </div>
                    </div>
                    <input type="hidden" name="cid" id="delcid">
                    <button type="submit" class="btn btn-danger btn-round shadow-danger waves-effect pull-right waves-light m-1"><i class="icon-trash"></i> Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 mx-auto">
        <div class="card">
            <div class="card-header"><i class="fa fa-table"></i> Users</div>
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
    function EditCategory(cid) {
        $.ajax({
            type: "POST",
            url: "<?= base_url(); ?>Category/GetCategoryJSON",
            data: {
                cid: cid
            },
            success: function(data) {
                if (data != "") {
                    var obj = JSON.parse(data);
                    $("#editcategory").val(obj.Category_Name);
                    $("#editcid").val(obj.CID);
                }
            }
        });
    }
    <?php
    if (!empty($msg)) {
        ?>
        var msg = "<?= $msg; ?>";
        $(document).ready(function() {
            if(msg == "Something went wrong !"){
                round_error_noti();
            }
            else{
                round_info_noti();
            }
            $(".lobibox-notify-msg").html('<?= $msg; ?>');
        });
    <?php
    }
    ?>

    function DeleteCategory(cid) {
        $("#delcid").val(cid);
    }
</script>