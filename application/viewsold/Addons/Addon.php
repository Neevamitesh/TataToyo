<style>
    .page-title{
        display:none;
    }
</style>
<div class="modal fade" id="Del-Date">
    <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="<?= base_url('AddOns/DeleteDate');?>" method="post">
                    <input type="hidden" name="Date" id="Date-Data">
                    <div class="modal-header gradient-forest">
                        <h4 class="modal-title text-white">
                            Confirmation
                        </h4>
                        <button class="close"  data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <h4 class="text-center text-info">
                            Do You Really Want To Delete?
                        </h4>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success shadow-success waves-effect waves-light" data-dismiss="modal">
                            No 
                        </button>
                        <button type="submit" class="btn btn-danger shadow-danger waves-effect waves-light">
                            Yes <i class="fa fa-trash"></i> 
                        </button>
                    </div>
                </form>
            </div>
    </div>
</div>
<div class="modal fade" id="Del-Title">
    <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="<?= base_url('AddOns/DeleteTitle');?>" method="post">
                    <input type="hidden" name="Title" id="Title-Data">
                    <div class="modal-header gradient-forest">
                        <h4 class="modal-title text-white">
                            Confirmation
                        </h4>
                        <button class="close"  data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <h4 class="text-center text-info">
                            Do You Really Want To Delete?
                        </h4>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success shadow-success waves-effect waves-light" data-dismiss="modal">
                            No 
                        </button>
                        <button type="submit" class="btn btn-danger shadow-danger waves-effect waves-light">
                            Yes <i class="fa fa-trash"></i> 
                        </button>
                    </div>
                </form>
            </div>
    </div>
</div>
<div class="modal fade" id="Del-Option">
    <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="<?= base_url('AddOns/DeleteOption');?>" method="post">
                    <input type="hidden" name="Option" id="Option-Data">
                    <div class="modal-header gradient-forest">
                        <h4 class="modal-title text-white">
                            Confirmation
                        </h4>
                        <button class="close"  data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <h4 class="text-center text-info">
                            Do You Really Want To Delete?
                        </h4>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success shadow-success waves-effect waves-light" data-dismiss="modal">
                            No 
                        </button>
                        <button type="submit" class="btn btn-danger shadow-danger waves-effect waves-light">
                            Yes <i class="fa fa-trash"></i> 
                        </button>
                    </div>
                </form>
            </div>
    </div>
</div>

<div class="modal fade" id="Edit-Date">
    <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="<?= base_url('AddOns/EditDate');?>" method="post">
                    <input type="hidden" name="Date-Ref" class="Date-Ref">
                    <div class="modal-header gradient-forest">
                        <h4 class="modal-title text-white">
                            Edit Date
                        </h4>
                        <button class="close"  data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Edit Date</label>
                            <input type="text" placeholder="Edit Date" name="Date" class="form-control Date" required>
                        </div>
                        <div class="form-group">
                            <label>Check If Reverse Format</label>
                            <input type="checkbox" id="Reverse-Ref" name="Reverse" value="rev">
                        </div>
                        <h5 class="text-info">Note: d-m-Y = (<?= date("d-m-Y")?>)</h5>
                        <h6 class="text-info">
                            <ul>
                                <li>Y: <?= date("Y")?> ,  y:  <?= date("y")?></li>
                                <li>M: <?= date("M")?> ,  m:  <?= date("m")?></li>
                                <li>D: <?= date("D")?> ,  d:  <?= date("d")?></li>
                            </ul>
                        </h6>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success shadow-success waves-effect waves-light" data-dismiss="modal">
                            No 
                        </button>
                        <button type="submit" class="btn btn-info shadow-info waves-effect waves-light">
                            Edit <i class="fa fa-pencil"></i> 
                        </button>
                    </div>
                </form>
            </div>
    </div>
</div>
<div class="modal fade" id="Edit-Title">
    <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="<?= base_url('AddOns/EditTitle');?>" method="post">
                    <input type="hidden" name="Title-Ref" class="Title-Ref">
                    <div class="modal-header gradient-forest">
                        <h4 class="modal-title text-white">
                            Edit Title
                        </h4>
                        <button class="close"  data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Edit Title</label>
                            <input type="text" placeholder="Add Title" name="Title" class="form-control Title" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success shadow-success waves-effect waves-light" data-dismiss="modal">
                            No 
                        </button>
                        <button type="submit" class="btn btn-info shadow-info waves-effect waves-light">
                            Edit <i class="fa fa-pencil"></i> 
                        </button>
                    </div>
                </form>
            </div>
    </div>
</div>

<div class="modal fade" id="Edit-Option">
    <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="<?= base_url('AddOns/EditOption');?>" method="post">
                    <input type="hidden" name="Option-Ref" class="Option-Ref">
                    <div class="modal-header gradient-forest">
                        <h4 class="modal-title text-white">
                            Edit Title
                        </h4>
                        <button class="close"  data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Title(s)</label>
                            <select name="Title" class="form-control Title" required>
                                <option value="">Select Title</option>
                                <?php
                                    foreach ($Title->result() as $key => $row) {
                                        ?>
                                            <option value="<?= $row->AOID?>"><?= $row->Title?></option>
                                        <?php
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Edit Data</label>
                            <input type="text" placeholder="Edit Title" name="Data" class="form-control Data" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success shadow-success waves-effect waves-light" data-dismiss="modal">
                            No 
                        </button>
                        <button type="submit" class="btn btn-info shadow-info waves-effect waves-light">
                            Edit <i class="fa fa-pencil"></i> 
                        </button>
                    </div>
                </form>
            </div>
    </div>
</div>


<div class="container-fluid">
    <?php
        $Msg = $this->session->flashdata("Lab-AddOns");
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
        <div class="card-header gradient-forest">
            <div class="card-title text-white">Label Contents</div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 col-12">
                    <form action="<?= base_url('AddOns/AddTitle');?>" method="post">
                        <div class="card">
                            <div class="card-header gradient-forest">
                                <div class="card-title text-white">Label Option Title</div>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Add Title</label>
                                    <input type="text" placeholder="Add Title" name="Title" class="form-control" required>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-success shadow-success waves-effect waves-light pull-right mb-1">
                                    Add
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-6 col-12">
                    <form action="<?= base_url('AddOns/AddTitleData');?>" method="post">
                        <div class="card">
                            <div class="card-header gradient-forest">
                                <div class="card-title text-white">Label Option Data</div>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Title(s)</label>
                                    <select name="Title" class="form-control" required>
                                        <option value="">Select Title</option>
                                        <?php
                                            foreach ($Title->result() as $key => $row) {
                                                ?>
                                                    <option value="<?= $row->AOID?>"><?= $row->Title?></option>
                                                <?php
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Add Data</label>
                                    <input type="text" placeholder="Edit Title" name="Data" class="form-control" required>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-success shadow-success waves-effect waves-light pull-right mb-1">
                                    Add Data
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-6 mx-auto">
                    <form action="<?= base_url('AddOns/AddDate');?>" method="post">
                        <div class="card">
                            <div class="card-header gradient-forest">
                                <div class="card-title text-white">Add Date</div>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Add Date</label>
                                    <input type="text" placeholder="Add Date" name="Date" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Check If Reverse Format</label>
                                    <input type="checkbox" name="Reverse" value="rev">
                                </div>
                                <h5 class="text-info">Note: d-m-Y = (<?= date("d-m-Y")?>)</h5>
                                <h6 class="text-info">
                                    <ul>
                                        <li>Y: <?= date("Y")?> ,  y:  <?= date("y")?></li>
                                        <li>M: <?= date("M")?> ,  m:  <?= date("m")?></li>
                                        <li>D: <?= date("D")?> ,  d:  <?= date("d")?></li>
                                    </ul>
                                </h6>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-success shadow-success waves-effect waves-light pull-right mb-1">
                                    Add Date
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header gradient-forest">
                    <div class="card-title text-white">Date(s)</div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <div id="default-datatable_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="Date" class="table table-bordered dataTable table-hover table-striped shadow-light" role="grid" aria-describedby="default-datatable_info">
                                        <thead>
                                        <tr role="row">
                                            <th class="sorting_asc" tabindex="0" aria-controls="default-datatable"
                                                rowspan="1" colspan="1" aria-sort="ascending"
                                                aria-label="Name: activate to sort column descending"
                                                >Sr No.</th>
                                            <th class="sorting" tabindex="0"
                                                aria-controls="default-datatable" rowspan="1"
                                                colspan="1" aria-label="Position: activate to sort column ascending"
                                                >Date</th>
                                            <th class="sorting" tabindex="0"
                                                aria-controls="default-datatable" rowspan="1"
                                                colspan="1" aria-label="Office: activate to sort column ascending"
                                                >Output</th>
                                            <th class="sorting" tabindex="0"
                                            aria-controls="default-datatable" rowspan="1"
                                            colspan="1" aria-label="Office: activate to sort column ascending">
                                            Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                foreach ($Dates->result() as $key => $row) {
                                                    $Date = ($row->isReverse == "1")? strrev(date($row->Data)) : date($row->Data);
                                                    ?>
                                                        <tr  class="main-row">
                                                            <td><?= $key+1?>
                                                                <input type="hidden" class="Rev" value="<?=($row->isReverse == "1")?'true':'false' ?>">
                                                            </td>
                                                            <td class="Date"><?= $row->Data?></td>
                                                            <td class="Date-Prev"><?= $Date?></td>
                                                            <td>
                                                                <button class="btn btn-info waves-effect waves-light shadow-info Edit-Date" data-toggle="modal" data-target="#Edit-Date">
                                                                    <i class="fa fa-pencil"></i>
                                                                </button>
                                                                <button class="btn btn-danger waves-effect waves-light  shadow-danger Date-Data" data-val="<?= $row->MDID?>" data-toggle="modal" data-target="#Del-Date">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                }
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th rowspan="1" colspan="1">Sr No.</th>
                                                <th rowspan="1" colspan="1">Data</th>
                                                <th rowspan="1" colspan="1">Output</th>
                                                <th rowspan="1" colspan="1">Action</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>  
                </div>  
            </div>                  

            <div class="card">
                <div class="card-header gradient-forest">
                    <div class="card-title text-white">Label Option Title(s)</div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <div id="default-datatable_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="Title" class="table table-bordered dataTable table-hover table-striped shadow-light" role="grid" aria-describedby="default-datatable_info">
                                        <thead>
                                        <tr role="row">
                                            <th class="sorting_asc" tabindex="0" aria-controls="default-datatable"
                                                rowspan="1" colspan="1" aria-sort="ascending"
                                                aria-label="Name: activate to sort column descending"
                                                >Sr No.</th>
                                            <th class="sorting" tabindex="0"
                                                aria-controls="default-datatable" rowspan="1"
                                                colspan="1" aria-label="Office: activate to sort column ascending"
                                                >Title</th>
                                            <th class="sorting" tabindex="0"
                                            aria-controls="default-datatable" rowspan="1"
                                            colspan="1" aria-label="Office: activate to sort column ascending">
                                            Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                foreach ($Title->result() as $key => $row) {
                                                    ?>
                                                        <tr class="main-row">
                                                            <td><?= $key+1?></td>
                                                            <td class="Title"><?= $row->Title?></td>
                                                            <td>
                                                                <button class="btn btn-info waves-effect waves-light shadow-info Edit-Title" data-toggle="modal" data-target="#Edit-Title">
                                                                    <i class="fa fa-pencil"></i>
                                                                </button>
                                                                <button class="btn btn-danger waves-effect waves-light  shadow-danger Title-Data" data-val="<?= $row->AOID?>" data-toggle="modal" data-target="#Del-Title">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                }
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th rowspan="1" colspan="1">Sr No.</th>
                                                <th rowspan="1" colspan="1">Title</th>
                                                <th rowspan="1" colspan="1">Action</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>  
                </div>  
            </div>  

              <div class="card">
                <div class="card-header gradient-forest">
                    <div class="card-title text-white">Label Option Data</div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <div id="default-datatable_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="Title" class="table table-bordered dataTable table-hover table-striped shadow-light" role="grid" aria-describedby="default-datatable_info">
                                        <thead>
                                        <tr role="row">
                                            <th class="sorting_asc" tabindex="0" aria-controls="default-datatable"
                                                rowspan="1" colspan="1" aria-sort="ascending"
                                                aria-label="Name: activate to sort column descending"
                                                >Sr No.</th>
                                            <th class="sorting" tabindex="0"
                                                aria-controls="default-datatable" rowspan="1"
                                                colspan="1" aria-label="Office: activate to sort column ascending"
                                                >Title</th>
                                            <th class="sorting" tabindex="0"
                                            aria-controls="default-datatable" rowspan="1"
                                            colspan="1" aria-label="Office: activate to sort column ascending"
                                            >Data</th>
                                            <th class="sorting" tabindex="0"
                                            aria-controls="default-datatable" rowspan="1"
                                            colspan="1" aria-label="Office: activate to sort column ascending">
                                            Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                foreach ($Options->result() as $key => $row) {
                                                    ?>
                                                        <tr class="main-row">
                                                            <input type="hidden" class="Title-Ref" value="<?= $row->AOID?>">
                                                            <td><?= $key+1?></td>
                                                            <td><?= $row->Title?></td>
                                                            <td class="Data"><?= $row->Data?></td>
                                                            <td>
                                                                <button class="btn btn-info waves-effect waves-light shadow-info Edit-Option" data-toggle="modal" data-target="#Edit-Option">
                                                                    <i class="fa fa-pencil"></i>
                                                                </button>
                                                                <button class="btn btn-danger waves-effect waves-light  shadow-danger Option-Data" data-val="<?= $row->ADID?>" data-toggle="modal" data-target="#Del-Option">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                }
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th rowspan="1" colspan="1">Sr No.</th>
                                                <th rowspan="1" colspan="1">Title</th>
                                                <th rowspan="1" colspan="1">Data</th>
                                                <th rowspan="1" colspan="1">Action</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>  
                </div>  
            </div>  
        </div>
    </div>
</div>


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
    $(function(){
        var Date = $("#Date").dataTable({
            responsive: true
        });
        var Title = $("#Title").dataTable({
            responsive: true
        });
        $(document).on('click','.Date-Data',function(){
            $("#Date-Data").val($(this).attr('data-val'));
        });
        $(document).on('click','.Title-Data',function(){
            $("#Title-Data").val($(this).attr('data-val'));
        });
        $(document).on('click','.Option-Data',function(){
            $("#Option-Data").val($(this).attr('data-val'));
        });
        $(document).on('click','.Edit-Date',function(){
            var row = $(this).parents('.main-row');
            console.log(row);
            $("#Edit-Date .Date-Ref").val($(row).find('.Date-Data').attr('data-val'));
            $("#Edit-Date .Date").val($(row).find('.Date').html());
            $("#Edit-Date .Reverse-Ref").prop('checked',$(row).find('.Rev').val());
        });

        $(document).on('click','.Edit-Title',function(){
            var row = $(this).parents('.main-row');
            $("#Edit-Title .Title-Ref").val($(row).find('.Title-Data').attr('data-val'));
            $("#Edit-Title .Title").val($(row).find('.Title').html());
        });

        $(document).on('click','.Edit-Option',function(){
            var row = $(this).parents('.main-row');
            $("#Edit-Option .Option-Ref").val($(row).find('.Option-Data').attr('data-val'));
            $("#Edit-Option .Title-Ref").val($(row).find('.Title-Ref').val());
            $("#Edit-Option .Data").val($(row).find('.Data').html());
            var val = $(row).find('.Title-Ref').val();
            $("#Edit-Option .Title option[value='"+val+"']").prop('selected',true);
            MySearch();
        });
    });
</script>
