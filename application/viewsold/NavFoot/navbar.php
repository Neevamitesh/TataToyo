<?php
  $type = !isset($type)? null : $type;
  $title = !isset($title)? "Home" : $title;
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <title><?=  !isset($title)? "Home" : $title; ?></title>
  <!--favicon-->
  <link rel="icon" href="<?= base_url(); ?>assets/images/nst.ico" type="image/x-icon">
  <!-- simplebar CSS-->
  <link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/notifications/css/lobibox.min.css"/>
  <link href="<?= base_url(); ?>assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
  <!-- Bootstrap core CSS-->
  <link href="<?= base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" />
  <link href="<?= base_url(); ?>assets/plugins/bootstrap-datatable/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css">
  <!-- <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap4.min.css">         -->
  <link href="<?= base_url(); ?>assets/plugins/bootstrap-datatable/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css">
  <link href="<?= base_url(); ?>assets/plugins/switchery/css/switchery.min.css" rel="stylesheet" />
  <link href="<?= base_url(); ?>assets/plugins/bootstrap-switch/bootstrap-switch.min.css" rel="stylesheet">
  <!-- animate CSS-->
  <link href="<?= base_url(); ?>assets/css/animate.css" rel="stylesheet" type="text/css" />
  <!-- Icons CSS-->
  <link href="<?= base_url(); ?>assets/css/icons.css" rel="stylesheet" type="text/css" />
  <!-- Sidebar CSS-->
  <link href="<?= base_url(); ?>assets/css/sidebar-menu.css" rel="stylesheet" />
  <!-- Custom Style-->
  <link href="<?= base_url(); ?>assets/css/app-style.css" rel="stylesheet" />
  <script src="<?= base_url(); ?>assets/js/jquery.min.js"></script>
  <link rel="stylesheet" href="<?= base_url('assets/css/select.css');?>">
  <style>
    .hidden {
      display: none;
    }

    .full-height {
      height: -webkit-fill-available;
    }
    td, th{
      text-align:center;
    }
  </style>
  <script>
   function cust_rm(t){
        $(t).remove();
    }
    var base;
    $(function(){
      base=$('#baseurl').html();  
      
      $(document).on('click','.cust-rm',function(){
          Remover($(this).parents('.main'));
      });
      function Remover(row){
          $(row).remove();
      }
    })
  </script>
</head>

<body style="overflow-y:auto;">
<div class="hidden" id="baseurl"><?= base_url()?></div>
  <!-- Start wrapper-->
  <div id="wrapper">

    <!--Start sidebar-wrapper-->
    <div id="sidebar-wrapper" data-simplebar="init" data-simplebar-auto-hide="true" >
      <div class="simplebar-track vertical" style="visibility: visible;"><div class="simplebar-scrollbar" style="visibility: visible; top: 0px; height: 619px;"></div></div>
      <div class="simplebar-track horizontal" style="visibility: hidden;"><div class="simplebar-scrollbar"></div></div>
      <div class="simplebar-scroll-content" style="padding-right: 17px; margin-bottom: -34px;">
        <div class="simplebar-content" style="padding-bottom: 17px; margin-right: -17px;">
          <div class="brand-logo">
            <a href="<?= base_url(); ?>Home">
              <img src="<?= base_url(); ?>assets/images/neevsoftlogo.jpg" class="" alt="logo icon" width="100%" >
              
            </a>
          </div>

          <ul class="sidebar-menu do-nicescrol">
            <li id="dashboard">
              <a href="<?= base_url(); ?>Home" class="waves-effect text-dark h6 mb-0"><i class="fa fa-home"></i><span>Dashboard</span>
              </a>
            </li>
            <?php
              if($role == "SuperAdmin" || $role[0] == "SuperAdmin"){
            ?>
            <li class="">
              <a href="javaScript:void();" class="waves-effect">
                <i class="icon-diamond"></i><span>Consignee Master</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="sidebar-submenu">
                <li><a href="<?= base_url("Consignee/Add");?>"><i class="fa fa-long-arrow-right"></i> Add Consignee</a></li>
                <li><a href="<?= base_url("Consignee/Edit");?>"><i class="fa fa-long-arrow-right"></i> Edit Consignee</a></li>
              </ul>
            </li>
            <li id="product">
              <a href="<?= base_url(); ?>Products" class="waves-effect text-dark h6 mb-0"><i class="fa fa-home"></i><span>Product Master</span>
              </a>
            </li>
            <!-- <li id="product">
              <a href="javaScript:void();" class="waves-effect">
                <i class="icon-diamond"></i><span>Product Master</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="sidebar-submenu">
                <li id="category"><a href="<?= base_url(); ?>Category"><i class="fa fa-long-arrow-right"></i> Category</a></li>
                <li id="addproduct"><a href="<?= base_url(); ?>Products"><i class="fa fa-long-arrow-right"></i> Product</a></li>
              </ul>
            </li> -->
            
            <li class="">
              <a href="javaScript:void();" class="waves-effect">
                <i class="icon-diamond"></i><span>Consignee Label</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="sidebar-submenu">
                <li><a href="<?= base_url("Consignee/PRNUplaod");?>"><i class="fa fa-long-arrow-right"></i> Add Label</a></li>
                <li><a href="<?= base_url("Consignee/PRNEdit");?>"><i class="fa fa-long-arrow-right"></i> Replace Label</a></li>
              </ul>
            </li>

            <li class="reports">
              <a href="javaScript:void();" class="waves-effect">
                <i class="icon-diamond"></i><span>Reports</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="sidebar-submenu">
                
                <li class="userreport"><a href="<?= base_url("Reports/UserReport");?>"><i class="fa fa-long-arrow-right"></i> User Report</a></li>
                <li class="productreport"><a href="<?= base_url("Reports/ProductReport");?>"><i class="fa fa-long-arrow-right"></i> Product Report</a></li>
                <li class="consigneereport"><a href="<?= base_url("Reports/Consignee");?>"><i class="fa fa-long-arrow-right"></i> Consignee Report</a></li>
                <li class="locationreport"><a href="<?= base_url("Reports/LocationMaster");?>"><i class="fa fa-long-arrow-right"></i> Location Master Report</a></li>
                <li class="batchreport"><a href="<?= base_url("Reports/BatchReport");?>"><i class="fa fa-long-arrow-right"></i> Batch Generated Report</a></li>
                <li class="userlogreport"><a href="<?= base_url("Reports/UserLog");?>"><i class="fa fa-long-arrow-right"></i> User Log Report</a></li>
              </ul>
            </li>

            <li id="locationmaster">
              <a href="<?= base_url(); ?>Location" class="waves-effect text-dark h6 mb-0"><i class="fa fa-map-marker"></i><span>Location Master</span>
              </a>
            </li>
            <li id="usermaster">
              <a href="<?= base_url(); ?>User" class="waves-effect text-dark h6 mb-0"><i class="fa fa-user"></i><span>User Master</span>
              </a>
            </li>

            <li class="">
              <a href="javaScript:void();" class="waves-effect">
                <i class="icon-diamond"></i><span>Additional Label</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="sidebar-submenu">
                <li><a href="<?= base_url("SpeLabel/index");?>"><i class="fa fa-long-arrow-right"></i> Add Label</a></li>
                <li><a href="<?= base_url("SpeLabel/Edit");?>"><i class="fa fa-long-arrow-right"></i> Replace Label</a></li>
              </ul>
            </li>

            <li id="addons">
              <a href="<?= base_url('AddOns'); ?>" class="waves-effect text-dark h6 mb-0"><i class="fa fa-paperclip"></i><span>Label Option(s)</span>
              </a>
            </li>

            <li class="">
              <a href="javaScript:void();" class="waves-effect">
                <i class="icon-diamond"></i><span>Preview Label</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="sidebar-submenu">
                <li><a href="<?= base_url("Preview/index");?>"><i class="fa fa-long-arrow-right"></i> Add Preview Label</a></li>
                <li><a href="<?= base_url("Preview/RepalceFile");?>"><i class="fa fa-long-arrow-right"></i> Replace Preview Label</a></li>
              </ul>
            </li>
            <?php
              }
            ?>
		
            <li class="">
              <a href="javaScript:void();" class="waves-effect">
                <i class="icon-diamond"></i><span>Print Label</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="sidebar-submenu">
                <li><a href="<?= base_url("Consignee/CreateBatch");?>"><i class="fa fa-long-arrow-right"></i> Create Batch</a></li>
                <!-- <li><a href="<?= base_url("Consignee/RePrint");?>"><i class="fa fa-long-arrow-right"></i> Re-Print Label</a></li> -->
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <!--End sidebar-wrapper-->

    <!--Start topbar header-->
    <header class="topbar-nav">
      <nav class="navbar navbar-expand fixed-top bg-white">
        <ul class="navbar-nav mr-auto align-items-center">
          <li class="nav-item">
            <a class="nav-link toggle-menu" href="javascript:void();">
              <i class="icon-menu menu-icon"></i>
            </a>
          </li>
          <li class="nav-item language">
            <a class="nav-link waves-effect ml-3 mt-1 text-dark h6" href="<?= base_url(); ?>">
              <i class="fa fa-home mr-1"></i><span>Home</span>
            </a>
          </li>

        </ul>
        <?php
          if($isexpiring == 1){
            ?>
                <ul class="navbar-nav align-items-center">
                  <li class="nav-item"><a class="nav-link" href="#">
                    <h4><b>Please renew this application (only <?= $days; ?> days left) !</b></h4>
                  </a></li>
              </ul>
            <?php
          }
        ?>
        <ul class="navbar-nav align-items-center right-nav-link">
          <li class="nav-item">
            <?php
            if (!empty($user_id)) {
              ?>
              <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" data-toggle="dropdown" href="#">
                <span class="user-profile"><i class="fa fa-user-o"></i></span>
              </a>
              <ul class="dropdown-menu dropdown-menu-right animated fadeIn">
                <li class="dropdown-item user-details">
                  <a href="javaScript:void();">
                    <div class="media">
                      <div class="avatar"><i class="fa fa-2x fa-user-o align-self-start mr-3"></i> </div>
                      <div class="media-body">
                        <h6 class="mt-2 user-title"><?= $Name; ?></h6>
                      </div>
                    </div>
                  </a>
                </li>
                <li class="dropdown-divider"></li>
                <!-- <li class="dropdown-item"><i class="icon-envelope mr-2"></i> Profile</li>
                <li class="dropdown-divider"></li>
                <li class="dropdown-item"><i class="icon-wallet mr-2"></i> Account</li>
                <li class="dropdown-divider"></li>
                <li class="dropdown-item"><i class="icon-settings mr-2"></i> Setting</li>
                <li class="dropdown-divider"></li> -->
                <li class="dropdown-item"><i class="icon-power mr-2"></i> <a href="<?= base_url(); ?>Auth/Logout">Logout</a></li>
              </ul>
            <?php
          } else {
            ?>
              <a class="nav-link waves-effect" href="<?= base_url(); ?>"><i class="fa fa-power-off"></i> Login</a>
            <?php
          }
          ?>
          </li>
        </ul>
      </nav>
    </header>
    <!--End topbar header-->
    <div class="content-wrapper">
        <div class="container-fluid">
            <div class="row pt-2 pb-2">
                <div class="col-sm-9">
                    <h4 class="page-title"><?= $title; ?></h4>
                    <!-- <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javaScript:void();">DashRock</a></li>
                        <li class="breadcrumb-item"><a href="javaScript:void();">UI Elements</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Typography</li>
                    </ol> -->
                </div>
                <!-- <div class="col-sm-3">
                    <div class="btn-group float-sm-right">
                        <button type="button" class="btn btn-outline-primary waves-effect waves-light"><i class="fa fa-cog mr-1"></i> Setting</button>
                        <button type="button" class="btn btn-outline-primary dropdown-toggle dropdown-toggle-split waves-effect waves-light" data-toggle="dropdown">
                            <span class="caret"></span>
                        </button>
                        <div class="dropdown-menu">
                            <a href="javaScript:void();" class="dropdown-item">Action</a>
                            <a href="javaScript:void();" class="dropdown-item">Another action</a>
                            <a href="javaScript:void();" class="dropdown-item">Something else here</a>
                            <div class="dropdown-divider"></div>
                            <a href="javaScript:void();" class="dropdown-item">Separated link</a>
                        </div>
                    </div>
                </div> -->
            </div>