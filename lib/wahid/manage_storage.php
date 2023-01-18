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
            <h4 class="modal-title" id="myModalLabel" >Add Category Storage</h4>
            <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button> -->
          </div>
          <form id="formModal<?= $formName ?>">
          <div class="modal-body">
            
            <div class="inputForm">
              <div class="form-group input-group-sm">
                <label for="">Storage Name</label>
                <input type="text" class="form-control" name="strName" id="strName">
              </div>
            </div>


            <div class="detailForm">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Barcode</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Weight</th>
                  </tr>
                </thead>
                <tbody class='listProduct'>
                  <!-- Data Here -->
                </tbody>
              </table>
            </div>

          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-danger btn-xs" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; Close</button>
            <button type="submit" class="btn btn-success btn-xs" id="Add<?= $formName ?>"><i class="fa fa-check"></i>&nbsp; Submit</button>
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
                  $('.modal-footer button[type=submit]').attr('id', 'Add<?= $formName ?>');
                  $('#myModalLabel').html("Add <?= $formDesc ?>");
                  $('.inputForm').show();
                  $('.detailForm').hide();
                  $('.modal-footer button[type=submit]').show();

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
              "url": "./lib/wahid/manage_storage_load.php",
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
                  let actionBtn = "<button style='font-size: 12px' class='btn btn-success btn-xs' title='Change Status Storage' id='change<?= $formName ?>' data-id='"+ data.id +"'><i class='fa fa-retweet'></i></button>";
                  let showBtn = "<button style='font-size: 12px' class='btn btn-danger btn-xs' title='Show Product This Storage' id='show<?= $formName ?>' data-id='"+ data.id +"'><i class='fa fa-eye'></i></button>"
                  
                  if (data.is_active == "0") {
                    actionBtn = "<button style='font-size: 12px' class='btn btn-success btn-xs' title='Storage Non-Active' disabled><i class='fa fa-retweet'></i></button>";
                  }

                  return actionBtn +" &nbsp; "+ showBtn;
                },
              },
              {
                "mData": null,
                "title": "Storage Name",
                "render": function (data, row, type, meta) {
                  return data.storage_name;
                },
              },
              {
                "mData": null,
                "title": "Status Storage",
                "render": function (data, row, type, meta) {
                  var status = '';
                  if(data.is_active == 1){
                    status = "<span class='label label-primary'><i class='fa fa-check'></i> active</span>";
                  }else{
                    status = "<span class='label label-danger'><i class='fa fa-times'></i> non-active</span>"
                  }
                  return status;
                },
              },
              {
                "mData": null,
                "title": "Created By",
                "render": function (data, row, type, meta) {
                  return data.created_by;
                },
              }
            ],

          });
        });


        // Action BUTTON Add Storage
        $('#formModal<?= $formName ?>').on('click', '#Add<?= $formName ?>', function(event) {
          event.preventDefault();
          
          if ($('#strName').val() == '') {
            swal({
              title: "Notice",
              text: "Please input storage name",
              icon: "warning"
            });
          }else{

            $.ajax({
              url: "./lib/wahid/manage_storage_process.php",
              method:"POST",
              data: "action=add&strName=" + $('#strName').val(),
              dataType: "json",
              beforeSend:function(data){
                $('#formModal<?= $formName ?>')[0].reset();  
                $('#Modal<?= $formName ?>').modal('hide');
              },
              success: function(msg) {
                swal({
                  title: msg.status,
                  text: msg.text,
                  icon: "success"
                });
              },
            })
          }

        });

        
        // Action BUTTON Change Status Storage
        $('#<?= $formName ?> tbody').on('click', '#change<?= $formName ?>', function() {
          var id = $(this).data('id');
          swal({
            title: "Change Status Storage",
            text: "Want to disable storage ?",
            icon: "warning",
            buttons: true
          }).then((change) => {
            if (change) {
              $.ajax({  
                url:"./lib/wahid/manage_storage_process.php",
                method:"POST",
                data:"action=statusUpdt&id=" + id,
                dataType:"json",
                success: function(msg) {
                  swal({
                    title: msg.status,
                    text: msg.text,
                    icon: "success"
                  });
                },
              }); 
            }
          })
        })



        // Detail Product from this Storage
        $('#<?= $formName ?> tbody').on('click', '#show<?= $formName ?>', function() {
          var idStorage = $(this).data('id');

          $('.inputForm').hide();
          $('.detailForm').show();
          $('.modal-footer button[type=submit]').hide();
          $('#myModalLabel').html("Detail <?= $formDesc ?>");
          $('#Modal<?= $formName ?>').modal('show');

          $.ajax({  
            url:"./lib/wahid/manage_storage_load.php",
            method:"POST",
            data:"type=groupDetail&keyId=" + idStorage,
            dataType:"json",
            success: function(data) {

              var showPrd
              $.each(data.view, function(index, value) {
                showPrd += "<tr> <td>"+ value.barcode +"</td> <td>"+ value.product_name +"</td> <td>"+ value.qty_stock +" Unit</td> <td>"+ value.gram +" Gram</td> </tr>"
              });

              $('.listProduct').html(showPrd);
            },
          }); 

        })

    </script>