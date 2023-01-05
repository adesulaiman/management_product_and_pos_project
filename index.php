<?php
require "config.php";
require "lib/base/db.php";
require "lib/base/security_login.php";

$getGroup = $adeQ->select($adeQ->prepare(
  "
    select u.* from core_user u
    where u.id=%d
  ",
  $_SESSION['userUniqId']
));


?>


<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $title ?></title>
  <link rel="icon" type="image/png" href="<?php echo $dir ?>assets/img/logo.png" />
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <link href="https://fonts.googleapis.com/css2?family=Righteous&display=swap" rel="stylesheet">
  <!--CUstom.css-->
  <link type="text/css" rel="stylesheet" href="<?php echo $dir ?>plugins/custom/custom.css" />
  <!--Import materialize.css-->
  <link type="text/css" rel="stylesheet" href="<?php echo $dir ?>plugins/toater/toastr.min.css" />
  <link rel="stylesheet" href="<?php echo $dir ?>plugins/autocomplate/styles.css">

  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="<?php echo $dir ?>plugins/bootstrap/css/bootstrap.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo $dir ?>plugins/dist/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo $dir ?>plugins/dist/css/ionicons.min.css">
  <!-- Select style -->
  <link rel="stylesheet" href="<?php echo $dir ?>plugins/select2/select2.min.css">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="<?php echo $dir ?>plugins/datepicker/datepicker3.css">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="<?php echo $dir ?>plugins/iCheck/all.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo $dir ?>plugins/dist/css/AdminLTE.min.css?v=1">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo $dir ?>plugins/dist/css/skins/_all-skins.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo $dir ?>plugins/iCheck/flat/blue.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="<?php echo $dir ?>plugins/morris/morris.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="<?php echo $dir ?>plugins/jvectormap/jquery-jvectormap-1.2.2.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="<?php echo $dir ?>plugins/datepicker/datepicker3.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo $dir ?>plugins/datatables/dataTables.bootstrap.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="<?php echo $dir ?>plugins/daterangepicker/daterangepicker.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="<?php echo $dir ?>plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
  <!-- dataTables Select Min Css -->
  <link rel="stylesheet" href="<?php echo $dir ?>plugins/datatables/dataTables.select.min.css">

  <link rel="stylesheet" type="text/css" href="<?php echo $dir ?>plugins/spinner/ladda.min.css">

  <link rel="stylesheet" href="<?php echo $dir ?>plugins/jQuery-Tree-Filter/jquery.treefilter.css">

  <!-- DM Uploadder -->
  <link rel="stylesheet" href="<?php echo $dir ?>plugins/dm-uploader/jquery.dm-uploader.min.css">
  <link rel="stylesheet" href="<?php echo $dir ?>plugins/dm-uploader/main.css">

  <link rel="stylesheet" href="<?php echo $dir ?>plugins/drag-and-drop-placeholders-into-PDF/css/style.css?v=2">

  <!-- jQuery 2.2.3 -->
  <script src="<?php echo $dir ?>plugins/jQuery/jquery-2.2.3.min.js"></script>
  <script type="text/javascript" src="<?php echo $dir ?>plugins/toater/toastr.min.js"></script>
  <script type="text/javascript" src="<?php echo $dir ?>plugins/dist/js/function.js?v=7"></script>
  <!-- Autocomplate -->
  <script src="<?php echo $dir ?>plugins/autocomplate/jquery.mockjax.js"></script>
  <script src="<?php echo $dir ?>plugins/autocomplate/jquery.autocomplete.js"></script>

  <script src="<?php echo $dir ?>plugins/chartjs/Chart.min.js"></script>
  <!-- DataTables -->
  <script src="<?php echo $dir ?>plugins/datatables/jquery.dataTables_1_10_20.min.js"></script>
  <script src="<?php echo $dir ?>plugins/datatables/dataTables.bootstrap.min.js"></script>


  <script src="<?php echo $dir ?>plugins/datatables/dataTables.buttons.min.js"></script>
  <script src="<?php echo $dir ?>plugins/datatables/buttons.flash.min.js"></script>
  <script src="<?php echo $dir ?>plugins/datatables/jszip.min.js"></script>
  <script src="<?php echo $dir ?>plugins/datatables/vfs_fonts.js"></script>
  <script src="<?php echo $dir ?>plugins/datatables/buttons.html5.min.js"></script>
  <script src="<?php echo $dir ?>plugins/datatables/buttons.print.min.js"></script>
  <script src="<?php echo $dir ?>plugins/datatables/dataTables.select.min.js"></script>

  <script src="<?php echo $dir ?>plugins/spinner/spin.min.js"></script>
  <script src="<?php echo $dir ?>plugins/spinner/ladda.min.js"></script>

  <!-- DM Uploader Js -->
  <script src="<?php echo $dir ?>plugins/dm-uploader/jquery.dm-uploader.min.js"></script>
  <script src="<?php echo $dir ?>plugins/dm-uploader/ui-main.js"></script>
  <script src="<?php echo $dir ?>plugins/dm-uploader/demo-ui.js"></script>



  <style>
    th,
    td {
      white-space: nowrap;
    }

    div.dataTables_wrapper {
      margin: 0 auto;
    }

    div.container {
      width: 80%;
    }

    .skin-black .wrapper,
    .skin-black .main-sidebar,
    .skin-black .left-side {
      background-color: #04374e !important;
    }

    .skin-black .sidebar-menu>li.header {
      background: #04374e !important;
    }


    .skin-black .sidebar-menu>li:hover>a,
    .skin-black .sidebar-menu>li.active>a {
      background: #032838 !important;
    }

    .skin-black .sidebar-menu>li>.treeview-menu {
      background: #032838;
    }
  </style>

  <style>
    a.back-to-top {
      display: none;
      width: 60px;
      height: 60px;
      text-indent: -9999px;
      position: fixed;
      z-index: 999;
      right: 20px;
      bottom: 20px;
      background: #27AE61 url("./img/up-arrow.png") no-repeat center 43%;
      -webkit-border-radius: 30px;
      -moz-border-radius: 30px;
      border-radius: 30px;
    }

    #loading-image {
      position: fixed;
      top: 50%;
      left: 50%;
      z-index: 999;
      -webkit-transform: translate(-50%, -50%);
      transform: translate(-50%, -50%);
    }

    #loading {
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
      position: fixed;
      display: block;
      opacity: 0.7;
      background-color: #ffffff;
      z-index: 9999;
      text-align: center;
    }

    .hide {
      display: none;
    }

    [style*="--aspect-ratio"]> :first-child {
      width: 100%;
    }

    [style*="--aspect-ratio"]>img {
      height: auto;
    }

    @supports (--custom:property) {
      [style*="--aspect-ratio"] {
        position: relative;
      }

      [style*="--aspect-ratio"]::before {
        content: "";
        display: block;
        padding-bottom: calc(100% / (var(--aspect-ratio)));
      }

      [style*="--aspect-ratio"]> :first-child {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
      }
    }
  </style>


</head>

<body class="hold-transition skin-black sidebar-mini fixed sidebar-mini-expand-feature">

  <div id="loading" class="hide">
    <img id="loading-image" src="assets/img/progress.gif" style="width: 100px;" alt="Loading..." />
  </div>

  <div class="wrapper">

    <header class="main-header">
      <!-- Logo -->
      <a href="<?php echo $dir ?>" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><img src="<?php echo $dir ?>assets/img/logo.png" style="width: 70%;" /><br></span>
        <!-- logo for regular state and mobile devices -->
        <!-- <span class="logo-lg"><img src="<?php echo $dir ?>assets/img/logo_apps.png" style="width: 60%;"/></span> -->
        <span class="logo-lg">PEMKAB SAMPANG</span>
      </a>
      <!-- Header Navbar: style can be found in header.less -->
      <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button -->
        <a href="#" class="sidebar-toggle hidden-md" data-toggle="offcanvas" role="button">
          <span class="sr-only">Toggle navigation</span>
        </a>

        <h3 style="font-weight:bolder;margin: 8px;text-align: center;"><?php echo $appname ?></h3>

        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">

          </ul>
        </div>
      </nav>
    </header>


    <aside class="main-sidebar">
      <!-- sidebar: style can be found in sidebar.less 
   <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="searchmenu" id="searchmenu" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form> -->
      <!--div class="header" align="center" style="border=1"><font color="#FFFFFF">MAIN NAVIGATION</font></div-->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?php echo $dir ?>assets/img/admin.jpg" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?php echo $_SESSION['username'] ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>

      <ul class="sidebar-menu" style="font-size: 13px;">
        <li class="header">
          <font><b>Main Menus</b></font><button type="button" class="btn btn-box-tool pull-right-container" data-widget="refresh" id="refreshmenu" title="Refresh Menu"><i id="refreshicon" class="fa fa-refresh pull-right"></i></button>
        </li>
      </ul>
      <section class="sidebar">
        <!-- search form -->
        <ul class="sidebar-menu" id="sidebarmenu" style="font-size: 13px; font-weight: bold;" data-widget="tree"></ul>
      </section>

      <!-- /.sidebar -->
    </aside>


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" id="mainContent" style="background: url(<?php echo $dir ?>assets/img/background-login3.jpeg);background-size: cover;">

    </div>

    <!-- jQuery UI 1.11.4 -->
    <script src="<?php echo $dir ?>plugins/dist/js/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
      $.widget.bridge('uibutton', $.ui.button);
    </script>
    <!-- Bootstrap 3.3.6 -->
    <script src="<?php echo $dir ?>plugins/bootstrap/js/bootstrap.min.js"></script>
    <!-- Input Mask 3.3.6 -->
    <script src="<?php echo $dir ?>plugins/input-mask/jquery.inputmask.js"></script>
    <script src="<?php echo $dir ?>plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="<?php echo $dir ?>plugins/input-mask/jquery.inputmask.extensions.js"></script>

    <!-- Morris.js charts -->
    <script src="<?php echo $dir ?>plugins/dist/js/raphael-min.js"></script>
    <!-- Sparkline -->
    <script src="<?php echo $dir ?>plugins/sparkline/jquery.sparkline.min.js"></script>
    <!-- jvectormap -->
    <script src="<?php echo $dir ?>plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="<?php echo $dir ?>plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <!-- jQuery Knob Chart -->
    <script src="<?php echo $dir ?>plugins/knob/jquery.knob.js"></script>
    <!-- daterangepicker -->
    <script src="<?php echo $dir ?>plugins/dist/js/moment.min.js"></script>
    <script src="<?php echo $dir ?>plugins/daterangepicker/daterangepicker.js"></script>
    <!-- datepicker -->
    <script src="<?php echo $dir ?>plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- Bootstrap WYSIHTML5 -->
    <script src="<?php echo $dir ?>plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
    <!-- Slimscroll -->
    <script src="<?php echo $dir ?>plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="<?php echo $dir ?>plugins/fastclick/fastclick.js"></script>
    <!-- bootstrap datepicker -->
    <script src="<?php echo $dir ?>plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo $dir ?>plugins/dist/js/app.min.js"></script>
    <!-- iCheck 1.0.1 -->
    <script src="<?php echo $dir ?>plugins/iCheck/icheck.min.js"></script>

    <!-- Select JS -->
    <script src="<?php echo $dir ?>plugins/select2/select2.full.min.js"></script>

    <!-- AdminLTE for demo purposes -->
    <script src="<?php echo $dir ?>plugins/dist/js/demo.js"></script>

    <script src="<?php echo $dir ?>plugins/dist/js/custom.js?v=1"></script>

    <script src="<?php echo $dir ?>plugins/jQuery-Tree-Filter/jquery.treefilter.js"></script>

    <script src='<?php echo $dir ?>plugins/drag-and-drop-placeholders-into-PDF/js/pdf.min.js'></script>
    <script src='<?php echo $dir ?>plugins/drag-and-drop-placeholders-into-PDF/js/interact.min.js?v=1'></script>
    <!-- <script src='<?php echo $dir ?>plugins/drag-and-drop-placeholders-into-PDF/js/interact.1.2.9.min.js?v=1'></script> -->
    <script src='<?php echo $dir ?>plugins/drag-and-drop-placeholders-into-PDF/js/pdf.worker.min.js'></script>
    <script src='<?php echo $dir ?>plugins/drag-and-drop-placeholders-into-PDF/js/index.js?v=8'></script>

    <!-- Resources -->
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>



    <script>
      function Iterate(data) {
        var i = 0;
        var menus = "";
        jQuery.each(data, function(index, value) {

          if (typeof value == 'object') {
            if (value.parent == '0') {

              if (value.links == '') {
                $parent = $('<li class="treeview" ><a href="#"><i class="fa ' + value.icon + '"></i><span>' + value.menu + '</span><span class="pull-right-container"><i class="fa fa-angle-double-left pull-right-container"></i></span></a><ul class="treeview-menu" data-widget="tree" id="idmenu' + value.idmenus + '"></ul></li>');
                $("#sidebarmenu").append($parent);
              } else {
                if (value.withframe == '1') {
                  $child = $('<li><a href="#" onclick="HtmlPush(\'' + value.links + '\',\'' + value.menu + '\');"><i class="fa ' + value.icon + '"></i>' + value.menu + '</a></li>');
                } else {
                  $child = $('<li><a href="#" onclick="HtmlLoad(\'' + value.links + '\',\'' + value.menu + '\');"><i class="fa ' + value.icon + '"></i>' + value.menu + '</a></li>');
                }

                $("#sidebarmenu").append($child);
              }

            } else {
              if (value.links == '') {
                //alert(value.menu); 
                /*  $child = $('<li><a href="#"><i class="fa '+value.icon+'"></i><span>'+value.menu+'</span></a><ul class="treeview-menu" data-widget="tree" id="idmenu'+value.idmenus+'"></ul></li>');
                 */
                $child = $('<li class="treeview" ><a href="#"><i class="fa ' + value.icon + '"></i><span>' + value.menu + '</span><span class="pull-right-container"><i class="fa fa-angle-double-left pull-right-container"></i></span></a><ul class="treeview-menu" data-widget="tree" id="idmenu' + value.idmenus + '"></ul></li>');

              } else {
                if (value.withframe == '1') {
                  $child = $('<li><a href="#" onclick="HtmlPush(\'' + value.links + '\',\'' + value.menu + '\');"><i class="fa ' + value.icon + '"></i>' + value.menu + '</a></li>');
                } else {
                  $child = $('<li><a href="#" onclick="HtmlLoad(\'' + value.links + '\',\'' + value.menu + '\');"><i class="fa ' + value.icon + '"></i>' + value.menu + '</a></li>');
                }
              }
              $("#idmenu" + value.parent).append($child);
            }
          }

        });

        $parent = $('<li class="logout"><a href="#"><i class="fa  fa-arrow-circle-o-left"></i><span>KELUAR</span><span class="pull-right-container"><i class="fa fa-angle-double-left pull-right-container"></i></span></a></li>');
        $("#sidebarmenu").append($parent);
        //$.AdminLTE.tree('.sidebar');

        $('.logout').on('click', function() {
          $.ajax({
            url: "./lib/base/logout.php",
            dataType: "json",
            success: function(msg) {
              window.location.href = msg;
            }
          })
        })

      };



      function HtmlPush(url) {
        alert("2");
        var html = [];
        html.push('<div class="tabIframeWrapper">');
        html.push('<iframe class="iframetab" frameborder = "0" src="' + url + '">Load Failed?</iframe>');
        html.push('</div>');
        var stringx = '<div class="tabIframeWrapper"><iframe class="iframetab" frameborder = "0" src="' + url + '">Load Failed?</iframe></div>';
        var tinggi = window.innerHeight - ($("#main-header").innerHeight() + $("#main-footer").innerHeight());
        $("#mainContent").empty();
        $("#mainContent").html(stringx);
        $("#mainContent").find("iframe").height($("#mainContent").innerHeight() - 20);
        $("#mainContent").find("iframe").width('100%');
        //alert($("#content-wrapper").innerHeight());
      }

      function HtmlPush(url, title, navigate) {

        var html = [];

        html.push('<div class="tabIframeWrapper">');
        html.push('<iframe class="iframetab" frameborder = "0" src="' + url + '">Load Failed?</iframe>');
        html.push('</div>');
        var stringx = '<div class="box box-default box-success"><div class="box-header with-border"><h3 class="box-title">' + title + '</h3></div><div class="box-body"><iframe class="iframetab" style="bottom:0;top:0;left:0;right:0;margin-top:0px;margin-bottom:0px;margin-right:0px;margin-left:0px;" frameborder = "0" src="' + url + '">Load Failed?</iframe></div></div>';
        var tinggi = window.innerHeight - ($("#main-header").innerHeight() + $("#main-footer").innerHeight());
        //alert(tinggi);
        $("#mainTitle").val(title);
        $("#mainNavigate").val(navigate);

        $("#mainContent").empty();
        $("#mainContent").html(stringx);
        $("#mainContent").find("iframe").height(tinggi);
        $("#mainContent").find("iframe").width('100%');
      }

      function HtmlLoad(url, title, navigate) {
        $("#mainTitle").val(title);
        $("#mainNavigate").val(navigate);
        $("#mainContent").empty();
        $("#mainContent").load(url);
      }

      function HtmlLoad(url, menux) {
        var linksx = '<li><a href="./"><i class="fa fa-dashboard"></i> Home</a> &#187; ' + menux + '</li>';
        //alert(linksx);
        if (typeof ws !== 'undefined') {
          ws.close();
        }
        if (typeof $('.ui-jqdialog') !== 'undefined') {
          $('body').children('.ui-jqdialog').remove();
        }
        $("#mainNavigate").html(linksx);
        $("#mainContent").empty();
        $("#mainContent").html('<div class="overlay" style="font-size: 20px;text-align:center;margin-top: 20%;"><i class="fa fa-refresh fa-spin"></i>  Mohon Tunggu</div>');
        $("#mainContent").load(url.replaceAll(" ", "%20"));
      }


      $('#refreshmenu').on("click", function() {
        $('#sidebarmenu').find('.treeview').empty();
        //$('#refreshicon').attr('class','fa fa-spinner'); 

        $.ajax({
          method: "POST",
          url: "./lib/base/load_menu.php",
          success: function(msg) {
            var jsonstr = msg;
            //console.log(JSON.parse(jsonstr));
            $("#sidebarmenu").html("");
            Iterate(JSON.parse(jsonstr));
          }
        });
      });
      $('#refreshmenu').trigger("click");

      var tree = new treefilter($("#sidebarmenu"), {
        searcher: $("input#searchmenu"),
        expanded: true,
        offsetLeft: 20,
        multiselect: true


      });

      $("[data-mask]").inputmask();
      HtmlLoad('welcome.php', 'Audit Trail');
    </script>



</body>

</html>