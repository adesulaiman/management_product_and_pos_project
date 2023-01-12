<?php

require "../../config.php";
require "../base/db.php";
require "../base/security_login.php";

$formDesc = "Print Barcode";
?>

<section class="content-header">
  <h1>
    <?php echo $formDesc ?>
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">Menus</a></li>
    <li class="active"><?php echo $formDesc ?></li>
  </ol>
</section>



<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-6">
              <select class="form-control select2 dataProduct" style="width: 100%;">
                <option value="">-- Find Product --</option>
              </select>
            </div>
            <div class="col-md-2">
              <input type="number" class="form-control qtyPrint" placeholder="Qty Print" />
            </div>
            <div class="col-md-2">
              <button class="btn btn-info addProduct"><i class="fa fa-fw fa-plus-circle"></i> Add</button>
            </div>
            <div class="col-md-2">
              <button class="btn btn-success btn-lg btn-block printBarcode"><i class="fa fa-fw fa-print"></i> PRINT</button>
            </div>
          </div>
        </div>
        <div class="row">
          <hr>
          <div class="col-md-12" style="padding: 0 30px;">
            <table class="table tableGrid table-hover table-bordered">
              <thead>
                <tr>
                  <th>Action</th>
                  <th>Barcode</th>
                  <th>Product Name</th>
                  <th>Gram</th>
                  <th>Sell Price</th>
                  <th>Qty Print</th>
                </tr>
              </thead>
              <tbody class="rowData">
              </tbody>
            </table>
          </div>

        </div>
        <!-- /.box-body -->
      </div>
    </div>
  </div>
</section>

<script>
  var printValue = {};

  var table = $(".tableGrid").DataTable({
    "dom": 'ftp',
    "pageLength": 9
  });


  $('.tableGrid tbody').on('click', '.btnDelete', function() {
    let id = $(this).data("id");
    delete printValue[id];
    table
      .row($(this).parents('tr'))
      .remove()
      .draw();
  });


  $(".select2").select2({
    ajax: {
      url: './lib/base/select_data.php?t=vw_select_product&filter=all',
      dataType: 'json',
      data: function(params) {
        return {
          search: params.term
        };
      }
    }
  });

  $(".addProduct").on("click", function() {

    var barcode = $(".dataProduct").val();
    var qtyPrint = $(".qtyPrint").val();

    if (barcode != "" && qtyPrint != "") {
      $("#loading").removeClass("hide");

      var dataForm = {
        "barcode": barcode
      }

      $.ajax({
        method: "POST",
        url: "./lib/base/getproduct.php",
        data: dataForm,
        dataType: 'json',
        success: function(msg) {
          if (msg.status == 'success') {
            var uniqid = Math.floor(Math.random() * 1000000);

            printValue[uniqid] = {
              "barcode": msg.product[0].barcode,
              "product_name": msg.product[0].product_name,
              "gram": msg.product[0].gram,
              "sell_price": msg.product[0].sell_price,
              "qtyPrint": qtyPrint,
            };

            table.row.add([
              `<button class="btn btn-danger btn-xs btnDelete data-id="` + uniqid + `""><i class="fa fa-fw fa-trash"></i></button>`,
              msg.product[0].barcode,
              msg.product[0].product_name + ` (` + msg.product[0].description + `)`,
              msg.product[0].gram,
              'Rp ' + format(Number(msg.product[0].sell_price)),
              qtyPrint
            ]).draw(false);


          } else {
            popup("error", msg.info);
          }

          $("#loading").addClass("hide");
        },
        error: function(err) {
          popup("error", err.responseText);
          $("#loading").addClass("hide");
        }
      });

    } else {
      popup("error", "Product Must Fill !!");
    }


  })
</script>