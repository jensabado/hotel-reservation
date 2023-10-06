<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- plugins:css -->
  <link rel="stylesheet" href="vendors/feather/feather.css">
  <link rel="stylesheet" href="vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="vendors/typicons/typicons.css">
  <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="css/vertical-layout-light/style.css">
  <!-- endinject -->
  <!-- <link rel="shortcut icon" href="images/favicon.png" /> -->
  <title><?= isset($page_title) ? $page_title : 'ES Admin' ?></title>

  <style>
  .errors {
    font-weight: 500;
    font-size: 12px;
  }

  .auth-form-btn:hover {
    background-color: #f8b100;
    border-color: #f8b100;
  }
  </style>
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              <div class="brand-logo" style="font-weight: bold;">
                ES Admin
              </div>
              <h6 class="fw-light">Sign in to continue.</h6>
              <form class="pt-3" id="login_form">
                <div class="form-group">
                  <input type="text" class="form-control form-control-lg login_form_field" id="login_id" name="login_id"
                    placeholder="Enter Username or Email">
                  <span class="text-danger errors" id="login_id_error"></span>
                </div>
                <div class="form-group">
                  <input type="password" class="form-control form-control-lg login_form_field" id="password"
                    name="password" placeholder="Enter Password">
                  <span class="text-danger errors" id="password_error"></span>
                </div>
                <div class="mt-3">
                  <button class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" type="submit">SIGN
                    IN</button>
                </div>
                <!-- <div class="my-2 d-flex justify-content-between align-items-center">
                  <div class="form-check">
                    <label class="form-check-label text-muted">
                      <input type="checkbox" class="form-check-input">
                      Keep me signed in
                    </label>
                  </div>
                  <a href="#" class="auth-link text-black">Forgot password?</a>
                </div> -->
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- plugins:js -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="vendors/js/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  <script src="vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="js/off-canvas.js"></script>
  <script src="js/hoverable-collapse.js"></script>
  <script src="js/template.js"></script>
  <script src="js/settings.js"></script>
  <script src="js/todolist.js"></script>

  <script>
  $(document).ready(function() {
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
            window.location.href = '<?= route_to('admin.home') ?>';
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
  </script>
</body>

</html>