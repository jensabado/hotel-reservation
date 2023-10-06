<?php $this->extend('admin/layout/base') ?>

<?php $this->section('content') ?>
<div class="content-wrapper">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <a href="<?= route_to('admin.add.room') ?>" class="btn btn-primary">ADD ROOM</a>
          <div class="table-responsive mt-3">
            <table class="table table-hover" id="table">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Room Type</th>
                  <th>Room No</th>
                  <th>Photo</th>
                  <th>Price</th>
                  <th>Action</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- content-wrapper ends -->
<?php $this->endSection(); ?>

<?= $this->section('script') ?>
<script>
$(window).on('load', function() {
  if (localStorage.getItem('message')) {
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
    });

    localStorage.removeItem('message');
  }
})

$(document).ready(function() {
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
      [5, 10, 25, 50, -1],
      [5, 10, 25, 50, "All"]
    ]
  });

  // delete
  $(document).on('click', '#get_delete', function(e) {
    e.preventDefault();

    let id = $(this).data('id');

    Swal.fire({
      icon: 'question',
      title: 'Hey!',
      text: 'Are you sure you want to delete this data?',
      iconColor: '#f8b600',
      confirmButtonColor: '#f8b600',
      showConfirmButton: true,
      showCancelButton: true,
      confirmButtonText: `Yes`,
      color: '#000',
      background: '#fff',
    }).then((result) => {
      if (result.isConfirmed) {
        let form = new FormData();
        form.append('id', id);

        $.ajax({
          type: "POST",
          url: "<?= route_to('admin.delete.room.submit') ?>",
          data: form,
          processData: false,
          contentType: false,
          cache: false,
          success: function(response) {
            console.log(response);
            if (response.status == 'success') {
              dataTable.ajax.reload(null, false);
              Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: response.message,
                iconColor: '#f8b600',
                confirmButtonColor: '#f8b600',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
                color: '#000',
                background: '#fff',
              })
            } else if (response.status == 'error') {
              dataTable.ajax.reload(null, false);
              Swal.fire({
                icon: 'error',
                title: 'Failed!',
                text: response.message,
                iconColor: '#f8b600',
                confirmButtonColor: '#f8b600',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
                color: '#000',
                background: '#fff',
              })
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Sorry!',
                text: 'Something went wrong!',
                iconColor: '#f8b600',
                confirmButtonColor: '#f8b600',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
                color: '#000',
                background: '#fff',
              })
            }
          },
          error: function(xhr, status, error) {
            console.error(xhr.responseText);
          }
        })
      }
    })
  })
})
</script>
<?= $this->endSection(); ?>