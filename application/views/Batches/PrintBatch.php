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
    <form action="<?= base_url('Consignee/PrintBarcodeLable');?>" id="PrintLable" method="post">
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
                                            <!-- <div class="input-group-append">
                                                <button type="button" id="" class="OrderBy btn btn-gradient-forest mx-auto waves-effect waves-light">By Name</button>
                                            </div> -->
                                            <!-- <div class="input-group-append">
                                                <button type="button" class="btn btn-gradient-scooter mx-auto waves-effect waves-light">Get Detail</button>
                                            </div> -->
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>ProductExcel(s)</label>
                                        <div class="input-group mb-3 main">
                                            <select id="Product" data-by="name" name="Product" disabled class="form-control Sel-Sort" required>
                                                <option value="">Select Excel</option>
                                            </select>
                                            <div class="input-group-append">
                                                <button type="button" id="GetDetails" class="btn btn-gradient-scooter mx-auto waves-effect waves-light">Get Detail</button>
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
                                <select name="Printer" id="printers" class="form-control mb-3" >
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
                                    if($location == 'sanand' || $location == 'pune'){
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
                    <!-- <button type="button" class="btn btn-gradient-forest shadow waves-light waves-effect" data-toggle="modal" data-target="#VariableDatas">View Variables</button> -->
                    <button type="button" id="PrintBarcodeLable" class="btn btn-success shadow-success pull-right">Print</button>
                   
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
   
    //07122019
    $(function(){
        ReloadTable(null,null);
        var img = $("<img class='img-fluid'>");
        $("#Batch #GetDetails").click(function(){
            $(".prev-titles").html("Wait.. <i class='fa fa-spinner fa-spin'></i>");
            $(".prev-titles").parent().show();
            $("#preview img, .previews img").remove();
            $("#preview , .previews").html("");
            var Product = $("#Product").val();
            var Consignee = $("#Consignee").val();
            if(Product == "" || Product == undefined){
                alert("Please Select Excel First !.");
                return false;
            }
            $.ajax({
                type: "POST",
                url: base+ "Consignee/GetPrintBatchDetails",
                data:{
                    Product:Product,
                    Consignee : Consignee
                },
                success:function(data){
                    //console.log(data);
                    //console.log(data);
                    var Detail = JSON.parse(data);
                        $(".prev-titles").parent().hide();
                        $("#preview , .previews").html(Detail.File);
                }
            });
        });
        $("#PrintBarcodeLable").click(function(){
            var Product = $("#Product").val();
            var Consignee = $("#Consignee").val();
            if(Product == "" || Product == undefined){
                alert("Please Select All Fields First !.");
                return false;
            }
            $.ajax({
                type: "POST",
                url: base+ "Consignee/PrintBarcodeLabel",
                data:{
                    Product:Product,
                    Consignee : Consignee
                },
                success:function(data){
                   
                        var Detail = JSON.parse(data);
                        printFile(Detail.PrnFile);
                    // }else{
                    //     alert('File Not Found');
                    // }
                }
            });
        });
        // $("#getPreview").click(function(){
        //     GetPreview();
        // });
       
        $(document).on('click',".previews",function(){
            // if($(this).has('img').length > 0){
                $("#view-prn").modal();
            // }
        });
      

      
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

    
</script>

 
    <script>
       
        $(function(){
            $("#Consignee").change(function(){
                var Consignee = $(this).val();
                $.ajax({
                    url: "<?= base_url('Consignee/GetPrintBatchInfo')?>",
                    type: "POST",
                    data: {
                        Consignee: Consignee
                    },
                    success:function(data){
                        var data = JSON.parse(data);
                        $("#Product").html(data.PrdCode);
                      
                        MySearch();
                    }
                });
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

    async function GetFile() {
        var Product = $("#Product").val();
        var Consignee = $("#Consignee").val();
        return new Promise(function (res, rej) {
            $.ajax({
                type: 'POST',
                url: '<?=base_url();?>Consignee/PrintBarcodeLabel',
                data: {
                    Consignee: Consignee,
                    Product: Product,
                    // Qty: Qty,
                    // AddOns: AddOns
                },
                success: function (data) {
                    var Res = JSON.parse(data);
                    console.log(Res);
                    
                    if (Res.Status == 'true') {
                        res(Res.PrnFile);
                    } else {
                        console.log("Something Went Wrong in Label");
                        // rej("Something Went Wrong in Label");
                    }
                }
            });
        });
    }

    function printFile(prnData){
        console.log('Prn Data: ',prnData);
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
                console.log(data);
                var msg = 'Unable to print';
                if(xhr.status == 200){
                    var resp = JSON.parse(data);
                    var text_class = (resp.Status)? 'text-success' : 'text-danger';
                    $('.loading_message').html(resp.Msg);
                }
            }
        });
    }
    </script>

<?php
    if(trim(strtolower($location)) == 'sanand' || trim(strtolower($location)) == 'pune'){
     ?>
        <script>
            $('#PrintBarcodeLable').click(async function (e) {return;
                if ($('#PrintLable')[0].checkValidity()) {
                    var file = await GetFile();
                    console.log(file);
                    printFile(file);
                    console.log('fine');
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

