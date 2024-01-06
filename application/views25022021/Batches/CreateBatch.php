<?php
    $location = trim(strtolower($location));
?>
<style>
    .page-title{
        display:none;
    }
    .previews *{
        cursor:zoom-in;
    }
</style>
<div id="BaseURL" class="hidden"><?= base_url();?></div>
<div class="modal fade" id="view-prn">
    <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="<?= base_url('Consignee/AddVariables');?>" method="post">
                    <input type="hidden" name="Consignee" id="AddVariables-Consignee">
                    <div class="modal-header gradient-forest">
                        <h4 class="modal-title text-white">
                            View PRN
                        </h4>
                    </div>
                    <div class="modal-body">
                        <div id="preview"></div>
                        <div class="jumbotron gradient-forest">
                            <h3 class="text-center h6 text-white prev-titles">Select Product First For Preview</h3>
                        </div>
                    </div>
                </form>
            </div>
    </div>
</div>
<?php
    $Msg = $this->session->flashdata("Lab-CreateBatch");
    if(!empty($Msg)){
        ?>
            <div class="alert alert-success alert-dismissible <?= ($Msg['Res'])?'':"alert-danger"?>" role="alert">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <div class="alert-icon contrast-alert">
                    <i class="icon-check"></i>
                </div>
                <div class="alert-message">
                    <span><strong><?= ($Msg['Res'])?'Success!':"Failed!"?></strong> <?= $Msg["Msg"]?></span>
                </div>
            </div>
        <?php
    }
?>
<div class="card" id="Batch">
    <form action="<?= base_url('Consignee/PrintLable');?>" id="PrintLable" method="post">
        <div class="card-header gradient-forest">
            <div class="card-title text-white">
                Batch Section           
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-7">
                    <div class="card">
                        <div class="card-header gradient-forest">
                            <div class="card-title text-white">
                                Basic Information           
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label>Consignee(s)</label>
                                        <div class="input-group mb-3 main">
                                            <select id="Consignee" data-by="name" name="Consignee" class="form-control Sel-Sort" required>
                                                <option value="">Select Consignee</option>
                                                <?php
                                                
                                                    foreach ($Consignee->result() as $key => $row) {
                                                        ?>
                                                        <option  data-name="<?= $row->consigneename?>" data-code="<?= $row->consigneecode?>" value="<?= $row->CMID?>"><?= $row->consigneename?></option>
                                                        <?php
                                                    }
                                                ?>
                                            </select>
                                            <div class="input-group-append">
                                                <button type="button" id="" class="OrderBy btn btn-gradient-forest mx-auto waves-effect waves-light">By Name</button>
                                            </div>
                                            <!-- <div class="input-group-append">
                                                <button type="button" class="btn btn-gradient-scooter mx-auto waves-effect waves-light">Get Detail</button>
                                            </div> -->
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Productname(s)</label>
                                        <div class="input-group mb-3 main">
                                            <select id="Product" data-by="name" name="Product" class="form-control Sel-Sort" required>
                                                <option value="">Select Product</option>
                                            </select>
                                            <label>Productcode(s)</label>
                                            <select id="PrdCode" name="ProductCode" class="form-control" required>
                                                <option value="">Select Product Code</option>
                                            </select>
                                            
                                            <div class="input-group-append">
                                                <button type="button" id="" class="OrderBy btn btn-gradient-forest mx-auto waves-effect waves-light">By Name</button>
                                            </div>
                                            <div class="input-group-append">
                                                <button type="button" id="GetDetails" class="btn btn-gradient-scooter mx-auto waves-effect waves-light">Get Detail</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card main">
                                        <div class="card-header gradient-forest">
                                            <div class="card-title text-white">
                                                Additional Option(s)         
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="col-12 mx-auto AddOn-Msg">
                                                <h5 class="p-3 text-center gradient-scooter text-light">Select Consignee First</h5>
                                            </div>
                                            <div id="AddOns-Main" class="row">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-12">
                    <div class="card">
                        <div class="card-header gradient-forest">
                            <div class="card-title text-white">
                                Label Preview           
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="jumbotron gradient-forest">
                                <h3 class="text-center h6 text-white prev-titles">Select Product First For Preview</h3>
                            </div>
                            <div class="previews">
                                        
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header gradient-forest">
                    <div class="card-title text-white">
                        Printer (Status)           
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-5 col-md-4 col-12">
                            <label for="Printer">Printer(s)</label>
                            <div class="input-group">
                                <select name="Printer" id="printers" class="form-control mb-3" required>
                                    <option value="">Select Printer</option>
                                    <?php
                                    
                                        foreach ($Printer->result() as $row) {
                                            ?>
                                            <option value="<?= $row->PRID?>"><?= $row->PrinterName?></option>
                                            <?php
                                        }
                                        
                                    ?>
                                </select>
                                 <?php
                                  //print_r($location);
                                    if($location == 'sanand' || $location == 'pune' || $location == 'chakan' || $location == 'lucknow'){
                                        ?>
                                            <button id="fetchPrinter" class="btn btn-gradient-forest waves-effect waves-light" type="button">
                                                Get
                                            </button>
                                        <?php
                                    }
                                ?>
                            </div>
                            <h6 class="loading_message text-center"></h4>
                        </div>
                        <div class="col-lg-4 col-md-6 col-12">
                            <label for="Qty">Quantity</label>
                            <input type="number" name="Qty" value="1" id="Qty" min="1" class="form-control" required>
                        </div>
                        <!-- <?php
                        if($role == "SuperAdmin" || $role[0] == "SuperAdmin"){
                        ?>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="B-Level">Select Brigthness</label>
                            <select name="Brightness" id="B-Level" class="form-control mb-3" required>
                                <option value="0">Default</option>
                                <?php
                                    for ($i=15;$i<31;$i++) {
                                        ?>
                                        <option value="<?= $i?>"><?= $i?></option>
                                        <?php
                                    }
                                ?>
                            </select>
                        </div>
                        <?php
                         }
                        ?> -->
                          <?php
                        if($role == "SuperAdmin" || $role[0] == "SuperAdmin"){
                        ?>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label for="B-Level">Select Brightness</label>
                            <select name="darkness" id="darkness" class="form-control mb-3" required>
                                <option value="0">Default</option>
                                <?php
                                    for ($i=15;$i<31;$i++) {
                                        ?>
                                        <option value="<?= $i?>"><?= $i?></option>
                                        <?php
                                    }
                                ?>
                            </select>
                        </div>
                        <?php
                         }
                        ?>
                    </div>
                    <div id="printer_data_loading">
                        <span id="loading_message"></span>
                    </div>
                    <div id="error_div" style="width:500px; display:none"><div id="error_message" class="text-danger text-center"></div>
                        <button type="button" class="btn btn-lg btn-success" onclick="trySetupAgain();">Try Again</button>
                    </div><!-- /error_div -->
                    
                    <div class="col-12">
                        <h4 id="#BatchMsg-Err" class="text-center text-danger"></h4>
                    </div>

                </div>
                <div class="card-footer">
                    <button type="button" class="btn btn-gradient-forest shadow waves-light waves-effect" data-toggle="modal" data-target="#VariableDatas">View Variables</button>
                    <button type="button" id="PrintLable-Sub" class="btn btn-success shadow-success pull-right">Print</button>
                    <button type="button" id="GetExcel" class="btn btn-success shadow-success pull-right mr-3">Download</button>
                </div>
            </div>
            <div class="modal fade" id="VariableDatas">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header gradient-forest">
                            <div class="modal-title text-white">Variables Data</div>
                        </div>
                        <div class="modal-body">
                            <!-- Product Data -->
                            <div class="card">
                                <div class="card-header gradient-forest">
                                    <div class="card-title text-white">
                                        Product Data
                                    </div>
                                </div>
                                <div class="card-body">
                                <div class="table-responsive">
                                    <div id="default-datatable_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <table id="Product-Data-Table" class="table table-bordered dataTable table-hover table-striped shadow-light" role="grid" aria-describedby="default-datatable_info">
                                                    <thead>
                                                        <tr role="row">
                                                            <th class="sorting_asc" tabindex="0" aria-controls="default-datatable"
                                                                rowspan="1" colspan="1" aria-sort="ascending"
                                                                aria-label="Name: activate to sort column descending"
                                                                >Sr No.</th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="default-datatable" rowspan="1"
                                                                colspan="1" aria-label="Position: activate to sort column ascending"
                                                                >Key</th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="default-datatable" rowspan="1"
                                                                colspan="1" aria-label="Office: activate to sort column ascending"
                                                                >Value</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th rowspan="1" colspan="1">Sr No.</th>
                                                            <th rowspan="1" colspan="1">Key</th>
                                                            <th rowspan="1" colspan="1">Value</th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>         
                                    <div class="row" id="prd-data">
                                    </div>
                                </div>
                            </div>
                            <!--End Product Data -->
                            <!-- Consignee Data  -->
                            <div class="card">
                                <div class="card-header gradient-forest">
                                    <div class="card-title text-white">
                                        Consignee Data
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <div id="default-datatable_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <table id="Consignee-Data-Table" class="table table-bordered dataTable table-hover table-striped shadow-light" role="grid" aria-describedby="default-datatable_info">
                                                        <thead>
                                                        <tr role="row">
                                                            <th class="sorting_asc" tabindex="0" aria-controls="default-datatable"
                                                                rowspan="1" colspan="1" aria-sort="ascending"
                                                                aria-label="Name: activate to sort column descending"
                                                                >Sr No.</th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="default-datatable" rowspan="1"
                                                                colspan="1" aria-label="Position: activate to sort column ascending"
                                                                >Key</th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="default-datatable" rowspan="1"
                                                                colspan="1" aria-label="Office: activate to sort column ascending"
                                                                >Value</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                            
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <th rowspan="1" colspan="1">Sr No.</th>
                                                                <th rowspan="1" colspan="1">Key</th>
                                                                <th rowspan="1" colspan="1">Value</th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>   
                                    <div class="row" id="consignee-data">
                                    </div>
                                </div>
                            </div>
                            <!-- End Consignee Data  -->
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>
 <!--Data Tables js-->
 <script src="<?= base_url();?>assets/plugins/bootstrap-datatable/js/jquery.dataTables.min.js"></script>
    <script src="<?= base_url();?>assets/plugins/bootstrap-datatable/js/dataTables.bootstrap4.min.js"></script>
    <script src="<?= base_url();?>assets/plugins/bootstrap-datatable/js/dataTables.buttons.min.js"></script>
    <script src="<?= base_url();?>assets/plugins/bootstrap-datatable/js/buttons.bootstrap4.min.js"></script>
    <script src="<?= base_url();?>assets/plugins/bootstrap-datatable/js/jszip.min.js"></script>
    <script src="<?= base_url();?>assets/plugins/bootstrap-datatable/js/pdfmake.min.js"></script>
    <script src="<?= base_url();?>assets/plugins/bootstrap-datatable/js/vfs_fonts.js"></script>
    <script src="<?= base_url();?>assets/plugins/bootstrap-datatable/js/buttons.html5.min.js"></script>
    <script src="<?= base_url();?>assets/plugins/bootstrap-datatable/js/buttons.print.min.js"></script>
    <script src="<?= base_url();?>assets/plugins/bootstrap-datatable/js/buttons.colVis.min.js"></script>

<script>
    var Prd,Tab1,Tab2;
//07122019
    function resetName(id){
        var id1 = '#Product';        
        var id2 = '#PrdCode';
        console.log($('#Product').val(),$('#PrdCode').val());
        
        // return;
        if(`#${id}` == id1){
            var val = $(id1).val();
            $(`${id2} [value = "${val}"]`).prop('selected',true);
            console.log('val');
            
        }else{
            var val = $(id2).val();
            $(`${id1} [value = "${val}"]`).prop('selected',true);
            console.log(val);
        }
        setTimeout(() => {
            MySearch();
        }, 20);
    }
    //07122019
    $(function(){
        ReloadTable(null,null);
        var img = $("<img class='img-fluid'>");
        $("#Batch #GetDetails").click(function(){
            var Product = $("#Product").val();
            if(Product == "" || Product == undefined){
                alert("Please Select Product First !.");
                return false;
            }
            $.ajax({
                type: "POST",
                url: base+ "Consignee/GetBatchDetails",
                data:{
                    Product:Product
                },
                success:function(data){
                    if(data != "false"){
                        var Detail = JSON.parse(data);
                        ReloadTable(Detail.Product,Detail.Consignee);
                        GetPreview();
                    }else{
                        alert('Product Not Found');
                    }
                }
            });
        });
        // $("#getPreview").click(function(){
        //     GetPreview();
        // });
        $(".OrderBy").click(function(){
            var Select = $(this).parents('.main').find('select');
            if($(Select).attr('data-by')=="name"){
                $(this).html("By Code");
                $(Select).children('option').each(function(){
                    $(this).html($(this).attr('data-code'));
                });
                $(Select).attr('data-by',"code");
            }else{
                $(this).html("By Name");
                $(Select).children('option').each(function(){
                    $(this).html($(this).attr('data-name'));
                });
                $(Select).attr('data-by',"name");
            }
            MySearch();
        });
        $(document).on('click',".previews",function(){
            // if($(this).has('img').length > 0){
                $("#view-prn").modal();
            // }
        });
        // $("#Batch #Consignee").change(function(){
        //     GetPreview();
        // });

        function GetPreview() {
            $(".prev-titles").html("Wait.. <i class='fa fa-spinner fa-spin'></i>");
            $(".prev-titles").parent().show();
            $("#preview img, .previews img").remove();
            $("#preview , .previews").html("");
            var Consignee = $("#Consignee").val();
            var Product =  $("#Product").val();
            var Opt = $(".AddOnsOpts").map(function(){
                return $(this).val();
            }).get();

            $.ajax({
                url: base+"Consignee/GetPreview",
                type: "POST",
                data: {
                    Consignee:Consignee,
                    Product:Product,
                    Opt:Opt
                },
                success:function(data){
                    var Res = JSON.parse(data);
                    console.log(Res.File);
                                        
                    if(Res.Res){
                        // img.attr('src',Res.File);
                        // $(".prev-titles").parent().hide();
                        // $("#preview , .previews").html(img);
                        $(".prev-titles").parent().hide();
                        $("#preview , .previews").html(Res.File.Data);
                    }else{
                        $(".prev-titles").html(Res.File.Data);
                    }
                }
            });
        }
    });


    function ReloadTable(Prd,Consignee){
        if(Tab1 != undefined && Tab2 != undefined){
            Tab1.destroy();Tab2.destroy();
        }
        $("#Product-Data-Table tbody").html(Prd);
        $("#Consignee-Data-Table tbody").html(Consignee);
        // Tab1 = $('#Consignee-Data-Table').DataTable();
        // Tab2 = $('#Product-Data-Table').DataTable();
    }

    // $(document).on('click','.cust-rm-t2 , .cust-rm-t1',function(){
    //     if($(this).parents('tbody').attr('id') == "Product-Data-Table"){
    //         Tab2.row($(this).parents('tr')).remove().draw(false);
    //     }else{
    //         Tab1.row($(this).parents('tr')).remove().draw(false);
    //     }
        
    //     // var Consignee = $('#Consignee-Data-Table tbody').html();
    //     // var Prd = $('#Product-Data-Table tbody').html();
    //     // ReloadTable(Prd,Consignee);
    // });
    
</script>

 
    <script>
        $(document).ready(function () {
            // ReloadTable(null,null);

            // $(document).on('dblclick',"tbody td",function(){
            //     // console.log(Tab2.row(this).data() = "News");
            //     if($(this).hasClass("editable")){
            //         if($(this).find('input').length > 0){
            //             $(this).html($(this).find('input').val().trim());
            //         }else{
            //             $(this).html($('<input class="form-control" type="text" value=" '+ $(this).html().trim()+' ">'));
            //             $(this).find('input').focus();
            //         }
            //     }
            // });

            // $(document).on('focusout',"td",function(){
            //     TabForm($(this));
            // });
            // $(document).on('keyup',"td",function(e){
            //     if(e.keyCode===13){
            //         e.preventDefault();
            //         TabForm($(this));
            //     }
            // });

            // function TabForm(elem){
            //     if($(elem).hasClass("editable")){
            //         if($(elem).find('input').length > 0){
            //             $(elem).html($(elem).find('input').val());
            //         }
            //     }
            //     var Consignee = $('#Consignee-Data-Table tbody').html();
            //     var Prd = $('#Product-Data-Table tbody').html();
            //     ReloadTable(Prd,Consignee);
            //     ChangeData();
            // }
            // function ChangeData(){
            //     $(".titleSet").map(function(){
            //             $(this).parent().find('.titleData').val($(this).html());
            //     });
            //     $(" .detailSet").map(function(){
            //             $(this).parent().find('.detailData').val($(this).html());
            //     });
            // }
            // $("#PrintLable").on('keyup keypress',function(e){
            //     if(e.keyCode === 13){
            //         e.preventDefault();
            //     }
            // });
            // $(document).on('click',".cust-rm-tr1",function(){
            //     console.log($(this).parents('tr'));
            //     // Tab1.row($(this).parents('tr')).remove().draw();
            // });
            // $(document).on('click',".cust-rm-tr2",function(){
            //     console.log($(this).parents('tr'));
            //     // Tab2.row($(this).parents('tr')).remove().draw();
            // });
            // $("#PrintLable").submit(function(e){
            //     var inp = $("<input type='hidden'>");
            //     $(".editable").map(function(){
            //         inp.attr('name',$(this).attr('name'));
            //         inp.val($(this).html());
            //         if($(this).find('input').length >0){
            //             $(this).find('input').attr('name',$(this).attr('name'));
            //             $(this).find('input').val($(this).html());
            //         }else{
            //             $(this).appent(inp);
            //         }
            //     });
            //     e.preventDefault();
            //     return false;
            // });
        });
        $(function(){
            $("#Consignee").change(function(){
                var Consignee = $(this).val();
                $.ajax({
                    url: "<?= base_url('Consignee/GetBatchInfo')?>",
                    type: "POST",
                    data: {
                        Consignee: Consignee
                    },
                    success:function(data){
                        var data = JSON.parse(data);
                        $("#Product").html(data.Prd);
                        $("#PrdCode").html(data.PrdCode);
                        $("#AddOns-Main").html(data.Addons);
                        ($("#AddOns-Main .col-12").length > 0)?
                            $("#AddOns-Main").parents('.main').show()  : 
                                $("#AddOns-Main").parents('.main').hide();
                        ($("#AddOns-Main .col-12").length > 0)?
                            $(".AddOn-Msg").hide() : $(".AddOn-Msg").show() ;
                        MySearch();
                    }
                });
            });
        });

        $(function(){
//07122019
            $(document).on("change", "#Product,#PrdCode",function(e){
                var ID = $(e.target).attr('id');
                // alert(ID);
                resetName(ID);
            });

        $("#darkness").change(function(){
            // var PrevType = $('.PrevType:checked').val();
            // var PrevLink = (PrevType == "HTML")? "Consignee/GetPreview" : "Consignee/GetFileDemo";
            var Consignee =$('#Consignee').val();
            var darkness = $('#darkness').val();
            // console.log(No);
            //var Darkness = $(this).html();
            $.ajax({
                url: "<?= base_url('Consignee/SetDarkness')?>",
                // url: base+PrevLink, 
                type: "POST",
                data: {
                    Consignee:Consignee,
                    darkness: darkness,
                   // Darkness:Darkness 
                }, 
                success:function(data){
                    console.log(data);
                    // var data = JSON.parse(data);
                        // $("#Consignee").html(Consignee);
                        $("#No").html(darkness);
                      //  $("#Darkness").html(data.Darkness);
                }
                                 
            });
        });
    });

    $(function(){
        $('#GetExcel').click(async function(){
            var Base = $('#BaseURL').html();
            var Consignee = $("#Consignee").val();
            var Product = $("#Product").val();
            var Qty = $("#Qty").val();
            var AddOns = $('.AddOnsOpts').map(function () {
                return $(this).val();
            }).get();
            if(Base != "" && Consignee != "" && Product != "" && Qty != ""){
                $.ajax({
                    type: 'POST',
                    url: Base + 'Consignee/PrintLable',
                    data: {
                        Consignee: Consignee,
                        Product: Product,
                        Qty: Qty,
                        AddOns: AddOns
                    },
                    success: function (data) {
                        var Res = JSON.parse(data);
                        if (Res.Res == 'true') {  
                            window.location.href = Res.Excel;
                        } else {
                            $('#BatchMsg-Err').html(Res.Msg);
                        }
                    }
                });
            }else{
                alert('Please fill all the fileds');
            }
        });
        
    });

    function fetchPrinter(){
        $('#fetchPrinter').prop('disabled',true);
        $('.loading_message').removeClass('text-danger');
        $('.loading_message').addClass('text-success');
        $('.loading_message').html('fetching printer(s)...');
        $('#printers').html('<option value="" disabled>Select printer</option>');
        return new Promise((res,rej)=>{
            try{
                $.ajax({
                    type:'GET',
                    url: 'http://localhost:1800/GetPrinter',
                    success:function(data,status,xhr){
                        $('#fetchPrinter').prop('disabled',false);
                        var msg = 'Unable to fetch printer(s)';
                        if(xhr.status == 200){
                            var resp = JSON.parse(data);
                            if(resp.Status){
                                msg = '';
                                resp.Resp.forEach((row)=>{
                                    $('#printers').append(`<option value="${row.Name}">${row.Name}</option>`);
                                });
                            }
                        }
                        $('.loading_message').html(msg);
                    },
                    error:function(err){
                        console.log(err);
                        $('.loading_message').removeClass('text-success');
                        $('.loading_message').addClass('text-danger');
                        $('.loading_message').html('Please start "Neevsoft browser printer"');
                        $('#fetchPrinter').prop('disabled',false);
                    },
                    beforeSend:function(){

                    }
                });
            }catch(e){
            }
        });
    }
    ///...Previous working printer function ............///
    // function fetchPrinter(){
    //     $('#fetchPrinter').prop('disabled',true);
    //     $('.loading_message').removeClass('text-danger');
    //     $('.loading_message').addClass('text-success');
    //     $('.loading_message').html('fetching printer(s)...');
    //     $('#printers').html('<option value="" disabled>Select printer</option>');
    //     return new Promise((res,rej)=>{
    //         try{
    //             $.ajax({
    //                 type:'POST',
    //                 url: 'http://localhost:1800/index.php/Comm/GetPrinter',
    //                 success:function(data,status,xhr){
    //                     $('#fetchPrinter').prop('disabled',false);
    //                     var msg = 'Unable to fetch printer(s)';
    //                     if(xhr.status == 200){
    //                         var resp = JSON.parse(data);
    //                         if(resp.Status){
    //                             msg = '';
    //                             resp.Printers.forEach((row)=>{
    //                                 $('#printers').append(`<option value="${row}">${row}</option>`);
    //                             });
    //                         }
    //                     }
    //                     $('.loading_message').html(msg);
    //                 },
    //                 error:function(err){
    //                     $('.loading_message').removeClass('text-success');
    //                     $('.loading_message').addClass('text-danger');
    //                     $('.loading_message').html('Please start "Neevsoft browser printer"');
    //                     $('#fetchPrinter').prop('disabled',false);
    //                 },
    //                 beforeSend:function(){

    //                 }
    //             });
    //         }catch(e){
    //         }
    //     });
    // }

    async function GetFile() {
        var Base = $('#BaseURL').html();
        var Consignee = $("#Consignee").val();
        var Product = $("#Product").val();
        var Qty = $("#Qty").val();
        var AddOns = $('.AddOnsOpts').map(function () {
            return $(this).val();
        }).get();
        return new Promise(function (res, rej) {
            $.ajax({
                type: 'POST',
                url: Base + 'Consignee/PrintLable',
                data: {
                    Consignee: Consignee,
                    Product: Product,
                    Qty: Qty,
                    AddOns: AddOns
                },
                success: function (data) {
                    var Res = JSON.parse(data);
                    console.log(Res.File);
                    
                    if (Res.Res == 'true') {
                        res(Res.File);
                    } else {
                        showErrorMessage(Res.Msg);
                        rej(Res.Msg);
                    }
                }
            });
        });
    }

    function printFile(prnData){
        var param = {
            PrinterName: $('#printers').val(),
            PrnData: prnData
        };
        $('.loading_message').html('sending print request...');
        $.ajax({
            type:'POST',
            data: param,
            url: 'http://localhost:1800/PrintFile ',
            success:function(data,status,xhr){
                var msg = 'Unable to print';
                if(xhr.status == 200){
                    var resp = JSON.parse(data);
                    var text_class = (resp.Status)? 'text-success' : 'text-danger';
                    $('.loading_message').html(resp.Msg);
                }
            }
        });
    }
    ////////////......................Previousp Print function .......................////
    // function printFile(prnData){
    //     var param = {
    //         PrinterName: $('#printers').val(),
    //         PrnData: prnData
    //     };
    //     $('.loading_message').html('sending print request...');
    //     $.ajax({
    //         type:'POST',
    //         data: param,
    //         url: 'http://localhost:1800/index.php/Comm/printFile',
    //         success:function(data,status,xhr){
    //             var msg = 'Unable to print';
    //             if(xhr.status == 200){
    //                 var resp = JSON.parse(data);
    //                 var text_class = (resp.Status)? 'text-success' : 'text-danger';
    //                 $('.loading_message').html(resp.Msg);
    //             }
    //         }
    //     });
    // }
    </script>

<?php
    if(trim(strtolower($location)) == 'sanand' || trim(strtolower($location)) == 'pune' || trim(strtolower($location)) == 'lucknow' || trim(strtolower($location)) == 'chakan'){
     ?>
        <script>
            $('#PrintLable-Sub').click(async function (e) {
                if ($('#PrintLable')[0].checkValidity()) {
                    var file = await GetFile();
                    printFile(file);
                } else {
                    alert("Please Fill All The Filed(s)");
                }
            });
            $('#fetchPrinter').click(function(){
                fetchPrinter();
            });
            fetchPrinter();
        </script>
     <?php
    }else{
        ?>
        <script type="text/javascript" src="<?= base_url('assets/js/BrowserPrint-1.0.4.js')?>"></script>
        <script type="text/javascript" src="<?= base_url('assets/js/DevDemo.js')?>"></script>
        <script type="text/javascript">
            var OSName="Unknown OS";
            if (navigator.appVersion.indexOf("Win")!=-1) OSName="Windows";
            //{
            //OSName="Windows";
            //document.write('<a href="ZebraWebPrint.exe" class="navbar-brand" href="#">Download the '+OSName+' App</a>');
            //}
            if (navigator.appVersion.indexOf("Mac")!=-1) OSName="MacOS";
            if (navigator.appVersion.indexOf("X11")!=-1) OSName="UNIX";
            if (navigator.appVersion.indexOf("Linux")!=-1) OSName="Linux";
        </script>
        <?php
    }
?>

