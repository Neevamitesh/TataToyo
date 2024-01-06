<style>
    .page-title{
        display:none;
    }
</style>

<div class="row">
    <div class="col-lg-12 mx-auto">
        <div class="card">
            <div class="card-header gradient-forest">
                <div class="card-title text-white">
                    BatchDetails Report
                </div>
            </div>
            <div class="card-body">

                <form action="" id="sortform_batch" method="post">
                    <div class="row">
                        <div class="col-md-4 mx-auto">
                            <label>Consignee(s)</label>
                            <div class="form-group">
                                <select id="Consigneeid_Rep"  name="Consignee" class="form-control select2" required>
                                    <option value="">Select Consignee</option>
                                    <?php
                                        foreach ($consignees->result() as $key => $row) {
                                            ?>
                                            <option  data-name="<?= $row->consigneename?>" data-code="<?= $row->consigneecode?>" value="<?= $row->CMID?>"><?= $row->consigneename?></option>
                                            <?php
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 mx-auto"> 
                            <label>Productname(s)</label>
                            <div class="form-group">
                                <select id="Productid" name="Product" class="form-control select2" required>
                                    <option value="">Select Product</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 mx-auto"> 
                            <label>Productcode(s)</label>
                            <div class="form-group">
                                <select id="ProductCodes" name="ProductCode" class="form-control select2" required>
                                    <option value="">Select Productcode</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fromdate">From Date</label>
                                <input type="date" name="fromdate" value="<?= date('Y-m-d'); ?>" id="fromdate" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="todate">To Date</label>
                                <input type="date" name="todate" value="<?= date('Y-m-d'); ?>" id="todate" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <p id="errormsg" class="text-center text-danger"></p>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-info pull-right">
                                <i class="fa fa-search"> Get Details</i>
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div><!-- End Row-->

<div class="row">
    <div class="col-lg-12 mx-auto">
        <div class="card">
            <div class="card-header gradient-forest">
            <div class="card-title text-white"><i class="fa fa-table "></i> Data Record
                <a href="" class ="downloadexcel">
                    <button class="btn btn-gradient-orange downloadexcel" style= "margin-left:90%;margin-top:-2em;" id = "downloadexcel" value = "<?= base_url()?>">Download</button>
                </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
               
                    <table id="LiveData_batchdetails" class="table  table-bordered">
                        <thead>
                            <tr>
                                <th>SNo</th>
                                <th>BatchID</th>
                                <th>ConsigneeName</th>
                                <th>Location</th>
                                <th>ProductCode</th>
                                <th>VCode</th>
                                <th>Rev No</th>
                                <th>Date</th>
                                <th>SerialNo</th>
                                <th>GeneratedAt</th>
                                <th>BarcodeData</th>
                                
                            </tr>
                        </thead>
                        <tbody id="tablebody">
                          
                            
                        </tbody>
                        <tfoot>
                            <th>SNo</th>
                                <th>BatchID</th>
                                <th>ConsigneeName</th>
                                <th>Location</th>
                                <th>ProductCode</th>
                                <th>VCode</th>
                                <th>Rev No</th>
                                <th>Date</th>
                                <th>SerialNo</th>
                                <th>GeneratedAt</th>
                                <th>BarcodeData</th>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div><!-- End Row-->
<script>
 var priData;
          $(function(){
                priData = $('#LiveData_batchdetails').DataTable({
                    dom: 'Bfrtip',
                    buttons:[
                            // {
                            //     extend:'print',
                            //     filename:'Primary-Data', 
                            //     title:'Primary-Data'
                            // }
                    ],
                });  
                $("#Consigneeid_Rep").change(function(){
                    var Consignee = $("#Consigneeid_Rep").val();
                    console.log(Consignee);
                    $.ajax({
                        type: 'POST',
                        url: "<?= base_url('Reports/GetProductInfo')?>",
                        data:{
                            Consignee:Consignee,
                        },
                        success:function(data){
                            var data = JSON.parse(data);
                           console.log(data);
                            $("#Productid").html(data.Prd);
                            $("#ProductCodes").html(data.PrdCode);
                        }
                    });
                });
                $(document).on('submit','#sortform_batch',function(e) {
                    e.preventDefault();
                    // alert();return;
                    var formdata = $('#sortform_batch').serialize();
                    $.ajax({
                        type: 'POST',
                        url: '<?= base_url(); ?>Consignee/GetBatchDetailsReport',
                        data: formdata,
                        success: function(data){
                          //  console.log(data);return;
                        // var Obj = JSON.parse(data);
                            $('#LiveData_batchdetails').DataTable().clear().destroy();
                            $("#tablebody").html(data);
                            $('#LiveData_batchdetails').DataTable({
                                // destroy: true,
                                responsive: true,
                                dom: 'Bfrtip',
                                buttons: [
                                        // {
                                        //     extend:'print',
                                        //     filename:'BatchDetails-Data', 
                                        //     title:''
                                        // },
                                        // {
                                        //     extend:'pdf',
                                        //     filename:'BatchDetails-Data', 
                                        //     title:''
                                        // }
                                ]
                            });
                        }
                    });
                    var base=$('#baseurl').html();
                    $.ajax({
                        type: 'POST',
                        url: '<?= base_url(); ?>Reports/GetExcel',
                        data: formdata,
                      
                        success: function(data){
                            var Obj = JSON.parse(data);
                            if(Obj.Msg == 'Success' ){
                                $("#downloadexcel").val(base+Obj.ExcelFile);
                                $(".downloadexcel").attr('href',base+Obj.ExcelFile);
                            }else{
                                 $('#errormsg').html(Obj.Msg);
                                    setTimeout(function(){
                                        $("#errormsg").html("");
                                    },2500);
                                $("#downloadexcel").val(base+Obj.ExcelFile);
                                $(".downloadexcel").attr('href','CreateBatchReport');
                            }
                        }
                    });
                });  

                $('#Productid,#ProductCodes').on("change",function(e){
                    var ID = $(e.target).attr('id');
                    resetName(ID);
                });
                function resetName(id){
                    var id1 = '#Productid';        
                    var id2 = '#ProductCodes';
                    
                    // return;
                    if(`#${id}` == id1){
                        var val = $(id1).val();
                        $(`${id2} [value = "${val}"]`).prop('selected',true);
                      
                        
                    }else{
                        var val = $(id2).val();
                       var data =  $(`${id1} [value = "${val}"]`).prop('selected',true);
                    }
                    setTimeout(() => {
                        MySearch();
                    }, 20);
                }

               
          })

    
</script>