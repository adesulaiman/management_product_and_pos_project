<?php

require "../../config.php";
require "../base/db.php";
require "../base/security_login.php";

$f = $_GET['f'];

$qForm = $adeQ->select($adeQ->prepare(
    "select * from core_forms where idform=%d",
    $f
));

$qField = $adeQ->select($adeQ->prepare(
    "select * from core_fields where id_form=%d and active is true order by id",
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
    $formDesc = $valForm['description'];
    $formCode = $valForm['formcode'];
}

//SHOW SCHEMA VIEW
$qSchemaView = $adeQ->select($adeQ->prepare(
    "select * from information_schema.columns where table_name=%s order by ordinal_position",
    $formView
));

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

                    <div class="row" style="margin:10px 0">
                        <div class="col-md-4" style="padding:0">
                            <b>Pilih OPD</b>
                            <select style="width:100%" class="data-opd">
                                <option value="">-- Select OPD --</option>
                            </select>
                        </div>
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


    $('.filterAdvCheck').click(function() {
        if ($(this).prop("checked") == true) {
            $('.advanceFilter').css('display', 'block');
        } else {
            $('.advanceFilter').css('display', 'none');
        }
    });

    $('.data-opd').select2({
        ajax: {
            url: './lib/base/select_data.php?t=vw_select_opd&filter=all',
            dataType: 'json',
            data: function(params) {
                return {
                    search: params.term
                };
            }
        }
    });

    $('.data-opd').on("change", function() {
        table.draw();
    });




    var table = $("#<?php echo $formName ?>").DataTable({
        "dom": 'Bltip',
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "./lib/base/load_data_with_date.php?t=<?php echo $formView ?>&f=<?php echo $f ?>",
            "data": function(data) {
                var dtQuery = $(".queryFilter").val();
                if (dtQuery === undefined || dtQuery === "") {
                    dtQuery = "opd = '" + $(".data-opd").val() + "'";
                } else {
                    dtQuery = dtQuery + " and opd = '" + $(".data-opd").val() + "'";
                }
                data.query = dtQuery;
            },
            "dataSrc": function(json) {
                setTimeout(() => {
                    $('.id-level').select2({
                        ajax: {
                            url: './lib/base/select_data.php?t=vw_select_level&filter=' + $(".data-opd").val(),
                            dataType: 'json',
                            data: function(params) {
                                return {
                                    search: params.term
                                };
                            }
                        }
                    });

                    $('.id-level').on("change", function() {
                        var level = $(this).val();
                        var id = $(this).attr("data-id");

                        $.ajax({
                            method: "POST",
                            url: "./lib/md/save_setting_level.php",
                            data: "id="+id+"&level="+level,
                            dataType: 'json',
                            success: function(msg) {
                                popup(msg.status, msg.info, '');
                            },
                            error: function(err) {
                                console.log(err);
                                popup('error', err.responseText, '');
                            }
                        });

                    });
                }, 10);

                return json.data;
            }
        },
        "searching": false,
        "scrollX": true,
        order: [[ 1, "asc" ]] ,
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
        buttons: [{
                text: '<i class="fa fa-search"></i> Search',
                action: function(e, dt, node, config) {
                    loadFormTw(null, "search");
                }
            },
            {
                text: '<i class="fa fa-refresh"></i> Refresh',
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
        },{
            "targets": [1],
            "visible": false
        }]
    });





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