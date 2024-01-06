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
            <div class="card-header"><i class="fa fa-table"></i> Users</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="LiveData" class="table default-datatable table-bordered">
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Label Design</th>
                                <th>Consignee Code</th>
                                <th>Consignee Name</th>
                                <th>Address</th>
                                <th>Mobile No.</th>
                                <th>City</th>
                                <th>State</th>
                                <th>Pincode</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($consignees->result() as $key => $con) {
                                // if ($con->createdby == $UID) {
                                    ?>
                                    <tr class="main">
                                        <td>
                                            <?= $key + 1; ?>
                                            <input type="hidden" class="Ref" value="<?=$con->CMID?>">
                                        </td>
                                        <td>
                                            <button type="button" data-toggle="modal" data-target="#largesizemodal" class="btn btn-primary previews" onclick="viewLabel('<?= $con->CMID; ?>')">View Label</button>
                                        </td>
                                        <td>
                                            <?= $con->consigneecode; ?>
                                        </td>
                                        <td>
                                            <?= $con->consigneename; ?>
                                        </td>
                                        <td>
                                            <?= $con->address; ?>
                                        </td>
                                        <td>
                                            <?= $con->mobileno; ?>
                                        </td>
                                        <td>
                                            <?= $con->city_name; ?>
                                        </td>
                                        <td>
                                            <?= $con->state_name; ?>
                                        </td>
                                        <td>
                                            <?= $con->pincode; ?>
                                        </td>
                                    </tr>
                                    <?php
                                // }
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
    function GetData(input) {
        var uid = input.value;
        window.location.href = "<?= base_url(); ?>Reports/Consignee/" + uid;
    }

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