<style>
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
<div class="modal fade" id="delmodal" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-star"></i> Error Message</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
               
                    <div class="alert alert-outline-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert"></button>
                        <div class="alert-icon">
                            <i class="icon-exclamation"></i>
                        </div>
                        <div class="alert-message res-msg">
                            
                        </div>
                    </div>
                    
                    <button type="button" class="btn btn-danger btn-round shadow-danger waves-effect pull-right waves-light m-1" data-dismiss="modal"><i class="icon-trash"></i> Cancel</button>
                
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header text-white gradient-forest">Verify Product</div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
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
                        <!-- <div class="input-group-append mt-5">
                            <button type="button" id="GetDetails" class="btn btn-gradient-scooter mx-auto waves-effect waves-light">Get Detail</button>
                        </div> -->
                    </div>
                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="date" name="Date" id="Date" placeholder="Select Date" class="form-control">
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
                        <?php
                    // if($UserRole == "Admin"){
                        ?>
                        <!-- <h5>SHIFT : <?= $shift?></h5> -->
                        <?php
                    // }
                    ?>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Scan Barcode Mode</label> <br>
                        <div class="form-check form-check-inline">
                            <input id="ScanType-Scan" type="radio" name="ScanType" value="Scan" checked class="ScanType form-check-input hidden">
                            <label for="ScanType-Scan" class="form-check-label">Single</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input id="ScanType-Type" type="radio" name="ScanType" value="Type" class="ScanType form-check-input hidden">
                            <label for="ScanType-Type" class="form-check-label">Multiple</label>
                        </div>
                    </div>
                </div>           
                <div class="col-md-12  mb-3 ">
                    <!-- <label>Scan Barcode <i class="fa fa-inventory"></i> </label> -->
                    <div class="input-group">
                        <input type="text" id="scandata" placeholder="Enter Scan Tertiary Data." name="scandata" class="form-control col-12">
                        <div class="input-group-append">
                            <button class="btn btn-gradient-forest waves-effect waves-light" hidden id="Save" type="button">
                                Scan
                            </button>
                        </div>
                    </div>
                     <h5 class="text-center BatchStatus-Msg mt-2 text-success"></h5>
                    <div class="col-12">
                                <h5 class="text-danger Prd-Msg"></h5>
                    </div>
                </div>
            </div>
            <div class="col-md-6 previewcard ">
                <div class="card">
                    <div class="card-header text-white gradient-forest">Label</div>
                    <div class="card-body">
                        <div class="jumbotron gradient-forest">
                            <h3 class="text-center h6 text-white prev-titles">Select Location First For Label Preview</h3>
                        </div>
                        <div class="previews text-danger">
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- </div> -->
        </div>
    </div>
</div>
<script>

var timer = setTimeout(function(){},0);
        var scanTime = 800;
     var Prd,Tab1,Tab2;
     var img = $("<img class='img-fluid'>");
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

    $('.ScanType').change(function(){
        $('#Save').prop('hidden',($('.ScanType:checked').val() == "Scan"));    
    });

    $('#scandata').on('input',function(){
            var scanType = $('.ScanType:checked').val();
            clearTimeout(timer);
            timer = setTimeout(function(){
                // var scanData = $('#scandata').val();
                // $('#scandata').val(setScanData(scanData));
                if(scanType == 'Scan'){
                    $('#Save').click();
                }else {
                    $('#scandata').val(`${ $('#scandata').val()},`);
                    var t = $('#scandata').val();
                    t = t.split(',').filter(function(row){
                        return row.trim() != "";
                    }).length;
                    if(t == $('#Product option:selected').attr('data-Barcodecount')) $('#Save').click() ;
                    console.log(t,$('#Product option:selected').attr('data-Barcodecount'));
                }
            },scanTime);
        });
        $(function(){
            $("#Consignee").change(function(){
                var Consignee = $(this).val();
                $.ajax({
                    url: "<?= base_url('Consignee/GetVerifyBatchInfo')?>",
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
    });
    function filterScanData(data){
        data = data.toString().trim();
        return data.substr(data.length - 1,1) == ',' ? data.substr(0,data.length - 1) : data;
    }
    $("#Save").click(function(){
        var productid = $("#Product").val();
        var Consignee = $("#Consignee").val();
        var ScanData = $("#scandata").val();
        ScanData = filterScanData(ScanData);
        var Dates = $("#Date").val();
        var AddOns = $('.AddOnsOpts').map(function () {
                return $(this).val();
            }).get();
        if(Consignee == "" || productid == "" || ScanData == ""){
            $(".Prd-Msg").html("Please Insert Above Fields First !!!");
            setTimeout(function(){
                $(".Prd-Msg").html("");
            },5000);
            return false;
        }else{
            
            $(".prev-titles").html("Wait.. <i class='fa fa-spinner fa-spin'></i>");
            $(".prev-titles").parent().show();
            $("#preview img, .previews img").remove();
            $("#preview , .previews").html("");
                $.ajax({
                    type: "POST",
                    url: base+ "Verify/GetData",
                    data:{
                        Consignee:Consignee,
                        productid:productid,
                        ScanData : ScanData,
                        AddOns : AddOns,
                        Date : Dates
                    },
                    success:function(data){ 
                        $("#scandata").val(""); //for  reset textbox
                        $("#scandata").focus(); //for focus textbox after add
                        var obj = JSON.parse(data);
                        console.log(obj);
                        if(obj.Msg == "success" ){
                            $(".prev-titles").parent().hide();
                            $("#preview , .previews").html(obj.File);
                            $(".BatchStatus-Msg").html("Data Save Successfully");
                            setTimeout(function(){
                                $(".BatchStatus-Msg").html("");
                            },5000);
                            
                        }else{
                            $(".prev-titles").parent().hide();
                            $("#preview , .previews").html(obj.Msg);
                            $('#delmodal').modal('show');   
                            $('.res-msg').html(obj.Msg);   
                            // new Audio('https://s3-us-west-2.amazonaws.com/s.cdpn.io/123941/Yodel_Sound_Effect.mp3').play();                      
                            new Audio('https://media.geeksforgeeks.org/wp-content/uploads/20190531135120/beep.mp3').play();   
                        }
                        
                    }
                });
            }
        });
        
    $("#scandata").focus();
</script>