<?=$this->extend('layout/base.php')?>

<?=$this->section('content')?>

<style>
.custom_accomodation_container {
  margin-top: 70px;
}

@media (min-width: 768px) {
  .custom_accomodation_container {
    margin-top: 100px;
  }
}

@media (min-width: 992px) {
  .custom_accomodation_container {
    margin-top: 120px;
  }
}

.btn-outline-secondary {
  /* border: 1px solid #2b3146; */
  background: transparent;
}
</style>

<div class="container custom_accomodation_container">
  <div class="row align-items-center justify-content-center">
    <div class="col-md-5 col-xl-4">
      <div class="card p-3">
        <h3 class="text-dark text-center">Forgot Password</h3>
        <form id="forgot_password_form">
          <div class="row mb-3">
            <div class="col-12">
              <div class="alert alert-success d-none" id="success_alert" role="alert"
                style="font-size: 13px; font-weight: 500;">
              </div>
              <?php if(isset($message)): ?>
              <div class="alert alert-danger" role="alert" id="error_alert" style="font-size: 13px; font-weight: 500;">
                <?= $message ?>
              </div>
              <script>
              setTimeout(function() {
                $('#error_alert').addClass('d-none');
              }, 5000); // 5000 milliseconds (5 seconds)
              </script>
              <?php endif; ?>
              <label style="font-size: 13px;">Email</label>
              <input type="text" name="email" id="email" class="form-control forgot_password_form_field"
                placeholder="Enter Email">
              <span class="text-danger errors" style="font-weight: 500; font-size: 12px;" id="email_error"></span>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <button type="submit" class="btn theme_btn button_hover w-100">Submit</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?=$this->endSection();?>

<?=$this->section('script');?>
<script defer>
$(document).ready(function() {
  $('#forgot_password_form').on('submit', function(e) {
    e.preventDefault();

    const form = new FormData(this);

    $.ajax({
      type: "POST",
      url: "<?= route_to('user.forgot_password.submit'); ?>",
      data: form,
      processData: false,
      contentType: false,
      cache: false,
      success: function(response) {
        console.log(response);
        if (response.status === 'success') {
          $('.errors').text('');
          $('.forgot_password_form_field').removeClass('border-danger');
          $('#email').val('');
          const successAlert = $('#success_alert');
          successAlert.text(response.message).removeClass('d-none');
          setTimeout(() => successAlert.addClass('d-none').text(''), 10000);
        } else if (response.status === 'error') {
          $('.errors').text('');
          $('.forgot_password_form_field').removeClass('border-danger');
          let error_messages = response.message;

          $.each(error_messages, function(field, error_message) {
            $('#' + field + '_error').text(error_message);
            $('#' + field).addClass('border-danger');
          });
        }
      },
      error: function(xhr, status, error) {
        console.error(xhr.responseText);
      }
    })
  })
})
</script>
<?=$this->endSection();?>