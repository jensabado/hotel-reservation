<?php 
use App\Libraries\CIAuth; 

$admin = CIAuth::admin();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title><?= isset($page_title) ? $page_title : 'ES Admin' ?> </title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="<?= base_url('admin/vendors/feather/feather.css') ?>">
  <link rel="stylesheet" href="<?= base_url('admin/vendors/mdi/css/materialdesignicons.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('admin/vendors/ti-icons/css/themify-icons.css') ?>">
  <link rel="stylesheet" href="<?= base_url('admin/vendors/typicons/typicons.css') ?>">
  <link rel="stylesheet" href="<?= base_url('admin/vendors/simple-line-icons/css/simple-line-icons.css') ?>">
  <link rel="stylesheet" href="<?= base_url('admin/vendors/css/vendor.bundle.base.css') ?>">
  <!-- endinject -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="<?= base_url('admin/css/vertical-layout-light/style.css') ?>">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <!-- endinject -->
  <!-- <link rel="shortcut icon" href="images/favicon.png" /> -->
</head>

<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
        <div class="me-3">
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
            <span class="icon-menu"></span>
          </button>
        </div>
        <div>
          <a class="navbar-brand brand-logo" href="index.html" style="font-weight: 900;">
            ES <span class="text-black">Admin</span>
          </a>
          <a class="navbar-brand brand-logo-mini" href="index.html" style="font-weight: 900;">
            ES
          </a>
        </div>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-top">
        <ul class="navbar-nav">
          <li class="nav-item font-weight-semibold d-none d-lg-block ms-0">
            <h1 class="welcome-text"><span class="text-black fw-bold"><?= isset($header) ? $header : '' ?></span></h1>
          </li>
        </ul>
        <ul class="navbar-nav ms-auto">
          <li class="nav-item dropdown d-none d-lg-block user-dropdown">
            <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
              <img class="img-xs rounded-circle" src="<?= base_url('admin/images/faces/face8.jpg') ?>" alt="Profile image"> </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
              <div class="dropdown-header text-center">
                <img class="img-md rounded-circle" src="<?= base_url('admin/images/faces/face8.jpg') ?>" alt="Profile image">
                <p class="mb-1 mt-3 font-weight-semibold"><?= $admin->username ?></p>
                <p class="fw-light text-muted mb-0"><?= $admin->email ?></p>
              </div>
              <a class="dropdown-item" href="<?= route_to('admin_logout') ?>"><i
                  class="dropdown-item-icon mdi mdi-power text-primary me-2"></i>Sign Out</a>
            </div>
          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
          data-bs-toggle="offcanvas">
          <span class="mdi mdi-menu"></span>
        </button>
      </div>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="<?= route_to('admin.home') ?>">
              <i class="mdi mdi-grid-large menu-icon"></i>
              <span class="menu-title">Dashboard</span>
            </a>
          </li>
          <li class="nav-item nav-category">Room</li>
          <li class="nav-item">
            <a class="nav-link" href="<?= route_to('admin.room_types') ?>">
              <i class="mdi mdi-room-service menu-icon"></i>
              <span class="menu-title">Room Types</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= route_to('admin.room') ?>">
              <i class="mdi mdi-room-service-outline menu-icon"></i>
              <span class="menu-title">Room</span>
            </a>
          </li>
        </ul>
      </nav>
      <!-- partial -->
      <div class="main-panel">
        <?php $this->renderSection('content') ?>
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

  <!-- plugins:js -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="<?= base_url('admin/vendors/js/vendor.bundle.base.js') ?>"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  <script src="<?= base_url('admin/vendors/chart.js/Chart.min.js') ?>"></script>
  <script src="<?= base_url('admin/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') ?>"></script>
  <script src="<?= base_url('admin/vendors/progressbar.js/progressbar.min.js') ?>"></script>

  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="<?= base_url('admin/js/off-canvas.js') ?>"></script>
  <script src="<?= base_url('admin/js/hoverable-collapse.js') ?>"></script>
  <script src="<?= base_url('admin/js/template.js') ?>"></script>
  <script src="<?= base_url('admin/js/settings.js') ?>"></script>
  <script src="<?= base_url('admin/js/todolist.js') ?>"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="<?= base_url('admin/js/jquery.cookie.js') ?>" type="text/javascript"></script>
  <script src="<?= base_url('admin/js/dashboard.js') ?>"></script>
  <script src="<?= base_url('admin/js/Chart.roundedBarCharts.js') ?>"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- End custom js for this page-->
  <?php $this->renderSection('script') ?>
</body>

</html>