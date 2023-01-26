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
  "select * from information_schema.columns where table_name=%s and table_schema=%s order by ordinal_position",
  $formView,
  $dbName
));

?>
<input type='hidden' class='queryFilter' />



<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Filter Files</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="form-group">

            <label style="margin-right: 20px;">
              <input class="flat-red" type="radio" name="filtering" value="with_column" checked /> Filter with attribute columns
            </label>
            <label>
              <input class="flat-red" type="radio" name="filtering" value="pdf_content" /> Filter Pdf by content
            </label>

          </div>
          <div class="form_with_column">
            <div class="col-md-4">
              <div class="form-group">
                <label for="exampleInputEmail1">File Name</label>
                <input type="text" class="form-control" id="id_file_name" name="id_file_name" placeholder="Enter File Name">
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">Tags</label>
                <select type="text" class="form-control" id="id_tags" name="id_tags" multiple>
                  <option>Select tags</option>
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="exampleInputEmail1">Description</label>
                <input type="text" class="form-control" id="id_description" name="id_description" placeholder="Enter Description">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="exampleInputEmail1">Category</label>
                <select type="text" class="form-control" id="id_category" name="id_category">
                  <option value="">Select Category</option>
                </select>
              </div>
            </div>
            <div class="col-md-12">
              <button class="btn btn-success filter_search"><i class="fa fa-fw fa-search"></i> Search</button>
              <button class="btn btn-primary filter_refresh"><i class="fa fa-refresh"></i> Refresh</button>
            </div>
          </div>

          <div class="form_pdf_content" style="display: none;">
            <div class="col-md-12">
              <div class="form-group">
                <label for="exampleInputEmail1">Content PDF</label>
                <input type="text" class="form-control" id="id_file_content_pdf" placeholder="Enter content pdf">
              </div>
            </div>
            <div class="col-md-12">
              <button class="btn btn-success filter_search_content"><i class="fa fa-fw fa-search"></i> Search Content</button>
              <button class="btn btn-primary filter_refresh"><i class="fa fa-refresh"></i> Refresh</button>
            </div>
          </div>
        </div>
        <!-- /.box-body -->
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <!-- /.box-header -->
        <div class="box-body">
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
  $('[name=filtering]').on('ifChecked', function(event) {
    if ($(this).val() == 'with_column') {
      $(".form_with_column").css("display", 'block');
      $(".form_pdf_content").css("display", 'none');
    } else {
      $(".form_pdf_content").css("display", 'block');
      $(".form_with_column").css("display", 'none');
    }
  });

  $('.datepicker').datepicker({
    format: '<?php echo $dateJS ?>',
    autoclose: true
  });

  $('input[type="radio"]').iCheck({
    checkboxClass: 'icheckbox_flat-green',
    radioClass: 'iradio_flat-green'
  });

  $('#id_tags').select2({
    ajax: {
      url: './lib/base/select_data.php?t=vw_select_tags&filter=all',
      dataType: 'json',
      data: function(params) {
        return {
          search: params.term
        };
      }
    }
  });


  $('#id_category').select2({
    ajax: {
      url: './lib/base/select_data.php?t=vw_select_category&filter=all',
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
      "url": "./lib/base/load_data_files.php?t=<?php echo $formView ?>&f=<?php echo $f ?>",
      "data": function(data) {
        var dtQuery = $(".queryFilter").val();
        var typeFilter = $('[name=filtering]:checked').val();
        data.typefilter = typeFilter;
        data.query = dtQuery;
      }
    },
    "searching": false,
    "scrollX": true,
    order: [
      [0, "desc"]
    ],
    scrollCollapse: true,
    "lengthMenu": [
      [10, 25, 50, -1],
      [10, 25, 50, "All"]
    ],
    "columns": [
      <?php
      foreach ($qSchemaView as $valField) {
        if (substr($valField['COLUMN_NAME'], 0, 3) != 'id_') {
          echo "{ data: '$valField[COLUMN_NAME]', width:'1150px' },";
        }
      }
      ?>
    ],
    buttons: [{
      text: '<i class="fa fa-download"></i> Download',
      className: "btn btn-default",
      action: function(e, dt, node, config) {
        var rowData = dt.rows(".selected").data()[0];

        if (rowData == null) {
          alert('Please Select Data !!');
        } else {
          var dokumenDownload = rowData.id_file_name;
          DownloadFile('assets/upload/' + dokumenDownload, dokumenDownload);
        }
      }
    }, ],
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
    $(this).attr("disabled", true);
    $(this).html("Mohon Tunggu");

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


        $(".formSubmit").attr("disabled", false);
        $(".formSubmit").html("Submit");

        if (msg.status) {
          table.ajax.reload();
          $('#Modal<?php echo $formName ?>').modal('toggle');
          popup('success', msg.msg, '');
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



  $('.filter_search').on('click', function() {
    $("#loading").removeClass("hide");
    var query = new Array();
    var queryTags = new Array();
    var id_file_name = $("#id_file_name").val();
    var id_description = $("#id_description").val();
    var id_category = $("#id_category").val();
    var id_tags = $("#id_tags").val();

    if (id_file_name != "") {
      query.push("UPPER(id_file_name) like '%" + id_file_name.toUpperCase() + "%'");
    }

    if (id_description != "") {
      query.push("UPPER(id_description) like '%" + id_description.toUpperCase() + "%'");
    }

    if (id_category != "") {
      query.push("UPPER(id_category) like '%" + id_category.toUpperCase() + "%'");
    }

    if (id_tags != null) {
      for (var i = 0; i < id_tags.length; i++) {
        queryTags.push("UPPER(id_tags) like '%" + id_tags[i].toUpperCase() + "%'");
      }
      query.push("(" + queryTags.join(" or ") + ")");
    }

    $('.queryFilter').val(query.join(' and '));
    table.draw();

    $("#loading").addClass("hide");


  })

  $('.filter_refresh').on('click', function() {

    $("#loading").removeClass("hide");

    $('.queryFilter').val('');
    table.draw();
    $("#loading").addClass("hide");

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
            if ($select['type_input'] == 'checkbox') {
              echo "
              $('.$select[name_field]').select2({
                tags: true,
                tokenSeparators: ['|', '	'],
                ajax: {
                  url: '$select[link_type_input]',
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