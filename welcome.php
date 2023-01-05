<?php
require "config.php";
require "lib/base/db.php";
require "lib/base/security_login_global.php";

// $getData = $adeQ->select("select 
// count(case when status_flag in ('Pending Approval', 'Pending TTE') then 1 else null end) pending,
// count(case when status_flag in ('Approval', 'TTE') then 1 else null end) done
// from vw_data_dokumen_masuk
// where id_user='$_SESSION[userid]'
// ");


// $getDataSurat = $adeQ->select("select *, 
// concat(ref_no_surat, '/', no_urut, '/', code_opd_tahun) as nomor,
// date_format(tanggal,'%d %M %Y') tanggal_format
// from data_agenda_no_surat where status is null and created_by='$_SESSION[userid]'");


// $jenis_dokumen = null;
// $nomor = null;
// $perihal = null;
// $tanggal = null;
// $tujuan = null;

// foreach ($getDataSurat as $data) {
//   $jenis_dokumen = $data['jenis_dokumen'];
//   $nomor = $data['nomor'];
//   $perihal = $data['perihal'];
//   $tanggal = $data['tanggal_format'];
//   $tujuan = $data['tujuan'];
// }

?>

<style>
  .container {

    width: 700px !important;

    text-align: center;

  }

  .file_drag_area {

    height: 350px;
    padding: 10%;
    border: 2px dashed #ccc;
    text-align: center;
    font-size: 24px;
  }

  .file_drag_over {
    color: #000;
    border-color: #000;

  }

  .bg-green,
  .callout.callout-success,
  .alert-success,
  .label-success,
  .modal-success .modal-body {
    background-color: #00a65a8a !important;
  }

  .bg-red,
  .callout.callout-danger,
  .alert-danger,
  .alert-error,
  .label-danger,
  .modal-danger .modal-body {
    background-color: #dd4b3985 !important;
  }
</style>

<!-- FILTER DATA -->
<section class="content" style="min-height:auto">
  <!-- Small boxes (Stat box) 
  <?php if ($getData[0]['pending'] > 0) { ?>
  <div class="alert alert-warning alert-dismissible" style="background: #d4d414 !important;border-color: #d4d414 !important;">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h4><i class="icon fa fa-warning"></i> Pending TTE !</h4>
    Anda masih memiliki <?php echo $getData[0]['pending'] ?> dokumen yang belum anda tanda tangani atau approve, silakan proses di <b>Dokumen Masuk</b>
  </div>
  <?php } ?>
  -->
  <div class="row">
    <!-- <div class="col-md-2"></div>
    <div class="col-lg-4 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h3><?php echo $getData[0]['done'] ?></h3>

          <p>Dokumen yang sudah di Approve dan TTE</p>
        </div>
        <div class="icon" style="top:-20px">
          <img class="text-center" src="assets/img/signImage.png" style="width: 80px;" title="Akun sudah memiliki sertifikat" />
        </div>
        <a href="#" class="small-box-footer"></a>
      </div>
    </div> -->
    <!-- ./col -->
    <!-- <div class="col-lg-4 col-xs-6">
      <div class="small-box bg-red">
        <div class="inner">
          <h3><?php echo $getData[0]['pending'] ?></h3>

          <p>Dokumen yang belum di Approve dan TTE</p>
        </div>
        <div class="icon" style="top:-20px">
          <img class="text-center" src="assets/img/pending.png" style="width: 80px;" title="Akun sudah memiliki sertifikat" />
        </div>
        <a href="#" class="small-box-footer"></a>
      </div>
    </div>
    <div class="col-md-2"></div> -->

</section>


<section class="content connectedSortable">
  <div class="nav-tabs-custom">
    <!-- Tabs within a box -->
    <ul class="nav nav-tabs pull-right">
      <li class="pull-left header"><i class="fa fa-inbox"></i> Selamat Datang</li>
    </ul>
    <div class="tab-content no-padding">
      <div class="row">
        <div class="col-md-12 text-center">
          <h3 style="margin:40px 0">Selamat Datang di IFM (Integrasi File Manager)</h3>
        </div>
      </div>
    </div>
  </div>
</section>



<!-- Modal Add New Data -->
<div class="modal fade" id="ModalHirarki" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" style="width:95%" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div class="box-body">

          <div class="col-md-3">
            <h3>Input Dokumen Hirarki TTE</h3>
            <hr>
            <form class="formData">
              <div class="form-group">
                <label for="embed pdf">Embed PDF</label><br>
                <label style="font-weight: 400;">
                  <input value='kop-param' name='embedPDF' type='radio' checked class='embedPDF' /> Kop dan Paramter
                </label>
                <label style="margin-left:10px;font-weight: 400;">
                  <input value='kop' name='embedPDF' type='radio' class='embedPDF' /> Kop
                </label>
                <label style="margin-left:10px;font-weight: 400;">
                  <input value='notuse' name='embedPDF' type='radio' class='embedPDF' /> Tidak Embed
                </label>
              </div>
              <div class="form-group">
                <label for="jenis_dokumen">Jenis Dokumen</label>
                <input type="text" name="jenis_dokumen" value="<?php echo $jenis_dokumen ?>" readonly class="form-control jenis_dokumen" id="exampleInputPass" placeholder="Jenis Dokumen">
              </div>
              <div class="form-group">
                <label>Nomor</label>
                <input type="text" name="nomor" value="<?php echo $nomor ?>" readonly class="form-control nomor" id="exampleInputPass" placeholder="Nomor">
              </div>
              <div class="form-group">
                <label>Tanggal</label>
                <input type="text" name="tanggal" value="<?php echo $tanggal ?>" readonly class="form-control tanggal" id="exampleInputPass" placeholder="Tanggal">
              </div>
              <div class="form-group">
                <label>Perihal</label>
                <input type="text" name="perihal" value="<?php echo $perihal ?>" readonly class="form-control perihal" id="exampleInputPass" placeholder="Perihal">
              </div>
              <div class="form-group">
                <label>Tujuan</label>
                <input type="text" name="tujuan" value="<?php echo $tujuan ?>" readonly class="form-control tujuan" id="exampleInputPass" placeholder="Perihal">
              </div>
              <div class="form-group">
                <label>Masa Berlaku dalam Hari</label>
                <input type="number" name="umur" class="form-control perihal" id="exampleInputPass" placeholder="Kosongkan jika tidak memiliki masa berlaku">
              </div>
              <div class="form-group">
                <label for="exampleInpuPass">Di TTE Oleh</label>
                <select class="form-control tte_oleh" name="tte_oleh" style='width: 100%;'>
                  <option value="">Pilih Pejabat</option>
                </select>
              </div>
            </form>
            <button class="btn btn-success uploadBtn"><i class="fa fa-upload"></i> Upload</button>
            <button class="btn btn-danger" data-dismiss="modal">Close</button>

          </div>
          <div class="col-md-9">
            <iframe style="width: 100%;height:600px" src="" class="framePDF"></iframe>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<script>
  $(document).ready(function() {

    $('.embedPDF').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass: 'iradio_flat-green'
    });

    $('.tte_oleh').select2({
      ajax: {
        url: './lib/base/select_data.php?t=vw_select_direct&filter=all',
        dataType: 'json',
        data: function(params) {
          return {
            search: params.term
          };
        }
      }
    });


    $('.imgDrag').on('click', function() {
      $(".pdfFile").click();
    });

    $(".pdfFile").on("change", function(e) {
      var dataFrom = new FormData();
      //File data
      var file_data = $('input[type="file"]');
      for (var i = 0; i < file_data.length; i++) {
        dataFrom.append(file_data[i].name, file_data[i].files[0]);
      }

      dataFrom.append("f", "2");
      dataFrom.append('formType', 'add');

      sendFile(dataFrom);

    });

    $('.file_drag_area').on('dragover', function() {
      $(this).addClass('file_drag_over');
      return false;
    });

    $('.file_drag_area').on('dragleave', function() {
      $(this).removeClass('file_drag_over');
      return false;
    });

    $('.file_drag_area').on('drop', function(e) {
      e.preventDefault();
      $(this).removeClass('file_drag_over');
      var formData = new FormData();
      var files_list = e.originalEvent.dataTransfer.files;
      for (var i = 0; i < files_list.length; i++) {
        formData.append('dokumen', files_list[i]);
      }

      formData.append('formType', 'add');
      formData.append("f", "2");
      sendFile(formData);


    });


    function sendFile(files) {

      var fReader = new FileReader();
      fReader.readAsDataURL(files.get("dokumen"));
      // console.log(input.files[0]);
      fReader.onloadend = function(event) {
        $(".framePDF").attr("src", event.target.result);
      }
      $('#ModalHirarki').modal('toggle');

      $(".uploadBtn").on("click", function() {

        $("#loading").removeClass("hide");
        var dataFrom = new FormData();
        var form_data = $('.formData').serializeArray();
        $.each(form_data, function(key, input) {
          dataFrom.append(input.name, input.value);
        });

        dataFrom.append("dokumen", files.get("dokumen"));
        dataFrom.append("tipe_send_dokumen", "direct");

        $.ajax({
          url: "./lib/base/save_dokumen_upload.php",
          method: "POST",
          data: dataFrom,
          processData: false,
          contentType: false,
          dataType: 'json',
          success: function(msg) {
            console.log(msg);
            $("#loading").addClass("hide");
            if (msg.status == 'success') {
              alert(msg.msg);
              //reset form
              $('#ModalHirarki').modal('toggle');
              $('.formData').trigger("reset");;
            } else {
              popup('error', msg.msg, '');
            }
          },
          error: function(err) {
            console.log(err);
            popup('error', err.responseText, '');
          }
        });

      });

    }
  });
</script>