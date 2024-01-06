<div class="modal fade" id="editmodal" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-star"></i> Edit Location</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url(); ?>Location/UpdateLocation" method="post">
                    <input type="hidden" name="locid" id="editlocid">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="locationcode">Location Code</label>
                                <input type="text" required name="locationcode" autocomplete="off" required class="form-control form-control-rounded" id="editcode" placeholder="Enter Location Code">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="locationname">Lcoation Name</label>
                                <input type="text" required name="locationname" required class="form-control form-control-rounded" id="editname" placeholder="Enter Location Name">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-info btn-round shadow-info waves-effect pull-right waves-light m-1"><i class="icon-pencil"></i> Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="delmodal" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-star"></i> Delete Location</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url(); ?>Location/DeleteLocation" method="post">
                    <div class="alert alert-outline-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <div class="alert-icon">
                            <i class="icon-exclamation"></i>
                        </div>
                        <div class="alert-message">
                            <span><strong>Confirm Delete Location ?</strong></span>
                        </div>
                    </div>
                    <input type="hidden" name="locid" id="delocid">
                    <button type="submit" class="btn btn-danger btn-round shadow-danger waves-effect pull-right waves-light m-1"><i class="icon-trash"></i> Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-4 mx-auto">
        <div class="card">
            <div class="card-body">
                <div class="card-title">Location Master</div>
                <hr>
                <form method="post" action="<?= base_url(); ?>Location/AddLocation" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="locationcode">Location Code</label>
                                <input type="text" required name="locationcode" autocomplete="off" required class="form-control form-control-rounded" id="locationcode" placeholder="Enter Location Code">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="locationname">Location Name</label>
                                <input type="text" required name="locationname" required class="form-control form-control-rounded" id="locationname" placeholder="Enter Location Name">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <br>
                            <button type="button" class="btn btn-outline-primary waves-effect waves-light m-1" onclick="AddField()" title="Add Printer"> <i class="fa fa-plus"></i> </button>
                        </div>
                        <div class="col-md-10">
                            <div class="col-md-10 field">
                                <div class="form-group">
                                    <label for="printername">Printer</label>
                                    <input type="text" class="form-control form-control-rounded" required name="printername[]" required placeholder="Enter Printer Name">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="printers">

                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary shadow-primary pull-right btn-round px-5"><i class="icon-plus"></i> Add</button>
                            </div>
                        </div>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 mx-auto">
        <div class="card">
            <div class="card-header"><i class="fa fa-table"></i> Category</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="" class="table default-datatable table-bordered">
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Location Code</th>
                                <th>Location Name</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $c = 1;
                            foreach ($location->result() as $loc) {
                                ?>
                                <tr>
                                    <td><?= $c; ?></td>
                                    <td><?= $loc->locationcode; ?></td>
                                    <td><?= $loc->locationname; ?></td>
                                    <td>
                                        <button type="button" class="btn btn-outline-info waves-effect waves-light m-1" data-target="#editmodal" onclick="EditLocation(<?= $loc->LOCID; ?>)" data-toggle="modal" title="Edit"> <i class="icon-pencil"></i> </button>
                                        <button type="button" class="btn btn-outline-danger waves-effect waves-light m-1" title="Delete" data-target="#delmodal" onclick="DeleteLocation(<?= $loc->LOCID; ?>)" data-toggle="modal"> <i class="fa fa-trash-o"></i> </button>
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

    var c = 1;
    function AddField(){
        var data = `
            <div class="col-md-2 field`+c+`"></div>
            <div class="col-md-10 field`+c+`">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                    <button class="btn btn-danger" title="Remove Printer" onclick="DeleteField('`+c+`')" type="button"><i class="fa fa-minus"></i></button>
                    </div>
                    <input type="text" class="form-control form-control-rounded" required name="printername[]" required placeholder="Enter Printer Name">
                </div>
            </div>
        `;
        $('#printers').append(data);
        c++;
    }

    function DeleteField(c){
        $('.field'+c).remove();
        // alert(c);
    }

    function EditLocation(locid) {
        $.ajax({
            type: "POST",
            url: "<?= base_url(); ?>Location/GetLocationJSON",
            data: {
                locid: locid
            },
            success: function(data) {
                if (data != "") {
                    var obj = JSON.parse(data);
                    $("#editcode").val(obj.locationcode);
                    $("#editname").val(obj.locationname);
                    $("#editlocid").val(obj.LOCID);
                }
            }
        });
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

    function DeleteLocation(locid) {
        $("#delocid").val(locid);
    }

</script>