<div class="row mt-4">
    <div class="col-12 col-lg-6 col-xl-3">
        <a href="<?= base_url(); ?>Reports/Consignee">
            <div class="card gradient-ibiza">
                <div class="card-body">
                    <div class="media">
                        <div class="media-body text-left">
                            <h4 class="text-white"><?= $consigneecount; ?></h4>
                            <span class="text-white">Total Consignee</span>
                        </div>
                        <div class="align-self-center"><span id="dash-chart-3"><canvas width="75" height="40" style="display: inline-block; width: 75px; height: 40px; vertical-align: top;"></canvas></span></div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-12 col-lg-6 col-xl-3">
        <a href="<?= base_url(); ?>Reports/BatchReport">
            <div class="card gradient-purpink">
                <div class="card-body">
                    <div class="media">
                        <div class="media-body text-left">
                            <h4 class="text-white">4530</h4>
                            <span class="text-white">Total Batch Generated</span>
                        </div>
                        <div class="align-self-center"><span id="dash-chart-1"><canvas width="81" height="35" style="display: inline-block; width: 81px; height: 35px; vertical-align: top;"></canvas></span></div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-12 col-lg-6 col-xl-3">
        <a href="<?= base_url(); ?>Reports/ProductReport">
            <div class="card gradient-scooter">
                <div class="card-body">
                    <div class="media">
                        <div class="media-body text-left">
                            <h4 class="text-white"><?= $productcount; ?></h4>
                            <span class="text-white">Total Products Added</span>
                        </div>
                        <div class="align-self-center"><span id="dash-chart-2"><canvas width="80" height="40" style="display: inline-block; width: 80px; height: 40px; vertical-align: top;"></canvas></span></div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <!-- <div class="col-12 col-lg-6 col-xl-3">
        <div class="card gradient-ohhappiness">
            <div class="card-body">
                <div class="media">
                    <div class="media-body text-left">
                        <h4 class="text-white">3524</h4>
                        <span class="text-white">Total Label Design</span>
                    </div>
                    <div class="align-self-center"><span id="dash-chart-4"><canvas width="100" height="25" style="display: inline-block; width: 100px; height: 25px; vertical-align: top;"></canvas></span></div>
                </div>
            </div>
        </div>
    </div> -->
</div>

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
</script>