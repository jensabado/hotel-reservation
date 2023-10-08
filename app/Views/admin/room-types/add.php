<?php $this->extend('admin/layout/base')?>

<?php $this->section('content')?>
<div class="content-wrapper">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <form action="" id="add_room_type_form" enctype="multipart/form-data">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="">Room Type Name</label>
                  <input type="text" name="add_name" id="add_name" class="form-control">
                  <span class="text-danger errors" style="font-size: 13px" id="add_name_error"></span>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="">Price</label>
                  <input type="text" name="add_price" id="add_price" class="form-control">
                  <span class="text-danger errors" style="font-size: 13px" id="add_price_error"></span>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="">Room Type Image</label>
                  <input type="file" name="add_image" id="add_image" class="form-control edit_form_field"
                    placeholder="Enter Room Price" accept="image/*">
                  <span class="text-danger errors" style="font-size: 13px" id="add_image_error"></span>
                </div>
              </div>
              <div class="col-12">
                <div class="form-group">
                  <div class="d-flex flex-column">
                    <label for="">Image Preview</label>
                    <img style="height: 200px; width: 200px; max-width: 100%; object-fit: cover;"
                      src="" alt="" id="preview_image">
                  </div>
                </div>
              </div>
              <div class="col-12">
                <div class="form-group">
                  <button class="btn btn-primary" type="submit">Submit</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- content-wrapper ends -->
<?php $this->endSection();?>

<?=$this->section('script')?>
<script>
$(document).ready(function() {
  // submit form
  $('#add_room_type_form').on('submit', function(e) {
    e.preventDefault();

    const form = new FormData(this);

    $.ajax({
      type: "POST",
      url: "<?=route_to('admin.add.room_type.submit');?>",
      data: form,
      processData: false,
      contentType: false,
      cache: false,
      success: function(response) {
        if (response.status === 'success') {
          localStorage.setItem('message', response.message);
          window.location.href = '<?=route_to('admin.room_types')?>';
        } else if (response.status === 'error') {
          handleValidationErrors(response.message);
        }
      },
      error: function(xhr, status, error) {
        console.error(xhr.responseText);
      }
    });
  })

  // sanitize price input
  $("#add_price").on("input", function() {
    var inputValue = $(this).val();

    // Remove any non-numeric and non-decimal characters
    inputValue = inputValue.replace(/[^0-9.]/g, "");

    // Remove leading zeros
    inputValue = inputValue.replace(/^0+/g, "");

    // Remove extra periods (allow only one)
    var parts = inputValue.split(".");
    if (parts.length > 2) {
      parts.pop();
      inputValue = parts.join(".");
    }

    // Update the input value
    $(this).val(inputValue);
  });

  // Prevent pasting into the text box
  $("#add_price").on("paste", function(e) {
    e.preventDefault();
  });

  // Listen for the file input change event
  $('#add_image').on('change', function() {
    // Get the selected file
    const file = this.files[0];

    if (file) {
      // Create a FileReader to read the file
      const reader = new FileReader();

      // Set up a callback function for when the file is loaded
      reader.onload = function(e) {
        // Update the source of the <img> tag with the loaded data URL
        $('#preview_image').attr('src', e.target.result);
      };

      // Read the file as a data URL (this will trigger the onload event)
      reader.readAsDataURL(file);
    } else {
      // If no file is selected, clear the image source
      $('#preview_image').attr('src', '');
    }
  });

  function resetForm() {
    $('.errors').text('');
    $('.add_form_field').removeClass('border-danger');
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
<?=$this->endSection();?>