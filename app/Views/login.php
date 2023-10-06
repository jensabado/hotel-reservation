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
        <h3 class="text-dark text-center">Login</h3>
        <form id="login_form">
          <div class="row mb-3 mt-2">
            <div class="col-12">
              <label class="form-label">Username/Email</label>
              <input type="text" name="login_id" id="login_id" class="form-control login_form_field"
                placeholder="Enter Username or Email">
              <span class="text-danger errors" id="login_id_error"></span>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <label class="form-label">Password</label>
              <div class="input-group">
                <input type="password" class="form-control login_form_field" placeholder="Enter Password" id="password"
                  name="password">
                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                  <i class="fas fa-eye"></i>
                </button>
              </div>
              <span class="text-danger errors" id="password_error"></span>
            </div>
          </div>
          <div class="row mb-2">
            <div class="col-12">
              <a href="<?= route_to('user.forgot-password.form') ?>" class="text-right">Forgot Password?</a>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <button type="submit" class="btn theme_btn button_hover w-100">Login</button>
              <p class="mt-1">Don't have an Account? <a href="register">Register here</a></p>
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
$(window).on('load', function() {
  if (sessionAndLocalStorageSuccessAlert()) {
    showSuccessAlert(sessionAndLocalStorageSuccessAlert());
  }
});

$(document).ready(function() {
  $('#togglePassword').click(function() {
    togglePasswordVisibility($('#password'));
  });

  $('#login_form').on('submit', function(e) {
    e.preventDefault();
    const form = new FormData(this);

    $.ajax({
      type: "POST",
      url: "<?= route_to('user.login.submit'); ?>",
      data: form,
      processData: false,
      contentType: false,
      cache: false,
      success: function(response) {
        if (response.status === 'success') {
          window.location.href = '/';
        } else if (response.status === 'error') {
          handleValidationErrors(response.message);
        }
      },
      error: function(xhr, status, error) {
        console.error(xhr.responseText);
      }
    });
  });

  function sessionAndLocalStorageSuccessAlert() {
    if (session('success')) {
      return session('message');
    } else if (localStorage.getItem('message')) {
      const message = localStorage.getItem('message');
      localStorage.removeItem('message');
      return message;
    }
  }

  function togglePasswordVisibility(passwordInput) {
    const icon = $('#togglePassword i');
    const inputType = passwordInput.attr('type');
    passwordInput.attr('type', inputType === 'password' ? 'text' : 'password');
    icon.toggleClass('fa-eye fa-eye-slash');
  }

  function resetForm() {
    $('.errors').text('');
    $('.login_form_field').removeClass('border-danger');
  }

  function handleValidationErrors(errorMessages) {
    resetForm();

    $.each(errorMessages, function(field, error_message) {
      $('#' + field + '_error').text(error_message);
      $('#' + field).addClass('border-danger');
    });
  }
});

function showSuccessAlert(message) {
  Swal.fire({
    icon: 'success',
    title: 'Success!',
    text: message,
    iconColor: '#f8b600',
    confirmButtonColor: '#f8b600',
    showConfirmButton: false,
    timer: 5000,
    timerProgressBar: true,
    color: '#000',
    background: '#fff',
  });
}
</script>

<?=$this->endSection();?>