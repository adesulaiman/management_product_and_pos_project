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

<style>
  .containerSign {
    width: 90% !important;
  }

  .table td.fit,
  .table th.fit {
    white-space: break-spaces;
    width: 1%;
  }

  @media (min-width: 768px) {
    .containerSign {
      width: 90% !important;
    }
  }
</style>

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
        <button type="button" class="btn btn-default closeBtn" data-dismiss="modal">Close</button>
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
    order: [
      [11, 'desc']
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
          echo "{ data: '$valField[COLUMN_NAME]', width:'150px' },";
        }
      }
      ?>
    ],
    buttons: [{
        text: '<i class="fa fa-pencil-square-o"></i> Sign & Approve',
        className: "btn btn-primary",
        action: function(e, dt, node, config) {
          var rowData = dt.rows(".selected").data()[0];

          if (rowData == null) {
            alert('Please Select Data !!');
          } else {
            loadForm(rowData.id, rowData.dokumen, rowData.status_flag, rowData.nomor);
          }


        }
      },
      {
        text: '<i class="fa fa-download"></i> Download',
        className: "btn btn-warning",
        action: function(e, dt, node, config) {
          var rowData = dt.rows(".selected").data()[0];

          if (rowData == null) {
            alert('Please Select Data !!');
          } else {
            var dokumenDownload = rowData.dokumen.split("<br>");
            for (var i = 0; i < dokumenDownload.length; i++) {
              DownloadFile('assets/pdffiles/' + dokumenDownload[i], dokumenDownload[i]);
            }
          }
        }
      },
      {
        text: '<i class="fa fa-calendar"></i> Revisi Masa Berlaku',
        className: "btn btn-success",
        action: function(e, dt, node, config) {
          var rowData = dt.rows(".selected").data()[0];

          if (rowData == null) {
            alert('Please Select Data !!');
          } else {
            revisiDokumen(rowData.id,rowData.dokumen, rowData.status_flag);
          }
        }
      },
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
    }, {
      "targets": [1],
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


  function loadForm(id, dokumen, status, nomor) {

    var xCode = '';
    if (status == 'Approval' || status == 'TTE') {
      alert("Dokumen sudah di Approve atau TTE !!");
      return false;
    }

    $(".modal-dialog").addClass("containerSign");
    $('#ModalText<?php echo $formName ?>').text("Review Dokumen");
    $('.formSubmit').css('display', 'none');
    $('.modal-title').css('display', 'none');
    $('.formSubmit').html('Sign');
    $('.actFilter').css('display', 'none');
    $('.closeBtn').css('display', 'none');

    var textSignBtn = "";
    var formPasssphare = "";
    if (status == 'Pending Approval') {
      textSignBtn = 'APPROVE';
    } else if (status == 'Pending TTE') {
      textSignBtn = 'SIGN';
      formPasssphare = `<div class="form-group">
                <label for="exampleInpuPass">Passphrase</label>
                <input type="password" class="form-control passphrase" id="exampleInputPass" placeholder="Enter Passpharase">
              </div>`;
    }

    var multiDocument = dokumen.split("<br>");
    var dokumenSelectedPublic = multiDocument[0];
    var ListPdf = "";
    var selected = "<i class='fa fa-fw fa-arrow-right' style='color:red'></i> ";
    for (var i = 0; i < multiDocument.length; i++) {
      if (i == 0) {
        ListPdf += "<tr class='selectDocument' data-dokumen='" + multiDocument[i] + "'><td class='fit'>" + selected + multiDocument[i] + "</td></tr>";
      } else {
        ListPdf += "<tr class='selectDocument' data-dokumen='" + multiDocument[i] + "'><td class='fit'>" + multiDocument[i] + "</td></tr>";
      }
    }


    var container = `
        <div class="col-md-12">	
              <div class="col-md-3" >
                  <h3>Review Dokumen</h3>
                  <hr>
                  ` + formPasssphare + `
                  <div class="form-group">
                    <label>OTP</label>
                    <input type="text" class="form-control otp" placeholder="Enter OTP">
                  </div>
                  <div class="form-group">
                    <button class="btn btn-primary sendOTP">Request OTP</button>
                    <button class="btn btn-success signDok">` + textSignBtn + `</button>
                  </div>

                  <table class="table table-hover table-list-pdf">
                    <thead>
                      <tr>
                        <th>Select View PDF</th>
                      </tr>
                    </thead>
                    <tbody>
                      ` + ListPdf + `
                    </tbody>
                  </table>

              </div>
              <div class="col-md-9">
                  <iframe style="width: 100%;height:600px" src="assets/pdffiles/` + multiDocument[0] + `#toolbar=0" class="framePDF"></iframe>
            </div>
          </div>
        </div>
    `;

    // <iframe src="assets/upload/` + dokumen + `" style="width:100%;height:90vh"/>
    $('.formModal<?php echo $formName ?>').html(container);

    $(".table-list-pdf").delegate(".selectDocument", "click", function() {
      var documentSelected = $(this).data("dokumen");
      ListPdf = "";
      for (var i = 0; i < multiDocument.length; i++) {
        if (documentSelected == multiDocument[i]) {
          ListPdf += "<tr class='selectDocument' data-dokumen='" + multiDocument[i] + "'><td class='fit'>" + selected + multiDocument[i] + "</td></tr>";
        } else {
          ListPdf += "<tr class='selectDocument' data-dokumen='" + multiDocument[i] + "'><td class='fit'>" + multiDocument[i] + "</td></tr>";
        }
      }
      $(".table-list-pdf tbody").html(ListPdf);
      $(".framePDF").attr("src", 'assets/pdffiles/'+documentSelected);

    });


    $('#Modal<?php echo $formName ?>').modal('show');

    $(".signDok").on("click", function() {

      $("#loading").removeClass("hide");
      var dataPost = "";
      var otp = $(".otp").val();

      if (status == 'Pending Approval') {
        dataPost = "xCode=" + xCode + "&id=" + id + "&dokumen=" + dokumen + "&otp=" + otp + "&status=" + status + "&nomor=" + nomor;
      } else if (status == 'Pending TTE') {
        var pass = $(".passphrase").val();
        dataPost = "xCode=" + xCode + "&id=" + id + "&dokumen=" + dokumen + "&passphrase=" + pass + "&otp=" + otp + "&status=" + status + "&nomor=" + nomor;
      }


      $.ajax({
        method: "POST",
        url: "./signDokumen.php",
        data: dataPost,
        dataType: 'json',
        success: function(msg) {
          // console.log(msg);
          $("#loading").addClass("hide");

          if (msg.status == 'success') {
            table.ajax.reload();
            $('#Modal<?php echo $formName ?>').modal('toggle');
            popup('success', msg.msg, '');
          } else {
            popup('error', msg.msg, '');
          }
          $('.formSubmit').html('Submit');

        },
        error: function(err) {
          $('.formSubmit').html('Submit');
          $("#loading").addClass("hide");

          console.log(err);
          popup('error', err.responseText, '');
        }
      });
    });

    $(".sendOTP").on("click", function() {
      $("#loading").removeClass("hide");

      $.ajax({
        url: "./lib/base/send_otp.php",
        method: "POST",
        data: "method=session",
        dataType: "json",
        success: function(msg) {
          $("#loading").addClass("hide");

          popup(msg.status, msg.text, '');
          if (msg.status == 'success') {
            xCode = msg.uniqid;
          }

        },
        error: function(err) {
          popup('error', 'Error System', '');
          console.log(err);
        }
      })
    });
  }






  function revisiDokumen(id, dokumen, status) {

    if (status == 'Pending TTE' || status == 'TTE') {

      $(".modal-dialog").addClass("containerSign");
      $('#ModalText<?php echo $formName ?>').text("Revisi Masa Berlaku Dokumen");
      $('.formSubmit').css('display', 'none');
      $('.modal-title').css('display', 'none');
      $('.formSubmit').html('Revisi');
      $('.actFilter').css('display', 'none');
      $('.closeBtn').css('display', 'none');


      var container = `
        <div class="col-md-12">	
              <div class="col-md-4" >
                  <h3>Revisi Masa Berlaku Dokumen</h3>
                  <hr>
                  <div class="form-group">
                    <label>Masa Berlaku Dokumen</label>
                    <input type="number" class="form-control umur" placeholder="Isi masa berlaku dokumen">
                    <small style="color:red">Kosongkan jika tidak memiliki masa berlaku<br>0 jika dokumen sudah tidak berlaku</small>
                  </div>
                  <div class="form-group">

                    <button class="btn btn-success revisiBtn">Revisi</button>
                    <button class="btn btn-danger" data-dismiss="modal">Cancel</button>
                  </div>

              </div>
              <div class="col-md-8">
                  <iframe style="width: 100%;height:600px" src="assets/pdffiles/` + dokumen + `#toolbar=0" class="framePDF"></iframe>
            </div>
          </div>
        </div>
      `;

      $('.formModal<?php echo $formName ?>').html(container);


      $('#Modal<?php echo $formName ?>').modal('show');

      $(".revisiBtn").on("click", function() {
        $("#loading").removeClass("hide");
        var umur = $(".umur").val();

        var dataPost = "umur="+umur+"&id="+id;
        $.ajax({
          method: "POST",
          url: "./lib/md/updateUmurDokumen.php",
          data: dataPost,
          dataType: 'json',
          success: function(msg) {
            // console.log(msg);
            $("#loading").addClass("hide");

            if (msg.status == 'success') {
              table.ajax.reload();
              $('#Modal<?php echo $formName ?>').modal('toggle');
              popup('success', msg.msg, '');
            } else {
              popup('error', msg.msg, '');
            }
            $('.formSubmit').html('Submit');

          },
          error: function(err) {
            $('.formSubmit').html('Submit');
            $("#loading").addClass("hide");

            console.log(err);
            popup('error', err.responseText, '');
          }
        });
      });

    } else {
      alert("Dokumen tidak berhak di revisi masa berlakunya !!");
      return false;
    }



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
        var fData = JSON.parse(msg);
        $('.formSubmit').html('Submit');
        $(".modal-dialog").removeClass("containerSign");
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