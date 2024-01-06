
<div class="row">
    <div class="col-lg-10 mx-auto">
        <form action="<?= base_url('Consignee/ViewFile');?>" method="post">
            <div class="card">
                <div class="card-header gradient-forest">
                    <div class="card-title text-white">
                        Select Batch           
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group input-group">
                        <select id="Batch" name="Batch" class="form-control">
                            <option value="">Select Batch</option>
                            <?php
                                foreach ($Batch->result() as $key => $row) {
                                    ?>
                                    <option value="<?= $row->CBID?>"><?= $row->CBID?></option>
                                    <?php
                                }
                            ?>
                        </select>
                        <div class="input-group-append">
                            <button type="button" id="GetBatchDetail" class="btn btn-gradient-scooter mx-auto d-block waves-effect waves-light">Get Info</button>
                        </div>
                    </div>
                    <div class="form-group">
                        <select name="Qtys[]" id="Qtys" class="form-group" multiple>
                                
                        </select>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    $(function(){
        $("#GetBatchDetail").click(function(){
            var Batch = $("#Batch").val();
            $.ajax({
                type: 'POST',
                url: "<?= base_url('Consignee/GetBatchQty')?>",
                data:{
                    Batch: Batch
                },
                success:function(data){
                    $("#Qtys").html(data);
                }
            });
            
        });
    })
</script>