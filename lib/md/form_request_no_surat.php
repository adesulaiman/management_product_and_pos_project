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

$getLastNo = $adeQ->select("select no_urut as nosurat from data_agenda_no_surat order by CAST(NumericOnly(no_urut) as int) desc limit 1");
$LastNo = intval($getLastNo[0]['nosurat']) + 1;
$opd_tahun = $_SESSION['opd_code'] . '/'. date("Y");
?>

<input type='hidden' class='queryFilter'/>

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
    <div class="col-md-4">
      <div class="box">
        <!-- /.box-header -->
        <div class="box-body">
          <h4>Request Nomor Surat</h4>
          <hr>
          <form class="formData" action="javascript:void(0)" method="POST">
            <div class="form-group">
              <label for="jenis_dokumen">Jenis Dokumen</label>
              <select class="form-control jenis_dokumen" style='width: 100%;' name="jenis_dokumen">
                <option value="">Pilih Jenis Dokumen</option>
              </select>
            </div>
            <div class="form-group">
              <label>Nomor</label>
              <div class="row">
                <div class="col-md-3">
                  <select class="form-control ref_no_surat" style='width: 100%;' name="ref_no_surat">
                    <option value="">Pilih Kode Surat</option>
                  </select>
                </div>
                <div class="col-md-4">
                  <input type="text" name="no_urut" class="form-control no_urut" id="exampleInputPass" value="<?php echo $LastNo ?>" placeholder="No Surat">
                </div>
                <div class="col-md-5">
                  <input type="text" name="code_opd_tahun" class="form-control code_opd_tahun" readonly id="exampleInputPass" value="<?php echo $opd_tahun ?>" placeholder="opd_tahun">
                </div>
              </div>
            </div>
            <div class="form-group">
              <label>Tanggal</label>
              <input type="text" name="tanggal" class="form-control tanggal datepicker" id="exampleInputPass" placeholder="Tanggal">
            </div>
            <div class="form-group">
              <label>Perihal</label>
              <input type="text" name="perihal" class="form-control perihal" id="exampleInputPass" placeholder="Perihal">
            </div>
            <div class="form-group">
              <label>Tujuan</label>
              <input type="text" name="tujuan" class="form-control tujuan" id="exampleInputPass" placeholder="Tujuan">
            </div>
          </form>
          <button class="btn btn-success btnRequest btn-block"><i class="fa fa-ticket"></i> Request No Surat</button>
        </div>
        <!-- /.box-body -->
      </div>
    </div>

    <div class="col-md-8">
      <div class="box">
        <!-- /.box-header -->
        <div class="box-body">
          <h4>List Agenda Nomor Surat</h4>
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

  $('.ref_no_surat').select2({
    dropdownAutoWidth: true,
    ajax: {
      url: './lib/base/select_data.php?t=vw_select_no_surat&filter=all',
      dataType: 'json',
      data: function(params) {
        return {
          search: params.term
        };
      }
    }
  });

  $('.jenis_dokumen').select2({
    ajax: {
      url: './lib/base/select_data.php?t=vw_select_jenis_dokumen&filter=all',
      dataType: 'json',
      data: function(params) {
        return {
          search: params.term
        };
      }
    }
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
      "url": "./lib/base/load_data_with_session.php?t=<?php echo $formView ?>&f=<?php echo $f ?>",
      "data": function(data) {
        var dtQuery = $(".queryFilter").val();
        data.query = dtQuery;
      }
    },
    "searching": false,
    "scrollX": true,
    // order: [[ 3, "desc" ]] ,
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
    }]
  });


  $('.btnRequest').on('click', function() {
    var dataFrom = new FormData();
    $(this).attr("disabled", true);
    $(this).html("Mohon Tunggu");

    var form_data = $('.formData').serializeArray();
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
    dataFrom.append("formType", "add");

    $.ajax({
      method: "POST",
      url: "./lib/md/save_request_nosurat.php",
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


        $(".btnRequest").attr("disabled", false);
        $(".btnRequest").html("Submit");

        if (msg.status) {
          table.ajax.reload();
          popup('success', msg.msg, '');
        }else{
          popup('error', msg.msg, '');
        }

      },
      error: function(err) {
        $(".formSubmit").attr("disabled", true);
        $(".formSubmit").html("Submit");
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
        ?>

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