<?= $this->extend('layout/base.php') ?>

<?= $this->section('content') ?>
<!--================Breadcrumb Area =================-->
<section class="breadcrumb_area">
  <div class="overlay bg-parallax" data-stellar-ratio="0.8" data-stellar-vertical-offset="0" data-background=""></div>
  <div class="container">
    <div class="page-cover text-center">
      <h2 class="page-cover-tittle">Accomodation</h2>
      <ol class="breadcrumb">
        <li><a href="index.html">Home</a></li>
        <li class="active">Accomodation</li>
      </ol>
    </div>
  </div>
</section>
<!--================Breadcrumb Area =================-->

<!--================ Accomodation Area  =================-->
<section class="accomodation_area section_gap">
  <div class="container">
    <div class="section_title text-center">
      <h2 class="title_color">Hotel Accomodation</h2>
      <p>We all live in an age that belongs to the young at heart. Life that is becoming extremely fast,</p>
    </div>
    <div class="row mb_30 justify-content-center">
      <?php foreach($room_data as $room) : ?>
      <div class="col-lg-3 col-sm-6">
        <div class="accomodation_item text-center">
          <div class="hotel_img">
            <img style="height: 250px; width: 100%; object-fit: cover; object-position: center !important;"
              src="<?= !empty($room['thumbnail']) ? base_url('room-type-image/' . $room['thumbnail']) : base_url('room-type-image/No-Image-Placeholder.svg.png') ?>"
              alt="">
            <a href="/book-now/<?= $room['id'] ?>" class="btn theme_btn button_hover">Book Now</a>
          </div>
          <a href="/book-now/<?= $room['id'] ?>">
            <h4 class="sec_h4"><?= $room['name'] ?></h4>
          </a>
          <h5>P<?= $room['price'] ?></h5>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<!--================ Accomodation Area  =================-->
<?php include('layout/include/footer.php') ?>
<?= $this->endSection(); ?>