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

//get status receive
$receive = $adeQ->select("select * from data_receive where id=$sub");
$statusRecive = $receive[0]['status_receive'] == 'received' ? true : false;

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
              <h3 style="font-size:35px;color:green;margin:0">Total : <b class="totalRecive">Rp 0</b></h3>
            </div>
            <?php
            if(!$statusRecive){
            ?>
            <div class="col-md-3">
              <button class="btn btn-block btn-success btn-lg" data-toggle="modal" data-target="#ModalStorage"><i class="fa fa-fw fa-dropbox"></i> Receive</button>
            </div>

            <?php } ?>
          </div>
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
<div class="modal fade" id="ModalStorage" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"> Select Storage for receive product</h4>
      </div>
      <div class="modal-body">
        <div class="box-body">
          <select class="form-control selectstorage">
            <?php
            $storage = $adeQ->select("select * from data_category_storage where coalesce(is_delete, 0) = 0");
            foreach ($storage as $store) {
              echo "
                  <option value='$store[id]'>$store[storage_name]</option>
                ";
            }
            ?>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-lg btn-success btnCommitReceive"><i class="fa fa-fw fa-dropbox"></i> Receive</button>
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

  getRecapReceive();


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
      "url": "./lib/base/load_data_with_date_sub.php?w=<?php echo $qFieldSub[0]['name_field'] . '=' . $sub; ?>&t=<?php echo $formView ?>&f=<?php echo $f ?>",
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
      <?php
      if(!$statusRecive){
      ?>
      {
        text: '<i class="fa fa-plus-circle"></i> Add',
        className: 'btn btn-default',
        action: function(e, dt, node, config) {
          loadForm(null, "add");

        }
      },
      {
        text: '<i class="fa fa-pencil-square-o"></i> Edit',
        className: 'btn btn-default',
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
        className: 'btn btn-danger',
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
        className: 'btn btn-default',
        action: function(e, dt, node, config) {
          loadFormTw(null, "search");
        }
      },
      {
        text: '<i class="fa fa-refresh"></i> Refresh',
        className: 'btn btn-default',
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
          table.draw(false);
          $('#Modal<?php echo $formName ?>').modal('toggle');
          popup('success', msg.msg, '');
        }

        getRecapReceive();
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

  });

  $(".btnCommitReceive").on("click", function() {
    var id_storage = $(".selectstorage").val();

    if (id_storage != "") {
      swal({
          title: "Commit Receive this Inovice ?",
          text: "Once commit Receive, this invoice will sync to stock product in system !",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willtransaction) => {
          if (willtransaction) {

            $("#loading").removeClass("hide");

            var dataPost = {
              id_receive: '<?php echo $sub ?>',
              id_storage: id_storage
            };
            $.ajax({
              method: "POST",
              url: "./lib/base/commitreceive.php",
              data: dataPost,
              dataType: 'json',
              success: function(msg) {
                if (msg.status == 'success') {
                  $("#ModalStorage").modal("hide");
                  popup('success', msg.info, '');
                  setTimeout(function() {
                    $(".back").click();
                  }, 500);
                  
                } else {
                  popup('error', msg.info, '');
                }

                $("#loading").addClass("hide");

              },
              error: function(err) {
                console.log(err);
                popup('error', err.responseText, '');
                $("#loading").addClass("hide");
              }
            });
          }
        });
    } else {
      popup("error", "Please select storage", "");
    }


  });




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

  function getRecapReceive() {
    var dataPost = {
      id_receive: '<?php echo $sub ?>'
    };
    $.ajax({
      method: "POST",
      url: "./lib/base/loadrekapreceive.php",
      data: dataPost,
      dataType: 'json',
      success: function(msg) {
        if (msg.status == 'success') {
          var total = 0;
          if (msg.data[0].total != null) {
            total = msg.data[0].total;
          }

          $(".totalRecive").html('Rp ' + total);
        } else {
          popup('error', msg.info, '');
        }

      },
      error: function(err) {
        popup('error', err.responseText, '');
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