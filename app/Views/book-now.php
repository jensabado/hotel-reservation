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
    <div class="row mb-5 mb_30 justify-content-center">
      <div class="col-lg-6">
        <img
          src="<?= !empty($room_data['thumbnail']) ? base_url('room-type-image/' . $room_data['thumbnail']) : base_url('room-type-image/No-Image-Placeholder.svg.png') ?>"
          style="height: 100%; max-width: 100%; width: 700px; object-fit: cover; object-position: center !important;">
      </div>
      <div class="col-lg-6 mt-5 mt-lg-0">
        <div class="row w-100">
          <div class="card p-3 w-100">
            <form action="" id="book_form">
              <div class="row pb-5 pb-lg-0">
                <div class="col-12">
                  <div class="form-group">
                    <label for="">First Name</label>
                    <input type="text" name="firstname" id="firstname" class="form-control book_form_field"
                      placeholder="Enter First Name">
                    <span class="text-danger errors" style="font-size: 13px" id="firstname_error"></span>
                  </div>
                  <div class="form-group">
                    <label for="">Middle Name</label>
                    <input type="text" name="middlename" id="middlename" class="form-control book_form_field"
                      placeholder="Enter Middle Name">
                    <span class="text-danger errors" style="font-size: 13px" id="middlename_error"></span>
                  </div>
                  <div class="form-group">
                    <label for="">Last Name</label>
                    <input type="text" name="lastname" id="lastname" class="form-control book_form_field"
                      placeholder="Enter Last Name">
                    <span class="text-danger errors" style="font-size: 13px" id="lastname_error"></span>
                  </div>
                  <div class="form-group">
                    <label for="">Address</label>
                    <textarea name="address" id="address" class="form-control book_form_field"
                      style="resize: none; height: 90px;" placeholder="Enter Complete Address"></textarea>
                    <span class="text-danger errors" style="font-size: 13px" id="address_error"></span>
                  </div>
                  <div class="form-group">
                    <label for="">Contact No.</label>
                    <input type="text" name="contact" id="contact" class="form-control book_form_field"
                      placeholder="Enter Contact No">
                    <span class="text-danger errors" style="font-size: 13px" id="contact_error"></span>
                  </div>
                  <div class="form-group">
                    <label for="">Check-in/Reserve Date</label>
                    <input type="date" name="reserved_date" id="reserved_date" class="form-control book_form_field">
                    <span class="text-danger errors" style="font-size: 13px" id="reserved_date_error"></span>
                  </div>
                  <div class="form-group">
                    <button type="submit" class="btn theme_btn button_hover">Book Now</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!--================ Accomodation Area  =================-->
<?php include('layout/include/footer.php') ?>
<?= $this->endSection(); ?>

<?= $this->section('script') ?>
<script>
$(document).ready(function() {
  // Get the current date and add one day to it
  var tomorrow = new Date();
  tomorrow.setDate(tomorrow.getDate() + 1);

  // Format the date in yyyy-mm-dd format
  var tomorrowFormatted = tomorrow.toISOString().split('T')[0];

  // Set the min attribute to tomorrow's date
  $('#reserved_date').attr('min', tomorrowFormatted);

  // Handle the date input change event to validate selected dates
  $('#reserved_date').on('change', function() {
    var selectedDate = $(this).val();

    // Compare the selected date with tomorrow's date
    if (selectedDate < tomorrowFormatted) {
      alert('Please select a date starting from tomorrow.');
      $(this).val(''); // Clear the input value
    }
  });

  // submit form
  $('#book_form').on('submit', function(e) {
    e.preventDefault();

    console.log('submit');

    const form = new FormData(this);
    form.append('id', '<?= $room_data['id'] ?>')

    $.ajax({
      type: "POST",
      url: "<?= route_to('user.book.submit'); ?>",
      data: form,
      processData: false,
      contentType: false,
      cache: false,
      beforeSend: function() {
        $('body').addClass('loading');
        $('.preloader-container').removeClass('hide');
      },
      complete:function() {
        $('body').removeClass('loading');
        $('.preloader-container').addClass('hide');
      },
      success: function(response) {
        if (response.status === 'success') {
          localStorage.setItem('message', response.message);
          window.location.href = '/';
        } else if (response.status === 'error') {
          handleValidationErrors(response.message);
        } else if (response.status === 'error_alert') {
          showAlert('error', 'Failed', response.message);
        } else {
          showAlert('error', 'Failed!', 'Something went wrong.');
        }
      },
      error: function(xhr, status, error) {
        console.error(xhr.responseText);
      }
    });
  });

  function resetForm() {
    $('.errors').text('');
    $('.book_form_field').removeClass('border-danger');
  }

  function handleValidationErrors(errorMessages) {
    resetForm();

    $.each(errorMessages, function(field, error_message) {
      $('#' + field + '_error').text(error_message);
      $('#' + field).addClass('border-danger');
    });
  }
})
</script>
<?= $this->endSection() ?>