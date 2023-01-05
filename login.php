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
			width: 55%;
			float: left;
		}

		.form-pass-otp {
			width: 35%;
			float: left;
		}

		.form-cek-tte {
			float: left;
			width: 25%;
			margin-right: 5px;
		}

		.form-login {
			float: left;
			width: 55%;
			margin-right: 10px;
		}

		.form-request-otp {
			width: 35%;
			margin-right: 10px;
		}

		.container-login100 {
			padding: 0px;
			justify-content: right !important;
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
		<div class="container-login100" style="background: url(assets/img/background-login2.jpeg);background-size: cover;">

			<div class="wrap-login100" style="padding: 0px 40px;float: right;width:520px;height:100vh;background: rgb(6 23 74 / 50%)">


				<form class="login100-form validate-form login text-center" style="width:100%;margin-top:180px" action="javascript:void(0);">
					<img src="<?php echo $dir ?>assets/img/logo.png" style="width: 50%;" /><br>
					<span class="login50-form-title" style="text-align: center;font-size: 30px;padding-bottom: 10px;width: 100%;display: block;color:white;font-family: 'Grand Hotel', cursive;">
						Sampang Hebat Bermartabat
					</span>
					<span class="login100-form-title" style="padding-bottom: 5px;color:white;font-size: 20px;    font-family: Cairo;">
						<?php echo $appname ?>
					</span>

					<div class="wrap-input100 validate-input form-userid" data-validate="User ID is required">
						<input class="input100 userid" type="text" name="userid" placeholder="User ID" style="border-radius:5px;color: white;background: rgb(0 0 0 / 30%);">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-user" aria-hidden="true"></i>
						</span>
					</div>

					<?php
					if ($login_method == 'otp') {
						echo '
						<div class="wrap-input100 validate-input form-pass-otp"  data-validate="Password is required">
							<input class="input100 pass" type="text" name="otp" placeholder="OTP" style="border-radius:5px;color: white;background: rgb(0 0 0 / 30%);">
							<span class="focus-input100"></span>
							<span class="symbol-input100">
								<i class="fa fa-lock" aria-hidden="true"></i>
							</span>
						</div>
						';
					} else if ($login_method == 'password') {
						echo '
						<div class="wrap-input100 validate-input" data-validate="Password is required">
							<input class="input100 pass" type="password" name="pass" placeholder="Password">
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

						<?php
						if ($login_method == 'otp') {
							echo '<br>
							<button type="button" class="login100-form-btn sendOTP ladda-button form-request-otp" data-color="blue" data-style="slide-right">
								Request OTP
							</button>';
						}
						?>


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
		var lOTP = Ladda.create(document.querySelector('.sendOTP'));
		var xCode = null;


		$('.sendOTP').on('click', function() {
			lOTP.start();

			var login = $('.login').serialize();
			$.ajax({
				url: "./lib/base/send_otp.php",
				method: "POST",
				data: "method=login&" + login,
				dataType: "json",
				success: function(msg) {
					popup(msg.status, msg.text, '');
					if (msg.status == 'success') {
						xCode = msg.uniqid;
					}

					lOTP.stop();

				},
				error: function(err) {
					popup('error', 'Error System', '');
					console.log(err);
					lOTP.stop();
				}
			})
		})


		$('.submit').on('click', function() {
			l.start();

			var login = $('.login').serialize();
			$.ajax({
				url: "./lib/base/login_act.php?xCode=" + xCode + "&" + login,
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



	<!-- Chart code -->
	<script>
		// Create root element
		// https://www.amcharts.com/docs/v5/getting-started/#Root_element
		var root = am5.Root.new("chartdiv");


		// Set themes
		// https://www.amcharts.com/docs/v5/concepts/themes/
		root.setThemes([
			am5themes_Animated.new(root)
		]);


		// Create chart
		// https://www.amcharts.com/docs/v5/charts/xy-chart/
		var chart = root.container.children.push(am5xy.XYChart.new(root, {
			panX: false,
			panY: false,
			wheelX: "none",
			wheelY: "none"
		}));

		// We don't want zoom-out button to appear while animating, so we hide it
		chart.zoomOutButton.set("forceHidden", true);


		// Create axes
		// https://www.amcharts.com/docs/v5/charts/xy-chart/axes/

		var xRenderer = am5xy.AxisRendererX.new(root, {
			minGridDistance: 30
		});

		var yRenderer = am5xy.AxisRendererY.new(root, {
			minGridDistance: 30
		});


		yRenderer.labels.template.setAll({
			oversizedBehavior: "wrap",
			textAlign: "right",
			maxWidth: 150
		});

		var yAxis = chart.yAxes.push(am5xy.CategoryAxis.new(root, {
			maxDeviation: 0,
			categoryField: "opd",
			renderer: yRenderer,
			tooltip: am5.Tooltip.new(root, {
				themeTags: ["axis"]
			})
		}));

		var xAxis = chart.xAxes.push(am5xy.ValueAxis.new(root, {
			maxDeviation: 0,
			min: 0,
			extraMax: 0.1,
			renderer: xRenderer
		}));



		// Add series
		// https://www.amcharts.com/docs/v5/charts/xy-chart/series/
		var series = chart.series.push(am5xy.ColumnSeries.new(root, {
			name: "Series 1",
			xAxis: xAxis,
			yAxis: yAxis,
			valueXField: "tte",
			categoryYField: "opd",
			tooltip: am5.Tooltip.new(root, {
				pointerOrientation: "left",
				labelText: "{valueX}"
			})
		}));


		// Rounded corners for columns
		series.columns.template.setAll({
			cornerRadiusTR: 5,
			cornerRadiusBR: 5
		});

		// Make each column to be of a different color
	/* 	series.columns.template.adapters.add("fill", function(fill, target) {
			return chart.get("colors").getIndex(series.columns.indexOf(target));
		});

		series.columns.template.adapters.add("stroke", function(stroke, target) {
			return chart.get("colors").getIndex(series.columns.indexOf(target));
		}); */


		// Set data
		var data = <?php echo json_encode($tteperopd, JSON_NUMERIC_CHECK); ?>;

		yAxis.data.setAll(data);
		series.data.setAll(data);
		sortCategoryAxis();

		// Get series item by category
		function getSeriesItem(category) {
			for (var i = 0; i < series.dataItems.length; i++) {
				var dataItem = series.dataItems[i];
				if (dataItem.get("categoryY") == category) {
					return dataItem;
				}
			}
		}

		chart.set("cursor", am5xy.XYCursor.new(root, {
			behavior: "none",
			xAxis: xAxis,
			yAxis: yAxis
		}));


		// Axis sorting
		function sortCategoryAxis() {

			// Sort by value
			series.dataItems.sort(function(x, y) {
				return x.get("valueX") - y.get("valueX"); // descending
				//return y.get("valueY") - x.get("valueX"); // ascending
			})

			// Go through each axis item
			am5.array.each(yAxis.dataItems, function(dataItem) {
				// get corresponding series item
				var seriesDataItem = getSeriesItem(dataItem.get("category"));

				if (seriesDataItem) {
					// get index of series data item
					var index = series.dataItems.indexOf(seriesDataItem);
					// calculate delta position
					var deltaPosition = (index - dataItem.get("index", 0)) / series.dataItems.length;
					// set index to be the same as series data item index
					dataItem.set("index", index);
					// set deltaPosition instanlty
					dataItem.set("deltaPosition", -deltaPosition);
					// animate delta position to 0
					dataItem.animate({
						key: "deltaPosition",
						to: 0,
						duration: 1000,
						easing: am5.ease.out(am5.ease.cubic)
					})
				}
			});

			// Sort axis items by index.
			// This changes the order instantly, but as deltaPosition is set,
			// they keep in the same places and then animate to true positions.
			yAxis.dataItems.sort(function(x, y) {
				return x.get("index") - y.get("index");
			});
		}


		// Make stuff animate on load
		// https://www.amcharts.com/docs/v5/concepts/animations/
		series.appear(1000);
		chart.appear(1000, 100);


		// pie charts

		// https://www.amcharts.com/docs/v5/getting-started/#Root_element
		var root = am5.Root.new("pie");
		

		// Create root element
		class MyTheme extends am5.Theme {
  
		  setupDefaultRules() {
			
			var theme = this;
			
			this.patterns = [
			  am5.LinePattern.new(this._root, {
				color: am5.color(0xffffff),
				rotation: 45
			  }),
			  am5.RectanglePattern.new(this._root, {
				color: am5.color(0xffffff)
			  }),
			  am5.CirclePattern.new(this._root, {
				color: am5.color(0xffffff)
			  })
			];
			
			this.currentPattern = 0;
			
			this.rule("Slice").setAll({
			  fillOpacity: 0.9,
			});
			
			this.rule("ColorSet").set("colors", [
			  am5.color("#ea8557"),
			  am5.color("#5768ea"),
			  am5.color("#57dfea"),
			  am5.color("#57eaae"),
			  am5.color("#57d4ea")
			]);
			

		  }
		}
	
		
			
		root.setThemes([
		  am5themes_Animated.new(root),
		  MyTheme.new(root)
		]);


		// Create chart
		// https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/
		var charts = root.container.children.push(
			am5percent.PieChart.new(root, {
				endAngle: 270,
				layout: root.verticalLayout
			})
		);
		
		

		// Create series
		// https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/#Series
		var seriesPie = charts.series.push(
			am5percent.PieSeries.new(root, {
				valueField: "value",
				categoryField: "jenis_dokumen",
				alignLabels: false,
				endAngle: 270,
				legendLabelText: "[{fill}]{jenis_dokumen}[/]",
				legendValueText: "[bold {fill}]{value}[/]"
			})
		);

		seriesPie.states.create("hidden", {
			endAngle: -90
		});

		// Disabling labels and ticks
		seriesPie.labels.template.set("visible", false);
		seriesPie.ticks.template.set("visible", false);

		// Set data
		// https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/#Setting_data
		seriesPie.data.setAll(
			<?php echo json_encode($tteperjenisdok, JSON_NUMERIC_CHECK); ?>
		);
		
		

		seriesPie.appear(1000, 100);

		// Add legend
		var legend = charts.children.push(am5.Legend.new(root, {
			nameField: "jenis_dokumen",
			// centerX: am5.percent(0),
			x: am5.percent(15)
		}));

		legend.data.setAll(seriesPie.dataItems);
	</script>

</body>

</html>