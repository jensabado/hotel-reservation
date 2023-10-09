<?php $this->extend('admin/layout/base')?>

<?php $this->section('content')?>
<!-- update modal -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="viewModalLabel">Update Reservation</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="">Firstname</label>
                <input type="text" name="" id="" class="form-control">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="">Middlename</label>
                <input type="text" name="" id="" class="form-control">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="">Lastname</label>
                <input type="text" name="" id="" class="form-control">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="">Email</label>
                <input type="text" name="" id="" class="form-control">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="">Contact</label>
                <input type="text" name="" id="" class="form-control">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label for="">Address</label>
                <textarea name="" id="" class="form-control" style="height: 80px !important; resize: none;"></textarea>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="">Room Type</label>
                <input type="text" name="" id="" class="form-control">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="">Reserved Date</label>
                <input type="date" name="" id="" class="form-control">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="">Booked Date</label>
                <input type="date" name="" id="" class="form-control">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="">Update Status</label>
                <select name="" id="" class="form-select">
                  <option value="" selected disabled>SELECT</option>
                  <option value="checked-in">Checked-in</option>
                  <option value="cancel">Cancel</option>
                </select>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<div class="content-wrapper">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <div class="table-responsive mt-3">
            <table class="table table-hover" id="table">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Contact</th>
                  <th>Room Type</th>
                  <th>Date Booked</th>
                  <th>Reserved Date</th>
                  <th>Bill</th>
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
<?php $this->endSection();?>

<?=$this->section('script')?>
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
      url: "<?=route_to('admin.pending_reservation_datatable')?>",
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
          url: "<?=route_to('admin.delete.room_type.submit')?>",
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
<?=$this->endSection();?>