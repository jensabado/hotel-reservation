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
        <h3 class="text-dark text-center">Register</h3>
        <form id="register_form">
          <div class="row mb-2 mt-2">
            <div class="col-12">
              <label style="font-size: 13px;">Username</label>
              <input type="text" name="username" id="username" class="form-control register_form_field"
                placeholder="Enter Username">
              <span class="text-danger errors" style="font-weight: 500; font-size: 12px;" id="username_error"></span>
            </div>
          </div>
          <div class="row mb-2">
            <div class="col-12">
              <label style="font-size: 13px;">Email</label>
              <input type="text" name="email" id="email" class="form-control register_form_field"
                placeholder="Enter Email">
              <span class="text-danger errors" style="font-weight: 500; font-size: 12px;" id="email_error"></span>
            </div>
          </div>
          <div class="row mb-4">
            <div class="col-12">
              <label style="font-size: 13px;">Password</label>
              <div class="input-group">
                <input type="password" class="form-control register_form_field" placeholder="Enter Password"
                  id="password" name="password">
                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                  <i class="fas fa-eye"></i>
                </button>
              </div>
              <span class="text-danger errors" style="font-weight: 500; font-size: 12px;" id="password_error"></span>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <button class="btn theme_btn button_hover w-100" type="submit">Register</button>
              <p class="mt-1" style="font-size: 13px;">Already have an Account? <a href="login">Login
                  here</a></p>
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
  // password show/hidden function
  $('#togglePassword').click(function() {
    const passwordInput = $('#password');
    const icon = $(this).find('i');

    if (passwordInput.attr('type') === 'password') {
      passwordInput.attr('type', 'text');
      icon.removeClass('fa-eye').addClass('fa-eye-slash');
    } else {
      passwordInput.attr('type', 'password');
      icon.removeClass('fa-eye-slash').addClass('fa-eye');
    }
  });

  // login form submit
  $('#register_form').on('submit', function(e) {
    e.preventDefault();

    const form = new FormData(this);

    $.ajax({
      type: "POST",
      url: "<?= route_to('user.register.submit'); ?>",
      data: form,
      processData: false,
      contentType: false,
      cache: false,
      success: function(response) {
        console.log(response);
        if (response.status === 'success') {
          localStorage.setItem('message', response.message);
          localStorage.setItem('icon', 'success');
          localStorage.setItem('title', 'Success!');
          window.location.href = 'login';
        } else if (response.status === 'error') {
          $('.errors').text('');
          $('.register_form_field').removeClass('border-danger');
          let error_messages = response.message;

          $.each(error_messages, function(field, error_message) {
            $('#' + field + '_error').text(error_message);
            $('#' + field).addClass('border-danger');
            // console.log('#' + field + '_error');
          });
        }
      },
      error: function(xhr, status, error) {
        // Handle errors if any
        console.error(xhr.responseText);
      }
    })
  })
});
</script>
<?=$this->endSection();?>