<div class="card">
    <div class="card-header text-white gradient-forest">Verify Reports</div>
    <div class="card-body">
        <div class="row">

            <div class="col-md-6">
                <div class="form-group">
                    <label>Scan Barcode Mode</label> <br>
                    <div class="form-check form-check-inline">
                        <input id="ScanType-Scan" type="radio" name="ScanType" value="Scan" checked class="ScanType form-check-input hidden">
                        <label for="ScanType-Scan" class="form-check-label">Single</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input id="ScanType-Type" type="radio" name="ScanType" value="Type"  class="ScanType form-check-input hidden">
                        <label for="ScanType-Type" class="form-check-label">Multiple</label>
                    </div>
                </div>
            </div>           
            <div class="col-md-3">
                <label for="Todate">To Date</label>
                <div class="form-group">
                    <input type="date" name="Todate" id="Todate" value = "<?=$ToDate;?>"class  = "form-control">
                </div>
            </div>
            <div class="col-md-3">
                <label for="Todate">From Date</label>
                <div class="form-group">
                    <input type="date" name="Todate" id="Fromdate" value = "<?=$FromDate;?>" class  = "form-control">
                </div>
            </div>
        </div>
        <div class="col-md-8 pl-5  mb-3 ">
            <label>Scan Barcode <i class="fa fa-inventory"></i> </label>
            <div class="input-group">
                <input type="text" id="scandata" placeholder="Enter Scan  Data." name="scandata" class="form-control col-12">
                <div class="input-group-append">
                    <button class="btn btn-gradient-forest waves-effect waves-light" hidden id="Save" type="button">
                        Scan
                    </button>
                </div>
            </div>
                <h5 class="text-center BatchStatus-Msg mt-2 text-success"></h5>
            <div class="col-12">
                <h5 class="text-center text-danger Prd-Msg"></h5>
            </div>
        </div>
    </div>
    <div class="card-body scandata hidden">
        <div class="row">
            <div class="col-md-6 col-12">
                <h4 class="text-primary text-center">
                    BarcodeData: <span class="Barcode-Data">N/A</span>
                </h4>
            </div>
            <div class="col-md-6 col-12">
                <h4 class="text-warning text-center">
                    Consignee: <span class="Consignee-Data">N/A</span>
                </h4>
            </div>
            <div class="col-md-6 col-12 mt-5">
                <h4 class="text-secondary text-center">
                    ProductName: <span class="ProductName-Data">N/A</span>
                </h4>
            </div>
            <div class="col-md-6 col-12 mt-5">
                <h4 class="text-Info text-center">
                    ProductCode: <span class="ProductCode-Data">N/A</span>
                </h4>
            </div>
            <div class="col-md-6 col-12 mt-5">
                <h4 class="text-danger text-center">
                    ScanBy: <span class="ScanBy-Data">N/A</span>
                </h4>
            </div>
            <div class="col-md-6 col-12 mt-5">
                <h4 class="text-success text-center">
                    ScanDate: <span class="ScanDate-Data">N/A</span>
                </h4>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <button type = "button" class = "btn btn-gradient-forest pull-right mr-3 getbtn hidden">Get</button>
    </div>
</div>
<?php if($VerifyRes->num_rows() > 0){
    ?>
        <div class="row">
            <div class="col-lg-12 mx-auto">
                <div class="card">
                    <div class="card-header"><i class="fa fa-table"></i> Scan Repo</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="dateLiveDatas" class="table default-datatable table-bordered">
                                <thead>
                                    <tr>
                                        <th>Sr. No.</th>
                                        <th>Barcode Data</th>
                                        <th>Consignee Name</th>
                                        <th>Product Name</th>
                                        <th>Product Code</th>
                                        <th>ScanBy</th>
                                        <th>ScanDate</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $c = 1;
                                        foreach($VerifyRes->result() as $row){
                                            ?>
                                                <tr>
                                                    <td><?=$c;?></td>
                                                    <td><?=$row->ScanData;?></td>
                                                    <td><?=$row->consigneename;?></td>
                                                    <td><?=$row->productname;?></td>
                                                    <td><?=$row->productcode;?></td>
                                                    <td><?=$row->username;?></td>
                                                    <td><?=$row->scandate;?></td>

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
    <?php
    }
?>
<script>
    $(function(){

        var timer = setTimeout(function(){},0);
        var scanTime = 800;
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
                    $('#scandata').val(`${ $('#scandata').val()},`)
                    
                }
            },scanTime);
            
        });
        $(document).on('change',"#Todate,#Fromdate",function(){
            
            var Todate = $('#Todate').val();
            var Fromdate = $('#Fromdate').val();
        
            if(Todate  && Fromdate){
                $('.getbtn').removeClass('hidden',true);
            }else{
                    $(".Prd-Msg").html("Data Not Found");
                        setTimeout(function(){
                            $(".Prd-Msg").html("");
                        },5000); 
            }
        });
        $('.getbtn').click(function(){
            var Todate = $('#Todate').val();
            var Fromdate = $('#Fromdate').val();
            if(Todate  && Fromdate){
                window.location.href="<?= base_url(); ?>Verify/BarcodeReport/"+Todate+'/'+Fromdate;
            }else{
                    $(".Prd-Msg").html("Please Select Date First");
                        setTimeout(function(){
                            $(".Prd-Msg").html("");
                        },5000);   
            }
        });
            $("#Save").click(function(){
                var ScanData = $("#scandata").val();
                $.ajax({
                    url: "<?= base_url('Verify/GetScanInfo')?>",
                    type: "POST",
                    data: {
                        ScanData: ScanData
                    },
                    success:function(data){
                        var obj = JSON.parse(data);
                        if(obj.msg == "Success"){
                            $('.scandata').removeClass('hidden',true);
                            $(".Barcode-Data").html(obj.ScanData);
                            $(".ProductName-Data").html(obj.PrdName);
                            $(".ProductCode-Data").html(obj.PrdCode);
                            $(".Consignee-Data").html(obj.Consignee);
                            $(".ScanBy-Data").html(obj.ScanBy);
                            $(".ScanDate-Data").html(obj.ScanDate);
                            $("#scandata").val('');
                        }else{
                                $(".Prd-Msg").html(obj.msg);
                                setTimeout(function(){
                                    $(".Prd-Msg").html("");
                                },5000);
                        }   
                        // t.resetForm();
                    }
                });
            });
    });
</script>