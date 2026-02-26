@extends('admin.layouts.app')
@section('title', 'Brand List')

@section('style')
    <link href="{{ asset('vendors/datatables.net-bs5/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
    <style>
      .pagination {
        --falcon-pagination-padding-x: 0.5rem;
        --falcon-pagination-padding-y: 0.25rem;
        --falcon-pagination-font-size: 0.875rem;
        --falcon-pagination-border-radius: var(--falcon-border-radius-sm);
      }
    </style>
@endsection
@section('content')

  <div class="card mb-3">
    <div class="card-header">
      <div class="row flex-between-center">
        <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
          <h5 class="fs-9 mb-0 text-nowrap py-2 py-xl-0">Brand List</h5>
        </div>
        <div class="col-8 col-sm-auto ms-auto text-end ps-0">
          <div>
            <button class="btn btn-falcon-default btn-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                <span class="fas fa-plus" data-fa-transform="shrink-3 down-2"></span>
                <span>Add New Brand</span>
            </button>
          </div>
        </div>
      </div>
    </div>
    <div class="card-body px-0">
      <!-- data table -->
        <table class="table mb-0 data-table fs-10" id="brandsTable">
          <thead class="bg-200">
            <tr>
              {{-- <th class="text-900 text-nowrap py-1"></th> --}}
              <th class="text-900 text-nowrap py-1">Name</th>
              <th class="text-900 text-nowrap py-1">Total Products</th>
              <th class="text-900 text-nowrap py-1">Status</th>
              <th class="text-900 text-nowrap py-1">Actions</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <!-- end data table -->
    </div>
  </div>

  <!-- model -->
  <div class="offcanvas offcanvas-end" id="offcanvasRight" tabindex="-1" aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header">
      <h5 id="offcanvasRightLabel">New Brand</h5><button class="btn-close text-reset" type="button" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form id="brandForm" class="row gx-2">
            <input type="hidden" id="brand_id" name="brand_id">
          <div class="col-12 mb-3">
              <label class="form-label" for="name">Brand Name: </label>
              <input class="form-control" id="name" type="text" name="name" />
          </div>
          <div class="col-sm-12 mb-3">
            <label class="form-label" for="logo">Brand image: </label>
            <input class="form-control" id="logo" type="file" name="logo">
          </div>
          <div class="col-12 mb-3">
            <div class="mb-3">
              <img id="previewImage" src="#" style="max-width: 70px; height: auto; display: none;" />
            </div>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {

        // brand datatable
        window.table = $('#brandsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.brands.index') }}",
            dom: "<'row mx-0'<'col-md-6'l><'col-md-6'f>>" + "<'table-responsive scrollbar'tr>" + "<'row g-0 align-items-center justify-content-center justify-content-sm-between'<'col-auto mb-2 mb-sm-0 px-3'i><'col-auto px-3'p>>",
            "createdRow": function (row, data, dataIndex) {
              $(row).addClass('btn-reveal-trigger');
            },
            language: {
                lengthMenu:     "_MENU_ Show entries",
                zeroRecords:    "No brands found",
                info:           "Showing _START_ to _END_ of _TOTAL_ brands",
                infoEmpty:      "No brands available",
                infoFiltered:   "(filtered from _MAX_ total brands)",
                search:         "Search:",
            },
            columns: [
                { data: 'name',
                  render: function(data, type, row) {
                    if (type !== 'display') return data;

                    let logoUrl = row.logo ? `<img src="${row.logo}" alt="${data}" width="40">` : `<img src="{{ asset('images/brands/no-image.png') }}" alt="no image" width="40">`;
                    return `<div class="d-flex align-items-center"><div class="me-2">${logoUrl}</div><div>${data}</div></div>`;
                  }
                 },
                { data: 'total_product' },
                { data: 'status',
                  render: function(data, type, row) {
                     if (type !== 'display') return data;

                     return data === 'active' ?
                         '<span class="badge bg-success add">Active</span>' :
                         '<span class="badge bg-danger">Inactive</span>';
                  }
                },
                { data: 'actions', orderable: false, searchable: false,
                  render: function(data, type, row) {
                    if (type !== 'display') return data;

                    return `<div class="dropstart font-sans-serif position-static d-inline-block">
                              <button class="btn btn-link text-600 btn-sm dropdown-toggle btn-reveal float-end" type="button"
                                id="dropdown-simple-pagination-table-item-${row.DT_RowIndex}" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true"
                                aria-expanded="false" data-bs-reference="parent">
                                <span class="fas fa-ellipsis-h fs-10"></span>
                              </button>
                              <div class="dropdown-menu dropdown-menu-end border py-2" aria-labelledby="dropdown-simple-pagination-table-item-${row.DT_RowIndex}">
                                <a class="dropdown-item edit" href="${data.editUrl}">Edit</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger delete" href="${data.deleteUrl}">Delete</a>
                              </div>
                            </div>`;
                  }
                }
            ]
        });

        
        $('#brandForm').submit(function (e) { 
            e.preventDefault();
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();
            let brandId = $('#brand_id').val();
            let url = brandId ? `/admin/brands/${brandId}` : "{{ route('admin.brands.store') }}";

            let formData = new FormData(this);
            if (brandId) {
                formData.append('_method', 'PUT'); 
            }


            console.log(formData);
            ajaxCall(url, 'POST', formData, function (res) {
                if (res.status == 'success') {
                    $('#offcanvasRight').offcanvas('hide');
                    $('#brandForm')[0].reset();
                    $('#brand_id').val('');
                    table.ajax.reload();
                }
            },
            function (response) {
                var response = JSON.parse(response.responseText);
                $.each(response.message, function (key, value) { 
                     $(`#${key}`).addClass('is-invalid').after(` <span class="invalid-feedback">${value}</span> `);
                });
            });
            
        });

        //logo preview
        $('#logo').change(function() {
            let reader = new FileReader();
            reader.onload = (e) => {
                $('#previewImage').attr('src', e.target.result).show();
            }
            reader.readAsDataURL(this.files[0]);
        });

        //edit brand
        $('body').on('click', '.edit', function(e) {
            e.preventDefault();
            let url = $(this).attr('href');

            $.get(url, function(response) {
                if (response.status === 'success') {

                    let data = response.data;

                    $('#brand_id').val(data.id);
                    $('#name').val(data.name);
                    $('#status').val(data.status);

                    let logoUrl = data.logo ? `{{ asset('images/brands/') }}/${data.logo}` : '#';
                    if (data.logo) {
                        $('#previewImage').attr('src', logoUrl).show();
                    } else {
                        $('#previewImage').hide();
                    }

                    $('#offcanvasRight').offcanvas('show');
                }
            });
        });

    });
</script>
@endsection