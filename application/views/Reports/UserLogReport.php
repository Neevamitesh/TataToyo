<div class="row">
    <div class="col-lg-6 mx-auto">
        <div class="card">
            <div class="card-header"><i class="fa fa-table"></i> Sort By</div>
            <div class="card-body">
                <?php
                if ($role[0] == "SuperAdmin") {
                    ?>                    
                        <!-- <div class="row">
                            <div class="col-md-12"> -->
                            <form action="<?= base_url(); ?>Reports/UserLog" method="post">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="user">Location</label>
                                            <select class="form-control" onchange="GetData(this)" required id="basic-select" required name="user">
                                                <option value="" selected disabled>Select Location</option>
                                                <?php
                                                foreach ($locations->result() as $loc) {
                                                    ?>
                                                    <option <?= $UID == $loc->LOCID ? "selected" : ""; ?> value="<?= $loc->LOCID; ?>"><?= $loc->locationname . " (" . $loc->locationcode . ")"; ?></option>
                                                <?php
                                            }
                                            ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="fromdate">From Date </label>
                                            <input type="date" name="fromdate" value="<?= $fromdate == "empty" ? date('Y-m-d') : $fromdate; ?>" id="fromdate" class="form-control input-sm" required value="<?= $fromdate != 'empty' ? date('Y-m-d', strtotime($fromdate)) : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="todate">To Date</label>
                                            <input type="date" name="todate" value="<?= $fromdate == "empty" ? date('Y-m-d') : $fromdate; ?>" id="todate" class="form-control input-sm" required value="<?= $fromdate != 'empty' ? date('Y-m-d', strtotime($todate)) : ''; ?>">
                                        </div>
                                        <button type="submit" class="btn btn-primary pull-right" title="Search"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                                </form>
                            <!-- </div>
                        </div> -->
                <?php
            }
            ?>
            </div>
        </div>
    </div>
</div><!-- End Row-->

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
                                <th>Username</th>
                                <th>Email</th>
                                <th>Contact</th>
                                <th>User Type</th>
                                <th>Logged At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if($logs != "empty"){
                                $c = 1;
                                foreach ($logs->result() as $log) {
                                    $logDate = date('Y-m-d', strtotime($log->LoggedAt));
                                    if ($logDate >= $fromdate && $logDate <= $todate) {
                                        ?>
                                        <tr class="main">
                                            <td>
                                                <?= $c; ?>
                                            </td>
                                            <td>
                                                <?= $log->username; ?>
                                            </td>
                                            <td>
                                                <?= $log->emailid; ?>
                                            </td>
                                            <td>
                                                <?= $log->mobileno; ?>
                                            </td>
                                            <td>
                                                <?= $log->usertype; ?>
                                            </td>
                                            <td>
                                                <?= date('d-m-Y h:i:sa', strtotime($log->LoggedAt)); ?>
                                            </td>
                                        </tr>
                                        <?php
                                        $c++;
                                    }
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

</script>