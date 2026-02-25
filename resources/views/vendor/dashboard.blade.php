@extends('vendor.layouts.app')
@section('title', 'Vendor Dashboard')
@section('style')

@endsection
@section('content')
    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">My Orders</h6>
                    <h3 class="text-primary">{{ $totalOrders }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">My Revenue</h6>
                    <h3 class="text-success">{{ Number::currency($totalRevenue, 'INR') }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">My Products</h6>
                    <h3 class="text-info">{{ $totalProducts }}</h3>
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

    </div>
@endsection
@section('script')
    <script src="{{ asset('vendors/chart/chart.umd.js') }}"></script>

    <script>
        // Order Status Chart
        $('#statusChartLoader').show();

        $.ajax({
            url: "{{ url('vendor/dashboard/order-status-chart') }}",
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
            url: "{{ url('vendor/dashboard/sales-chart') }}",
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