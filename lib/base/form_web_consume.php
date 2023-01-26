<?php

require "../../config.php";
require "../base/db.php";
require "../base/security_login.php";

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

//SHOW SCHEMA VIEW
$qSchemaView = $adeQ->select($adeQ->prepare(
    "select * from information_schema.columns where table_name=%s order by ordinal_position", $formView
));

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
      <div class="col-xs-12 col-md-7"> 
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
              <table id="<?php echo $formName ?>" class="table table-bordered table-striped nowrap">
                <thead>
                <tr>
                  <?php
                  foreach($qSchemaView as $valField)
                  {
                    if(substr($valField['COLUMN_NAME'],0,3) != 'id_')
                    {
                      echo "<th>".ucfirst(str_replace("_", " ", $valField['COLUMN_NAME']))."</th>";
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

        <div class="col-xs-12 col-md-5"> 
            <div class="box">
              <div class="box-header">
                <h3 class="box-title">Access Field API</h3>
                <button style="margin-left:20px" class="btn btn-xs btn-success commitField">Commit</button>
              </div>
              <div class="box-body" style="height:450px; overflow-y: auto;">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                        <th style="width:10%"><input type="checkbox" class="flat-red rolemenuAll"></th>
                        <th style="width:90%;font-size: 15px">Field</th>
                    </tr>
                  </thead>
                  <tbody class='listMenu'>
                    
                  </tbody>
                </table>      
              </div>
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
          <form class="formModal<?php echo $formName ?>" action='#' method='post'>

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
    format: '<?php echo $dateJS?>',
    autoclose: true
  });


$('.filterAdvCheck').click(function(){
    if($(this).prop("checked") == true){
        $('.advanceFilter').css('display', 'block');
    }else{
        $('.advanceFilter').css('display', 'none');
    }
});


    var table = $("#<?php echo $formName ?>").DataTable({
      "dom": 'Bltip',
      "processing": true,
      "serverSide": true,
      "ajax": {
        "url" : "./lib/base/load_data_with_date.php?t=<?php echo $formView ?>&f=<?php echo $f?>",
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
      foreach($qSchemaView as $valField)
      {
        if(substr($valField['COLUMN_NAME'],0,3) != 'id_')
        {
          echo "{ data: '$valField[COLUMN_NAME]', width:'150px' },";
        }
      }
      ?>
      ],
      buttons: [
          {
            text: '<i class="fa fa-plus-circle"></i> Add',
            action: function ( e, dt, node, config ) {
              loadForm(null, "add"); 

            }
          },
          {
            text: '<i class="fa fa-pencil-square-o"></i> Edit',
            action: function ( e, dt, node, config ) {
              var rowData = dt.rows(".selected").data()[0];  
              
              if(rowData == null)
              {
                alert('Please Select Data !!');
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
                alert('Please Select Data !!');
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
          },
          {
            text: '<i class="fa fa-link"></i> Get REST API',
            action: function ( e, dt, node, config ) {
              var rowData = dt.rows(".selected").data()[0];  
              
              if(rowData == null)
              {
                alert('Please Select Data !!');
              }else{
                $.ajax({
                  url: "./lib/base/form_modal_act_api.php?type=getRestAPI&id=" + rowData.id,
                  dataType: 'json',
                  success: function( msg ) {
                      $('#ModalText<?php echo $formName ?>').text("REST API Sampang");
                      $('.formModal<?php echo $formName ?>').html(msg.html);
                      $('.actFilter').css('display', 'none');
                      $('.formSubmit').css('display', 'none');
                      $('#Modal<?php echo $formName ?>').modal('show');
                  },
                  error: function(err){
                    console.log(err);
                    popup('error', err.responseText, '');
                  }
                }); 
              }
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

$('.formSubmit').on('click', function(){
   var form = $('.formModal<?php echo $formName ?>').serialize();
   
   $.ajax({
      method: "POST",
      url: "./lib/base/save_data_with_date.php",
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



$('.actFilter2').on('click', function(){

  var query = [];
  var notFil = ['0', ''];
  <?php
  $getValFil = $adeQ->select($adeQ->prepare("select * from core_filter where idform=%s", $f));
  foreach($getValFil as $val)
  {
    if($val['logic'] == 'like')
    {
      echo "var value = \"'%\" + $('.fil$val[name_field]').val() + \"%'\";";
      echo "
      if(!notFil.includes($('.fil$val[name_field]').val()))
      {
        query.push('lower($val[name_field]) $val[logic] lower(' + value + ')');
      }";
    }else{
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



  table.draw();
  $('#Modal<?php echo $formName ?>').modal('toggle');

})

$('.resetFilter').on('click', function(){

  $('.queryFilter').val('');
  table.draw();
})


$(".commitField").on("click", function(){
  var rowData = table.rows(".selected").data()[0];  

  if(rowData == null)
  {
    alert('Please Select Data !!');
  }else{
    var ckRoleMenu = [];
    $.each($(".rolemenu:checked"), function(){
        ckRoleMenu.push($(this).val());
    });

    
    ckRoleMenu = ckRoleMenu.join('~');
    
    $.ajax({
      url : "./lib/base/rest_api.php",
      method: "POST",
      data : "type=saveAccessField&id="+rowData.id+"&field="+ckRoleMenu,
      dataType: "json",
      success: function(msg){
        popup(msg.status, msg.massage, '');
      },
      error:function(e){
        popup('error', 'Error System', '');
        console.log(e);
      }
    });
  }
  
})


$("#<?php echo $formName ?> tbody").on('click', 'tr', function () {
    var id = table.rows( $(this) ).data()[0].id;
    getFieldApi(id);
}).click(); 




function getFieldApi(id, param){

$(".listMenu").html('<div class="overlay" style="font-size: 20px;text-align:center;"><i class="fa fa-refresh fa-spin"></i></div>');

$.ajax({
    method: "POST",
    url: "./lib/base/rest_api.php",
    data : "type=getField&id=" + id + "&param=" + param,
    dataType: "json",
    success: function( msg ) {
      
      var list;
      $.each(msg, function(index, value){
          var check = (value.check == "0") ? '' : 'checked';
          list += '<tr><td><input value="'+value.field+'" type="checkbox" '+check+' class="flat-red rolemenu"></td><td>'+value.field+'</td></tr>';
      });
      $(".listMenu").html(list);

      $('.rolemenu').iCheck({
        checkboxClass: 'icheckbox_flat-green',
        radioClass: 'iradio_flat-green'
      });

      $('.rolemenuAll').iCheck({
        checkboxClass: 'icheckbox_flat-green',
        radioClass: 'iradio_flat-green'
      });

      $('.rolemenuAll').on('ifUnchecked', function (event) {
          $('.rolemenu').iCheck('uncheck');
      });

      $('.rolemenuAll').on('ifChecked', function (event) {
          $('.rolemenu').iCheck('check');
      });
      
      
      
    },
      error: function(err){
        console.log(err);
      }
  }); 
}



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
    url: "./lib/base/form_modal_act_with_date.php?f=<?php echo $f ?>&type="+type+val,
    success: function( msg ) {

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


        $("[data-mask]").inputmask();


        $('#Modal<?php echo $formName ?>').modal('show');
    },
      error: function(err){
        console.log(err);
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
    url: "./lib/base/form_modal_act_with_date.php?f=<?php echo $f ?>&type="+type+val,
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
</script>