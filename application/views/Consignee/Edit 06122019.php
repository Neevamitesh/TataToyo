<style>
    .page-title{
        display:none;
    }
</style>
<div class="modal fade" id="AddMoreVariables">
    <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form action="<?= base_url('Consignee/AddVariables');?>" method="post">
                    <input type="hidden" name="Consignee" id="AddVariables-Consignee">
                    <div class="modal-header gradient-forest">
                        <h4 class="modal-title text-white">
                            Add Consignee Variable(s)
                        </h4>
                    </div>
                    <div class="modal-body">
                        <div class="card ">
                            <div class="card-header gradient-forest">
                                <div class="card-title text-white">
                                    Label Variable(s)
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row Variable-Main">
                                    <div class="col-lg-4 col-md-6 col-sm-6 col-12 main">
                                        <div class="card">
                                            <div class="card-header gradient-forest">
                                                <div class="card-title text-white ">
                                                    Variable
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label>Title</label>
                                                    <input type="text" placeholder="Title" name="LabelType[]" class="form-control form-control-rounded" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Value</label>
                                                    <textarea placeholder="Value" name="Value[]" class="form-control" required></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>                        
                                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                                        <button type="button" class="btn btn-sm btn-primary mt-5 mb-auto h-50 w-50 waves-effect gradient-forest btn-round shadow-dark AddVariables" title"Add More Labels"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="form-group">
                                    <button class="btn btn-gradient-scooter pull-right shadow-info waves-effect waves-light">Add Variables</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
    </div>
</div>
<div class="Variable-copy hidden">
    <div class="col-lg-4 col-md-6 col-sm-6 col-12 main">
        <div class="card">
            <div class="card-header gradient-forest">
                <div class="card-title text-white">
                    Variable
                    <button type="button" class="close btn-outline-danger cust-rm" title="remove">&times;</button>
                </div>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" placeholder="Title" name="LabelType[]" class="form-control form-control-rounded" required>
                </div>
                <div class="form-group">
                    <label>Value</label>
                    <textarea placeholder="Value" name="Value[]" class="form-control " required></textarea>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    $Msg = $this->session->flashdata("Lab-Consignee-Edit");
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

<div class="modal fade show" id="cust-rm-alert">
    <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content animated bounceIn">
        <form action="<?= base_url("Consignee/DeleteLabelVariable")?>" method="post">
            <div class="modal-header gradient-forest">
                <h5 class="modal-title text-white">Delete Variable</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h3 class="text-center">Do You Want To Delete ?.</h3>
                <input type="hidden" name="LabelVariable" id="LabelVariable">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success shadow-success" data-dismiss="modal">No</button>
                <button  class="btn btn-danger shadow-danger"><i class="fa fa-trash"></i> Delete</button>
            </div>
        </form>
    </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-10 mx-auto">
        <div class="card">
            <div class="card-header gradient-forest">
                <div class="card-title text-white">
                    Select Consignee           
                </div>
            </div>
            <div class="card-body">
                <div class="form-group input-group">
                    <select id="Consignee" class="form-control">
                        <option value="">Select Consignee</option>
                        <?php
                            foreach ($Consignee->result() as $key => $row) {
                                ?>
                                <option value="<?= $row->CMID?>"><?= $row->consigneename?></option>
                                <?php
                            }
                        ?>
                    </select>
                    <div class="input-group-append">
                        <button type="button" id="GetInfo" class="btn btn-gradient-scooter mx-auto d-block waves-effect waves-light">Get Info</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card hidden consigneeInfo">
    <div class="card-header gradient-forest">
        <div class="card-title text-white">Consignee Info</div>
    </div>
    <div class="card-body">
        <form action="<?= base_url('Consignee/EditInfo');?>" id="EditConsignee" method="post">
            <input type="hidden" name="ConsigneeRef" id="AddVariables-Ref">
            <div class="card">
                <div class="card-header gradient-forest">
                    <div class="card-title text-white">Basic Info</div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="Name">Consignee Name</label>
                                <input type="text" placeholder="Consignee Name" name="Name" id="Name" class="form-control form-control-rounded" required>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="Code">Consignee Code</label>
                                <input type="text" name="Code" placeholder="Consignee Code"  id="Code" class="form-control form-control-rounded" required>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="Name">Contact No.</label>
                                <input type="number" maxlength="10" placeholder="Contact No." title="Contact No."  name="Contact" id="Contact" class="form-control form-control-rounded" required>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="Address">Consignee Address</label>
                                <textarea name="Address" id="Address" placeholder="Consignee Address" class="form-control form-control-rounded" required></textarea>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="State">Select State</label>
                                <select class="form-control form-control-rounded" name="State" id="State" required>
                                    <option value="">Select State</option>
                                    <?php
                                        foreach ($States->result() as $key => $row){
                                            ?>
                                                <option value="<?= $row->state_id?>"><?= $row->state_name?></option>
                                            <?php
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="City">Select City</label>
                                <select class="form-control form-control-rounded" name="City" id="City" required>
                                    <option value="">Select City</option>
                                </select>
                            </div>
                        </div>
                        <!-- @@25092019 -->
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                            <div class="form-group">
                            <label for="Location">Location</label>
                            <select class="form-control form-control-rounded" name="Location" id="Location" required>
                                <option value="">Select Location</option>
                                <?php
                                    foreach ($location->result() as $loc) {
                                        ?>
                                        <option value="<?= $loc->LOCID; ?>"><?= $loc->locationname . " (" . $loc->locationcode . ")"; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            </div>
                            
                        </div>
                        <!-- @@25092019 end-->
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="PinCode">Pincode</label>
                                <input type="number" placeholder="Pincode" minlegth="6" maxlength="6" name="PinCode" id="PinCode" class="form-control form-control-rounded" required>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                                    <label>Select Date Format</label>
                            <select name="Date" id="Dates" class="form-control" required>
                                <?php
                                    foreach ($Dates->result() as $key => $row) {
                                        $Date = ($row->isReverse == "1")? "(Rev)(".strrev(date($row->Data)).')' : '('.date($row->Data).')';
                                        ?>
                                        <option value="<?= $row->MDID?>"><?= "(".trim($row->Data).")".$Date?></option>
                                        <?php
                                    }
                                ?>
                            </select>  
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label>SR No. Length (Default = 1)</label>
                            <input type="number" id="SRLen" name="IndexLen" min="1" value="1" class="form-control" required>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label>Maintain SR No</label><br>
                            <input type="radio" name="SrAuto" class="m-2 mr-0 SRMain" value="yes">yes
                            <input type="radio" name="SrAuto" class="m-2 mr-0 SRMain" value="no">no
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="form-group">
                        <button type="submit" class="btn btn-gradient-scooter waves-effect waves-light pull-right mb-3 shadow-info">Update Info</button>
                    </div>
                </div>
            </div>
        </form>
        <form action="<?= base_url('Consignee/EditOpt')?>" method="post">
        <input type="hidden" name="Consignee" class="AddVariables-Ref">
            <div class="card ">
                <div class="card-header gradient-forest">
                    <div class="card-title text-white">
                        Label Option(s)
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-12 mx-auto">
                            <div class="form-group">
                                <label>Additional Options</label>
                                <select name="Options[]" id="AddOpt" multiple class="form-control">
                                    
                                </select>
                            </div>
                        </div>
                    </div>
                </div>    
                <div class="card-footer">
                    <div class="form-group">
                        <button class="btn btn-success shadow-success waves-effect waves-light pull-right">
                            Update Additional Option
                        </button>
                    </div>
                </div>
            </div>
        
        </form>
        <form action="<?= base_url('Consignee/EditLabels')?>" method="post">
            <div class="card ">
                <div class="card-header gradient-forest">
                    <div class="card-title text-white">
                        Label Variable(s)
                    </div>
                </div>
                <div class="card-body">
                    <div class="Variable-Main-Exist row">
                    
                    </div> 
                </div>
                <div class="card-footer">
                    <div class="form-group">
                        <button type="submit" class="btn btn-gradient-scooter waves-effect waves-light pull-right  shadow-info"><i class="fa fa-edit"></i> Update Consignee</button>
                        <button type="button" class="btn btn-primary  waves-effect gradient-forest btn-round shadow-dark pull-right mr-3" data-toggle="modal" data-target="#AddMoreVariables" title="Add More Labels"><i class="fa fa-plus"></i> Add More Variables</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    $(function(){
        var base=$('#baseurl').html();
        $('#State').change(function(){
            var State = $(this).val();
            $.ajax({
                type: 'POST',
                url: base+"Consignee/GetCity",
                data:{
                    State:State,
                },
                success:function(data){
                    $('#City').html(data);
                }
            });
        });
        
        $(document).on('click','.cust-rm-alert',function(){
            var Consignee = $(this).attr('data-lab');
            $("#cust-rm-alert #LabelVariable").val(Consignee);
        });
        $('#GetInfo').click(function(){
            var Consignee = $("#Consignee").val();
            if(Consignee != undefined && Consignee != ""){
                GetInfo(Consignee);
            }else{
                alert("Select Consignee First!.");
            }
        });


        $(document).on('click','.AddVariables',function(){
            AddVariable();
        });

        function GetInfo(Consignee){
            $.ajax({
                type: 'POST',
                url: base+"Consignee/GetConsignee",
                data:{
                    Consignee:Consignee,
                },
                success:function(data){
                    var Info = JSON.parse(data);
                    $('#EditConsignee #Name').val(Info.Name);
                    $('#EditConsignee #Code').val(Info.Code);
                    $('#EditConsignee #Contact').val(Info.Contact);
                    $('#EditConsignee #Address').val(Info.Address);
                    $('#EditConsignee #State option[value='+Info.State+']').prop('selected',true);
                    $('#EditConsignee #City').html(Info.City);
                    $('#EditConsignee #PinCode').val(Info.PinCode);
                    //@@
                    $('#EditConsignee #Location option[value='+Info.Location+']').prop('selected',true);
                    $(".Variable-Main-Exist").html(Info.Label);
                    $("#AddVariables-Consignee , #AddVariables-Ref , .AddVariables-Ref").val(Info.Ref);
                    
                    $("#AddOpt").html(Info.AddOpt);
                    $('#Dates option[value='+Info.Dates+']').prop('selected',true);
                    $("#SRLen").val(Info.Len);
                    $(".SRMain[value="+Info.SRMain+"]").prop('checked',true);
                    MySearch();
                    $(".consigneeInfo").show();
                }
            });
        }
       
        function AddVariable(row) {
            var tab = $('.Variable-copy').html();
            $(".Variable-Main").append(tab).children(':last').hide().fadeIn();
            $(".Variable-Main").append($('.AddVariables').parent());
        }
    })
</script>