<?php
use App\Libraries\CIAuth;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- <link rel="icon" href="user/image/favicon.png" type="user/image/png"> -->
  <title><?= isset($page_title) ? $page_title : 'EasyStay Reservations' ?></title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="<?= base_url('user/css/bootstrap.css') ?>">
  <link rel="stylesheet" href="<?= base_url('user/vendors/linericon/style.css') ?>">
  <link rel="stylesheet" href="<?= base_url('user/css/font-awesome.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('user/vendors/owl-carousel/owl.carousel.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('user/vendors/bootstrap-datepicker/bootstrap-datetimepicker.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('user/vendors/nice-select/css/nice-select.css') ?>">
  <link rel="stylesheet" href="<?= base_url('user/vendors/owl-carousel/owl.carousel.min.css') ?>">
  <!-- main css -->
  <link rel="stylesheet" href="<?= base_url('user/css/style.css') ?>">
  <link rel="stylesheet" href="<?= base_url('user/css/responsive.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
</head>

<body>
  <!--================Header Area =================-->
  <header class="header_area">
    <div class="container">
      <nav class="navbar navbar-expand-lg navbar-light">
        <!-- Brand and toggle get grouped for better mobile display -->
        <a class="navbar-brand logo_h font-weight-bold" href="/">
          <!-- <img src="user/image/Logo.png" alt=""> -->
          EasyStay Reservations
        </a>
        <div class="hamburger">
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <?php if(CIAuth::check()) { ?>
          <div class="custom-dropdown d-flex d-lg-none">
            <i class="fa-solid fa-user ml-md-3 mb-3 mb-lg-0 dropdown-button" id="dropdown-button"></i>
            <ul class="dropdown-list">
              <li><i class="fa-solid fa-user"></i>Profile</li>
              <li><i class="fa-solid fa-hotel"></i>Reservation</li>
              <li onclick="location.href='<?= route_to('user_logout') ?>'"><i
                  class="fa-solid fa-right-from-bracket"></i>Logout</li>
            </ul>
          </div>
          <?php } else { ?>
            <a href="login" class="btn theme_btn button_hover ml-md-3 mb-3 mb-lg-0 d-flex d-lg-none" style="margin-top: 10px;">Login</a>
          <?php } ?>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse offset" id="navbarSupportedContent">
          <ul class="nav navbar-nav menu_nav ml-auto">
            <li class="nav-item active"><a class="nav-link" href="/">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="about.html">About us</a></li>
            <li class="nav-item"><a class="nav-link" href="accomodation">Accomodation</a></li>
            <li class="nav-item"><a class="nav-link" href="gallery.html">Gallery</a></li>
            <!-- <li class="nav-item submenu dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button"
                                aria-haspopup="true" aria-expanded="false">Blog</a>
                            <ul class="dropdown-menu">
                                <li class="nav-item"><a class="nav-link" href="blog.html">Blog</a></li>
                                <li class="nav-item"><a class="nav-link" href="blog-single.html">Blog Details</a></li>
                            </ul>
                        </li> -->
            <li class="nav-item"><a class="nav-link" href="contact.html">Contact</a></li>
          </ul>
          <?php if(CIAuth::check()) { ?>
          <div class="custom-dropdown d-none d-lg-flex ">
            <i class="fa-solid fa-user ml-md-3 mb-3 mb-lg-0 dropdown-button" id="dropdown-button"
              style="margin-top: unset;"></i>
            <ul class="dropdown-list">
              <li><i class="fa-solid fa-user"></i>Profile</li>
              <li><i class="fa-solid fa-hotel"></i>Reservation</li>
              <li onclick="location.href='<?= route_to('user_logout') ?>'"><i
                  class="fa-solid fa-right-from-bracket"></i>Logout</li>
            </ul>
          </div>
          <?php } else {; ?>
          <a href="login" class="btn theme_btn button_hover ml-md-3 mb-3 mb-lg-0 d-none d-lg-flex">Login</a>
          <?php } ?>
        </div>
      </nav>
    </div>
  </header>
  <!--================Header Area =================-->

  <?= $this->renderSection('content') ?>


  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="<?= base_url('user/js/popper.js') ?>"></script>
  <script src="<?= base_url('user/js/bootstrap.min.js') ?>"></script>
  <script src="<?= base_url('user/vendors/owl-carousel/owl.carousel.min.js') ?>"></script>
  <script src="<?= base_url('user/js/jquery.ajaxchimp.min.js') ?>"></script>
  <script src="<?= base_url('user/js/mail-script.js') ?>"></script>
  <script src="<?= base_url('user/vendors/bootstrap-datepicker/bootstrap-datetimepicker.min.js') ?>"></script>
  <script src="<?= base_url('user/vendors/nice-select/user/js/jquery.nice-select.js') ?>"></script>
  <script src="<?= base_url('user/js/stellar.js') ?>"></script>
  <script src="<?= base_url('user/vendors/lightbox/simpleLightbox.min.js') ?>"></script>
  <script src="<?= base_url('user/js/custom.js') ?>"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
  $(document).ready(function() {
    // Toggle the dropdown list when a button is clicked
    $(".dropdown-button").click(function() {
      $(this).closest(".custom-dropdown").toggleClass("open");
    });

    // Handle the click event on a dropdown item
    $(".dropdown-list li").click(function() {
      var selectedColor = $(this).data("color");
      $(this).closest(".custom-dropdown").find(".dropdown-button").text(selectedColor);
      $(this).closest(".custom-dropdown").removeClass("open");
    });

    // Close the dropdown if the user clicks outside of it
    $(document).click(function(event) {
      $(".custom-dropdown").each(function() {
        if (!$(event.target).closest(this).length) {
          $(this).removeClass("open");
        }
      });
    });

    $(window).on('resize', function() {
      $(".custom-dropdown").removeClass("open");
    });
  });
  </script>

  <?= $this->renderSection('script') ?>
</body>

</html>