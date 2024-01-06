<div class="row">
    <div class="col-lg-12 mx-auto">
        <div class="card">
            <div class="card-header"><i class="fa fa-table"></i> Locations</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="" class="table default-datatable table-bordered">
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Location Code</th>
                                <th>Location Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $c = 1;
                                foreach($location->result() as $user){
                                    ?>
                                        <tr>
                                            <td><?= $c; ?></td>
                                            <td><?= $user->locationcode; ?></td>
                                            <td><?= $user->locationname; ?></td>
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
    <?php
    if (!empty($msg)) {
        ?>
        var msg = "<?= $msg; ?>";
        $(document).ready(function() {
            if(msg == "Something went wrong !"){
                round_error_noti();
            }
            else{
                round_info_noti();
            }
            $(".lobibox-notify-msg").html('<?= $msg; ?>');
        });
    <?php
    }
    ?>

    function DeleteCategory(cid) {
        $("#delcid").val(cid);
    }
</script>