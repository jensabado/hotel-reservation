<?=$this->extend('layout/base.php')?>

<?=$this->section('content')?>

<style>
.custom_accommodation_container {
  margin-top: 70px;
}

@media (min-width: 768px) {
  .custom_accommodation_container {
    margin-top: 100px;
  }
}

@media (min-width: 992px) {
  .custom_accommodation_container {
    margin-top: 120px;
  }
}

.btn-outline-secondary {
  background: transparent;
}

.form-label {
  font-size: 13px;
}

.errors {
  font-weight: 500;
  font-size: 12px;
}
</style>

<div class="container custom_accommodation_container">
  <div class="row align-items-center justify-content-center">
    <div class="col-md-5 col-xl-4">
      <div class="card p-3">
        <h3 class="text-dark text-center">Forgot Password</h3>
        <form id="forgot_password_form">
          <div class="row mb-3">
            <div class="col-12">
              <div class="alert alert-success d-none" id="success_alert" role="alert"></div>
              <?php if(isset($message)): ?>
              <div class="alert alert-danger" role="alert" id="error_alert">
                <?= $message ?>
              </div>
              <script>
              setTimeout(function() {
                $('#error_alert').addClass('d-none');
              }, 5000);
              </script>
              <?php endif; ?>
              <label class="form-label">Email</label>
              <input type="text" name="email" id="email" class="form-control forgot_password_form_field"
                placeholder="Enter Email">
              <span class="text-danger errors" id="email_error"></span>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <button type="submit" class="btn theme_btn button_hover w-100" id="submit_btn">Submit</button>
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
  const forgotPasswordForm = $('#forgot_password_form');
  const submitButton = $('#submit_btn');
  const successAlert = $('#success_alert');

  forgotPasswordForm.on('submit', function(e) {
    e.preventDefault();

    const form = new FormData(this);

    $.ajax({
      type: "POST",
      url: "<?= route_to('user.forgot_password.submit'); ?>",
      data: form,
      processData: false,
      contentType: false,
      cache: false,
      beforeSend: function() {
        submitButton.prop('disabled', true).text('Processing...');
      },
      complete: function() {
        submitButton.prop('disabled', false).text('Submit');
      },
      success: function(response) {
        if (response.status === 'success') {
          resetForm();
          successAlert.text(response.message).removeClass('d-none');
          setTimeout(() => successAlert.addClass('d-none').text(''), 10000);
        } else if (response.status === 'error') {
          handleValidationErrors(response.message);
        }
      },
      error: function(xhr, status, error) {
        console.error(xhr.responseText);
      }
    });
  });

  function resetForm() {
    $('.errors').text('');
    $('.forgot_password_form_field').removeClass('border-danger');
    $('#email').val('');
  }

  function handleValidationErrors(errorMessages) {
    resetForm();

    $.each(errorMessages, function(field, error_message) {
      $('#' + field + '_error').text(error_message);
      $('#' + field).addClass('border-danger');
    });
  }
});
</script>
<?=$this->endSection();?>