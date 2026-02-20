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
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <div class="row flex-between-center">
                        <div class="col-6 col-sm-auto d-flex align-items-center pe-0">
                            <h5 class="fs-9 mb-0 text-nowrap py-2 py-xl-0">Sales & Order Overview</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body overflow-hidden p-4 p-lg-6 pt-0">
                    <div class="row g-4 align-items-stretch">

                        <!-- Revenue / Sales Chart -->
                        <div class="col-lg-6">
                            <div class="text-center my-5" id="revenueChartLoader">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading sales data...</span>
                                </div>
                            </div>
                            <canvas id="salesChart" style="max-height: 380px;"></canvas>
                        </div>

                        <!-- Order Status Pie Chart -->
                        <div class="col-lg-6">
                            <div class="text-center my-5" id="statusChartLoader">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading status data...</span>
                                </div>
                            </div>
                            <canvas id="statusChart" style="max-height: 380px;"></canvas>
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
        $(document).ready(function () {

            // Customers DataTable
            let table = $('#customersTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.customers.index') }}",
                dom: "<'row mx-0'<'col-md-6'l><'col-md-6'f>>" + "<'table-responsive scrollbar'tr>" +
                    "<'row g-0 align-items-center justify-content-center justify-content-sm-between'<'col-auto mb-2 mb-sm-0 px-3'i><'col-auto px-3'p>>",
                "createdRow": function (row, data, dataIndex) {
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
                    render: function (data, type, row) {
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
                {
                    data: 'date'
                },
                {
                    data: 'actions',
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        if (type !== 'display') return data;

                        return `<a class="dropdown-item text-danger delete" href="${data.deleteUrl}">Delete</a>`;
                    }
                }
                ]
            });

        });
    </script>

    <script>
        // Order Status Chart
        $('#statusChartLoader').show();

        $.ajax({
            url: "{{ url('admin/dashboard/order-status-chart') }}",
            type: "GET",
            success: function (res) {
                $('#statusChartLoader').hide();

                if (!res.labels || !res.data) {
                    console.warn('Invalid status chart data');
                    return;
                }

                const statusColors = {
                    'pending': 'rgba(255, 193, 7, 0.85)',   // yellow/orange – waiting
                    'processing': 'rgba(54, 162, 235, 0.85)',  // blue – in progress
                    'completed': 'rgba(40, 167, 69, 0.85)',   // green – success
                    'cancelled': 'rgba(220, 53, 69, 0.85)',   // red – danger/negative
                };

                // Map colors based on labels (case-insensitive)
                const backgroundColors = res.labels.map(label => {
                    const key = label.toLowerCase();
                    return statusColors[key] || statusColors['default'];
                });

                const ctx = document.getElementById('statusChart').getContext('2d');

                new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: res.labels,
                        datasets: [{
                            data: res.data,
                            backgroundColor: backgroundColors,
                            borderColor: '#ffffff',
                            borderWidth: 2,
                            hoverOffset: 16
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 20,
                                    font: { size: 13 }
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: (context) => {
                                        let value = context.raw;
                                        let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        let percentage = ((value / total) * 100).toFixed(1);
                                        return `${context.label}: ${value} (${percentage}%)`;
                                    }
                                }
                            }
                        },
                        animation: { duration: 1200 }
                    }
                });
            }
        });

        // Revenue Line Chart
        $('#revenueChartLoader').show();
        $.ajax({
            url: "{{ url('admin/dashboard/sales-chart') }}",
            type: "GET",
            success: function (res) {
                $('#revenueChartLoader').hide();

                if (!res.labels || !res.data) {
                    console.warn('Invalid sales chart data');
                    return;
                }

                const ctx = document.getElementById('salesChart').getContext('2d');

                // Nice gradient fill under the line
                const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                gradient.addColorStop(0, 'rgba(54, 162, 235, 0.35)');
                gradient.addColorStop(1, 'rgba(54, 162, 235, 0.02)');

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: res.labels,
                        datasets: [{
                            label: 'Revenue (₹)',
                            data: res.data,
                            borderColor: 'rgb(54, 162, 235)',
                            backgroundColor: gradient,
                            borderWidth: 3,
                            tension: 0.35,          // smoother curve
                            fill: true,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: 'rgb(54, 162, 235)',
                            pointBorderWidth: 2,
                            pointRadius: 5,
                            pointHoverRadius: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                                align: 'end',
                                labels: { font: { size: 14 } }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: (value) => '₹' + value.toLocaleString()
                                }
                            },
                            x: {
                                grid: { display: false }
                            }
                        },
                        interaction: {
                            intersect: false,
                            mode: 'index'
                        },
                        animation: { duration: 1400 }
                    }
                });
            }
        });
    </script>
@endsection