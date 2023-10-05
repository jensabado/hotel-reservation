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
        <h3 class="text-dark text-center">Reset Password</h3>
        <form id="reset_password_form">
          <div class="row mb-3">
            <div class="col-12">
              <label style="font-size: 13px;">New Password</label>
              <div class="input-group">
                <input type="password" class="form-control reset_password_form_field" placeholder="Enter New Password"
                  id="new_password" name="new_password">
                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                  <i class="fas fa-eye"></i>
                </button>
              </div>
              <span class="text-danger errors" style="font-weight: 500; font-size: 12px;"
                id="new_password_error"></span>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-12">
              <label style="font-size: 13px;">Confirm Password</label>
              <div class="input-group">
                <input type="password" class="form-control reset_password_form_field" placeholder="Enter New Password"
                  id="confirm_password" name="confirm_password">
                <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                  <i class="fas fa-eye"></i>
                </button>
              </div>
              <span class="text-danger errors" style="font-weight: 500; font-size: 12px;"
                id="confirm_password_error"></span>
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
  function togglePassword(passwordInput, icon) {
    if (passwordInput.attr('type') === 'password') {
      passwordInput.attr('type', 'text');
      icon.removeClass('fa-eye').addClass('fa-eye-slash');
    } else {
      passwordInput.attr('type', 'password');
      icon.removeClass('fa-eye-slash').addClass('fa-eye');
    }
  }

  $('#togglePassword').click(function() {
    togglePassword($('#new_password'), $(this).find('i'));
  });

  $('#togglePasswordConfirm').click(function() {
    togglePassword($('#confirm_password'), $(this).find('i'));
  });


  $('#reset_password_form').on('submit', function(e) {
    e.preventDefault();

    const form = new FormData(this);
    form.append('token', '<?= $token ?>');

    $.ajax({
      type: "POST",
      url: "<?= route_to('user.reset-password.submit'); ?>",
      data: form,
      processData: false,
      contentType: false,
      cache: false,
      success: function(response) {
        console.log(response);
        if (response.status === 'success') {
          localStorage.setItem('message', response.message);
          window.location.href = 'login';
        } else if (response.status === 'error') {
          $('.errors').text('');
          $('.reset_password_form_field').removeClass('border-danger');
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