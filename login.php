<?php
require "config.php";
require "lib/base/db.php";


?>


<!DOCTYPE html>
<html lang="en">

<head>
	<title><?php echo $title ?></title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--===============================================================================================-->
	<link rel="icon" type="image/png" href="<?php echo $dir ?>assets/img/logo.png" />


	<link type="text/css" rel="stylesheet" href="<?php echo $dir ?>plugins/toater/toastr.min.css" />
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo $dir ?>plugins/login/vendor/bootstrap/css/bootstrap.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo $dir ?>plugins/login/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo $dir ?>plugins/login/vendor/animate/animate.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo $dir ?>plugins/login/vendor/css-hamburgers/hamburgers.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo $dir ?>plugins/login/vendor/select2/select2.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?php echo $dir ?>plugins/login/css/util.css">
	<link rel="stylesheet" type="text/css" href="<?php echo $dir ?>plugins/login/css/main.css">

	<link rel="stylesheet" type="text/css" href="<?php echo $dir ?>plugins/spinner/ladda.min.css">
	<!--===============================================================================================-->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Grand+Hotel&family=Yesteryear&display=swap" rel="stylesheet">

	<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200&display=swap" rel="stylesheet">

	<style>
		.form-userid {
			margin-right: 10px;
			width: 45%;
			float: left;
		}

		.form-pass-otp {
			width: 50%;
			float: left;
		}

		.form-cek-tte {
			float: left;
			width: 25%;
			margin-right: 5px;
		}

		.form-login {
			float: left;
			width: 97%;
			margin-right: 10px;
		}

		.form-request-otp {
			width: 35%;
			margin-right: 10px;
		}

		.container-login100 {
			padding: 0px;
		}

		.dashboardContainer {
			float: left;
			width: 65%;
			padding: 20px;
			height: 100vh;
		}


		#chartdiv {
			width: 100%;
			height: 66vh;
		}

		#pie {
			width: 100%;
			height: 66vh;
		}

		.col-md-3 {
			float: left;
			width: 24%;
			background-color: white;
			height: 160px;
			padding: 25px 0;
			margin: 0 4px;
			border-radius: 10px;
			margin-bottom: 20px;
		}

		.row {
			clear: both;
			background-color: rgb(255 255 255 / 85%);
			border-radius: 10px;
		}

		.col-md-charts {
			float: left;
			padding: 10px;
			width: 60%;
			height: 75vh;
		}

		.col-md-pie {
			float: left;
			padding: 10px;
			width: 40%;
		}

		@media only screen and (max-width: 768px) {
			.col-md-3 {
				width: 98%;
				margin-top: 5px !important;
			}
		}

		@media (max-width: 767px) {

			.dashboardContainer {
				display: none;
			}

			.form-userid {
				width: 100%;
				float: left;
			}

			.form-pass-otp {
				width: 100%;
				float: left;
			}

			.form-cek-tte {
				float: left;
				width: 47%;
			}

			.form-login {
				float: left;
				width: 47%;
				margin-bottom: 5px;
			}

			.form-request-otp {
				width: 96%;
			}
		}
	</style>

</head>

<body>

	<div class="limiter">
		<div class="container-login100" style="background: url(assets/img/background-login4.jpg);background-size: cover;">

			<div class="wrap-login100" style="padding: 40px;float: right;width:520px;background: rgb(0 0 0 / 60%)">


				<form class="login100-form validate-form login text-center" style="width:100%;" action="javascript:void(0);">
					<img src="<?php echo $dir ?>assets/img/logo.png" style="width: 50%;" /><br>
					<span class="login50-form-title" style="text-align: center;font-size: 30px;padding-bottom: 10px;width: 100%;display: block;color:white;font-family: 'Grand Hotel', cursive;">
						<?php echo $organization ?>
					</span>
					<span class="login100-form-title" style="padding-bottom: 5px;color:white;font-size: 20px;    font-family: Cairo;">
						<?php echo $appname ?>
					</span>

					<div class="wrap-input100 validate-input form-userid" data-validate="User ID is required">
						<input class="input100 userid" type="text" name="userid" placeholder="User ID" style="border-radius:5px;color: white;background: rgb(0 0 0 / 80%);">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-user" aria-hidden="true"></i>
						</span>
					</div>

					<?php
					if ($login_method == 'otp') {
						echo '
						<div class="wrap-input100 validate-input form-pass-otp"  data-validate="Password is required">
							<input class="input100 pass" type="text" name="otp" placeholder="OTP" style="border-radius:5px;color: white;background: rgb(0 0 0 / 80%);">
							<span class="focus-input100"></span>
							<span class="symbol-input100">
								<i class="fa fa-lock" aria-hidden="true"></i>
							</span>
						</div>
						';
					} else if ($login_method == 'password') {
						echo '
						<div class="wrap-input100 validate-input form-pass-otp" data-validate="Password is required">
							<input class="input100 pass" type="password" name="pass" placeholder="Password" style="border-radius:5px;color: white;background: rgb(0 0 0 / 80%);">
							<span class="focus-input100"></span>
							<span class="symbol-input100">
								<i class="fa fa-lock" aria-hidden="true"></i>
							</span>
						</div>
						';
					}

					?>



					<div style="width:100%">

						<button type="button" class="login100-form-btn submit ladda-button form-login" data-color="green" data-style="slide-right">
							Login
						</button>


						<!-- 
						<a href="verify.php" style="width:150px; margin-left: 10px;color: white;" class="login100-form-btn ladda-button" data-color="red" data-style="contract">
							Cek Validasi
						</a> -->

					</div>
				</form>
			</div>
		</div>
	</div>




	<!--===============================================================================================-->
	<script src="<?php echo $dir ?>plugins/login/vendor/jquery/jquery-3.2.1.min.js"></script>

	<script src="<?php echo $dir ?>plugins/spinner/spin.min.js"></script>
	<script src="<?php echo $dir ?>plugins/spinner/ladda.min.js"></script>
	<!--===============================================================================================-->
	<script type="text/javascript" src="<?php echo $dir ?>plugins/toater/toastr.min.js"></script>
	<script type="text/javascript" src="<?php echo $dir ?>plugins/dist/js/function.js"></script>

	<script src="<?php echo $dir ?>plugins/login/vendor/bootstrap/js/popper.js"></script>
	<script src="<?php echo $dir ?>plugins/login/vendor/bootstrap/js/bootstrap.min.js"></script>
	<!--===============================================================================================-->
	<script src="<?php echo $dir ?>plugins/login/vendor/select2/select2.min.js"></script>
	<!--===============================================================================================-->
	<script src="<?php echo $dir ?>plugins/login/vendor/tilt/tilt.jquery.min.js"></script>

	<!-- Resources -->
	<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
	<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
	<script src="//cdn.amcharts.com/lib/5/themes/Micro.js"></script>

	<script>
		$('.js-tilt').tilt({
			scale: 1.1
		})
	</script>
	<!--===============================================================================================-->
	<script src="<?php echo $dir ?>plugins/login/js/main.js"></script>

	<script type="text/javascript">
		var l = Ladda.create(document.querySelector('.submit'));

		$(document).keypress(function(event) {
			var keycode = (event.keyCode ? event.keyCode : event.which);
			if (keycode == '13') {
				$('.submit').click();
			}
		});

		$('.submit').on('click', function() {
			l.start();

			var login = $('.login').serialize();
			$.ajax({
				url: "./lib/base/login_act.php?" + login,
				dataType: "json",
				success: function(msg) {
					popup(msg.status, msg.text, '');

					if (msg.status == 'success') {
						window.location.href = msg.page;
					} else {
						l.stop();
					}

				},
				error: function(err) {
					popup('error', 'Error System', '');
					console.log(err);
					l.stop();
				}
			})
		})
	</script>


</body>

</html>