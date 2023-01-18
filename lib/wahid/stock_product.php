<?php

require "../../config.php";
require "../base/db.php";
require "../base/security_login.php";



if (isset($_SESSION['userid'])) {
    if (isset($_GET['f'])) {
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
  
      $qOption = $adeQ->select("select barcode,product_name from data_product");
      $qOptionSt = $adeQ->select("select id,storage_name from data_category_storage");
  
      foreach ($qForm as $valForm) {
        $formName = $valForm['formname'];
        $formDesc = $valForm['description'];
        $formCode = $valForm['formcode'];
        $formView = $valForm['formview'];
      }
    }
}


?>

<input type='hidden' class='queryFilter'/>
  <section class="content-header">
    <h1>
      <?= $formDesc ?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Menus</a></li>
      <li class="active"><?= $formDesc ?></li>
    </ol>
  </section>
  
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title"><?= $formDesc ?></h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <table id="<?= $formName ?>" class="table table-bordered table-striped nowrap" style="width: 100%;">
              <!-- Data Here -->
            </table>
          </div>
          <!-- /.box-body1 -->
        </div>
      </div>
    </div>
  </section>


  <!-- Modal -->
  <div id="Modal<?= $formName ?>" class="modal fade mr-tp-100" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel" >Add <?= $formDesc ?></h4>
          <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button> -->
        </div>
        <form id="formModal<?= $formName ?>">
        <div class="modal-body">
          
          <div class="form-group input-group-sm">
            <label for="">Barcode</label>
            <input type="text" name="barcode" id="barcode" class="form-control" value="Please select a product name" readonly>
          </div>

          <div class="form-group input-group-sm">
            <label for="">Product Name</label>
            <select name="productName" id="productName" class="form-control">
              <option value="0">-- select --</option>
              <?php 
                foreach ($qOption as $prd) {
                  echo "<option value='". $prd["product_name"] ."'> ". $prd["product_name"] ." </option>";
                } 
              ?>
            </select>
          </div>

          <div class="form-group input-group-sm">
            <label for="">Storage</label>
            <select name="storage" id="storage" class="form-control">
              <option value="0">-- select --</option>
              <?php 
                foreach ($qOptionSt as $str) {
                  echo "<option value='". $str["id"] ."'> ". $str["storage_name"] ." </option>";
                } 
              ?>
            </select>
          </div>

          <div class="form-group input-group-sm">
            <label for="">Qty Stock</label>
            <input type="number" name="qtyStock" id="qtyStock" class="form-control">
          </div>

          <div class="form-group input-group-sm">
            <label for="">Gram</label>
            <input type="number" name="gram" id="gram" class="form-control">
          </div>

          <div class="form-group input-group-sm">
            <label for="">Product Stock Date</label>
            <input type="text" class="form-control" value="<?= date('d / m / Y') ?>" readonly>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; Close</button>
          <button type="submit" class="btn btn-success btn-sm" id="submitStock"><i class="fa fa-check"></i>&nbsp; Submit</button>
        </div>
        </form>
      </div>
    </div>
  </div>





  

  <script>
    $(document).ready(function() {
      var table = $('#<?= $formName ?>').DataTable({
        dom: 'Blfrtip',
        buttons: [
          {
            text: '<i class="fa fa-plus-circle"></i>&nbsp; Add',
            action: function ( e, dt, node, config ) {

              $('#myModalLabel').html("Add <?= $formDesc  ?>");
              $('.modal-footer button[type=submit]').attr('id', 'submitProduct');

              $('#productName').change(function() { 
                var productName = $(this).val(); 
                $.ajax({
                  type: 'POST', 
                  url: './lib/wahid/stock_product_load.php', 
                  data: 'type=changeProduct&keyId=' + productName, 
                  success: function(response) { 
                    $('#barcode').val(response); 
                  }
                });
              });

              $('#Modal<?= $formName ?>').modal('show');
            }
          }
        ],
        searching: false,
        responsive: true,
        paging: true,
        "scrollX": true,
        scrollCollapse: true,
        "ajax": {
          "type": "POST",
          "url": "./lib/wahid/stock_product_load.php",
          "data": function(data) {
            var dtType = "group";
            var dtKeyId = "0";
            data.type = dtType;
            data.keyId = dtKeyId;
          }
        },
        "sAjaxDataProp": "",
        "order": [[ 0, "asc" ]],
        "aoColumns": [
          {
            "mData": null,
            "title": "Action",
            "render": function (data, row, type, meta) {
              let actionBtn = "<button style='font-size: 12px' class='btn btn-success btn-xs' title='Move Storage Product' id='change<?= $formName ?>' data-id='"+ data.id +"'><i class='fa fa-arrows'></i></button>";
              let showBtn = "<button style='font-size: 12px' class='btn btn-primary btn-xs' title='Update Stock Product' id='edit<?= $formName ?>' data-id='"+ data.id +"'><i class='fa fa-pencil'></i></button>";

              return actionBtn +" &nbsp; "+ showBtn;
            },
          },
          {
            "mData": null,
            "title": "Barcode",
            "render": function (data, row, type, meta) {
              return data.barcode;
            },
          },
          {
            "mData": null,
            "title": "Product Name",
            "render": function (data, row, type, meta) {
              return data.product_name;
            },
          },
          {
            "mData": null,
            "title": "Storage",
            "render": function (data, row, type, meta) {
              return data.storage_name;
            },
          },
          {
            "mData": null,
            "title": "Qty",
            "render": function (data, row, type, meta) {
              return data.qty_stock + " Unit";
            },
          },
          {
            "mData": null,
            "title": "Weight",
            "render": function (data, row, type, meta) {
              return data.gram + " gram";
            },
          },
          {
            "mData": null,
            "title": "Stock Date",
            "render": function (data, row, type, meta) {
              return data.stock_date;
            },
          },
          {
            "mData": null,
            "title": "Created By",
            "render": function (data, row, type, meta) {
              return data.created_by;
            },
          },
        ],
      });

    });



    // Add Stock Produk Process
    $('#formModal<?= $formName ?>').on('click', '#submitStock', function(event) {
      event.preventDefault();

      if($('#productName').val() == '' || $('#storage').val() == '' || $('#qtyStock').val() == ''|| $('#gram').val() == '') {
        swal({
          title: "Notice",
          text: "Please complete the input data",
          icon: "warning"
        })
        
      }else{

        $.ajax({  
          url:"./lib/wahid/stock_product_process.php",  
          method:"POST",
          dataType:"json",
          data: "action=add&" + $('#formModal<?= $formName ?>').serialize(),
          beforeSend:function(data){
            $('#submitStock').val("Inserting");  
            $('#formModal<?= $formName ?>')[0].reset();  
            $('#Modal<?= $formName ?>').modal('hide');
          },
          success: function(msg) {
            swal({
              title: msg.status,
              text: msg.text,
              icon: "success"
            }).then((oke) => {
              if(oke) {
                
              }
            });
          },
        });

      }

    });


    $('#<?= $formName ?> tbody').on('click', '#edit<?= $formName ?>', function(event){
      event.preventDefault();

      $('#myModalLabel')
    });

  </script>