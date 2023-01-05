<?php

require "../../config.php";
require "db.php";
require "security_login.php";

$formName = "Unggah dan Berbagi File";
$formDesc = "Unggah dan Berbagi File";

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
                

                <div class="col-md-5">
                    <div class="form-group">
                      <h5>Nama Dokumen : </h5>
                      <input type="text" class="form-control" id="nameDokumen" placeholder="Masukan Nama Dokumen">
                    </div>

                    <div class="form-group">
                      <h5>Deskripsi : </h5>
                      <textarea type="text" class="form-control" id="deskripsi" />
                    </div>

                    <div class="form-group">
                      <h5>Kirim Ke : </h5>
                      <?php 
                        $qGroup = $adeQ->select("select * from select_group_rules");

                        foreach($qGroup as $group){
                          echo "<label><input value='$group[id]' type='checkbox' class='flat-red rolemenu'/> $group[text]</label><br>";
                        }
                      ?>
                        
                    </div>
                </div>

                <div class="col-md-5">
                  <div class="row" id="drag-and-drop-zone" style="margin-bottom:10px">
            
                      <div class="col-md-5  d-md-block  d-sm-none">
                        <label for="file-input">
                          <h5>Upload File : </h5>
                          <img src="<?php echo $dir?>assets/img/noimage.jpg" style="border: 2px dashed grey;margin: 10px 0px;width:100px"/>
                        </label>
                        
                        <input id="file-input" type="file" />
                        <div class="progress progress-sm active" style="margin:0;width:60%">
                            <div class="progress-bar progress-bar-success progress-bar-striped progress-upload" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                        </div>
                        <span class="nameFile" style="font-weight:bold; font-size:12px"></span>
                      
                      </div>
                    </div>
                </div>
				
				<div class="row"></div>
				
				<div class="form-group">
					<button id="upload" class="btn btn-success">Upload File</button>
				</div>
				
              

                </div>
          
            <!-- /.box-body -->
          </div>
        </div>
      </div>
    </section>  


<script>   

uploadImage('#drag-and-drop-zone', 'lib/base/upload_file_action.php', 15000000, ['jpg', 'jpeg', 'img', 'png', 'pdf', 'xlsx', 'xls', 'docx', 'doc']);

 
$('#upload').on('click', function(evt){
  evt.preventDefault();
  
  var group = [];
  var namaFile = $("#nameDokumen").val();
  var deskripsi = $("#deskripsi").val();
  var fileUpload = $("#file-input").val();
  console.log(fileUpload);
  jQuery(".rolemenu:checked").each(function(){
    group.push($(this).val());
  });

  if(group.length > 0 && namaFile != null ){
      // console.log($('#drag-and-drop-zone').data('dmUploader'));

      //UPLOAD LATEST SELECT FILE WITH UPDATE QUEUE
      var lastUpload = $('#drag-and-drop-zone').data('dmUploader').queue;
      $('#drag-and-drop-zone').data('dmUploader').queue = [lastUpload[lastUpload.length - 1]];
      $('#drag-and-drop-zone').dmUploader('start');

  }else{
      popup('error', 'Mohon untuk melengkapi datanya !', ''); 
  }

  
  
});


$('.rolemenu').iCheck({
  checkboxClass: 'icheckbox_flat-green',
  radioClass: 'iradio_flat-green'
});



function uploadImage(id, urlPhp, maxSize, extFilter)
{
  $(id).dmUploader({ //
    url: urlPhp,
    maxFileSize: maxSize, 
    auto: false,
    dataType: "json",
    multiple: false,
    extFilter: extFilter,
    extraData: function() {
      var group = [];
      var namaFile = $("#nameDokumen").val();
      var deskripsi = $("#deskripsi").val();
      jQuery(".rolemenu:checked").each(function(){
        group.push($(this).val());
      });
      
      return {
      "namaFile": namaFile,
      "status": "new",
      "deskripsi" : deskripsi,
      "group" : group
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
      console.log(id, file);
      if (typeof FileReader !== "undefined"){
        var reader = new FileReader();
        var img = this.find('img');
        var name = file.name.split(".");
        // $("#nameDokumen").val(file.name);
        var dataExt = ['jpg', 'jpeg', 'img', 'png'];
        reader.onload = function (e) {
          if(dataExt.includes(name[name.length - 1]) ){
            img.attr('src', e.target.result);
          }else{
            img.attr('src', "<?php echo $dir?>assets/img/file.png");
          }
          
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
      

      popup(data.status, data.massage, ''); 
      $("#nameDokumen").val(null);
      $("#deskripsi").val(null);
      $('.rolemenu').iCheck('uncheck');
      var img = this.find('img');
      img.attr('src', "<?php echo $dir?>assets/img/noimage.jpg");
      $(".progress-upload").css("width", "0%");
    },
    onUploadError: function(id, xhr, status, message){
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