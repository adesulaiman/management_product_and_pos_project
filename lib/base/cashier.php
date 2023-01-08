<?php

require "../../config.php";
require "../base/db.php";
require "../base/security_login.php";


?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Patua+One&display=swap" rel="stylesheet">

<style>
  .tableFixHead {
    overflow: auto;
    height: 300px;
  }

  .tableFixHead thead th {
    position: sticky;
    top: 0;
    z-index: 1;
  }

  /* Just common table stuff. Really. */
  table {
    border-collapse: collapse;
    width: 100%;
  }

  th,
  td {
    padding: 10px 6px !important;
    white-space: normal !important;
  }

  th {
    background: #eee;
  }

  .totalContainer {
    border-top: 2px solid #cfcaca;
    margin-top: 20px;
  }

  h2 {
    font-family: 'Patua One', cursive !important;
  }

  h2,
  h3,
  h4 {
    margin: 5px 0 !important;
  }

  .btntransaksi {
    width: 19%;
    height: 80px;
    font-size: 13px;
    font-family: 'Patua One', cursive;
  }

  .btnPay {
    width: 25%;
    height: 60px;
    float: right;
    font-size: 17px;
    font-family: 'Patua One', cursive;
  }

  .cash-amount,
  .transfer-amount,
  .debit-amount,
  .credit-amount,
  .dp-amount {
    display: none;
  }
</style>


<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-7">
              <div style="height: 70vh;">
                <div class="user-panel">
                  <div class="pull-left image">
                    <img src="assets/img/logocashier.png" alt="User Image">
                  </div>
                  <div class="pull-left info">
                    <p style="font-size: 21px;margin-bottom: 0;color:black">Ade Sulaeman</p>
                    <a href="#" style="color:#3c8dbc">Staff Cashier</a>
                  </div>
                  <div style="float:right">
                    <h4>No Struk : #jkl12jk23l12</h4>
                  </div>
                </div>


                <div class="tableFixHead">
                  <table class="table table-striped" style="font-family: 'Patua One', cursive; ">
                    <thead>
                      <tr>
                        <th>Product Name</th>
                        <th>Product Code</th>
                        <th>Qty</th>
                        <th>Gram</th>
                        <th>Price</th>
                        <th>Total</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Cicin A1 Kadar 30 karat sdhjaks d sdklajkd jsakdj </td>
                        <td>728197891</td>
                        <td>12</td>
                        <td>2.782</td>
                        <td>10.000.000</td>
                        <td>24.000.000.000</td>
                      </tr>
                      <tr>
                        <td>728197891</td>
                        <td>Cicin A1 Kadar 30 karat</td>
                        <td>12</td>
                        <td>2.782</td>
                        <td>10.000.000</td>
                        <td>24.000.000.000</td>
                      </tr>
                      <tr>
                        <td>728197891</td>
                        <td>Cicin A1 Kadar 30 karat</td>
                        <td>12</td>
                        <td>2.782</td>
                        <td>10.000.000</td>
                        <td>24.000.000.000</td>
                      </tr>
                      <tr>
                        <td>728197891</td>
                        <td>Cicin A1 Kadar 30 karat</td>
                        <td>12</td>
                        <td>2.782</td>
                        <td>10.000.000</td>
                        <td>24.000.000.000</td>
                      </tr>
                      <tr>
                        <td>728197891</td>
                        <td>Cicin A1 Kadar 30 karat</td>
                        <td>12</td>
                        <td>2.782</td>
                        <td>10.000.000</td>
                        <td>24.000.000.000</td>
                      </tr>
                      <tr>
                        <td>728197891</td>
                        <td>Cicin A1 Kadar 30 karat</td>
                        <td>12</td>
                        <td>2.782</td>
                        <td>10.000.000</td>
                        <td>24.000.000.000</td>
                      </tr>
                      <tr>
                        <td>728197891</td>
                        <td>Cicin A1 Kadar 30 karat</td>
                        <td>12</td>
                        <td>2.782</td>
                        <td>10.000.000</td>
                        <td>24.000.000.000</td>
                      </tr>
                      <tr>
                        <td>728197891</td>
                        <td>Cicin A1 Kadar 30 karat</td>
                        <td>12</td>
                        <td>2.782</td>
                        <td>10.000.000</td>
                        <td>24.000.000.000</td>
                      </tr>
                    </tbody>
                  </table>

                </div>


                <div class="totalContainer col-md-12">
                  <h2 style="float: left;">Total</h2>
                  <h2 style="float: right;">Rp 452.231.000</h2>
                </div>

                <div class="totalChange col-md-12">
                  <h4 style="float: left;">Payment Method</h4>
                  <h4 style="float: right;">Cash / Debit</h4>
                </div>

                <div class="totalChange col-md-12">
                  <h4 style="float: left;">Payment</h4>
                  <h4 style="float: right;">Rp 452.231.000</h4>
                </div>


                <div class="totalChange col-md-12">
                  <h4 style="float: left;">Change</h4>
                  <h4 style="float: right;">Rp 40.000</h4>
                </div>


              </div>
            </div>

            <div class="col-md-5" style="margin-top:20px">
              <div class="col-md-12">
                <label>Add Product</label>
                <div class="input-group input-group-sm inputScanner">
                  <input type="text" class="form-control">
                  <span class="input-group-btn">
                    <button type="button" class="btn btn-success btn-flat"><i class="fa fa-fw fa-plus-square"></i> Add</button>
                    <button type="button" class="btn btn-info btn-flat searchProduct"><i class="fa fa-fw fa-search"></i> Search Product</button>
                  </span>
                </div>

                <div class="input-group input-group-sm inputManual" style="display: none;">
                  <select class="form-control select2" style="width: 100%;">
                    <option selected="selected">Alabama</option>
                    <option>Alaska</option>
                    <option>California</option>
                    <option>Delaware</option>
                    <option>Tennessee</option>
                    <option>Texas</option>
                    <option>Washington</option>
                  </select>
                  <span class="input-group-btn">
                    <button type="button" class="btn btn-success btn-flat"><i class="fa fa-fw fa-plus-square"></i> Add</button>
                    <button type="button" class="btn btn-danger btn-flat closeSearch"><i class="fa fa-close"></i> Close</button>
                  </span>
                </div>
              </div>

              <div class="col-md-12">
                <label style="margin-top: 30px;">Payment Method</label>
              </div>
              <div class="col-md-12">
                <button class="btn btn-default btntransaksi" data-id="cash"><i class="fa fa-fw fa-money"></i> Cash</button>
                <button class="btn btn-default btntransaksi" data-id="transfer"><i class="fa fa-fw fa-exchange"></i> Transfer</button>
                <button class="btn btn-default btntransaksi" data-id="debit"><i class="fa fa-fw fa-credit-card"></i> Debit</button>
                <button class="btn btn-default btntransaksi" data-id="credit"><i class="fa fa-fw fa-credit-card"></i> Credit</button>
                <button class="btn btn-default btntransaksi" data-id="dp"><i class="fa fa-fw fa-money"></i> DP</button>
              </div>

              <div class="col-md-12" style="height: 230px;">
                <label style="margin-top: 30px;">Payment</label>
                <div class="col-md-12 cash-amount">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-4 control-label" style="margin-top: 7px;">Cash Amount</label>

                    <div class="col-sm-8">
                      <div class="input-group">
                        <span class="input-group-addon">Rp</span>
                        <input type="text" class="form-control">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-12 transfer-amount">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-4 control-label" style="margin-top: 7px;">Transfer Amount</label>

                    <div class="col-sm-8">
                      <div class="input-group">
                        <span class="input-group-addon">Rp</span>
                        <input type="text" class="form-control">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-12 debit-amount">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-4 control-label" style="margin-top: 7px;">Debit Amount</label>

                    <div class="col-sm-8">
                      <div class="input-group">
                        <span class="input-group-addon">Rp</span>
                        <input type="text" class="form-control">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-12 credit-amount">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-4 control-label" style="margin-top: 7px;">Credit Amount</label>

                    <div class="col-sm-8">
                      <div class="input-group">
                        <span class="input-group-addon">Rp</span>
                        <input type="text" class="form-control">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-12 dp-amount">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-4 control-label" style="margin-top: 7px;">DP Amount</label>

                    <div class="col-sm-8">
                      <div class="input-group">
                        <span class="input-group-addon">Rp</span>
                        <input type="text" class="form-control">
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-12">
                <button class="btn btn-success btnPay"><i class="fa fa-fw fa-check-square-o"></i> PAY</button>
              </div>


            </div>
          </div>
        </div>
        <!-- /.box-body -->
      </div>
    </div>
  </div>
</section>

<script>
  $("body").addClass("sidebar-collapse");

  $(".select2").select2();

  $(".searchProduct").on("click", function() {
    $(".inputScanner").css("display", "none");
    $(".inputManual").css("display", "inline-table");
  });

  $(".closeSearch").on("click", function() {
    $(".inputScanner").css("display", "inline-table");
    $(".inputManual").css("display", "none");
  });

  $(".btntransaksi").on("click", function() {

    let selected = $(this).data("select");
    let type = $(this).data("id");
    if (selected == "1") {
      $(this).removeClass("btn-info");
      $(this).addClass("btn-default");

      if (type == 'cash') {
        $(".cash-amount").css("display", "none");
      }

      if (type == 'transfer') {
        $(".transfer-amount").css("display", "none");
      }

      if (type == 'debit') {
        $(".debit-amount").css("display", "none");
      }

      if (type == 'credit') {
        $(".credit-amount").css("display", "none");
      }

      if (type == 'dp') {
        $(".dp-amount").css("display", "none");
      }

      $(this).data("select", "0");
    } else {
      $(this).removeClass("btn-default");
      $(this).addClass("btn-info");

      if (type == 'cash') {
        $(".cash-amount").css("display", "block");
      }

      if (type == 'transfer') {
        $(".transfer-amount").css("display", "block");
      }

      if (type == 'debit') {
        $(".debit-amount").css("display", "block");
      }

      if (type == 'credit') {
        $(".credit-amount").css("display", "block");
      }

      if (type == 'dp') {
        $(".dp-amount").css("display", "block");
      }

      $(this).data("select", "1");
    }

  });
</script>