@extends('vendor.layouts.app')
@section('title', 'Order List')

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
          <h5 class="fs-9 mb-0 text-nowrap py-2 py-xl-0">Order List</h5>
        </div>
      </div>
    </div>
    <div class="card-body px-0">
      <!-- data table -->
      <table class="table mb-0 data-table fs-10" id="ordersTable">
        <thead class="bg-200">
          <tr>
            <th class="text-900 text-nowrap py-1">Order</th>
            <th class="text-900 text-nowrap py-1">Date</th>
            <th class="text-900 text-nowrap py-1">Items</th>
            <th class="text-900 text-nowrap py-1">Payment</th>
            <th class="text-900 text-nowrap py-1">Status</th>
            <th class="text-900 text-nowrap py-1">Amount</th>
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

        // Order DataTable
         window.table = $('#ordersTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('vendor.orders.index') }}",
            dom: "<'row mx-0'<'col-md-6'l><'col-md-6'f>>" + "<'table-responsive scrollbar'tr>" + "<'row g-0 align-items-center justify-content-center justify-content-sm-between'<'col-auto mb-2 mb-sm-0 px-3'i><'col-auto px-3'p>>",
            "createdRow": function (row, data, dataIndex) {
              $(row).addClass('btn-reveal-trigger');
            },
            language: {
                lengthMenu:     "_MENU_ Show entries",
                zeroRecords:    "No orders found",
                info:           "Showing _START_ to _END_ of _TOTAL_ orders",
                infoEmpty:      "No orders available",
                infoFiltered:   "(filtered from _MAX_ total orders)",
                search:         "Search:",
            },
            columns: [
                { data: 'order_no',
                  render: function(data, type, row) {
                    if (type !== 'display') return data;
                    return `<a href="${data.url}"><strong>#${data.order_no}</strong></a> by <strong>${data.user}</strong><br /><a href="javascript:void(0);">${data.email}</a>`;
                  }
                 },
                { data: 'date'},
                { data: 'items' },
                { data: 'payment_status',
                  render: function(data, type, row) {
                    if (type !== 'display') return data;

                    if (data === 'pending') {
                        return `<span class="badge badge rounded-pill d-block badge-subtle-warning">Pending<span class="ms-1 fas fa-stream" data-fa-transform="shrink-2"></span></span>`;
                    } else if (data === 'paid') {
                        return `<span class="badge badge rounded-pill d-block badge-subtle-success">Paid<span class="ms-1 fas fa-check" data-fa-transform="shrink-2"></span></span>`;
                    } else if (data === 'failed') {
                        return `<span class="badge badge rounded-pill d-block badge-subtle-danger">Failed<span class="ms-1 fas fa-ban" data-fa-transform="shrink-2"></span></span>`;
                    }
                  }
                 },
                { data: 'order_status',
                  render: function(data, type, row) {
                    if (type !== 'display') return data;

                    if (data === 'pending') {
                        return `<span class="badge badge rounded-pill d-block badge-subtle-warning">Pending<span class="ms-1 fas fa-stream" data-fa-transform="shrink-2"></span></span>`;
                    } else if (data === 'processing') {
                        return `<span class="badge badge rounded-pill d-block badge-subtle-primary">Processing<span class="ms-1 fas fa-redo" data-fa-transform="shrink-2"></span></span>`;
                    } else if (data === 'completed') {
                        return `<span class="badge badge rounded-pill d-block badge-subtle-success">Completed<span class="ms-1 fas fa-check" data-fa-transform="shrink-2"></span></span>`;
                    } else if (data === 'cancelled') {
                        return `<span class="badge badge rounded-pill d-block badge-subtle-danger">Cancelled<span class="ms-1 fas fa-ban" data-fa-transform="shrink-2"></span></span>`;
                    }
                  }
                },
                { data: 'amount'}
            ]
        });

    });
</script>
@endsection