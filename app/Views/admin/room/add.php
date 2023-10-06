<?php $this->extend('admin/layout/base') ?>

<?php $this->section('content') ?>
<div class="content-wrapper">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <form action="" id="add_room_form" enctype="multipart/form-data">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="">Room Type</label>
                  <select name="add_room_type" id="add_room_type" class="form-select add_form_field">
                    <option value="" selected>SELECT</option>
                    <?php foreach($get_data as $data) : ?>
                    <option value="<?= $data['id'] ?>"><?= $data['name'] ?></option>
                    <?php endforeach; ?>
                  </select>
                  <span class="text-danger errors" style="font-size: 13px" id="add_room_type_error"></span>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="">Room Name/Number</label>
                  <input type="text" name="add_room_no" id="add_room_no" class="form-control add_form_field"
                    placeholder="Enter Room Name/Number">
                  <span class="text-danger errors" style="font-size: 13px" id="add_room_no_error"></span>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="">Room Price</label>
                  <input type="text" name="add_room_price" id="add_room_price" class="form-control add_form_field"
                    placeholder="Enter Room Price" inputmode="numeric">
                  <span class="text-danger errors" style="font-size: 13px" id="add_room_price_error"></span>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="">Room Image</label>
                  <input type="file" name="add_room_image" id="add_room_image" class="form-control add_form_field"
                    placeholder="Enter Room Price" accept="image/*">
                  <span class="text-danger errors" style="font-size: 13px" id="add_room_image_error"></span>
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
<?php $this->endSection(); ?>

<?= $this->section('script') ?>
<script>
$(document).ready(function() {
  // initializing select2
  $('#add_room_type').select2();

  // initializing datatables
  var dataTable = $('#table').DataTable({
    "serverSide": true,
    "paging": true,
    "pagingType": "simple",
    "scrollX": true,
    "sScrollXInner": "100%",
    "ajax": {
      url: "<?= route_to('admin.room.datatable') ?>",
      type: "POST",
      error: function(xhr, error, code) {
        console.log(xhr, code);
      }
    },
    "order": [
      [0, 'desc']
    ],
    "lengthMenu": [
      [10, 25, 50, -1],
      [10, 25, 50, "All"]
    ]
  });

  // submit form
  $('#add_room_form').on('submit', function(e) {
    e.preventDefault();

    const form = new FormData(this);

    $.ajax({
      type: "POST",
      url: "<?= route_to('admin.add.room.submit'); ?>",
      data: form,
      processData: false,
      contentType: false,
      cache: false,
      success: function(response) {
        if (response.status === 'success') {
          localStorage.setItem('message', response.message);
          window.location.href = '<?= route_to('admin.room') ?>';
        } else if (response.status === 'error') {
          handleValidationErrors(response.message);
        }
      },
      error: function(xhr, status, error) {
        console.error(xhr.responseText);
      }
    });
  })

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
<?= $this->endSection(); ?>