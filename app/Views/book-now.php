<?= $this->extend('layout/base.php') ?>

<?= $this->section('content') ?>
<!--================Breadcrumb Area =================-->
<section class="breadcrumb_area">
  <div class="overlay bg-parallax" data-stellar-ratio="0.8" data-stellar-vertical-offset="0" data-background=""></div>
  <div class="container">
    <div class="page-cover text-center">
      <h2 class="page-cover-tittle"><?= $room_data['name'] ?></h2>
      <h3 class="text-warning">PHP <?= $room_data['price'] ?></h3>
    </div>
  </div>
</section>
<!--================Breadcrumb Area =================-->

<!--================ Accomodation Area  =================-->
<section class="accomodation_area section_gap">
  <div class="container">
    <div class="section_title text-center">
      <h2 class="title_color">Book Now</h2>
    </div>
    <div class="row mb_30 justify-content-center">
      <div class="col-md-6">
        <img
          src="<?= !empty($room_data['thumbnail']) ? base_url('room-type-image/' . $room_data['thumbnail']) : base_url('room-type-image/No-Image-Placeholder.svg.png') ?>"
          style="height: 100%; max-width: 100%; width: 700px; object-fit: cover; object-position: center !important;">
      </div>
      <div class="col-md-6">
        <div class="row">
          <div class="col-12">
            <div class="card p-3">
              <form action="">
                <div class="row">
                  <div class="col-12">
                    <div class="form-group">
                      <label for="">First Name</label>
                      <input type="text" name="" id="" class="form-control" placeholder="Enter First Name">
                    </div>
                    <div class="form-group">
                      <label for="">Middle Name</label>
                      <input type="text" name="" id="" class="form-control" placeholder="Enter Middle Name">
                    </div>
                    <div class="form-group">
                      <label for="">Last Name</label>
                      <input type="text" name="" id="" class="form-control" placeholder="Enter Last Name">
                    </div>
                    <div class="form-group">
                      <label for="">Address</label>
                      <textarea name="" id="" class="form-control" style="resize: none; height: 90px;"
                        placeholder="Enter Complete Address"></textarea>
                    </div>
                    <div class="form-group">
                      <label for="">Contact No.</label>
                      <input type="text" name="" id="" class="form-control" placeholder="Enter Contact No">
                    </div>
                    <div class="form-group">
                      <label for="">Check-in/Reserve Date</label>
                      <input type="date" name="" id="" class="form-control">
                    </div>
                    <div class="form-group">
                      <button class="btn theme_btn button_hover">Book Now</button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!--================ Accomodation Area  =================-->
<?php include('layout/include/footer.php') ?>
<?= $this->endSection(); ?>