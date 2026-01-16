@extends('admin.layouts.app')
@section('title', 'Category List')

@section('style')
    <link href="{{ asset('vendors/datatables.net-bs5/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
@endsection
@section('content')

  <div class="card mb-3">
    <div class="card-header">
      <div class="row flex-between-center">
        <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
          <h5 class="fs-9 mb-0 text-nowrap py-2 py-xl-0">Category List</h5>
        </div>
        <div class="col-8 col-sm-auto ms-auto text-end ps-0">
          <div>
            <button class="btn btn-falcon-default btn-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                <span class="fas fa-plus" data-fa-transform="shrink-3 down-2"></span>
                <span class="d-none d-sm-inline-block ms-1">New</span>
            </button>
          </div>
        </div>
      </div>
    </div>
    <div class="card-body px-0">
      <div id="tableExample" data-list='{"valueNames":["name","status","actions"],"page":10,"pagination":true}'>
        <!-- data table -->
        {{-- <table class="table mb-0 data-table fs-10" data-datatables="data-datatables"> --}}
        <table class="table mb-0 data-table fs-10">
          <thead class="bg-200">
            <tr>
              <th class="text-900 sort text-nowrap">Name</th>
              <th class="text-900 sort text-nowrap">Total Products</th>
              <th class="text-900 sort text-nowrap">Status</th>
              <th class="text-900 sort text-nowrap">Actions</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <!-- end data table -->
      </div>
    </div>
  </div>

    <!-- model -->
  <div class="offcanvas offcanvas-end" id="offcanvasRight" tabindex="-1" aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header">
      <h5 id="offcanvasRightLabel">New Category</h5><button class="btn-close text-reset" type="button" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form id="categoryForm" class="row gx-2">
            <input type="hidden" id="category_id" name="category_id">
          <div class="col-12 mb-3">
              <label class="form-label" for="name">Category Name: </label>
              <input class="form-control datetimepicker" id="name" type="text" name="name" />
          </div>
          <div class="col-sm-12 mb-3">
            <label class="form-label" for="sub_category">Select category: </label>
            <select class="form-select" id="sub_category" name="sub_category">
                <option value="" selected>Select Category</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
          </div>
          <div class="col-sm-12 mb-3">
            <label class="form-label" for="status">Select status: </label>
            <select class="form-select" id="status" name="status">
              <option value="active" selected>Active</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>
          <div class="col-12">
            <button class="btn btn-primary" type="submit">Submit</button>
          </div>
        </form>
    </div>
  </div>
  <!-- end model -->

@endsection
@section('script')
<script src="{{ asset('vendors/datatables.net/dataTables.min.js') }}"></script>
<script src="{{ asset('vendors/datatables.net-bs5/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('vendors/datatables.net-fixedcolumns/dataTables.fixedColumns.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {

        let table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.categories.index') }}",
            columns: [
                { data: 'name' },
                { data: 'total_product' },
                { data: 'status' },
                { data: 'actions',orderable: false,searchable: false }
            ]
        });

        //delete category
        $('body').on('click', '.delete', function(e) {
            e.preventDefault();

            var url = $(this).attr("href");

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "delete",
                        url: url,

                        success: function(response) {
                            Swal.fire(
                                'Deleted!',
                                response.success,
                                'success'
                            )
                            table.ajax.reload();
                        }
                    });
                }
            })
        });

        
        $('#categoryForm').submit(function (e) { 
            e.preventDefault();
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();
            let categoryId = $('#category_id').val();
            let url = categoryId ? `/admin/categories/${categoryId}` : "{{ route('admin.categories.store') }}";
            // let method = categoryId ? 'PUT' : 'POST';

            let formData = new FormData(this);
            if (categoryId) {
                formData.append('_method', 'PUT'); 
            }


            console.log(formData);
            ajaxCall(url, 'POST', formData, function (res) {
                if (res.status == 'success') {
                    $('#offcanvasRight').offcanvas('hide');
                    $('#categoryForm')[0].reset();
                    $('#category_id').val('');
                    window.location.href = '{{ route('admin.categories.index') }}';
                }
            },
            function (response) {
                var response = JSON.parse(response.responseText);
                $.each(response.message, function (key, value) { 
                     $(`#${key}`).addClass('is-invalid').after(` <span class="invalid-feedback">${value}</span> `);
                });
            });
            
        });

        $('body').on('click', '.edit', function(e) {
            e.preventDefault();
            let url = $(this).attr('href');

            $.get(url, function(response) {
                if (response.status === 'success') {

                    let data = response.data;

                    $('#category_id').val(data.id);
                    $('#name').val(data.name);
                    $('#sub_category').val(data.parent_id);
                    $('#status').val(data.status);

                    $('#offcanvasRight').offcanvas('show');
                }
            });
        });

    });
</script>
@endsection