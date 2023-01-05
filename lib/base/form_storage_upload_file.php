<?php

require "../../config.php";
require "db.php";
require "security_login.php";

$f = $_GET['f'];

$qForm = $adeQ->select($adeQ->prepare(
    "select * from core_forms where idform=%d", $f
));

$qField = $adeQ->select($adeQ->prepare(
    "select * from core_fields where id_form=%d and active is true order by id", $f
));

$qFieldSelect = $adeQ->select($adeQ->prepare(
    "select * from core_fields where id_form=%d and active is true and type_input in ('select', 'checkbox') order by id", $f
));

foreach($qForm as $valForm)
{
  $formName = $valForm['formname'];
  $formView = $valForm['formview'];
  $formDesc = $valForm['description'];
  $formCode = $valForm['formcode'];
}

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
              <table id="<?php echo $formName ?>" class="table table-bordered table-striped nowrap">
                <thead>
                <tr>
                  <?php
                  foreach($qField as $valField)
                  {
                    echo "<th>".ucfirst(str_replace("_", " ", $valField['name_field']))."</th>";
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
          <form class="formModal<?php echo $formName ?>" action='javascript:void(0);' method='post'>

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




<!-- Modal Add New Data -->
<div class="modal fade" id="ModalUpload<?php echo $formName ?>" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="ModalText<?php echo $formName ?>"></h4>
      </div>
      <div class="modal-body">
        <div class="box-body">
        <div class="row">
            
        <div class="form-row" id="drag-and-drop-zone">
          
          <div class="col-md-3  d-md-block  d-sm-none">
            <label for="file-input">
              Upload Image or File
              <img src="<?php echo $dir?>assets/img/noimage.jpg" style="border: 2px dashed grey;margin: 10px 0px;width:100%"/>
            </label>
            
            <input id="file-input" type="file" />

            <div class="progress progress-sm active">
                <div class="progress-bar progress-bar-success progress-bar-striped progress-upload" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                </div>
              </div>
           
          </div>

        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary formSubmit">Submit</button>
      </div>
    </div>
  </div>
</div>


<script>
    var table = $("#<?php echo $formName ?>").DataTable({
      "dom": 'Bltip',
      "processing": true,
      "serverSide": true,
      "ajax": {
        "url" : "./lib/base/load_data.php?t=<?php echo $formView ?>&f=<?php echo $f?>",
        "data" : function(data){
          var dtQuery = $(".queryFilter").val();
          data.query = dtQuery;
        }
      },
      "searching": false,
      "scrollX": true,
      scrollCollapse: true,
      "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
      "columns": [
      <?php
      foreach($qField as $valField)
      {
        echo "{ data: '$valField[name_field]', width:'150px' },";
      }
      ?>
      ],
      buttons: [
          {
            text: '<i class="fa fa-plus-circle"></i> Add',
            action: function ( e, dt, node, config ) {
              // loadForm(null, "add"); 
              uploadImage('#drag-and-drop-zone', 'lib/base/upload_file_action.php', 15000000, ['jpg', 'img', 'png']);
              $('#ModalUpload<?php echo $formName ?>').modal('show');
            }
          },
          {
            text: '<i class="fa fa-pencil-square-o"></i> Edit',
            action: function ( e, dt, node, config ) {
              var rowData = dt.rows(".selected").data()[0];  
              
              if(rowData == null)
              {
                alert('Mohon pilih data terlebih dahulu');
              }else{
                loadForm(rowData.id, "edit");
              }

              
            }
          },
          {
            text: '<i class="fa fa-trash-o"></i> Delete',
            action: function ( e, dt, node, config ) {
              var rowData = dt.rows(".selected").data()[0];  
              
              if(rowData == null)
              {
                alert('Mohon pilih data terlebih dahulu');
              }else{
                loadFormTw(rowData.id, "delete");
              }
            }
          },
          {
            text: '<i class="fa fa-search"></i> Search',
            action: function ( e, dt, node, config ) {
              loadFormTw(null, "search");
            }
          },
          {
            text: '<i class="fa fa-refresh"></i> Refresh',
            action: function ( e, dt, node, config ) {
              $('.queryFilter').val('');
              table.draw();
            }
          }
        ],
        select: {
            style: 'single'
        },
        "columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false
            }
        ]
    });



   

$('#upload').on('click', function(evt){
    evt.preventDefault();
	var jum_file = $('#jum_file').val();
	if(jum_file == 0){
		$('#proses').modal('show');
		$('.loader').css("display", "none"); // hide loader
		$('.info-upload').html("<span style='color:red;font-size:20px'><strong> Harap Isi Keterangan Permintaan </strong></span>");
	}else{
		$('#drag-and-drop-zone').dmUploader('start');
	}
    
  });



  
$('.formSubmit').on('click', function(){
   var form = $('.formModal<?php echo $formName ?>').serialize();
   
   $.ajax({
      method: "POST",
      url: "./lib/base/save_data.php",
      data: form + '&f=<?php echo $f ?>',
      dataType: 'json',
      success: function( msg ) {
        
        $.each(msg.validate, function (index, value){
             if(value.err == 'validate')
             {
               $('.grp'+value.field).removeClass( "has-error" ).addClass( "has-error" );
               $('.err'+value.field).html(value.msg);
             }else{
               $('.grp'+value.field).removeClass( "has-error" );
               $('.err'+value.field).html(null);
             }
          })

        if(msg.status)
        {
          table.ajax.reload();
          $('#Modal<?php echo $formName ?>').modal('toggle');
          popup('success', msg.msg, '');
        }
      },
      error: function(err){
        console.log(err);
        popup('error', err.responseText, '');
      }
    }); 
})



$('.actFilter').on('click', function(){
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

  for(i=0; i < filter.length; i++)
  {
    if(logic[i] == 'like')
    {
      query.push('UPPER('+ filter[i] + ') ' + logic[i] + " '%" + valFilter[i].toUpperCase() + "%'");
    }else
    {
      query.push(filter[i] + ' ' + logic[i] + " '" + valFilter[i] + "'");
    }
  }
  $('.queryFilter').val(query.join(' and '));

  console.log(query.join(' and '));

  table.draw();
  $('#Modal<?php echo $formName ?>').modal('toggle');

})


function loadForm(id, type)
{
  if(id == null)
  {
    var val = '';
  }else
  {
    var val = "&v="+id;
  }

  $.ajax({
    method: "POST",
    url: "./lib/base/form_modal_act.php?f=<?php echo $f ?>&type="+type+val,
    success: function( msg ) {
       // console.log(JSON.parse(msg));
        var fData = JSON.parse(msg)
        $('#ModalText<?php echo $formName ?>').text(fData.type);
        $('.formModal<?php echo $formName ?>').html(fData.data);

        $('.datepicker').datepicker({
          format: '<?php echo $dateJS?>',
          autoclose: true
        });

        <?php
        foreach ($qFieldSelect as $select ) {
          if($select['case_cade'] != null)
          {
            $qCaseCade = $adeQ->select($adeQ->prepare("select * from core_fields where id_form=%d and id=%d", $f, $select['case_cade']));
            foreach($qCaseCade as $caseCade)
            {
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
          }else{
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

        $('#Modal<?php echo $formName ?>').modal('show');
    }
  }); 
}




function loadFormTw(id, type)
{
  if(id == null)
  {
    var val = '';
  }else
  {
    var val = "&v="+id;
  }

  $.ajax({
    method: "POST",
    url: "./lib/base/form_modal_act.php?f=<?php echo $f ?>&type="+type+val,
    success: function( msg ) {
       // console.log(JSON.parse(msg));
        var fData = JSON.parse(msg)
        $('#ModalText<?php echo $formName ?>').text(fData.type);
        $('.formModal<?php echo $formName ?>').html(fData.data);
        dynamicSearch(fData.data);

        $('.datepicker').datepicker({
          format: '<?php echo $dateJS?>',
          autoclose: true
        });        

        if(type == 'search')
        {
          $('.formSubmit').css('display', 'none');
          $('.actFilter').css('display', 'inline');
        }else
        {
          $('.actFilter').css('display', 'none');
          $('.formSubmit').css('display', 'inline');
        }


        $('#Modal<?php echo $formName ?>').modal('show');
    }
  }); 
}




function dynamicSearch(form)
{
    var next = 1;
    var i = 1;
    var formFilter = $(".formFilter").html();
    $(".add-more").click(function(e){
        e.preventDefault();
        next = next + 1;
        var newIn = formFilter.replace("formRow", "formRow"+next);
        var newInput = $(newIn);
        var removeBtn = "<div class='col-md-2'><button data-id='"+next+"' class='btn btn-danger remove-me' >-</button></div>";
        $(".formFilter").after(newInput);
        $('.formRow'+next).append(removeBtn);
        $('.remove-me').click(function(e){
            e.preventDefault();
            var fieldNum = $(this).data('id');
            var fieldID = ".formRow" + fieldNum;
            $(fieldID).remove();
        });
    });
}

function uploadImage(id, urlPhp, maxSize, extFilter)
{
  $(id).dmUploader({ //
    url: urlPhp,
    maxFileSize: maxSize, 
  auto: true,
  dataType: "html",
  extFilter: extFilter,
  extraData: function() {
    return {
    "jum_file": $('#jum_file').val()
    };
  },
    onDragEnter: function(){
      this.addClass('active');
    },
    onDragLeave: function(){
      this.removeClass('active');
    },
    onInit: function(){
      ui_add_log('Penguin initialized :)', 'info');
    },
    onComplete: function(){
      ui_add_log('All pending tranfers finished');
    },
    onNewFile: function(id, file){
      if (typeof FileReader !== "undefined"){
        var reader = new FileReader();
        var img = this.find('img');
        
        reader.onload = function (e) {
          img.attr('src', e.target.result);
        }
        reader.readAsDataURL(file);
      }
    },
    onBeforeUpload: function(id){
      // about tho start uploading a file
      ui_add_log('Starting the upload of #' + id);
      ui_multi_update_file_status(id, 'uploading', 'Uploading...');
      $(".progress-upload").css("width", "0%");
    },
    onUploadCanceled: function(id) {
      // Happens when a file is directly canceled by the user.
      ui_multi_update_file_status(id, 'warning', 'Canceled by User');
      $(".progress-upload").css("width", "0%");
      ui_multi_update_file_controls(id, true, false);
    },
    onUploadProgress: function(id, percent){
      // Updating file progress
      console.log(id + " - " + percent);
      $(".progress-upload").css("width", percent+"%");
    },
    onUploadSuccess: function(id, data){
      // A file was successfully uploaded
      ui_add_log('Server Response for file #' + id + ': ' + JSON.stringify(data));
      ui_add_log('Upload of file #' + id + ' COMPLETED', 'success');
      ui_multi_update_file_status(id, 'success', 'Upload Complete');
      $(".progress-upload").css("width", "100%");
      console.log(data);
    },
    onUploadError: function(id, xhr, status, message){
      console.log(id + " - " + message);
      ui_multi_update_file_status(id, 'danger', message);
      $(".progress-upload").css("width", "0%");
    },
    onFallbackMode: function(){
      // When the browser doesn't support this plugin :(
      ui_add_log('Plugin cant be used here, running Fallback callback', 'danger');
    },
    onFileSizeError: function(file){
      console.log(file);
      ui_add_log('File \'' + file.name + '\' cannot be added: size excess limit', 'danger');
      $(".progress-upload").css("width", "0%");
    },
    onFileExtError: function(file){
      console.log(file);
      $(".progress-upload").css("width", "0%");
    }
  });
}
  
</script>