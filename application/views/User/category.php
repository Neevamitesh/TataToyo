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
    <div class="col-lg-4 mx-auto">
        <div class="card">
            <div class="card-body">
                <div class="card-title">Add Category</div>
                <hr>
                <form method="post" action="<?= base_url(); ?>Category/UploadCategory">
                    <div class="form-group">
                        <label for="input-7">Category</label>
                        <input type="text" name="category" required class="form-control form-control-rounded" id="category" placeholder="Enter Category Name">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary shadow-primary pull-right btn-round px-5"><i class="icon-plus"></i> Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6 mx-auto">
        <div class="card">
            <div class="card-header"><i class="fa fa-table"></i> Category</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="" class="table default-datatable table-bordered">
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Category</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $c = 1;
                            foreach ($category->result() as $cat) {
                                ?>
                                <tr>
                                    <td><?= $c; ?></td>
                                    <td><?= $cat->Category_Name; ?></td>
                                    <td>
                                        <button type="button" class="btn btn-outline-info waves-effect waves-light m-1" data-target="#editmodal" onclick="EditCategory(<?= $cat->CID; ?>)" data-toggle="modal" title="Edit"> <i class="icon-pencil"></i> </button>
                                        <button type="button" class="btn btn-outline-danger waves-effect waves-light m-1" title="Delete" data-target="#delmodal" onclick="DeleteCategory(<?= $cat->CID; ?>)" data-toggle="modal"> <i class="fa fa-trash-o"></i> </button>
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