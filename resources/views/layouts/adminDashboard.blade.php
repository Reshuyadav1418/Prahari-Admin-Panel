@extends('layouts.admin.admin_master')

@section('page-content')

<style>
    body {
        background: #f5f5f5;
        color: #000;
    }

    .card {
        background: #fff;
        border: 1px solid #ddd;
        color: #000;
    }

    .card small {
        color: #666;
    }

    .card h4 {
        color: #000;
    }

    .chart-container {
        background: #fff;
        border: 1px solid #ddd;
    }

    select {
        background: #fff;
        color: #000;
        border: 1px solid #ccc;
    }
</style>
<h4>Dashboard</h4>

<div class="row g-3">

    @php
        $cards = [
            ['title' => 'Total Prahari', 'value' => $totalPrahari, 'id' => 'totalPrahari'],
            ['title' => 'Total Cases', 'value' => $totalCases, 'id' => 'totalCases'],
            ['title' => 'Total Challans', 'value' => $totalChallans, 'id' => 'totalChallans'],
            ['title' => 'Total Revenue', 'value' => '₹ ' . number_format($totalRevenue, 2), 'id' => 'totalRevenue'],
            ['title' => 'Pending Withdrawals', 'value' => '₹ ' . number_format($pendingWithdrawals, 2), 'id' => 'pendingWithdrawals'],
            ['title' => "Today's Cases", 'value' => $todaysCases, 'id' => 'todaysCases'],
            ['title' => "Today's Challans", 'value' => $todaysChallans, 'id' => 'todaysChallans'],
            ['title' => 'Active Prahari', 'value' => $activePrahari, 'id' => 'activePrahari'],
        ];
    @endphp

    @foreach($cards as $card)
    <div class="col-md-3 col-sm-6">
        <div class="card shadow-sm rounded-2 p-3 h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small>{{ $card['title'] }}</small>
                    <h4 class="fw-bold mt-1" id="{{ $card['id'] }}">{{ $card['value'] }}</h4>
                </div>
                <div>
                    <i class="bi bi-arrow-right text-secondary"></i>
                </div>
            </div>
        </div>
    </div>
    @endforeach

</div>

<!-- Charts Section -->
<div class="row mt-4 g-3">

    <!-- Cases Overview -->
    <div class="col-md-8">
        <div class="card shadow-sm rounded-2 p-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-semibold mb-0">Cases Overview</h6>
                <select class="form-select form-select-sm w-auto">
                    <option>This Month</option>
                    <option>Last Month</option>
                </select>
            </div>

            <canvas id="casesChart" height="350"></canvas>
        </div>
    </div>

    <!-- Challan Status -->
    <div class="col-md-4">
        <div class="card shadow-sm rounded-2 p-3">
            <h6 class="fw-semibold mb-3">Challan Status</h6>

            <canvas id="challanChart" height="200"></canvas>

            <div class="mt-3">
                <div class="d-flex justify-content-between small text-dark">
                    <span><span style="color:#10b981; font-size: 16px; line-height: 1; vertical-align: middle; margin-right: 4px;">●</span> Paid</span>
                    <span><span style="color:#f59e0b; font-size: 16px; line-height: 1; vertical-align: middle; margin-right: 4px;">●</span> Pending</span>
                    <span><span style="color:#f43f5e; font-size: 16px; line-height: 1; vertical-align: middle; margin-right: 4px;">●</span> Cancelled</span>
                </div>
            </div>
        </div>
    </div>

</div>

</div>

<!-- Chart JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Line Chart (Cases Overview)
    const ctx = document.getElementById('casesChart').getContext('2d');
    
    // Create dynamic gradient
    const gradientBg = ctx.createLinearGradient(0, 0, 0, 350);
    gradientBg.addColorStop(0, 'rgba(99, 102, 241, 0.35)');
    gradientBg.addColorStop(1, 'rgba(99, 102, 241, 0.01)');

    const casesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($casesChartLabels ?? []) !!},
            datasets: [
                {
                    label: 'Cases',
                    data: {!! json_encode($casesChartData ?? []) !!},
                    borderColor: 'rgba(99, 102, 241, 1)',
                    backgroundColor: gradientBg,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: 'rgba(99, 102, 241, 1)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }
            ]
        },
        options: {
            plugins: {
                legend: {
                    labels: {
                        color: '#374151',
                        font: {
                            weight: '600'
                        }
                    }
                }
            },
            scales: {
                x: {
                    ticks: { color: '#4b5563' },
                    grid: { color: 'rgba(0,0,0,0.05)' }
                },
                y: {
                    ticks: { color: '#4b5563' },
                    grid: { color: 'rgba(0,0,0,0.05)' }
                }
            }
        }
    });

    // Doughnut Chart (Challan Status)
    const ctx2 = document.getElementById('challanChart').getContext('2d');
    const challanChart = new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Paid', 'Pending', 'Cancelled'],
            datasets: [{
                data: [{{ $challanPaid ?? 0 }}, {{ $challanPending ?? 0 }}, {{ $challanCancelled ?? 0 }}],
                backgroundColor: ['#10b981', '#f59e0b', '#f43f5e'],
                hoverBackgroundColor: ['#059669', '#d97706', '#e11d48'],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            plugins: {
                legend: {
                    labels: {
                        color: '#374151',
                        font: {
                            weight: '600'
                        }
                    }
                }
            },
            cutout: '70%'
        }
    });
</script>

@push('page-script')
<script>
$(document).ready(function() {
    function updateDashboardCards() {
        $.ajax({
            url: "{{ route('admin.dashboard.data') }}",
            method: 'GET',
            success: function(data) {
                $('#totalPrahari').text(data.totalPrahari);
                $('#totalCases').text(data.totalCases);
                $('#totalChallans').text(data.totalChallans);
                $('#totalRevenue').text(data.totalRevenue);
                $('#pendingWithdrawals').text(data.pendingWithdrawals);
                $('#todaysCases').text(data.todaysCases);
                $('#todaysChallans').text(data.todaysChallans);
                $('#activePrahari').text(data.activePrahari);

                if (data.chart) {
                    casesChart.data.labels = data.chart.casesChartLabels;
                    casesChart.data.datasets[0].data = data.chart.casesChartData;
                    casesChart.update();

                    challanChart.data.datasets[0].data = [
                        data.chart.challanPaid, 
                        data.chart.challanPending, 
                        data.chart.challanCancelled
                    ];
                    challanChart.update();
                }
            },
            error: function() {
                console.log('Error fetching dashboard data');
            }
        });
    }

    // Update every 30 seconds
    setInterval(updateDashboardCards, 30000);
});
</script>
@endpush

@endsection