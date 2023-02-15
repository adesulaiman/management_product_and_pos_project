<?php

require "../../config.php";
require "../base/db.php";
require "../base/security_login.php";

$f = $_GET['f'];
$script = $_GET['s'];
$fParent = $_GET['f_parent'];
$sub = $_GET['sub'];
$subdesc = $_GET['sub_desc'];

$formDesc = $subdesc;

$qForm = $adeQ->select($adeQ->prepare(
    "select * from core_forms where idform=%d",
    $f
));

$qField = $adeQ->select($adeQ->prepare(
    "select * from core_fields where id_form=%d and active is true order by id",
    $f
));

$qFieldSub = $adeQ->select($adeQ->prepare(
    "select * from core_fields where id_form=%d and active is true and type_field='sub' order by id",
    $f
));


$qFieldSelect = $adeQ->select($adeQ->prepare(
    "select * from core_fields where id_form=%d and active is true and type_input in ('select', 'checkbox') order by id",
    $f
));

$qFieldimage = $adeQ->select($adeQ->prepare(
    "select * from core_fields where id_form=%d and active is true and type_input in ('image', 'file') order by id",
    $f
));

foreach ($qForm as $valForm) {
    $formName = $valForm['formname'];
    $formView = $valForm['formview'];
    // $formDesc = $valForm['description'];
    $formCode = $valForm['formcode'];
}

//SHOW SCHEMA VIEW
$qSchemaView = $adeQ->select($adeQ->prepare(
    "select * from information_schema.columns where table_name=%s order by ordinal_position",
    $formView
));

$getRs = $adeQ->select($adeQ->prepare("select r.*, s.nama from data_product_out_resaller r inner join data_resaller s on r.id_resaller = s.id where r.invoice = %s", $sub));
$getDetailrs = $adeQ->select($adeQ->prepare("select * from data_product_out_resaller_detail where invoice = %s", $sub));
$tableDetail = "";
$total = 0;
$paidoff = $getRs[0]['status_payment'];
foreach ($getDetailrs as $rs) {
    $tableDetail .= "
        <tr>
            <td>$rs[barcode]</td>
            <td>$rs[product_name]</td>
            <td>" . number_format($rs["pen"]) . "%</td>
            <td>$rs[qty_out]</td>
            <td>" . number_format($rs["qty_out"] * $rs["netto_gram_out"], 2) . "</td>
            <td>$rs[emas_murni]</td>
        </tr>
    ";
    $total += $rs["emas_murni"];
}

?>


<input type='hidden' class='queryFilter' />

<section class="content-header">
    <h1>
        <button onclick="HtmlLoad('./lib/base/<?php echo $script ?>?f=<?php echo $fParent ?>','');" class='btn btn-danger back'><i class="fa fa-angle-left"></i> Back</button> <?php echo $formDesc ?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Menus</a></li>
    </ol>
</section>



<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-9">
                            <h4>Rate Gold Total : <b><?php echo number_format($total, 3) ?> Gr</b> <button style="margin-left:10px" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#ModalDetails">See Detail</button></h4>
                            <h4>Reseller Name : <b><?php echo $getRs[0]['nama'] ?></b></h4>
                        </div>
                        <?php if ($paidoff == 'installment') {?>
                        <div class="col-md-3">
                            <button class="btn btn-lg btn-success btn-block btn-commit"><i class="fa fa-fw fa-check-square-o"></i> Paid Off</button>
                        </div>
                        <?php } ?>
                    </div>
                    <hr>
                    <table id="<?php echo $formName ?>" class="stripe row-border order-column table table-bordered table-striped nowrap">
                        <thead>
                            <tr>
                                <?php
                                foreach ($qSchemaView as $valField) {
                                    if (substr($valField['COLUMN_NAME'], 0, 3) != 'id_') {
                                        echo "<th>" . ucfirst(str_replace("_", " ", $valField['COLUMN_NAME'])) . "</th>";
                                    }
                                }
                                ?>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
</section>


<!-- Modal Add New Data -->
<div class="modal fade" id="ModalDetails" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Details Product Out</h4>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Barcode</th>
                            <th>Product Name</th>
                            <th>Pen</th>
                            <th>Qty</th>
                            <th>Total Netto Gram</th>
                            <th>Emas Murni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php echo $tableDetail; ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal Add New Data -->
<div class="modal fade" id="Modal<?php echo $formName ?>" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="ModalText<?php echo $formName ?>"></h4>
            </div>
            <div class="modal-body">
                <div class="box-body">
                    <form class="formModal<?php echo $formName ?>" action='javascript:void(0);' enctype="multipart/form-data" method='post'>

                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary formSubmit">Submit</button>
                <button type="button" class="btn btn-primary actFilter">Filter</button>
            </div>
        </div>
    </div>
</div>


<script>
    $('.datepicker').datepicker({
        format: '<?php echo $dateJS ?>',
        autoclose: true
    });

    $(".btn-commit").on("click", function() {
        swal({
                title: "Commit Installment ?",
                text: "Once commit installment, you will not be able to back this installment !",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willtransaction) => {
                if (willtransaction) {
                    $("#loading").removeClass("hide");

                    var formData = {
                        f: 28,
                        formType: "commit",
                        invoice: '<?php echo $sub ?>'
                    };

                    $.ajax({
                        method: "POST",
                        url: "./lib/base/save_data_with_date_rs.php",
                        data: formData,
                        dataType: 'json',
                        success: function(msg) {
                            if (msg.status == 'success') {
                                popup('success', msg.info, '');
                                $(".back").click();
                            } else {
                                popup('error', msg.info, '');
                            }

                            $("#loading").addClass("hide");
                        },
                        error: function(err) {
                            $("#loading").addClass("hide");
                            popup('error', err.responseText, '');
                        }
                    });
                }

            });

    });


    $('.filterAdvCheck').click(function() {
        if ($(this).prop("checked") == true) {
            $('.advanceFilter').css('display', 'block');
        } else {
            $('.advanceFilter').css('display', 'none');
        }
    });


    var table = $("#<?php echo $formName ?>").DataTable({
        "dom": 'Bltip',
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "./lib/base/load_data_with_date_sub_is.php?w=<?php echo $qFieldSub[0]['name_field'] . "='" . $sub; ?>'&t=<?php echo $formView ?>&f=<?php echo $f ?>",
            "data": function(data) {
                var dtQuery = $(".queryFilter").val();
                data.query = dtQuery;
            }
        },
        "searching": false,
        "scrollX": true,
        scrollCollapse: true,
        "lengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ],
        "columns": [
            <?php
            foreach ($qSchemaView as $valField) {
                if (substr($valField['COLUMN_NAME'], 0, 3) != 'id_') {
                    echo "{ data: '$valField[COLUMN_NAME]', width:'150px' },";
                }
            }
            ?>
        ],
        buttons: [
            <?php if ($paidoff == 'installment') {?>
                        
            {
                text: '<i class="fa fa-plus-circle"></i> Pay',
                className: "btn btn-success",
                action: function(e, dt, node, config) {
                    loadForm(null, "add");

                }
            },
            {
                text: '<i class="fa fa-pencil-square-o"></i> Edit',
                className: "btn btn-default",
                action: function(e, dt, node, config) {
                    var rowData = dt.rows(".selected").data()[0];

                    if (rowData == null) {
                        alert('Please Select Data !!');
                    } else {
                        loadForm(rowData.id, "edit");
                    }


                }
            },
            {
                text: '<i class="fa fa-trash-o"></i> Delete',
                className: "btn btn-danger",
                action: function(e, dt, node, config) {
                    var rowData = dt.rows(".selected").data()[0];

                    if (rowData == null) {
                        alert('Please Select Data !!');
                    } else {
                        loadFormTw(rowData.id, "delete");
                    }
                }
            },
            <?php } ?>
            {
                text: '<i class="fa fa-search"></i> Search',
                className: "btn btn-default",
                action: function(e, dt, node, config) {
                    loadFormTw(null, "search");
                }
            },
            {
                text: '<i class="fa fa-refresh"></i> Refresh',
                className: "btn btn-default",
                action: function(e, dt, node, config) {
                    $('.queryFilter').val('');
                    table.draw();
                }
            }
        ],
        select: {
            style: 'single'
        },
        "columnDefs": [{
            "targets": [0],
            "visible": false
        }]
    });


    $('.formSubmit').on('click', function() {
        var dataFrom = new FormData();

        var form_data = $('.formModal<?php echo $formName ?>').serializeArray();
        $.each(form_data, function(key, input) {
            ;
            dataFrom.append(input.name, input.value);
        });

        //File data
        var file_data = $('input[type="file"]');
        for (var i = 0; i < file_data.length; i++) {
            dataFrom.append(file_data[i].name, file_data[i].files[0]);
        }

        dataFrom.append("f", "<?php echo $f ?>");

        dataFrom.append("<?php echo $qFieldSub[0]['name_field']; ?>", "<?php echo $sub ?>");

        $.ajax({
            method: "POST",
            url: "./lib/base/save_data_with_date.php",
            data: dataFrom,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(msg) {
                console.log(msg);
                $.each(msg.validate, function(index, value) {
                    if (value.err == 'validate') {
                        $('.grp' + value.field).removeClass("has-error").addClass("has-error");
                        $('.err' + value.field).html(value.msg);
                    } else {
                        $('.grp' + value.field).removeClass("has-error");
                        $('.err' + value.field).html(null);
                    }
                })

                if (msg.status) {
                    table.ajax.reload();
                    $('#Modal<?php echo $formName ?>').modal('toggle');
                    popup('success', msg.msg, '');
                }
            },
            error: function(err) {
                console.log(err);
                popup('error', err.responseText, '');
            }
        });
    })



    $('.actFilter2').on('click', function() {

        var query = [];
        var notFil = ['0', ''];
        <?php
        $getValFil = $adeQ->select($adeQ->prepare("select * from core_filter where idform=%s", $f));
        foreach ($getValFil as $val) {
            if ($val['logic'] == 'like') {
                echo "var value = \"'%\" + $('.fil$val[name_field]').val() + \"%'\";";
                echo "
      if(!notFil.includes($('.fil$val[name_field]').val()))
      {
        query.push('lower($val[name_field]) $val[logic] lower(' + value + ')');
      }";
            } else {
                echo "var value = \"'\" + $('.fil$val[name_field]').val() + \"'\";";
                echo "
      if(!notFil.includes($('.fil$val[name_field]').val()))
      {
        query.push('$val[name_field] $val[logic] ' + value);
      }";
            }
        }

        ?>

        $('.queryFilter').val(query.join(" and "));

        table.draw();

    })




    $('.actFilter').on('click', function() {
        var filter = new Array();
        var logic = new Array();
        var valFilter = new Array();
        var query = new Array();

        $('.filter').each(function() {
            filter.push($(this).val());
        });
        $('.logic').each(function() {
            logic.push($(this).val());
        });
        $('.valueFilter').each(function() {
            valFilter.push($(this).val());
        });

        for (i = 0; i < filter.length; i++) {
            if (logic[i] == 'like') {
                query.push('UPPER(' + filter[i] + ') ' + logic[i] + " '%" + valFilter[i].toUpperCase() + "%'");
            } else {
                query.push(filter[i] + ' ' + logic[i] + " '" + valFilter[i] + "'");
            }
        }
        $('.queryFilter').val(query.join(' and '));



        table.draw();
        $('#Modal<?php echo $formName ?>').modal('toggle');

    })

    $('.resetFilter').on('click', function() {

        $('.queryFilter').val('');
        table.draw();

    })


    function loadForm(id, type) {
        if (id == null) {
            var val = '';
        } else {
            var val = "&v=" + id;
        }


        $.ajax({
            method: "POST",
            url: "./lib/base/form_modal_act_with_date.php?f=<?php echo $f ?>&type=" + type + val,
            success: function(msg) {

                var fData = JSON.parse(msg)
                $('#ModalText<?php echo $formName ?>').text(fData.type);
                $('.formModal<?php echo $formName ?>').html(fData.data);

                $('.datepicker').datepicker({
                    format: '<?php echo $dateJS ?>',
                    autoclose: true
                });

                <?php
                foreach ($qFieldSelect as $select) {
                    if ($select['case_cade'] != null) {
                        $qCaseCade = $adeQ->select($adeQ->prepare("select * from core_fields where id_form=%d and id=%d", $f, $select['case_cade']));
                        foreach ($qCaseCade as $caseCade) {
                            echo "
                var id = $('.$caseCade[name_field]').val();
                if(id.length == 0)
                {
                  $('.$select[name_field]').prop('disabled', true);
                  $('.$caseCade[name_field]').on('change', function(){
                      $('.$select[name_field]').prop('disabled', false);
                      var id = $(this).val();
                      $('.$select[name_field]').val(null).trigger('change');
                      $('.$select[name_field]').select2({
                        ajax: {
                          url: '$select[link_type_input]&filter=' + id,
                          dataType: 'json',
                          data: function (params) {
                            return {search: params.term};
                          }
                        }
                    });
                 });
                }else{
                  $('.$select[name_field]').prop('disabled', false);
                  $('.$select[name_field]').select2({
                        ajax: {
                          url: '$select[link_type_input]&filter=' + id,
                          dataType: 'json',
                          data: function (params) {
                            return {search: params.term};
                          }
                        }
                    });
                    $('.$caseCade[name_field]').on('change', function(){
                      $('.$select[name_field]').prop('disabled', false);
                      var id = $(this).val();
                      $('.$select[name_field]').val(null).trigger('change');
                      $('.$select[name_field]').select2({
                        ajax: {
                          url: '$select[link_type_input]&filter=' + id,
                          dataType: 'json',
                          data: function (params) {
                            return {search: params.term};
                          }
                        }
                    });
                 });
                }
               
              ";
                        }
                    } else {
                        if (strpos($select['link_type_input'], 'sub') !== false) {
                            $linkwithsub = str_replace("sub=", "sub=" . $sub, $select['link_type_input']);
                            echo "
              $('.$select[name_field]').select2({
                ajax: {
                  url: '$linkwithsub',
                  dataType: 'json',
                  data: function (params) {
                    return {search: params.term};
                  }
                }
            });
            ";
                        } else {
                            echo "
              $('.$select[name_field]').select2({
                ajax: {
                  url: '$select[link_type_input]',
                  dataType: 'json',
                  data: function (params) {
                    return {search: params.term};
                  }
                }
            });
            ";
                        }
                    }
                }
                ?>

                $("[name=method]").on("change", function(){
                    var method = $(this).val();
                    if(method == "Emas Murni"){
                        $(".nominal").attr("placeholder", "Gold Rate");
                        $("[for=nominal]").html("Gold Rate");
                    }else{
                        $(".nominal").attr("placeholder", "Nominal Transfer");
                        $("[for=nominal]").html("Nominal");
                    }
                });

                $('.actFilter').css('display', 'none');
                $('.formSubmit').css('display', 'inline');

                <?php
                foreach ($qFieldimage as $img) {
                    echo 'imageInit($(".' . $img['name_field'] . '-img"), $(".' . $img['name_field'] . '-input"));';
                }
                ?>



                $("[data-mask]").inputmask();


                $('#Modal<?php echo $formName ?>').modal('show');
            },
            error: function(err) {
                console.log(err);
            }
        });
    }




    function loadFormTw(id, type) {
        if (id == null) {
            var val = '';
        } else {
            var val = "&v=" + id;
        }

        $.ajax({
            method: "POST",
            url: "./lib/base/form_modal_act_with_date.php?f=<?php echo $f ?>&type=" + type + val,
            success: function(msg) {
                // console.log(JSON.parse(msg));
                var fData = JSON.parse(msg)
                $('#ModalText<?php echo $formName ?>').text(fData.type);
                $('.formModal<?php echo $formName ?>').html(fData.data);
                dynamicSearch(fData.data);

                $('.datepicker').datepicker({
                    format: '<?php echo $dateJS ?>',
                    autoclose: true
                });

                if (type == 'search') {
                    $('.formSubmit').css('display', 'none');
                    $('.actFilter').css('display', 'inline');
                } else {
                    $('.actFilter').css('display', 'none');
                    $('.formSubmit').css('display', 'inline');
                }


                $('#Modal<?php echo $formName ?>').modal('show');
            }
        });
    }




    function dynamicSearch(form) {
        var next = 1;
        var i = 1;
        var formFilter = $(".formFilter").html();
        $(".add-more").click(function(e) {
            e.preventDefault();
            next = next + 1;
            var newIn = formFilter.replace("formRow", "formRow" + next);
            var newInput = $(newIn);
            var removeBtn = "<div class='col-md-2'><button data-id='" + next + "' class='btn btn-danger remove-me' >-</button></div>";
            $(".formFilter").after(newInput);
            $('.formRow' + next).append(removeBtn);
            $('.remove-me').click(function(e) {
                e.preventDefault();
                var fieldNum = $(this).data('id');
                var fieldID = ".formRow" + fieldNum;
                $(fieldID).remove();
            });
        });
    }
</script>