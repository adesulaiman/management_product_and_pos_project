<?php

require "../../config.php";
require "../base/db.php";
require "../base/security_login.php";
require "../base/security_so.php";

$nostruk = uniqid();
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
        width: 45%;
        height: 80px;
        font-size: 13px;
        font-family: 'Patua One', cursive;
    }

    .btnPay {
        width: 50%;
        height: 60px;
        float: right;
        font-size: 17px;
        font-family: 'Patua One', cursive;
    }

    .tuker-pen,
    .total-kadar,
    .re-seller,
    .store-name {
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
                        <div class="col-md-8">
                            <div style="height: 70vh;">
                                <div class="user-panel">
                                    <div class="pull-left image">
                                        <i class="fa fa-fw fa-random" style="color: green; font-size: 40px; margin-top: 5px;"></i>
                                    </div>
                                    <div class="pull-left info">
                                        <p style="font-size: 21px;margin-bottom: 0;color:black">Out Product</p>
                                        <a href="#" style="color:#3c8dbc"><?php echo $_SESSION['username'] ?> - Staff</a>
                                    </div>
                                    <div style="float:right">
                                        <h4>Invoice No : &ensp;<span style="float: right;" class="strukNumber">INVR<?php echo $nostruk ?></span></h4>
                                        <h5>Date : &nbsp;<span style="float: right;"><?php echo date("d F Y") ?></span></h5>
                                    </div>
                                </div>


                                <div class="tableFixHead">
                                    <table class="table table-striped" style="font-family: 'Patua One', cursive; ">
                                        <thead>
                                            <tr>
                                                <th style="width: 10px;">Action</th>
                                                <th>Product Name</th>
                                                <th style="width: 100px;">Product Code</th>
                                                <th style="width: 50px;">Netto</th>
                                                <th style="width: 50px;">Brutto</th>
                                                <th style="width: 140px;">Qty</th>
                                                <th style="width: 140px;">Pen %</th>
                                                <th>E-Murni</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bodyRawTable">
                                        </tbody>
                                    </table>

                                </div>


                                <div class="totalContainer col-md-12">
                                    <h2 style="float: left;">Total Emas Murni</h2>
                                    <h2 style="float: right;" class="totalAllAmount">0</h2>
                                </div>

                            </div>
                        </div>

                        <div class="col-md-4" style="margin-top:20px">
                            <div class="col-md-12">
                                <label>Add Product</label>
                                <div class="input-group input-group-sm inputScanner">
                                    <input type="text" class="form-control inputProductScanner" maxlength="6" , placeholder="ex : 128i17uxj2123">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-info btn-flat searchProduct"><i class="fa fa-fw fa-search"></i> Search Product</button>
                                    </span>
                                </div>

                                <div class="input-group input-group-sm inputManual" style="display: none;">
                                    <select class="form-control select2 inputProductManual" style="width: 100%;">
                                        <option value="">-- Find Product --</option>
                                    </select>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-success btn-flat addManual"><i class="fa fa-fw fa-plus-square"></i> Add</button>
                                        <button type="button" class="btn btn-danger btn-flat closeSearch"><i class="fa fa-close"></i> Close</button>
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label style="margin-top: 30px;">Type Out Product</label>
                            </div>
                            <div class="col-md-12">
                                <button class="btn btn-default btntransaksi" data-id="reseller"><i class="fa fa-group"></i> Reseller</button>
                                <button class="btn btn-default btntransaksi" style="margin-left: 10px;" data-id="other_store"><i class="fa fa-fw fa-exchange"></i> Other Store</button>
                            </div>

                            <div class="col-md-12" style="height: 280px;">
                                <label style="margin-top: 30px;">Process Form</label>
                                <div class="col-md-12 re-seller">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label" style="margin-top: 7px;">Reseller</label>

                                        <div class="col-sm-9">
                                            <select style="width: 100%;" class="form-control reseller">
                                                <option value="">-- Select Reseller --</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 store-name">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label" style="margin-top: 7px;">Store Name</label>

                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-fw fa-bank"></i></span>
                                                <input type="text" class="form-control storeName">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <button class="btn btn-success btnPay"><i class="fa fa-fw fa-check-square-o"></i> Process</button>
                            </div>


                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
</section>

<!-- Modal Add New Data -->
<div class="modal fade" id="ModalChooseStorage" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-xs" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Select Storage Product</h4>
            </div>
            <div class="modal-body">
                <div class="box-body">
                    <select class="form-control selectStorage" style="width: 100%;">

                    </select>
                </div>
            </div>
            <div class="modal-footer modalbtn">

            </div>
        </div>
    </div>
</div>

<script>
    var vListProduct = {};


    $("body").addClass("sidebar-collapse");

    $(".inputProductScanner").focus();

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


    $(".reseller").select2({
        ajax: {
            url: './lib/base/select_data.php?t=vw_select_reseller&filter=all',
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



    var typePayment = [];
    var typeProcess = "";

    $(".btntransaksi").on("click", function() {

        let type = $(this).data("id");
        $(".btntransaksi").removeClass("btn-info");
        $(".btntransaksi").addClass("btn-default");
        typeProcess = type;


        $(this).addClass("btn-info");

        if (type == 'reseller') {
            $(".re-seller").css("display", "block");
            $(".tuker-pen").css("display", "block");
            $(".total-kadar").css("display", "block");
            $(".store-name").css("display", "none");
        }

        if (type == 'other_store') {
            $(".re-seller").css("display", "none");
            $(".tuker-pen").css("display", "none");
            $(".total-kadar").css("display", "none");
            $(".store-name").css("display", "block");
        }


        $(".paymentMethod").html(typePayment.join(" & "));

    });


    $(".addManual").on("click", function() {
        var getBarcode = $(".inputProductManual").val();
        var dataForm = {
            "barcode": getBarcode
        };
        if (getBarcode != "") {
            getProduct(dataForm);
        } else {
            popup("error", "Barcode must fill !!");
        }
    });

    $(".inputProductScanner").on("keyup", function() {
        var value = $(this).val();
        if (value.length == 6) {
            $(this).blur();
        }
    });

    $(".inputProductScanner").on("focusout", function() {
        var getBarcode = $(this).val();
        var dataForm = {
            "barcode": getBarcode
        };
        if (getBarcode != "") {
            getProduct(dataForm);
        }

        $(this).val(null);
    });

    $(".btnPay").on("click", function() {

        if (!jQuery.isEmptyObject(vListProduct)) {
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

                        var resellerInfo = {
                            "reseller": $(".reseller").val(),
                            "storeName": $(".storeName").val(),
                        };


                        var dataTransaction = {
                            "resellerInfo": resellerInfo,
                            "vListProduct": vListProduct,
                            "nostruk": "INVR<?php echo $nostruk ?>",
                            "method": typeProcess
                        };


                        $.ajax({
                            method: "POST",
                            url: "./lib/base/commit_po.php",
                            data: dataTransaction,
                            dataType: 'json',
                            success: function(msg) {
                                $("#loading").addClass("hide");

                                var typeTemplate = "";
                                if(typeProcess == "reseller"){
                                    typeTemplate = "nota_reseller";
                                }else {
                                    typeTemplate = "nota_os";
                                }

                                if (msg.status == 'success') {
                                    window.open('./lib/report/generate_pdf.php?template='+typeTemplate+'&struk=INVR<?php echo $nostruk ?>', "popupWindow", "width=900, height=600, scrollbars=yes");

                                    swal(msg.info, {
                                        icon: "success",
                                    }).then((value) => {
                                        HtmlLoad('./lib/base/outproduct.php?f=27', 'Process Out Product');
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
        } else {
            popup("error", "Please create transaction !!");
        }


    });



    function getProduct(dataForm) {

        $.ajax({
            method: "POST",
            url: "./lib/base/getproduct.php",
            data: dataForm,
            dataType: 'json',
            success: function(msg) {


                if (msg.status == "success") {

                    $("#ModalChooseStorage").modal("show");
                    $(".modalbtn").html(`
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="btnConfirm">Confirm</button>
          `);

                    var selectboxStorage = "";
                    $.each(msg.product, function(idx, val) {
                        selectboxStorage += "<option value='" + idx + "'>" + val.storage_name + "</option>"
                    });

                    $(".selectStorage").html(selectboxStorage);

                    $("#btnConfirm").on("click", function() {

                        $("#ModalChooseStorage").modal("hide");
                        var selectStorage = $(".selectStorage").val();

                        var uniqid = Math.floor(Math.random() * 1000000);


                        vListProduct[uniqid] = {
                            "productName": msg.product[selectStorage].product_name,
                            "barcode": msg.product[selectStorage].uniq_barcode,
                            "netto_gram": msg.product[selectStorage].netto_gram,
                            "brutto_gram": msg.product[selectStorage].brutto_gram,
                            "qty": 1,
                            "price": msg.product[selectStorage].sell_price
                        };

                        var template = `<tr>
                        <td><button class="btn btn-danger btn-xs btn-delete-item" data-id="` + uniqid + `"><i class="fa fa-fw fa-trash"></i></button></td>
                        <td>` + msg.product[selectStorage].product_name + ` (` + msg.product[selectStorage].kadar_product + ` karat) <small style="font-weight: 100;margin-top: 2px;" class="label bg-orange">` + msg.product[selectStorage].storage_name + `</small></td>
                        <td>` + msg.product[selectStorage].uniq_barcode + `</td>
                        <td>` + msg.product[selectStorage].netto_gram + `</td>
                        <td>` + msg.product[selectStorage].brutto_gram + `</td>
                        <td><input type="number" class="form-control input-sm qtyInput qty` + uniqid + `" data-id="` + uniqid + `" value="1"></td>
                        <td><input type="number" class="form-control input-sm priceInput price` + uniqid + `" data-id="` + uniqid + `" value="0"></td>
                        <td><span class="totalInput` + uniqid + ` totalPrice">` + format(Number(msg.product[selectStorage].sell_price)) + `</span></td>
                      </tr>`;
                        $(".bodyRawTable").append(template);


                        $(".btn-delete-item").on("click", function() {
                            let id = $(this).data("id");
                            delete vListProduct[id];
                            var cek = $(this).parent("td").parent("tr").remove();

                            var totalAll = 0;
                            $('.totalPrice').each(function() {
                                totalAll += Number($(this).html().replaceAll(",", ""))
                            });
                            $(".totalAllAmount").html(format(totalAll));
                        });


                        $(".qtyInput").on("click", function() {
                            // Select the text field
                            var focusgram = $(this);
                            focusgram.select();
                        });

                        $(".priceInput").on("focusout", function() {
                            // Select the text field
                            var id = $(this).data("id");
                            var qty = $(".qty" + id).val();
                            var price = $(".price" + id).val();

                            vListProduct[id]['qty'] = qty;
                            vListProduct[id]['price'] = price;

                            var total = qty * vListProduct[id]['netto_gram'] * price / 100;
                            $(".totalInput" + id).html(format(total, ".", 3));

                            var totalAll = 0;
                            $('.totalPrice').each(function() {
                                totalAll += Number($(this).html().replaceAll(",", ""))
                            });
                            $(".totalAllAmount").html(format(totalAll, ".", 3));
                        });

                        $(".qtyInput").on("focusout", function() {
                            // Select the text field
                            var id = $(this).data("id");
                            var qty = $(".qty" + id).val();
                            var price = $(".price" + id).val();

                            vListProduct[id]['qty'] = qty;
                            vListProduct[id]['price'] = price;

                            var total = qty * vListProduct[id]['netto_gram'] * price / 100;
                            $(".totalInput" + id).html(format(total, ".", 3));

                            var totalAll = 0;
                            $('.totalPrice').each(function() {
                                totalAll += Number($(this).html().replaceAll(",", ""))
                            });
                            $(".totalAllAmount").html(format(totalAll, ".", 3));
                        });

                        $(".priceInput").on("click", function() {
                            // Select the text field
                            var focusgram = $(this);
                            focusgram.select();
                        });

                        $('.qtyInput').on('keypress', function(e) {
                            if (e.which === 13) {
                                $(this).blur();
                            }
                        });

                        $('.priceInput').on('keypress', function(e) {
                            if (e.which === 13) {
                                $(this).blur();
                            }
                        });

                        var totalAll = 0;
                        $('.totalPrice').each(function() {
                            totalAll += Number($(this).html().replaceAll(",", ""))
                        });
                        $(".totalAllAmount").html( format(totalAll));
                        popup('success', msg.info, '');

                        $(".inputProductScanner").val(null);
                        $(".inputProductScanner").focus();


                    });




                } else {
                    popup('error', msg.info, '');
                }

            },
            error: function(err) {
                console.log(err);
                popup('error', err.responseText, '');
            }
        });
    }
</script>