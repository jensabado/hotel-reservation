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
                <h3 class="text-dark text-center">Login</h3>
                <form id="login_form">
                    <div class="row mb-3 mt-2">
                        <div class="col-12">
                            <label style="font-size: 13px;">Username/Email</label>
                            <input type="text" name="login_id" id="login_id" class="form-control login_form_field"
                                placeholder="Enter Username or Password">
                            <span class="text-danger errors" style="font-weight: 500; font-size: 12px;"
                                id="login_id_error"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label style="font-size: 13px;">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control login_form_field"
                                    placeholder="Enter Password" id="password" name="password">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <span class="text-danger errors" style="font-weight: 500; font-size: 12px;"
                                id="password_error"></span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-12">
                            <a href="" class="text-right" style="font-size: 12px;">Forgot Password?</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn theme_btn button_hover w-100">Login</button>
                            <p class="mt-1" style="font-size: 13px;">Don't have an Account? <a href="register">Register
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
$(window).on('load', function() {
    if (localStorage.getItem('status') === 'account_created') {
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: localStorage.getItem('message'),
            iconColor: '#f8b600',
            confirmButtonColor: '#f8b600',
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true,
            color: '#000',
            background: '#fff',
        })

        localStorage.removeItem('status');
        localStorage.removeItem('message');
    }
})

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
                console.log(response);
                if (response.status === 'success') {
                    window.location.href = '/';
                } else if (response.status === 'error') {
                    $('.errors').text('');
                    $('.login_form_field').removeClass('border-danger');
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