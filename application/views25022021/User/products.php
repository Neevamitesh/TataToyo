<style>
    .upload-chooser {
        border: 2px dotted #ccc;
        padding: 7px;
        clear: both;
        color: gray;
        cursor: pointer;
        width: 100%;
    }
    #uploadbtn{
        margin-top: 30%;
    }
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        margin: 0; 
    }
</style>

<div class="row">
    <div class="col-lg-6 mx-auto">
        <div class="card">
            <div class="card-body">
                <div class="card-title">Upload Products Excel</div>
                <b><p>Allowed File Formats (.xlsx, .csv)</p></b>
                <hr>
                <form method="post" action="<?= base_url(); ?>Products/UploadProductsExcel" id="excelform" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="consignee">Consignee</label>
                                <select class="form-control form-control-rounded" onchange = "GetData(this)" required id="consignee" name="consignee">
                                    <option value="" selected disabled>Select Consignee</option>
                                    <?php
                                    foreach ($consignees->result() as $cat) {
                                        ?>
                                        <option <?= ($cat->CMID == $CMID)?"selected":"" ?> value="<?= $cat->CMID; ?>"><?= $cat->consigneename." (".$cat->consigneecode.")"." (".$cat->LOC.")"; ?></option>
                                    <?php
                                }
                                ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <label for="">Excel File</label>
                            <label class="upload-chooser" for="excel">
                                <center>
                                    Select Product Excel <i class="fa fa-folder-open"></i>
                                </center>
                                <input type="file" class="banner form-control" onchange="banners(this, 'excel')" required accept=".xlsx, .xls, .csv" name="excel" id="excel" style="display: none;">
                            </label>
                            <center>
                                <p class="text-muted" id="excelname">
                                    <span style="color:red;">
                                        Please Select Excel File
                                    </span>
                                </p>
                            </center>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" id="uploadbtn" class="btn btn-primary btn-sm pull-right">Upload</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <!-- <p class="percent">0</p> -->
                            <div class="progress hidden" id="progressdiv">
                                <div class="progress-bar bar gradient-scooter" style="width:0%"></div>
                            </div>
                        </div>
                    </div>
                    <div id="status"></div>
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
                                <th>Consignee Code</th>
                                <th>Consignee Name</th>
                                <th>Product Name</th>
                                <th>Product Code</th>
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
                                    <td><?= $pro->consigneecode; ?></td>
                                    <td><?= $pro->consigneename; ?></td>
                                    <td><?= $pro->productname; ?></td>
                                    <td><?= $pro->productcode; ?></td>
                                    <td>
                                        <button type="button" class="btn btn-outline-info waves-effect waves-light m-1" onclick="viewProduct(<?= $pro->id; ?>)" title="Edit"> <i class="icon-pencil"></i> </button>
                                        <button type="button" class="btn btn-outline-danger waves-effect waves-light m-1" title="Delete" data-target="#delmodal" onclick="DeleteProduct(<?= $pro->id; ?>)" data-toggle="modal"> <i class="fa fa-trash-o"></i> </button>
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

    $("#excelform").on("submit", function(e){
        // e.preventDefault();
        // var formData = $("#excelform").serialize();
        $("#uploadbtn").html("Uploading...");
        $("#uploadbtn").attr("disabled", true);
        // $.ajax({
        //     type: "POST",
        //     url: "<?= base_url(); ?>Products/UploadProductsExcel",
        //     data: formData,
        //     success: function(data){
        //         if(data == "success"){
        //             round_info_noti();
        //             $(".lobibox-notify-msg").html('Product Added !');
        //         }
        //         else if(data == "Invalid Excel Format !"){
        //             round_error_noti();
        //             $(".lobibox-notify-msg").html('Invalid Excel Format !');
        //         }
        //         else{
        //             round_error_noti();
        //             $(".lobibox-notify-msg").html('Something went wrong !');
        //         }
        //     }
        // });
    });

    // $(function() {

    //     var bar = $('.bar');
    //     var percent = $('.percent');
    //     var status = $('#status');

    //     $('form').ajaxForm({
    //         beforeSend: function() {
    //             $("#progressdiv").removeClass("hidden");
    //             status.empty();
    //             var percentVal = '0%';
    //             bar.width(percentVal);
    //             percent.html(percentVal);
    //         },
    //         uploadProgress: function(event, position, total, percentComplete) {
    //             var percentVal = percentComplete + '%';
    //             bar.width(percentVal);
    //             percent.html(percentVal);
    //         },
    //         complete: function(xhr) {
    //             status.html(xhr.responseText);
    //         }
    //     });
    // }); 

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
            if (msg == "success" || msg == "Product Updated !") {
                round_info_noti();
            } else {
                round_error_noti();
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
    function GetData(input){
        var cmid = input.value;
       // var comp = encodeURIComponent($('#serbox').val());
        window.location.href="<?= base_url(); ?>Products/index/"+cmid;
    }
</script>