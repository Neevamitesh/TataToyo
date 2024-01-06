<style>
    .page-title{
        display:none;
    }
</style>
<div class="Variable-copy hidden">
    <div class="col-lg-3 col-md-4 col-sm-6 col-12 main">
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
                    <textarea placeholder="Value" name="Value[]" class="form-control" required></textarea>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    $Msg = $this->session->flashdata("Lab-Consignee-Add");
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


<div class="card">
    <form action="<?= base_url("Consignee/AddConsignee")?>" method="post">
        <div class="card-header gradient-forest">
            <div class="card-title text-white">Consignee Master</div>
        </div>
        <div class="card-body">
            <div class="card ">
                <div class="card-header gradient-forest">
                    <div class="card-title text-white">
                        Basic Info
                    </div>
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
                                <input type="number" maxlength="10" placeholder="Contact No." title="Contact No." minlength="10" name="Contact" id="Name" class="form-control form-control-rounded" required>
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
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="PinCode">Pincode</label>
                                <input type="number" placeholder="Pincode" minlegth="6" maxlength="6" name="PinCode" id="PinCode" class="form-control form-control-rounded" required>
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
                    </div>
                </div>    
            </div>
          
            <div class="card ">
                <div class="card-header gradient-forest">
                    <div class="card-title text-white">
                        Lable Option(s)
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-6 col-12">
                            <div class="form-group">
                            <label>Additional Options</label>
                            <select name="Options[]" multiple class="form-control">
                                <?php
                                    foreach ($Options->result() as $key => $row) {
                                        ?>
                                        <option value="<?= $row->AOID?>"><?= $row->Title?></option>
                                        <?php
                                    }
                                ?>
                            </select>
                        </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                                    <label>Select Date Format</label>
                            <select name="Date" class="form-control" required>
                                <?php
                                    foreach ($Dates->result() as $key => $row) {
                                        echo $row->isReverse;
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
                            <input type="number" name="IndexLen" min="1" value="1" class="form-control" required>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12">
                            <label>Maintain SR No</label><br>
                            <input type="radio" name="SrAuto" class="m-2 mr-0" value="yes">yes
                            <input type="radio" name="SrAuto" class="m-2 mr-0" value="no">no
                        </div>
                    </div>
                   
                </div>    
            </div>
            <div class="card ">
                <div class="card-header gradient-forest">
                    <div class="card-title text-white">
                        Label Variable(s)
                    </div>
                </div>
                <div class="card-body">
                    <div class="row Variable-Main">
                        <!-- <div class="col-lg-3 col-md-4 col-sm-6 col-12 main">
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
                        </div>                         -->
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                            <button type="button" class="btn btn-sm btn-primary mt-5 mb-auto h-50 w-50 waves-effect gradient-forest btn-round shadow-dark AddVariables" title"Add More Labels"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="card-footer">
            <div class="form-group">
                <button type="submit" class="btn btn-gradient-scooter waves-effect waves-light pull-right mb-3 shadow-info">Add Consignee</button>
            </div>
        </div>
    </form>
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

        $(document).on('click','.AddVariables',function(){
            AddVariable();
        });

        $(document).on('click','.cust-rm',function(){
            Remover($(this).parents('.main'));
        });
        
        function Remover(row){
            $(row).remove();
        }
        function AddVariable(row) {
            var tab = $('.Variable-copy').html();
            $(".Variable-Main").append(tab).children(':last').hide().fadeIn();
            $(".Variable-Main").append($('.AddVariables').parent());
        }
    })
</script>