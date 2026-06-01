@extends('layouts.admin.admin_master')

@push('page-style')
<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .stat-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .stat-title {
        color: #4b5563;
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 10px;
    }
    .stat-value {
        color: #111827;
        font-size: 24px;
        font-weight: 700;
    }
    .chart-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        height: 100%;
    }
    .chart-title {
        color: #111827;
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 20px;
    }
    .date-picker-btn {
        background: #fff;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        padding: 8px 12px;
        font-size: 14px;
        color: #374151;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        min-width: 250px;
    }
    .export-btn {
        background: grey;
        border: none;
        border-radius: 6px;
        padding: 6px 16px;
        font-size: 14px;
        font-weight: 500;
        color: #fff;
        text-decoration: none;
    }
    .export-btn:hover {
        background: #f3f4f6;
        color: #111827;
    }
</style>
@endpush
@section('page-content')
<div class="d-flex justify-content-between align-items-center mt-4 mb-4">
    <h4 class="fw-bold mb-0">Reports</h4>   
    <div>
        <input type="text" id="dateRange" class="date-picker-btn" placeholder="Select Date Range">
        <small class="text-muted d-block mt-2">Default: Last 90 days</small>
    </div>
</div>
<div class="row mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-title">Total Cases</div>
            <div class="stat-value" id="valTotalCases">0</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-title">Total Challans</div>
            <div class="stat-value" id="valTotalChallans">0</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-title">Total Revenue <small style="font-size: 11px; color: #999;">(All Transactions)</small></div>
            <div class="stat-value">₹ <span id="valTotalRevenue">5000</span></div>
        </div>
    </div>
</div>
<div class="row mb-4">
    <div class="col-md-6 mb-3 mb-md-0">
        <div class="chart-card">
            <div class="chart-title">Cases Trend</div>
            <canvas id="casesChart" height="200"></canvas>
        </div>
    </div>
    <div class="col-md-6">
        <div class="chart-card">
            <div class="chart-title">Revenue Trend</div>
            <canvas id="revenueChart" height="200"></canvas>
        </div>
    </div>
</div>
<div class="d-flex align-items-center gap-3 mt-5">
    <span class="fw-bold">Export Report</span>
    <button type="button" class="export-btn" onclick="alert('PDF Export would be handled via a library like dompdf.')">PDF</button>
    <button type="button" class="export-btn" onclick="exportCSV()">CSV</button>
</div>
@endsection
@push('page-script')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    let casesChartInst = null;
    let revenueChartInst = null;
    
    let rawReportData = {
        totalCases: 0,
        totalChallans: 0,
        totalRevenue: 0,
        casesTrend: { labels: [], data: [] },
        revenueTrend: { labels: [], data: [] }
    };
    // Helper function to convert to float
    function floatval(value) {
        return parseFloat(value) || 0;
    }
    $(document).ready(function() {
        // Initialize Date Range Picker with extended default range
        flatpickr("#dateRange", {
            mode: "range",
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "d M Y",
            defaultDate: [
                new Date(new Date().getTime() - 90 * 24 * 60 * 60 * 1000), // 90 days ago
                new Date() // Today
            ],
            onChange: function(selectedDates, dateStr, instance) {
                if(selectedDates.length === 2) {
                    let start = formatDate(selectedDates[0]);
                    let end = formatDate(selectedDates[1]);
                    fetchReportData(start, end);
                }
            }
        });
        // Initial Fetch
        let dateRange = document.getElementById("dateRange")._flatpickr.selectedDates;
        if(dateRange.length === 2) {
            fetchReportData(formatDate(dateRange[0]), formatDate(dateRange[1]));
        }
        initCharts();
    });

    function formatDate(date) {
        let d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;
        return [year, month, day].join('-');
    }
    function fetchReportData(startDate, endDate) {
        $.ajax({
            url: "{{ route('admin.reports.data') }}",
            type: "GET",
            data: {
                start_date: startDate,
                end_date: endDate
            },
            success: function(response) {
                console.log('=== FULL RESPONSE ===', response);
                console.log('Debug Info:', response.debug);
                console.log('Total Revenue:', response.totalRevenue);
                console.log('Total Cases:', response.totalCases);
                console.log('Total Challans:', response.totalChallans);
                
                rawReportData = response;
                
                // Update total values
                $('#valTotalCases').text(response.totalCases);
                $('#valTotalChallans').text(response.totalChallans);
                $('#valTotalRevenue').text(response.totalRevenue);

                // Ensure data is in array format
                let casesTrend = response.casesTrend;
                let revenueTrend = response.revenueTrend;
                if (!Array.isArray(casesTrend.labels)) {
                    casesTrend.labels = Object.values(casesTrend.labels);
                }
                if (!Array.isArray(casesTrend.data)) {
                    casesTrend.data = Object.values(casesTrend.data);
                }
                if (!Array.isArray(revenueTrend.labels)) {
                    revenueTrend.labels = Object.values(revenueTrend.labels);
                }
                if (!Array.isArray(revenueTrend.data)) {
                    revenueTrend.data = Object.values(revenueTrend.data).map(v => floatval(v));
                }
                console.log('Cases Trend:', casesTrend);
                console.log('Revenue Trend:', revenueTrend);
                updateCharts(casesTrend, revenueTrend);
            },
            error: function(xhr) {
                console.error('AJAX Error:', xhr);
                console.log('Error Details:', xhr.responseText);
                alert('Failed to fetch report data: ' + xhr.statusText);
            }
        });
    }
    function initCharts() {
        // CASES TREND - vibrant blue-indigo line with gradient fill
        const ctxCases = document.getElementById('casesChart').getContext('2d');
        const casesGradient = ctxCases.createLinearGradient(0, 0, 0, 300);
        casesGradient.addColorStop(0, 'rgba(99, 102, 241, 0.35)');
        casesGradient.addColorStop(1, 'rgba(99, 102, 241, 0.01)');

        casesChartInst = new Chart(ctxCases, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Cases',
                    data: [],
                    borderColor: '#6366f1',
                    backgroundColor: casesGradient,
                    borderWidth: 2.5,
                    pointRadius: 4,
                    pointBackgroundColor: '#6366f1',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e1b4b',
                        titleColor: '#c7d2fe',
                        bodyColor: '#fff',
                        padding: 10,
                        cornerRadius: 8
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#6b7280', font: { size: 11 } }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(99,102,241,0.08)' },
                        ticks: { color: '#6b7280', font: { size: 11 } }
                    }
                }
            }
        });

        // REVENUE TREND - vibrant emerald-teal gradient bars
        const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
        const revenueGradient = ctxRevenue.createLinearGradient(0, 0, 0, 300);
        revenueGradient.addColorStop(0, '#10b981');
        revenueGradient.addColorStop(1, '#0891b2');

        revenueChartInst = new Chart(ctxRevenue, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    label: 'Revenue (₹)',
                    data: [],
                    backgroundColor: revenueGradient,
                    borderRadius: 6,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#064e3b',
                        titleColor: '#6ee7b7',
                        bodyColor: '#fff',
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return ' ₹ ' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#6b7280', font: { size: 11 } }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(16,185,129,0.08)' },
                        ticks: {
                            color: '#6b7280',
                            font: { size: 11 },
                            callback: function(value) { return '₹' + value; }
                        }
                    }
                }
            }
        });
    }
    function updateCharts(casesTrend, revenueTrend) {
        if(casesChartInst) {
            casesChartInst.data.labels = casesTrend.labels;
            casesChartInst.data.datasets[0].data = casesTrend.data;
            casesChartInst.update();
        }
        if(revenueChartInst) {
            revenueChartInst.data.labels = revenueTrend.labels;
            revenueChartInst.data.datasets[0].data = revenueTrend.data;
            revenueChartInst.update();
        }
    }
    window.exportCSV = function() {
        let csvContent = "data:text/csv;charset=utf-8,";       
        // Summary
        csvContent += "Report Summary\n";
        csvContent += "Total Cases,Total Challans,Total Revenue\n";
        csvContent += `${rawReportData.totalCases.replace(/,/g, '')},${rawReportData.totalChallans.replace(/,/g, '')},${rawReportData.totalRevenue.replace(/,/g, '')}\n\n`;

        // Cases Trend
        csvContent += "Cases Trend\n";
        csvContent += "Date,Count\n";
        for (let i = 0; i < rawReportData.casesTrend.labels.length; i++) {
            csvContent += `${rawReportData.casesTrend.labels[i]},${rawReportData.casesTrend.data[i]}\n`;
        }
        csvContent += "\n";
        // Revenue Trend
        csvContent += "Revenue Trend\n";
        csvContent += "Date,Revenue\n";
        for (let i = 0; i < rawReportData.revenueTrend.labels.length; i++) {
            csvContent += `${rawReportData.revenueTrend.labels[i]},${rawReportData.revenueTrend.data[i]}\n`;
        }
        let encodedUri = encodeURI(csvContent);
        let link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "prahari_report.csv");
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    };
</script>
@endpush
