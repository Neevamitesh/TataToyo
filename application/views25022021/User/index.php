<!DOCTYPE html>
<html lang="en">


<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<meta name="description" content="" />
	<meta name="author" content="" />
	<title>Login</title>
	<!--favicon-->
	<link rel="icon" href="<?= base_url(); ?>assets/images/nst.ico" type="image/x-icon">
	<!-- Bootstrap core CSS-->
	<link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" />
	<!-- animate CSS-->
	<link href="<?= base_url(); ?>assets/css/animate.css" rel="stylesheet" type="text/css" />
	<!-- Icons CSS-->
	<link href="<?= base_url(); ?>assets/css/icons.css" rel="stylesheet" type="text/css" />
	<!-- Custom Style-->
	<link href="<?= base_url(); ?>assets/css/app-style.css" rel="stylesheet" />
	<link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/notifications/css/lobibox.min.css"/>

</head>

<body class="authentication-bg">
	<!-- Start wrapper-->
	<div id="wrapper">
		<div class="card card-authentication1 mx-auto my-5 animated zoomIn">
			<div class="card-body">
				<div class="card-content p-2">
					<div class="text-center">
						<img src="<?= base_url(); ?>assets/images/neevsoftlogo.jpg" style="width: 100%;"/>
					</div>
					<div class="card-title text-uppercase text-center py-2">Sign In</div>
					<form method="post" action="<?= base_url(); ?>Auth/UserValidation">
						<div class="form-group">
							<div class="position-relative has-icon-left">
								<label for="exampleInputUsername" class="sr-only">Username</label>
								<input type="text" id="exampleInputUsername" class="form-control" name="email" placeholder="Username">
								<div class="form-control-position">
									<i class="icon-user"></i>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="position-relative has-icon-left">
								<label for="exampleInputPassword" class="sr-only">Password</label>
								<input type="password" id="exampleInputPassword" name="password" class="form-control" placeholder="Password">
								<div class="form-control-position">
									<i class="icon-lock"></i>
								</div>
							</div>
						</div>
						<!-- <div class="form-row mr-0 ml-0">
							<div class="form-group col-6">
								<div class="icheck-material-primary">
									<input type="checkbox" id="user-checkbox" checked="" />
									<label for="user-checkbox">Remember me</label>
								</div>
							</div>
							<div class="form-group col-6 text-right">
								<a href="authentication-reset-password.html">Reset Password</a>
							</div>
						</div> -->

						<div class="form-group">
							<button type="submit" class="btn btn-danger shadow-danger btn-block waves-effect waves-light">Sign In</button>
						</div>

					</form>
				</div>
			</div>
		</div>

		<!--Start Back To Top Button-->
		<a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>
		<!--End Back To Top Button-->
	</div>
	<!--wrapper-->

	<!-- Bootstrap core JavaScript-->
	<script src="<?= base_url(); ?>assets/js/jquery.min.js"></script>
	<script src="<?= base_url(); ?>assets/js/popper.min.js"></script>
	<script src="<?= base_url(); ?>assets/js/bootstrap.min.js"></script>
	<!-- waves effect js -->
	<script src="<?= base_url(); ?>assets/js/waves.js"></script>
	<script src="<?= base_url(); ?>/assets/js/sidebar-menu.js"></script>
	<!-- Custom scripts -->
	<script src="<?= base_url(); ?>assets/js/app-script.js"></script>
	<script src="<?= base_url(); ?>assets/plugins/notifications/js/lobibox.min.js"></script>
  <script src="<?= base_url(); ?>assets/plugins/notifications/js/notifications.min.js"></script>
  <script src="<?= base_url(); ?>assets/plugins/notifications/js/notification-custom-script.js"></script>
	<script>
    <?php
    if (!empty($msg)) {
        ?>
        var msg = "<?= $msg; ?>";
        $(document).ready(function() {
            if(msg == "Something went wrong !" || msg == "Password Incorrect !"){
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

</body>


</html>