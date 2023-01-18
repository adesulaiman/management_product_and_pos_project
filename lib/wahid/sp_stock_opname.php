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
  
      $qOption = $adeQ->select("select id,product_name from data_product");
  
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

      <div id="StockOpname" class="row">
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

        <!-- <div class="col-md-4">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">title Detail</h3>
              <br><small>Select one of the Stock Opname Data <i class="fa fa-eye"></i></small>
            </div>
            <div class="box-body" style="height:350px; overflow-y: auto;">
              <table class="table table-bordered table-striped nowrap" id="view">
                <thead>
                  <tr>
                    <th class="width:80%">Product</th>
                    <th class="width:20%">Action</th>
                  </tr>
                </thead>
                <tbody class='listProduct'>
                  
                </tbody>
              </table>
            </div>
          </div>
        </div> -->
        
      </div>

      <div id="subStockOpname" class="row">
        <div class="col-lg-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title"><?= $formDesc ?></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="sub<?= $formName ?>" class="table table-bordered table-striped nowrap" style="width: 100%;">
                <thead>
                  <tr><th>DATA</th></tr>
                </thead>
                <tbody>
                  <tr><td>Wahid</td></tr>
                </tbody>
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
            <form id="formModal<?php echo $formName ?>" method='post'>
            <div class="modal-body">
  
              <!-- Form Add and Update Detail Product Stock Opname-->
              <div class="formDetailProduct">
                <input type="hidden" name="idDetail" id="idDetail">
                <input type="hidden" name="id" id="id">
                <div class="form-group input-group-sm">
                  <label>Produck Name</label>
                  <select name="productId" id="productId" class="form-control" required>
                    <option value="0"> -- Select -- </option>
                    <?php
                      foreach ($qOption as $opt) {
                        echo "<option value='". $opt['id'] ."'>". $opt['product_name'] ."</option>";
                      }
                    ?>
                  </select>
                </div>
                <input type="hidden" name="productName" id="productName">

                <div class="form-group input-group-sm">
                  <label for="">Phisycal</label>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="input-group input-group-sm">
                        <span class="input-group-addon" id="basic-addon1">Qty</span>
                        <input type="number" name="qtyPhisycal" id="qtyPhisycal" class="form-control" aria-describedby="basic-addon1" required>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="input-group input-group-sm">
                        <span class="input-group-addon" id="basic-addon1">Gram</span>
                        <input type="number" name="gramPhisycal" id="gramPhisycal" class="form-control" aria-describedby="basic-addon1" required>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="form-group input-group-sm">
                  <label for="">Adjusment</label>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="input-group input-group-sm">
                        <span class="input-group-addon" id="basic-addon1">Qty</span>
                        <input type="number" name="qtyAdjs" id="qtyAdjs" class="form-control" aria-describedby="basic-addon1" required>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="input-group input-group-sm">
                        <span class="input-group-addon" id="basic-addon1">Gram</span>
                        <input type="number" name="gramAdjs" id="gramAdjs" class="form-control" aria-describedby="basic-addon1" required>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Form Input Stock Opname -->
              <div class="formStockOpname">
                <div class="form-group input-group-sm">
                  <label for="">Stock Opname Info</label>
                  <textarea name="soInfo" id="soInfo" class="form-control" style="resize:vertical"></textarea>
                </div>
                <div class="form-group input-group-sm">
                  <label for="">Created Date</label>
                  <input type="text" name="soDate" id="soDate" value="<?= date('d / m / Y') ?>" class="form-control" disabled>
                </div>
              </div>
            

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger btn-sm" id="closeAdd" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; Close</button>
              <button type="submit" class="btn btn-success btn-sm formSubmit" id="submitData"><i class="fa fa-check"></i>&nbsp; Submit</button>
            </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Modal Detail Product -->
      <div id="ModalDetail<?= $formName ?>" class="modal fade mr-tp-100" role="dialog">
        <div class="modal-dialog modal-md">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="myModalLabel" >Detail Product : <span class="HeaderProduct" >product</span> </h4>
              <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button> -->
            </div>
            <div class="modal-body">
            
            <table class="table table-bordered">
                <tr class="bg-info">
                  <th>Stock Opname Info</th>
                  <td class="stOpname">Stock Opname Wahid</td>
                </tr>
                <tr>
                  <th>Status Stock Opname</th>
                  <td class="StatusSo">status</td>
                </tr>
                <tr>
                  <th>Product</th>
                  <td class="product">Mie Goreng</td>
                </tr>
                <tr>
                  <th>Qty Phisycal</th>
                  <td class="qtyPhisycal">Mie Goreng</td>
                </tr>
                <tr>
                  <th>Gram Phisical</th>
                  <td class="gramPhisycal">Mie Goreng</td>
                </tr>
                <tr>
                  <th>Qty Adjusment</th>
                  <td class="qtyAdjs">Mie Goreng</td>
                </tr>
                <tr>
                  <th>Gram Adjusment</th>
                  <td class="gramAdjs">Mie Goreng</td>
                </tr>
            </table>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger btn-sm" id="closeAdd" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; Close</button>
            </div>
          </div>
        </div>
      </div>

    <script>

      // Load Data Stock Opname
      $(document).ready(function() {

          $('#StockOpname').show();
          var table = $('#<?= $formName ?>').DataTable({
            dom: 'Blfrtip',
            buttons: [
              {
                text: '<i class="fa fa-plus-circle"></i>&nbsp; Add',
                action: function ( e, dt, node, config ) {
                  $('#myModalLabel').html("Add <?= $formDesc ?>");
                  $('.formDetailProduct').hide();
                  $('.formStockOpname').show();
                  $('.modal-footer button[type=submit]').html("<i class='fa fa-check'></i>&nbsp; Submit");
                  $('.modal-footer button[type=submit]').attr('id', 'submitStockOpname');

                  $('#Modal<?= $formName ?>').modal('show');
                }
              },
              {
                text: '<i class="fa fa-plus-"></i>&nbsp; action',
                action: function ( e, dt, node, config ) {
                  $('#StockOpname').hide();
                  $('#subStockOpname').show();
                }
              },
            ],
            searching: false,
            responsive: true,
            paging: true,
            "scrollX": true,
            scrollCollapse: true,
            "ajax": {
              "type": "POST",
              "url": "./lib/wahid/sp_stock_opname_load.php",
              "data": function(data) {
                var dtQuery = $(".queryFilter").val();
                data.query = dtQuery;
              },
            },
            "sAjaxDataProp": "",
            "order": [[ 0, "asc" ]],
            "aoColumns": [
              {
                  "mData": null,
                  "title": "Action",
                  "sortable": false,
                  "render": function (data, row, type, meta) {
                      let btnAdd = "<button style='font-size: 14px;' class='btn btn-success btn-xs' id='add<?= $formName ?>' name='add<?= $formName ?>' data-id='"+ data.id +"' title='Input Stock Opname'><i class='fa fa-plus-square'></i></button>";

                      if (data.status_stock_opname == "closed") {
                        btnAdd = "<button style='font-size: 14px;' class='btn btn-success btn-xs' title='Button Disabled' disabled><i class='fa fa-plus-square'></i></button>";
                      }
                      btnAdd += "&nbsp; <button style='font-size: 14px;' class='btn btn-danger btn-xs' id='view<?= $formName ?>' name='view<?= $formName ?>' data-idview='"+ data.id +"' title='View Stock Opname'><i class='fa fa-eye'></i></button>";

                      return btnAdd;
                  }
              },
	            {
	                "mData": null,
	                "title": "Stock Opname Info",
	                "render": function (data, row, type, meta) {
	                    return "<a href=# id='infoSt'>" + data.stock_opname_info + "</a>";
	                }
	            },
	            {
	                "mData": null,
	                "title": "Stock Opname Time",
	                "render": function (data, row, type, meta) {
	                    return data.stock_opname_time;
	                }
	            },
	            {
	                "mData": null,
	                "title": "Status Stock Opname",
	                "render":function (data, row, type, meta) {
                      status = '';
                      if(data.status_stock_opname == "open") {
                        status = "<button id=lock<?= $formName ?> class='btn btn-primary btn-xs' data-idsts='"+ data.id +"' title='Closed Stock Opname'><i class='fa fa-unlock-alt'></i> &nbsp; "+data.status_stock_opname+"</button>";
                      }else{
                        status = "<button class='btn btn-primary btn-xs' disabled title='Its Closed'><i class='fa fa-lock'></i> &nbsp; "+data.status_stock_opname+"</button>";
                      }

	                    return status;
	                }
	            },
	            {
	                "mData": null,
	                "title": "Create By",
	                "render": function (data, row, type, meta) {
	                    return data.created_by;
	                }
	            }
	          ],
          });

          var tableSub = $('');
        });

        // 

        $('#<?= $formName ?> a').on('click', '#infoSt', function(){
          $('#StockOpname').hide();
          $('#subStockOpname').show();
        });


        // Show Detail Product by ID Stock Opname
        $('#<?= $formName ?> tbody').on( 'click', '#view<?= $formName ?>', function () {
          var view_id = $(this).data("idview");
          $.ajax({
            type: "POST",
            url: "./lib/wahid/sp_stock_opname_detail.php",
            data: "type=group&keyId=" + view_id,
            dataType: "json",
            success: function(msg) {
              var show;
              $.each(msg.view, function(index, value) {
                actionEdit = "<button style='font-size: 12px;' class='btn btn-success btn-xs' id='edit<?= $formName ?>' data-id='"+ value.id_product +"' data-idso='"+ value.id_stock_opname +"' title='Edit Product'><i class='fa fa-pencil'></i></button>"
                actionShow = "<button style='font-size: 12px;' class='btn btn-primary btn-xs' id='show<?= $formName ?>' data-idview='"+ value.id_product +"' data-idso='"+ value.id_stock_opname +"' title='Show Product'><i class='fa fa-eye'></i></button>"
                actionDel = "<button style='font-size: 12px;' class='btn btn-danger btn-xs' id='del<?= $formName ?>' data-iddel='"+ value.id +"' title='Delete Product'><i class='fa fa-trash'></i></button>"

                if (value.status_stock_opname == "open") {
                  show += '<tr><td>'+ value.product_name +'</td><td>'+ actionEdit +'&nbsp;'+ actionShow +'&nbsp;'+ actionDel +'</td></tr>';
                } else {
                  show += '<tr><td>'+ value.product_name +'</td><td>'+ actionShow +'</td></tr>';
                }
                
              })
              $('.listProduct').html(show);
            },
          });
        });

        
        // Show Modal Add Stoct Opname (Id Stock Opname & Product)
        $('#<?= $formName ?> tbody').on( 'click', '#add<?= $formName ?>', function () {
          var employee_id = $(this).data("id");

          $('#myModalLabel').html("Add <?= $formDesc ?> Product");
          $('.modal-footer button[type=submit]').html("<i class='fa fa-check'></i>&nbsp; Submit");
          $('#productId').attr('disabled', false);
          $('.modal-footer button[type=submit]').attr('id', 'submitData');
          $('.formDetailProduct').show();
          $('.formStockOpname').hide();

          $('#formModal<?= $formName ?>')[0].reset();
          

          $('#id').val(employee_id);
          $('#productId').change(function() { 
            var productId = $(this).val(); 
            $.ajax({
              type: 'POST', 
              url: './lib/wahid/sp_stock_opname_lproduct.php', 
              data: 'productId=' + productId, 
              success: function(response) { 
                $('#productName').val(response); 
              }
            });
          });
          $("#Modal<?= $formName ?>").modal("show");
	      });


        // Add Data Detail Stock Opname Process
        $('#formModal<?= $formName ?>').on('click', '#submitData', function(event){
          event.preventDefault();

          if($('#qtyPhisycal').val() == '' || $('#gramPhisycal').val() == '' || $('#qtyAdjs').val() == ''|| $('#gramAdjs').val() == '') {
            swal({
              title: "Notice",
              text: "Please complete the input data",
              icon: "warning"
            })

          }else{

            $.ajax({  
              url:"./lib/wahid/sp_stock_opname_detail_add.php",  
              method:"POST",
              dataType:"json",
              data:$('#formModal<?= $formName ?>').serialize(),
              beforeSend:function(data){
                $('#submitData').val("Inserting");  
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
                    $('view<?= $formName ?>').load();
                  }
                });
              },
            }); 
          }
          
        });


        // Show Modal Update Detail Stock Opname Detail
        $('#view tbody').on( 'click', '#edit<?= $formName ?>', function () {
          var Id = $(this).data("id");
          var Idso = $(this).data("idso");

          $('#myModalLabel').html("Update <?= $formDesc ?> Product");
          $('.modal-footer button[type=submit]').html("<i class='fa fa-pencil'></i>&nbsp; Update");
          $('.modal-footer button[type=submit]').attr('id', 'updateData');
          $('.formDetailProduct').show();
          $('.formStockOpname').hide();
          $('#productId').attr('disabled', true);

          $.ajax({
            type: 'POST', 
            url: './lib/wahid/sp_stock_opname_detail.php', 
            data: 'type=single&keyId='+ Id +'&idSo=' + Idso,
            dataType: 'json',
            success: function(data) {
              $.each(data.view, function(index, value) {
              $('#idDetail').val(value.id);
              $('#id').val(value.id_stock_opname);
              $('#productId').val(value.id_product);
              $('#productName').val(value.product_name);
              $('#qtyPhisycal').val(value.qty_phisycal);
              $('#gramPhisycal').val(value.gram_physycal);
              $('#qtyAdjs').val(value.qty_adjusment);
              $('#gramAdjs').val(value.gram_adjusment);
              })
            }
          });
          $("#Modal<?= $formName ?>").modal("show");
	      });


        // Process Update Stock Opname Detail
        $('#formModal<?= $formName ?>').on('click', '#updateData', function(){

          $.ajax({  
            url:"./lib/wahid/sp_stock_opname_process.php",
            method:"POST",
            data:"action=update&" + $('#formModal<?= $formName ?>').serialize(),
            dataType:"json",
            beforeSend:function(data){
              $('#formModal<?= $formName ?>')[0].reset();
              $('#Modal<?= $formName ?>').modal('hide');
            },
            success: function(msg) {
              swal('Success', msg.text, msg.status);
              $('#view<?= $formName ?>').reload();
            },
          }); 
        });


        // Delete Data Detail Product Stock Opname
        $('#view tbody').on( 'click', '#del<?= $formName ?>', function(){
          swal({
            title: "Delete",
            text: "do you want to delete this product?",
            icon: "info",
            buttons: true,
            dangerMode: true

          }).then((isDelete) => {
            if(isDelete) {
              $.ajax({  
                url:"./lib/wahid/sp_stock_opname_process.php",
                method:"POST",
                data:"action=delete&idDetail=" + $(this).data("iddel"),
                dataType:"json",
                success: function(msg) {
                  swal({
                    title: msg.status,
                    text: msg.text,
                    icon: "success"
                  }).then((oke) => {
                    if(oke) {
                      $('.listProduct').reload();
                    }
                  });
                },
              });
            }
          });
          return false;
        });


        // Add Data Stock Opname Process
        $('#formModal<?= $formName ?>').on('click', '#submitStockOpname', function(event){
          event.preventDefault();

          if($('#soInfo').val() == '') {
            swal({
              title: "Notice",
              text: "Please complete the input data",
              icon: "warning"
            })

          }else{

            $.ajax({  
              url:"./lib/wahid/sp_stock_opname_process.php",  
              method:"POST",
              dataType:"json",
              data: "action=addSo&soInfo=" + $('#soInfo').val(),
              beforeSend:function(data){
                $('#submitData').val("Inserting");  
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
                    $('view<?= $formName ?>').load();
                  }
                });
              },
            }); 
          }
          
        });


        // Closed Stock Opname
        $('#<?= $formName ?> tbody').on('click', '#lock<?= $formName ?>', function() {
          var idStatus = $(this).data("idsts");
          
          swal({
            title: "Close this stock opname ?",
            text: "You will not be able to add more products",
            icon: "info",
            buttons: true,
            dangerMode: true

          }).then((isClosed) => {
            if(isClosed) {

              $.ajax({  
                url:"./lib/wahid/sp_stock_opname_process.php",
                method:"POST",
                data:"action=statusUpdt&idSt=" + idStatus,
                dataType:"json",
                success: function(msg) {
                  swal({
                    title: msg.status,
                    text: msg.text,
                    icon: "success"
                  }).then((oke) => {
                    if(oke) {
                      $('.listProduct').reload();
                    }
                  });
                },
              });
            }
          });

        })


        // Show detail product
        $('#view tbody').on('click', '#show<?= $formName ?>', function() {
          var IdDetail = $(this).data('idview');
          var IdSo = $(this).data('idso');

          $.ajax({
            type: 'POST', 
            url: './lib/wahid/sp_stock_opname_detail.php', 
            data: 'type=single&keyId='+ IdDetail +'&idSo=' +IdSo,
            dataType: 'json',
            success: function(data) {
              $.each(data.view, function(index, value) {
                $('.HeaderProduct').html(value.product_name);
                $('.StatusSo').html("<span class='badge badge-info'>"+ value.status_stock_opname +"</span>");
                $('.stOpname').html(value.stock_opname_info);
                $('.product').html(value.product_name);
                $('.qtyPhisycal').html(value.qty_phisycal + " Unit");
                $('.gramPhisycal').html(value.gram_physycal + " Gram");
                $('.qtyAdjs').html(value.qty_adjusment + " Unit");
                $('.gramAdjs').html(value.gram_adjusment + " Gram");
              })
            }
          });
          $('#ModalDetail<?= $formName ?>').modal('show');

        })


    </script>