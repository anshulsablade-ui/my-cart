@extends('admin.layouts.app')
@section('title', 'Dashboard')
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
    <div class="row g-3 mb-4">

        <!-- Stats Card -->
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Total Orders</h6>
                    <h3 class="text-primary">{{ $totalOrders }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Total Revenue</h6>
                    <h3 class="text-success">{{ Number::currency($totalRevenue, 'INR') }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Products</h6>
                    <h3 class="text-info">{{ $totalProducts }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">Users</h6>
                    <h3 class="text-warning">{{ $totatUsers }}</h3>
                </div>
            </div>
        </div>

    </div>

    <!-- Chart Section -->
    <div class="row g-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row flex-between-center">
                        <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                            <h5 class="fs-9 mb-0 text-nowrap py-2 py-xl-0">Sales</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body overflow-hidden p-lg-6 pt-lg-0">
                    <div class="row align-items-center">

                        <div class="col-lg-12 ">
                            <div id="revenueChartLoader" class="text-center my-4">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                            <canvas id="salesChart" height="70"></canvas>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card mb-3">
                <div class="card-header">
                    <div class="row flex-between-center">
                        <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                            <h5 class="fs-9 mb-0 text-nowrap py-2 py-xl-0">Customers List</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0">
                    <!-- data table -->
                    <table class="table mb-0 data-table fs-10" id="customersTable">
                        <thead class="bg-200">
                            <tr>
                                <th class="text-900 text-nowrap py-1">Customer</th>
                                <th class="text-900 text-nowrap py-1">Email</th>
                                <th class="text-900 text-nowrap py-1">Date</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <!-- end data table -->
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('vendors/datatables.net/dataTables.min.js') }}"></script>
    <script src="{{ asset('vendors/datatables.net-bs5/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('vendors/chart/chart.umd.js') }}"></script>
    <script>
        $(document).ready(function() {

            // Customers DataTable
            let table = $('#customersTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.customers.index') }}",
                dom: "<'row mx-0'<'col-md-6'l><'col-md-6'f>>" + "<'table-responsive scrollbar'tr>" +
                    "<'row g-0 align-items-center justify-content-center justify-content-sm-between'<'col-auto mb-2 mb-sm-0 px-3'i><'col-auto px-3'p>>",
                "createdRow": function(row, data, dataIndex) {
                    $(row).addClass('btn-reveal-trigger');
                },
                language: {
                    lengthMenu: "_MENU_ Show entries",
                    zeroRecords: "No customers found",
                    info: "Showing _START_ to _END_ of _TOTAL_ customers",
                    infoEmpty: "No customers available",
                    infoFiltered: "(filtered from _MAX_ total customers)",
                    search: "Search:",
                },
                columns: [{
                        data: 'name',
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
                                    <h6 class="mb-0">${data}</h6>
                                    <a href="javascript:void(0);">${row.email}</a>
                                </div>
                            </div>`;
                        }
                    },
                    {
                        data: 'date'
                    },
                    {
                        data: 'actions',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            if (type !== 'display') return data;

                            return `<a class="dropdown-item text-danger delete" href="${data.deleteUrl}">Delete</a>`;
                        }
                    }
                ]
            });


            // sales chart
            $('#revenueChartLoader').show();
            $.ajax({
                url: "{{ url('admin/dashboard/sales-chart') }}",
                type: "GET",
                success: function(res) {

                    const ctx = document.getElementById('salesChart').getContext('2d');

                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: res.labels,
                            datasets: [{
                                label: 'Revenue (₹)',
                                data: res.data,
                                borderWidth: 3,
                                tension: 0.4
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    display: true
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                    $('#revenueChartLoader').hide();
                }
            });
        });
    </script>
@endsection
