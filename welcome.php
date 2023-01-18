<?php
require "config.php";
require "lib/base/db.php";
require "lib/base/security_login_global.php";

function sctNom($nom)
{
  //convert digit
  $nomConvert = "";
  if ($nom > 1000000000) {
    $nomConvert = round(($nom / 1000000000), 1) . "M";
  } else if ($nom > 1000000) {
    $nomConvert = round(($nom / 1000000), 1) . "B";
  } else if ($nom > 1000) {
    $nomConvert = round(($nom / 1000), 1) . "K";
  } else {
    $nomConvert = ($nom == null) ? 0 : $nom;
  }
  return $nomConvert;
}

$salesCurr = $adeQ->select("
select 
sum((payment_cash + payment_trasnfer + payment_debit + payment_credit + payment_dp)) sales
from data_sales
where DATE_FORMAT(sales_date, \"%Y%m\") = " . date("Ym") . " 
");

$salesLast = $adeQ->select("
select 
sum((payment_cash + payment_trasnfer + payment_debit + payment_credit + payment_dp)) sales
from data_sales
where DATE_FORMAT(sales_date, \"%Y%m\") = " . date("Ym", strtotime("-1 months")) . " 
");

$totalProduct = $adeQ->select("
select sum(s.qty_stock * p.sell_price) total from data_stock_product s
inner join data_product p on s.barcode=p.barcode
");

$totalItems = $adeQ->select("
select count(1) items from data_product where coalesce(is_delete, 0)=0
");

$salesGraph = $adeQ->select("
select 
sum((payment_cash + payment_trasnfer + payment_debit + payment_credit + payment_dp)) value,
DATE_FORMAT(sales_date, \"new Date(%Y, %m, %d).getTime()\") date
from data_sales
where DATE_FORMAT(sales_date, \"%Y\") = " . date("Y") . "
group by DATE_FORMAT(sales_date, \"%Y %m %d\")
");

$top10sales = $adeQ->select("
select concat(barcode, ' - ', product_name) product, sum(qty) qtysales from data_sales_detail
where DATE_FORMAT(sales_date, \"%Y%m\") = " . date("Ym") . "
group by product_name
order by sum(qty) desc
limit 10
");




?>

<style>
  .container {

    width: 700px !important;

    text-align: center;

  }

  .file_drag_area {

    height: 350px;
    padding: 10%;
    border: 2px dashed #ccc;
    text-align: center;
    font-size: 24px;
  }

  .file_drag_over {
    color: #000;
    border-color: #000;

  }

  .bg-green,
  .callout.callout-success,
  .alert-success,
  .label-success,
  .modal-success .modal-body {
    background-color: #00a65a8a !important;
  }

  .bg-red,
  .callout.callout-danger,
  .alert-danger,
  .alert-error,
  .label-danger,
  .modal-danger .modal-body {
    background-color: #dd4b3985 !important;
  }

  #chartdiv {
    width: 100%;
    height: 500px;
  }
</style>

<!-- FILTER DATA -->
<section class="content" style="min-height:auto">

</section>

<div class="row" style="padding:0 15px">
  <div class="col-lg-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-aqua">
      <div class="inner">
        <h3><?php echo sctNom($salesCurr[0]['sales']) ?></h3>

        <p>Sales Current Month</p>
      </div>
      <div class="icon">
        <i class="fa fa-fw fa-line-chart"></i>

      </div>
    </div>
  </div>
  <!-- ./col -->
  <div class="col-lg-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-green">
      <div class="inner">
        <h3><?php echo sctNom($salesLast[0]['sales']) ?></h3>

        <p>Sales Last Month</p>
      </div>
      <div class="icon">
        <i class="fa fa-fw fa-bar-chart"></i>
      </div>
    </div>
  </div>
  <!-- ./col -->
  <div class="col-lg-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-yellow">
      <div class="inner">
        <h3><?php echo sctNom($totalProduct[0]['total']) ?></h3>

        <p>Total Stock</p>
      </div>
      <div class="icon">
        <i class="fa fa-fw fa-money"></i>
      </div>
    </div>
  </div>
  <!-- ./col -->
  <div class="col-lg-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-red">
      <div class="inner">
        <h3><?php echo sctNom($totalItems[0]['items']) ?></h3>

        <p>Total Products</p>
      </div>
      <div class="icon">
        <i class="fa fa-fw fa-diamond"></i>
      </div>
    </div>
  </div>
  <!-- ./col -->
</div>

<section class="content connectedSortable">
  <div class="nav-tabs-custom">
    <!-- Tabs within a box -->
    <ul class="nav nav-tabs pull-right">
      <li class="pull-left header"><i class="fa fa-inbox"></i> Grafik Sales of Year and 10 top sales items</li>
    </ul>
    <div class="tab-content no-padding">
      <div class="row">
        <div class="col-md-8 text-center">
          <h4>Sales Trand per Month</h4>
          <div id="chartdiv"></div>
        </div>
        <div class="col-md-4">
          <h4>Top Sales Product</h4>
          <table class="table table-hover">
            <thead>
              <tr>
                <th>Rank</th>
                <th>Product</th>
                <th>Qty Sales</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $rank = 1;
              foreach ($top10sales as $dt) {
                echo "
                <tr>
                  <td>$rank</td>
                  <td>$dt[product]</td>
                  <td>$dt[qtysales]</td>
                </tr>";
                $rank++;
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>



<script>
  am5.ready(function() {

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
      panX: true,
      panY: true,
      wheelX: "panX",
      wheelY: "zoomX",
      pinchZoomX: true
    }));

    chart.get("colors").set("step", 3);


    // Add cursor
    // https://www.amcharts.com/docs/v5/charts/xy-chart/cursor/
    var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {}));
    cursor.lineY.set("visible", false);


    // Create axes
    // https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
    var xAxis = chart.xAxes.push(am5xy.DateAxis.new(root, {
      maxDeviation: 0.3,
      baseInterval: {
        timeUnit: "day",
        count: 1
      },
      renderer: am5xy.AxisRendererX.new(root, {}),
      tooltip: am5.Tooltip.new(root, {})
    }));

    var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
      maxDeviation: 0.3,
      renderer: am5xy.AxisRendererY.new(root, {})
    }));


    // Create series
    // https://www.amcharts.com/docs/v5/charts/xy-chart/series/
    var series = chart.series.push(am5xy.LineSeries.new(root, {
      name: "Series 1",
      xAxis: xAxis,
      yAxis: yAxis,
      valueYField: "value",
      valueXField: "date",
      tooltip: am5.Tooltip.new(root, {
        labelText: "{valueY}"
      })
    }));
    series.strokes.template.setAll({
      strokeWidth: 2
    });

    // Create animating bullet by adding two circles in a bullet container and
    // animating radius and opacity of one of them.
    series.bullets.push(function(root, series, dataItem) {
      if (dataItem.dataContext.bullet) {
        var container = am5.Container.new(root, {});
        var circle0 = container.children.push(am5.Circle.new(root, {
          radius: 5,
          fill: am5.color(0xff0000)
        }));
        var circle1 = container.children.push(am5.Circle.new(root, {
          radius: 5,
          fill: am5.color(0xff0000)
        }));

        circle1.animate({
          key: "radius",
          to: 20,
          duration: 1000,
          easing: am5.ease.out(am5.ease.cubic),
          loops: Infinity
        });
        circle1.animate({
          key: "opacity",
          to: 0,
          from: 1,
          duration: 1000,
          easing: am5.ease.out(am5.ease.cubic),
          loops: Infinity
        });

        return am5.Bullet.new(root, {
          sprite: container
        })
      }
    })

    // Set data
    var data = [<?php
                foreach ($salesGraph as $sl) {
                  echo "{date : $sl[date], value:$sl[value]},";
                }
                ?>];

    series.data.setAll(data);


    // Make stuff animate on load
    // https://www.amcharts.com/docs/v5/concepts/animations/
    series.appear(1000);
    chart.appear(1000, 100);

  }); // end am5.ready()
</script>