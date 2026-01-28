@extends('admin.layouts.app')
@section('title', 'Product List')

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
          <h5 class="fs-9 mb-0 text-nowrap py-2 py-xl-0">Product List</h5>
        </div>
        <div class="col-8 col-sm-auto ms-auto text-end ps-0">
          <div>
            <button class="btn btn-falcon-default btn-sm" type="button" onclick="window.location='{{ route('admin.products.create') }}'">
                <span class="fas fa-plus" data-fa-transform="shrink-3 down-2"></span>
                <span>Add New Product</span>
            </button>
          </div>
        </div>
      </div>
    </div>
    <div class="card-body px-0">
      <!-- data table -->
      <table class="table mb-0 data-table fs-10" id="productsTable">
        <thead class="bg-200">
          <tr>
            <th class="text-900 text-nowrap py-1">Name</th>
            <th class="text-900 text-nowrap py-1">Price</th>
            <th class="text-900 text-nowrap py-1">Category</th>
            <th class="text-900 text-nowrap py-1">Brand</th>
            <th class="text-900 text-nowrap py-1">Stock</th>
            <th class="text-900 text-nowrap py-1">Status</th>
            <th class="text-900 text-nowrap py-1">Actions</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
      <!-- end data table -->
    </div>
  </div>

@endsection
@section('script')
<script src="{{ asset('vendors/datatables.net/dataTables.min.js') }}"></script>
<script src="{{ asset('vendors/datatables.net-bs5/dataTables.bootstrap5.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {

        // Product DataTable
        let table = $('#productsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.products.index') }}",
            dom: "<'row mx-0'<'col-md-6'l><'col-md-6'f>>" + "<'table-responsive scrollbar'tr>" + "<'row g-0 align-items-center justify-content-center justify-content-sm-between'<'col-auto mb-2 mb-sm-0 px-3'i><'col-auto px-3'p>>",
            "createdRow": function (row, data, dataIndex) {
              $(row).addClass('btn-reveal-trigger');
            },
            language: {
                lengthMenu:     "_MENU_ Show entries",
                zeroRecords:    "No products found",
                info:           "Showing _START_ to _END_ of _TOTAL_ products",
                infoEmpty:      "No products available",
                infoFiltered:   "(filtered from _MAX_ total products)",
                search:         "Search:",
            },
            columns: [
                { data: 'name',
                  render: function(data, type, row) {
                     if (type !== 'display') return data;

                     let image = `<img src="${row.image.imageUrl}" alt="${data}" width="40">`;
                     return `<div class="d-flex"><div class="me-2">${image}</div><div>${data}</div></div>`;
                  }
                 },
                { data: 'final_price',
                  render: function(data, type, row) {
                     if (type !== 'display') return data;

                     return `₹${data}`;
                  }
                 },
                { data: 'category' },
                { data: 'brand' },
                { data: 'stock' },
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
                                <a class="dropdown-item" href="${data.editUrl}">Edit</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger delete" href="${data.deleteUrl}">Delete</a>
                              </div>
                            </div>`;
                  }
                }
            ]
        });

        // Add Product
        $('#productForm').submit(function (e) { 
            e.preventDefault();
            
            let formData = new FormData(this);
            
            console.log(formData);
            ajaxCall('{{ route('admin.products.store') }}', 'POST', formData, function (res) {
                if (res.status == 'success') {
                    window.location.href = '{{ route('admin.products.index') }}';
                }
            },
            function (response) {
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();
                var response = JSON.parse(response.responseText);
                $.each(response.message, function (key, value) { 
                     $(`#${key}`).addClass('is-invalid').after(` <span class="invalid-feedback">${value}</span> `);
                });
            });
            
        });
    });
</script>
@endsection