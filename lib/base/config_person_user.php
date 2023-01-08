<?php

require "../../config.php";
require "db.php";
require "security_login_global.php";

$qSession = $adeQ->select($adeQ->prepare(
  "
    select u.* from core_user u where u.id=%d
  ",
  $_SESSION['userUniqId']
));




?>
<section class="content-header">
  <h1>
    Pengaturan User
  </h1>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-3">

      <div class="box box-primary">
        <div class="box-body box-profile text-center">
          <img class="profile-user-img img-responsive img-circle" src="<?php echo $dir ?>assets/img/admin.jpg" alt="User profile picture">

          <h3 class="profile-username text-center"><?php echo $qSession[0]['username'] ?></h3>

        </div>
        <!-- /.box-body -->
      </div>

    </div>

    <div class="col-md-9">
      <div class="box">
        <!-- /.box-header -->
        <div class="box-body">
          <div class="col-md-12">

            <form class="formPersonusers" action="javascript:void(0);" method="post">

              <div class="form-group grpuserid">
                <label for="userid">User ID <span style="color:red">*</span></label>
                <input type="text" name="userid" disabled class="form-control" placeholder="Enter userid" value="<?php echo $qSession[0]['userid'] ?>">
              </div>

              <div class="form-group grpusername">
                <label for="username">Username <span style="color:red">*</span></label>
                <input type="text" name="username" class="form-control username" placeholder="Enter username" value="<?php echo $qSession[0]['username'] ?>">
                <span class="help-block errusername"></span>
              </div>

              <div class="form-group grpemail">
                <label for="email">Email </label>
                <input type="email" name="email" disabled class="form-control email" placeholder="Enter email" value="<?php echo $qSession[0]['email'] ?>">
                <span class="help-block erremail"></span>
              </div>

              <div class="form-group grppass">
                <label for="userpass">Password <span style="color:red">*</span></label>
                <input type="password" name="pass" class="form-control pass" placeholder="Enter Password">
                <span class="help-block errpass"></span>
              </div>

              <div class="form-group grprepass">
                <label for="userpass">Ulangi Password <span style="color:red">*</span></label>
                <input type="password" name="repass" class="form-control repass" placeholder="Enter Password">
                <span class="help-block errrepass"></span>
              </div>

              <div class="form-group grpfirstname">
                <label for="firstname">First Name </label>
                <input type="text" name="firstname" class="form-control firstname" placeholder="Enter firstname" value="<?php echo $qSession[0]['firstname'] ?>">
                <span class="help-block errfirstname"></span>
              </div>

              <div class="form-group grplastname">
                <label for="lastname">Last Name </label>
                <input type="text" name="lastname" class="form-control lastname" placeholder="Enter lastname" value="<?php echo $qSession[0]['lastname'] ?>">
                <span class="help-block errlastname"></span>
              </div>

              <button type="button" class="btn btn-success formSubmit">Change</button>
            </form>

          </div>
        </div>
        <!-- /.box-body -->
      </div>
    </div>
  </div>
</section>



<script>
  $("[data-mask]").inputmask();

  $('.formSubmit').on('click', function() {

    var form = $('.formPersonusers').serialize();
    console.log(form);
    $.ajax({
      method: "POST",
      url: "./lib/base/save_data_user_person.php",
      data: form,
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
          popup('success', msg.msg, '');
        }
      },
      error: function(err) {
        console.log(err);
        popup('error', err.responseText, '');
      }
    });
  })
</script>