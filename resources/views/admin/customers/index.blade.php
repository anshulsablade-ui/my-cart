@extends('admin.layouts.app')
@section('title', 'Customers List')

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
          <h5 class="fs-9 mb-0 text-nowrap py-2 py-xl-0">Customers List</h5>
        </div>
        {{-- <div class="col-8 col-sm-auto ms-auto text-end ps-0">
          <div>
            <button class="btn btn-falcon-default btn-sm" type="button" onclick="window.location='{{ route('admin.customers.create') }}'">
                <span class="fas fa-plus" data-fa-transform="shrink-3 down-2"></span>
                <span>Add New Customers</span>
            </button>
          </div>
        </div> --}}
      </div>
    </div>
    <div class="card-body px-0">
      <!-- data table -->
      <table class="table mb-0 data-table fs-10" id="customersTable">
        <thead class="bg-200">
          <tr>
            <th class="text-900 text-nowrap py-1">Customers</th>
            <th class="text-900 text-nowrap py-1">Date</th>
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
<script>
    $(document).ready(function() {

        // Customers DataTable
        window.table = $('#customersTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.customers.index') }}",
            dom: "<'row mx-0'<'col-md-6'l><'col-md-6'f>>" + "<'table-responsive scrollbar'tr>" + "<'row g-0 align-items-center justify-content-center justify-content-sm-between'<'col-auto mb-2 mb-sm-0 px-3'i><'col-auto px-3'p>>",
            "createdRow": function (row, data, dataIndex) {
              $(row).addClass('btn-reveal-trigger');
            },
            language: {
                lengthMenu:     "_MENU_ Show entries",
                zeroRecords:    "No customers found",
                info:           "Showing _START_ to _END_ of _TOTAL_ customers",
                infoEmpty:      "No customers available",
                infoFiltered:   "(filtered from _MAX_ total customers)",
                search:         "Search:",
            },
            columns: [
                { data: 'name',
                  render: function(data, type, row) {
                    if (type !== 'display') return data;

                    let avatar = (row.image != null) ? 
                        `<img class="rounded-circle" src="${row.image}" alt="${data}" />` : 
                        `<div class="avatar-name rounded-circle"><span>${data.charAt(0)}</span></div>`;
                    return `<div class="d-flex align-items-center">
                                <div class="avatar avatar-2xl">
                                    ${avatar}
                                </div>
                                <div class="ms-2">
                                    <a href="/admin/customers/${row.id}"><h6 class="mb-0">${data}</h6></a>
                                    <a href="javascript:void(0);">${row.email}</a>
                                </div>
                            </div>`;
                  }
                },
                { data: 'date' },
                { data: 'actions', orderable: false, searchable: false,
                  render: function(data, type, row) {
                    if (type !== 'display') return data;

                    return `<a class="dropdown-item text-danger delete" href="${data.deleteUrl}">Delete</a>`;
                  }
                }
            ]
        });

    });
</script>
@endsection