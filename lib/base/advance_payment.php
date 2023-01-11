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
    height: 430px;
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

  .debitNumber,
  .transferNumber,
  .creditNumber {
    display: none;
  }

  .containerCashAmount,
  .containerTransferAmount,
  .containerDebitAmount,
  .containerCreditAmount,
  .containerDPAmount {
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
                    <p style="font-size: 21px;margin-bottom: 0;color:black"><?php echo $_SESSION['username'] ?></p>
                    <a href="#" style="color:#3c8dbc">Staff Cashier</a>
                  </div>
                  <div style="float:right">
                    <h4>Bill No : &ensp;<span style="float:right" class="strukNumber">-</span></h4>
                    <h5>Date : &nbsp;<span style="float:right" class="dateTransaksi">-</span></h5>
                  </div>
                </div>


                <div class="tableFixHead">
                  <table class="table table-striped" style="font-family: 'Patua One', cursive; ">
                    <thead>
                      <tr>
                        <th>Product Name</th>
                        <th>Product Code</th>
                        <th style="width: 100px;">Gram</th>
                        <th style="width: 90px;">Qty</th>
                        <th>@ Price</th>
                        <th>Total</th>
                      </tr>
                    </thead>
                    <tbody class="bodyRawTable">
                    </tbody>
                  </table>

                </div>


                <div class="totalContainer col-md-12">
                  <h2 style="float: left;">Total</h2>
                  <h2 style="float: right;" class="totalAllAmount">Rp 0</h2>
                </div>

                <div class="col-md-12">
                  <h4 style="float: left;">Payment Method</h4>
                  <h4 style="float: right;" class="paymentMethod">-</h4>
                </div>

                <div class=" col-md-12 containerCashAmount">
                  <h4 style="float: left;">Cash Amount</h4>
                  <h4 style="float: right;" class="cashAmount">Rp 0</h4>
                </div>

                <div class=" col-md-12 containerTransferAmount">
                  <h4 style="float: left;">Transfer Amount</h4>
                  <h4 style="float: right;" class="transferAmount">Rp 0</h4>
                </div>

                <div class=" col-md-12 containerDebitAmount">
                  <h4 style="float: left;">Debit Amount</h4>
                  <h4 style="float: right;" class="debitAmount">Rp 0</h4>
                </div>

                <div class=" col-md-12 containerCreditAmount">
                  <h4 style="float: left;">Credit Amount</h4>
                  <h4 style="float: right;" class="creditAmount">Rp 0</h4>
                </div>

                <div class=" col-md-12 containerDPAmount">
                  <h4 style="float: left;">DP Amount</h4>
                  <h4 style="float: right;" class="dpAmount">Rp 0</h4>
                </div>


                <div class="totalChange col-md-12">
                  <h4 style="float: left;">Remaining Payment</h4>
                  <h4 style="float: right;color:red;font-weight:bolder" class="change">Rp 0</h4>
                </div>


              </div>
            </div>

            <div class="col-md-5" style="margin-top:20px">
              <div class="col-md-12">
                <label>No Bill</label>
                <div class="input-group input-group-sm inputManual">
                  <select class="form-control select2 inputProductManual" style="width: 100%;">
                    <option value="">-- Find Bill --</option>
                  </select>
                  <span class="input-group-btn">
                    <button type="button" class="btn btn-success btn-flat addManual"><i class="fa fa-fw fa-search"></i> Show</button>
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
              </div>

              <div class="col-md-12" style="height: 280px;">
                <label style="margin-top: 30px;">Payment</label>
                <div class="col-md-12 cash-amount">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-4 control-label" style="margin-top: 7px;">Cash Amount</label>

                    <div class="col-sm-8">
                      <div class="input-group">
                        <span class="input-group-addon">Rp</span>
                        <input type="number" class="form-control inputCashAmount">
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
                        <input type="number" class="form-control inputTransferAmount">
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
                        <input type="number" class="form-control inputDebitAmount">
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
                        <input type="number" class="form-control inputCreditAmount">
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-12" style="height: 120px;">
                <div class="form-group transferNumber">
                  <label for="inputEmail3" class="col-sm-4 control-label" style="margin-top: 7px;">Transfer Account Number</label>

                  <div class="col-sm-8">
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-fw fa-exchange"></i></span>
                      <input type="text" class="form-control inputTransferNumber">
                    </div>
                  </div>
                </div>

                <div class="form-group debitNumber">
                  <label for="inputEmail3" class="col-sm-4 control-label" style="margin-top: 7px;">Debit Number</label>

                  <div class="col-sm-8">
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-fw fa-credit-card"></i></span>
                      <input type="text" class="form-control inputDebitNumber">
                    </div>
                  </div>
                </div>

                <div class="form-group creditNumber">
                  <label for="inputEmail3" class="col-sm-4 control-label" style="margin-top: 7px;">Credit Number</label>

                  <div class="col-sm-8">
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-fw fa-credit-card"></i></span>
                      <input type="text" class="form-control inputCreditNumber">
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
  var vListProduct = {};

  
  $("body").addClass("sidebar-collapse");
  var paymentdp = 0;
  var nostruk = "";

  function format(n, sep, decimals) {
    sep = sep || "."; // Default to period as decimal separator
    decimals = decimals || 2; // Default to 2 decimals

    return n.toLocaleString().split(sep)[0] +
      sep +
      n.toFixed(decimals).split(sep)[1];
  }

  $(".select2").select2({
    ajax: {
      url: './lib/base/select_data.php?t=select_vw_dp&filter=all',
      dataType: 'json',
      data: function(params) {
        return {
          search: params.term
        };
      }
    }
  });

  $(".searchProduct").on("click", function() {
    $(".inputScanner").css("display", "none");
    $(".inputManual").css("display", "inline-table");
  });

  $(".closeSearch").on("click", function() {
    $(".inputScanner").css("display", "inline-table");
    $(".inputManual").css("display", "none");
  });

  $(".inputCashAmount").on("keyup", function() {
    let value = $(this).val();
    $(".cashAmount").html("Rp " + format(Number(value)));

    var getTotalAmount = $(".totalAllAmount").html();
    var getTotalPayment = Number(paymentdp) +
      Number($(".inputCreditAmount").val()) +
      Number($(".inputTransferAmount").val()) +
      Number($(".inputDebitAmount").val()) +
      Number($(".inputCashAmount").val());

    getTotalAmount = getTotalAmount.replaceAll("Rp ", "");
    getTotalAmount = getTotalAmount.replaceAll(",", "");
    getTotalAmount = Number(getTotalAmount);
    var changeAmount = getTotalPayment - getTotalAmount;

    if(changeAmount >= 0 ){
      $(".change").css("color", "green");  
    }else{
      $(".change").css("color", "red");
    }

    $(".change").html("Rp " + format(changeAmount));
  });

  $(".inputTransferAmount").on("keyup", function() {
    let value = $(this).val();
    $(".transferAmount").html("Rp " + format(Number(value)));

    var getTotalAmount = $(".totalAllAmount").html();
    var getTotalPayment = Number(paymentdp)  +
      Number($(".inputCreditAmount").val()) +
      Number($(".inputTransferAmount").val()) +
      Number($(".inputDebitAmount").val()) +
      Number($(".inputCashAmount").val());

    getTotalAmount = getTotalAmount.replaceAll("Rp ", "");
    getTotalAmount = getTotalAmount.replaceAll(",", "");
    getTotalAmount = Number(getTotalAmount);
    var changeAmount = getTotalPayment - getTotalAmount;
    if(changeAmount >= 0 ){
      $(".change").css("color", "green");  
    }else{
      $(".change").css("color", "red");
    }

    $(".change").html("Rp " + format(changeAmount));
  });

  $(".inputDebitAmount").on("keyup", function() {
    let value = $(this).val();
    $(".debitAmount").html("Rp " + format(Number(value)));

    var getTotalAmount = $(".totalAllAmount").html();
    var getTotalPayment = Number(paymentdp)  +
      Number($(".inputCreditAmount").val()) +
      Number($(".inputTransferAmount").val()) +
      Number($(".inputDebitAmount").val()) +
      Number($(".inputCashAmount").val());

    getTotalAmount = getTotalAmount.replaceAll("Rp ", "");
    getTotalAmount = getTotalAmount.replaceAll(",", "");
    getTotalAmount = Number(getTotalAmount);
    var changeAmount = getTotalPayment - getTotalAmount;

    if(changeAmount >= 0 ){
      $(".change").css("color", "green");  
    }else{
      $(".change").css("color", "red");
    }
    $(".change").html("Rp " + format(changeAmount));
  });

  $(".inputCreditAmount").on("keyup", function() {
    let value = $(this).val();
    $(".creditAmount").html("Rp " + format(Number(value)));

    var getTotalAmount = $(".totalAllAmount").html();
    var getTotalPayment = Number(paymentdp)  +
      Number($(".inputCreditAmount").val()) +
      Number($(".inputTransferAmount").val()) +
      Number($(".inputDebitAmount").val()) +
      Number($(".inputCashAmount").val());

    getTotalAmount = getTotalAmount.replaceAll("Rp ", "");
    getTotalAmount = getTotalAmount.replaceAll(",", "");
    getTotalAmount = Number(getTotalAmount);
    var changeAmount = getTotalPayment - getTotalAmount;

    if(changeAmount >= 0 ){
      $(".change").css("color", "green");  
    }else{
      $(".change").css("color", "red");
    }
    $(".change").html("Rp " + format(changeAmount));
  });

  $(".inputDPAmount").on("keyup", function() {
    let value = $(this).val();
    $(".dpAmount").html("Rp " + format(Number(value)));

    var getTotalAmount = $(".totalAllAmount").html();
    var getTotalPayment = Number(paymentdp)  +
      Number($(".inputCreditAmount").val()) +
      Number($(".inputTransferAmount").val()) +
      Number($(".inputDebitAmount").val()) +
      Number($(".inputCashAmount").val());

    getTotalAmount = getTotalAmount.replaceAll("Rp ", "");
    getTotalAmount = getTotalAmount.replaceAll(",", "");
    getTotalAmount = Number(getTotalAmount);
    var changeAmount = getTotalPayment - getTotalAmount;
    $(".change").html("Rp " + format(changeAmount));


  });



  var typePayment = [];

  $(".btntransaksi").on("click", function() {

    let selected = $(this).data("select");
    let type = $(this).data("id");


    if (selected == "1") {
      $(this).removeClass("btn-info");
      $(this).addClass("btn-default");

      if (type == 'cash') {
        typePayment.splice(typePayment.indexOf('Cash'), 1);
        $(".cash-amount").css("display", "none");
        $(".inputCashAmount").val(null);
        $(".containerCashAmount").css("display", "none");
      }

      if (type == 'transfer') {
        typePayment.splice(typePayment.indexOf('Transfer'), 1);
        $(".transfer-amount").css("display", "none");
        $(".transferNumber").css("display", "none");
        $(".inputTransferNumber").val(null);
        $(".inputTransferAmount").val(null);
        $(".containerTransferAmount").css("display", "none");
      }

      if (type == 'debit') {
        typePayment.splice(typePayment.indexOf('Debit'), 1);
        $(".debit-amount").css("display", "none");
        $(".debitNumber").css("display", "none");
        $(".inputDebitNumber").val(null);
        $(".inputDebitAmount").val(null);
        $(".containerDebitAmount").css("display", "none");
      }

      if (type == 'credit') {
        typePayment.splice(typePayment.indexOf('Credit'), 1);
        $(".creditNumber").css("display", "none");
        $(".inputCreditNumber").val(null);
        $(".credit-amount").css("display", "none");
        $(".inputCreditAmount").val(null);
        $(".containerCreditAmount").css("display", "none");
      }

      if (type == 'dp') {
        typePayment.splice(typePayment.indexOf('DP'), 1);
        $(".dp-amount").css("display", "none");
        $(".inputDPAmount").val(null);
        $(".containerDPAmount").css("display", "none");
      }

      $(this).data("select", "0");
    } else {
      $(this).removeClass("btn-default");
      $(this).addClass("btn-info");

      if (type == 'cash') {
        typePayment.push("Cash");
        $(".cash-amount").css("display", "block");
        $(".containerCashAmount").css("display", "block");
      }

      if (type == 'transfer') {
        typePayment.push("Transfer");
        $(".transferNumber").css("display", "block");
        $(".transfer-amount").css("display", "block");
        $(".containerTransferAmount").css("display", "block");
      }

      if (type == 'debit') {
        typePayment.push("Debit");
        $(".debitNumber").css("display", "block");
        $(".debit-amount").css("display", "block");
        $(".containerDebitAmount").css("display", "block");
      }

      if (type == 'credit') {
        typePayment.push("Credit");
        $(".creditNumber").css("display", "block");
        $(".credit-amount").css("display", "block");
        $(".containerCreditAmount").css("display", "block");
      }

      if (type == 'dp') {
        typePayment.push("DP");
        $(".dp-amount").css("display", "block");
        $(".containerDPAmount").css("display", "block");
      }

      $(this).data("select", "1");
    }

    $(".paymentMethod").html(typePayment.join(" & "));

  });


  $(".addManual").on("click", function() {
    var getBarcode = $(".inputProductManual").val();
    var dataForm = {
      "nostruk": getBarcode
    };
    if (getBarcode != "") {
      getProduct(dataForm);
    } else {
      popup("error", "Barcode must fill !!");
    }
  });

  $(".btnPay").on("click", function() {

      swal({
          title: "Commit trancation ?",
          text: "Once commit transaction, you will not be able to back this transaction !",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willtransaction) => {
          if (willtransaction) {
            $("#loading").removeClass("hide");

            var inputAmount = {};
            var cardNo = {
              "inputTransferNumber": $(".inputTransferNumber").val(),
              "inputDebitNumber": $(".inputDebitNumber").val(),
              "inputCreditNumber": $(".inputCreditNumber").val()
            };

            $.each(typePayment, function(idx, val) {
              inputAmount[val] = $(".input" + val + "Amount").val();
            });

            var dataTransaction = {
              "payment": inputAmount,
              "typePayment": typePayment.join(" and "),
              "cardNo": cardNo,
              "nostruk": nostruk,
              "method" : "pelunasandp" 
            };

            $.ajax({
              method: "POST",
              url: "./lib/base/commit_transaction.php",
              data: dataTransaction,
              dataType: 'json',
              success: function(msg) {
                $("#loading").addClass("hide");

                if (msg.status == 'success') {
                  swal(msg.info, {
                    icon: "success",
                  }).then((value) => {
                    HtmlLoad('./lib/base/advance_payment.php?f=13', 'Cashier');
                  });
                } else {
                  popup("error", msg.info);
                }

              },
              error: function(msg) {
                console.log(msg);
                popup("error", "Error system !!");
              },
            });




          }
        });
    


  });




  function getProduct(dataForm) {
    $("#loading").removeClass("hide");
    $.ajax({
      method: "POST",
      url: "./lib/base/getDetailTransaksi.php",
      data: dataForm,
      dataType: 'json',
      success: function(msg) {

        $(".bodyRawTable").html(null);

        if (msg.status == "success") {

          var uniqid = Math.floor(Math.random() * 1000000);


          $.each(msg.data_detail_sales, function(idx, val) {
   
            var template = `<tr>
                        <td>` + val.product_name + ` (` + val.kadar_product + ` karat) <small style="font-weight: 100;margin-top: 2px;" class="label bg-orange">`+val.storage_name+`</small></td>
                        <td>`+val.id_category_storage+`-` + val.barcode + `</td>
                        <td>` + val.gram + `</td>
                        <td>` + val.qty + `</td>
                        <td>` + format(Number(val.price)) + `</td>
                        <td><span class="totalPrice">` + format(Number(val.price) * Number(val.qty)) + `</span></td>
                      </tr>`;
            $(".bodyRawTable").append(template);
          });

          $(".containerDPAmount").css("display", "block");
          $(".dpAmount").html("Rp " + format(Number(msg.data_sales.payment_dp)));
          $(".change").html("Rp " + format(Number(msg.data_sales.change)));
          $(".strukNumber").html(msg.data_sales.nobill);
          nostruk = msg.data_sales.nobill;
          $(".dateTransaksi").html(msg.data_sales.sales_date);
          paymentdp = msg.data_sales.payment_dp;



          var totalAll = 0;
          $('.totalPrice').each(function() {
            totalAll += Number($(this).html().replaceAll(",", ""))
          });
          $(".totalAllAmount").html("Rp " + format(totalAll));
          popup('success', msg.info, '');

          $(".inputProductScanner").val(null);
          $(".inputProductScanner").focus();


        } else {
          popup('error', msg.info, '');
        }

        $("#loading").addClass("hide");

      },
      error: function(err) {
        $("#loading").addClass("hide");
        console.log(err);
        popup('error', err.responseText, '');
      }
    });
  }
</script>