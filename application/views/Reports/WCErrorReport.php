<!-- <div class="modal fade" id="largesizemodal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-star"></i> Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias, dicta. Voluptate cumque odit quam velit maiores sint rerum, dolore impedit commodi. Tempora eveniet odit vero rem blanditiis, tenetur laudantium cumque.</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias, dicta. Voluptate cumque odit quam velit maiores sint rerum, dolore impedit commodi. Tempora eveniet odit vero rem blanditiis, tenetur laudantium cumque.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                <button type="button" class="btn btn-primary"><i class="fa fa-check-square-o"></i> Save changes</button>
            </div>
        </div>
    </div>
</div> -->

<div class="modal fade" id="largesizemodal">
    <div class="modal-dialog modal-md">
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
                            <h3 class="text-center h6 text-white prev-titles">Fetching Preview <i class="fa fa-spin fa-spinner"></i> </h3>
                        </div>
                    </div>
                </form>
            </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 mx-auto">
        <div class="card">
            <div class="card-header"><i class="fa fa-table"></i> Sort By</div>
            <div class="card-body">
                <?php
                // if ($role[0] == "SuperAdmin") {
                    ?>
                    <form action="" id="sortform" method="post">
                        <div class="row">
                            <div class="col-md-4 mx-auto">
                                <div class="form-group">
                                    <label for="user">Consignee</label>
                                    <select class="form-control form-control-rounded" required id="user" name="user">
                                        <option value="" selected disabled>Select Consignee</option>
                                        <?php
                                        foreach ($consignees->result() as $loc) {
                                            ?>
                                            <option <?= $CMID == $loc->CMID ? "selected" : ""; ?> value="<?= $loc->CMID; ?>"><?= $loc->consigneename . " (" . $loc->consigneecode . ")"; ?></option>
                                        <?php
                                    }
                                    ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 mx-auto">
                                <div class="form-group">
                                    <label for="fromdate">From Date</label>
                                    <input type="date" name="fromdate" value="<?= $fromdate != null ? $fromdate : date('Y-m-d'); ?>" id="fromdate" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-4 mx-auto">
                                <div class="form-group">
                                    <label for="todate">To Date</label>
                                    <input type="date" name="todate" value="<?= $todate != null ? $todate : date('Y-m-d'); ?>" id="todate" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-info pull-right">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                <?php
            // }
            ?>
            </div>
        </div>
    </div>
</div><!-- End Row-->

<div class="row">
    <div class="col-lg-12 mx-auto">
        <div class="card">
            <div class="card-header"><i class="fa fa-table"></i> Error Report</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="LiveData" class="table default-datatable table-bordered">
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Consignee Name</th>
                                <th>ProductName / Product Code</th>
                                <th>Error Message</th>
                                <th>Printer Dpi</th>
                                <th>CreatedAt</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if($isdata == 1){
                                    $c = 1;
                                    foreach($wcerror->result() as $verified){
                                        ?>
                                            <tr>
                                                <td><?= $c; ?></td>     
                                                <td><?= $verified->consigneename; ?></td>
                                                <td><?= $verified->Product; ?></td>
                                                <td><?= $verified->ErrorMsg; ?></td>
                                                <td><?= $verified->PrinterDpi; ?></td>
                                                <td><?= date('d-m-Y h:i:s a', strtotime($verified->CreatedAt)); ?></td>
                                            </tr>
                                        <?php
                                        $c++;
                                    }
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
    $("#sortform").submit(function(e){
        e.preventDefault();
        var cmid = $("#user").val();
        var fdate = $("#fromdate").val();
        var tdate = $("#todate").val();
        window.location.href = "<?= base_url(); ?>Reports/WCErrorReport/" + cmid+"/"+fdate+"/"+tdate;
    });

    function viewLabel(cmid) {
        
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
var Consignee;
$(document).on('click',".previews",function(){
    Consignee = $(this).parents('.main').find('.Ref').val();
    console.log(Consignee);
    
        GetPreview();
        $("#view-prn").modal();
});
var img = $("<img class='img-fluid'>");
function GetPreview() {
    $(".prev-titles").html("Wait.. <i class='fa fa-spinner fa-spin'></i>");
    $(".prev-titles").parent().show();
    $("#preview img, .previews img").remove();
    
    $.ajax({
        url: base+"Consignee/GetFileDemo",
        type: "POST",
        data: {
            Consignee:Consignee,
        },
        success:function(data){
            var Res = JSON.parse(data);
            if(Res.Res){
                img.attr('src',Res.File);
                $(".prev-titles").parent().hide();
                $("#preview").html(img);
            }else{
                $(".prev-titles").html(Res.File);
            }
        }
    });
}
</script>