<style>
    .page-title{
        display:none;
    }
</style>
<?php
    $Msg = $this->session->flashdata("Lab-File-Pre");
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

<div class="row">
    <div class="col-lg-10 mx-auto">
        <form action="<?= base_url("Preview/AddPreview");?>" enctype="multipart/form-data" method="post">
            <div class="card">
                <div class="card-header gradient-forest">
                    <div class="card-title text-white">
                        Replace Label Preview File
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-4 col-md-6 col-12">
                            <label for="Consignee">Consignee</label>
                            <select class="form-control form-control-rounded" name="Consignee" id="Consignee" required>
                                <option value="">Select Consignee</option>
                                <?php
                                    foreach ($OurConsignee->result() as $key => $row) {
                                        ?>
                                        <option value="<?= $row->CMID?>"><?= $row->consigneename?></option>
                                        <?php
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="col-xl-4 col-md-6 col-12">
                            <label>Upload html File</label>
                            <div class="input-group">
                                <input type="file"  name="PRNFile" class="" id="PRNFile" required>
                            </div>
                            <p class="File-info text-center text-success h4 pt-2"></p>
                        </div>
                        <div class="col-xl-4 col-md-6 col-12">
                            <label>Upload Attachment File</label>
                            <div class="input-group">
                                <input type="file"  name="Attach" class="" id="Attach" required>
                            </div>
                            <p class="File-info text-center text-success h4 pt-2"></p>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="form-group">
                        <button class="btn btn-gradient-forest pull-right mb-2">Upload File</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    // $(function(){
    //     $("#PRNFile").change(function(e){
    //         $(".File-info").html(e.target.files[0].name);
            
    //     });
    // }); 
</script>