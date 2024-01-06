<div class="row">
    <div class="col-lg-12 mx-auto">
        <div class="card">
            <div class="card-body">
                <div class="card-title">Add Product</div>
                <hr>
                <form method="post" action="<?= base_url(); ?>Products/UploadProducts" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="consignee">Consignee</label>
                                <select class="form-control form-control-rounded" required id="consignee" name="consignee">
                                    <option value="" selected disabled>Select Consignee</option>
                                    <?php
                                    foreach ($consignees->result() as $cat) {
                                        ?>
                                        <option value="<?= $cat->CMID; ?>"><?= $cat->consigneename." (".$cat->consigneecode.")"; ?></option>
                                    <?php
                                }
                                ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="category">Product Category</label>
                                <select class="form-control form-control-rounded" required id="category" name="category">
                                    <option value="" selected disabled>Select Category</option>
                                    <?php
                                    foreach ($category->result() as $cat) {
                                        ?>
                                        <option value="<?= $cat->CID; ?>"><?= $cat->Category_Name; ?></option>
                                    <?php
                                }
                                ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="productcode">Product Code</label>
                                <input type="text" required name="productcode" required class="form-control form-control-rounded" id="productcode" placeholder="Enter Product Code">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="productname">Product Name</label>
                                <input type="text" required name="productname" required class="form-control form-control-rounded" id="productname" placeholder="Enter Product Name">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="price">Price</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text form-control-rounded"><i class="fa fa-rupee"></i></span>
                                </div>
                                <input type="number" required name="price" required class="form-control form-control-rounded" id="price" placeholder="Enter Price">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="qty">Quantity</label>
                                <input type="number" required name="qty" required class="form-control form-control-rounded" id="qty" placeholder="Enter Quantity">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="descone">Description</label>
                                <input type="text" required name="descone" required class="form-control form-control-rounded" id="descone" placeholder="Enter Description">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <br>
                            <label class="upload-chooser" for="banner">
                                <center>
                                    Select Product Image <i class="fa fa-folder-open"></i>
                                </center>
                                <input type="file" class="banner form-control" onchange="banners(this, 'add')" required accept="image" name="banner" id="banner" style="display: none;">
                            </label>
                            <center>
                                <p class="text-muted" id="bannername">
                                    <span style="color:red;">
                                        Please Select Banner Image
                                    </span>
                                </p>
                            </center>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-outline-primary waves-effect waves-light m-1" onclick="AddField('add')" title="Add Field"> <i class="fa fa-plus"></i> </button>
                        </div>
                    </div>
                    <br>
                    <div class="row" id="productdetail">
                        <div class="col-md-3 field">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                <button class="btn btn-danger" type="button"><i class="fa fa-asterisk"></i></button>
                                </div>
                                <input type="text" class="form-control form-control-rounded" required name="key[]" required placeholder="Enter Attribute Key">
                            </div>
                        </div>
                        <div class="col-md-3 field">
                            <div class="form-group">
                                <input type="text" required name="value[]" required class="form-control form-control-rounded" id="" placeholder="Enter Attribute Value">
                            </div>
                        </div>
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
    <div class="col-lg-12 mx-auto" id="editproducts">
        
    </div>
</div><!-- End Row-->

<div class="row">
    <div class="col-lg-12 mx-auto">
        <div class="card">
            <div class="card-header"><i class="fa fa-table"></i> Products</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="" class="table default-datatable table-bordered">
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Category</th>
                                <th>Product Code</th>
                                <th>Product Name</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Description One</th>
                                <th>Description Two</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $c = 1;
                            foreach ($Products->result() as $pro) {
                                ?>
                                <tr>
                                    <td><?= $c; ?></td>
                                    <td><?= $pro->Category_Name; ?></td>
                                    <td><?= $pro->productcode; ?></td>
                                    <td><?= $pro->productname; ?></td>
                                    <td><?= $pro->productprice; ?></td>
                                    <td><?= $pro->productstockquantity; ?></td>
                                    <td><?= $pro->productdescriptionone; ?></td>
                                    <td><?= $pro->productdescriptiontwo; ?></td>
                                    <td>
                                        <button type="button" class="btn btn-outline-info waves-effect waves-light m-1" onclick="viewProduct(<?= $pro->id; ?>)" title="Edit"> <i class="icon-pencil"></i> </button>
                                        <button type="button" class="btn btn-outline-danger waves-effect waves-light m-1" title="Delete" data-target="#delmodal" onclick="DeleteCategory(<?= $pro->id; ?>)" data-toggle="modal"> <i class="fa fa-trash-o"></i> </button>
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

    $(function() {

        var bar = $('.bar');
        var percent = $('.percent');
        var status = $('#status');

        $('form').ajaxForm({
            beforeSend: function() {
                $("#progressdiv").removeClass("hidden");
                status.empty();
                var percentVal = '0%';
                bar.width(percentVal);
                percent.html(percentVal);
            },
            uploadProgress: function(event, position, total, percentComplete) {
                var percentVal = percentComplete + '%';
                bar.width(percentVal);
                percent.html(percentVal);
            },
            complete: function(xhr) {
                status.html(xhr.responseText);
            }
        });
    }); 

    function banners(input, type) {
        var file = input.files[0];
        var fileName = input.files[0].name;
        // var type = input.files[0].type;
        // console.log("bannername"+id);
        // console.log(type);
        if(type == "add")
            $("#bannername").html(fileName);
        else if(type=="edit")
            $("#editbannername").html(fileName);
        else if(type=="excel")
            $("#excelname").html(fileName);
    }
    // <div class="form-group">
    //                 <label for="">Attribute Key</label>
    //                 <input type="text" required name="key[]" required class="form-control form-control-rounded" id="" placeholder="Enter Attribute Key">
    //             </div>
    var c = 1;
    function AddField(type){
        var data = `
            <div class="col-md-3 field`+type+``+c+`">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                    <button class="btn btn-danger" onclick="DeleteField('`+c+`', '`+type+`')" type="button"><i class="fa fa-minus"></i></button>
                    </div>
                    <input type="text" class="form-control form-control-rounded" required name="key[]" required placeholder="Enter Attribute Key">
                </div>
            </div>
            <div class="col-md-3 field`+type+``+c+`">
                <div class="form-group">
                    <input type="text" required name="value[]" required class="form-control form-control-rounded" id="" placeholder="Enter Attribute Value">
                </div>
            </div>
        `;
        if(type == "add"){
            $('#productdetail').append(data);
        }
        else{
            $('#editvariables').append(data);
        }
        c++;
    }

    function DeleteField(c, type){
        $('.field'+type+''+c).remove();
        // alert(c);
    }

    function DeleteNewField(pdid){
        $('.newfield'+pdid).remove();
        // alert(c);
    }

    <?php
    if (!empty($msg)) {
        ?>
        var msg = "<?= $msg; ?>";
        $(document).ready(function() {
            if (msg == "Something went wrong !") {
                round_error_noti();
            } else {
                round_info_noti();
            }
            $(".lobibox-notify-msg").html('<?= $msg; ?>');
        });
        <?php
    }
    ?>

    function viewProduct(pid) {
        $.ajax({
            type: "POST",
            url: "<?= base_url(); ?>Products/ViewProduct",
            data: {
                pid: pid
            },
            success: function(data) {
                $("#editproducts").html(data);
                $("html, body").animate({ scrollTop: 0 }, "slow");
                // return false;
            }
        });
    }
</script>