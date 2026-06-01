@extends('layouts.admin.admin_master')

@push('page-style')
<style>
    .simple-table {
        width: 100%;
        background: #fff;
        border-collapse: collapse;
        margin-top: 20px;
    }
    .simple-table thead th {
        background: #fff !important;
        color: #333 !important;
        font-weight: 600;
        padding: 12px 10px;
        border-bottom: 2px solid #eee;
    }
    .simple-table tbody td {
        padding: 12px 10px;
        border-bottom: 1px solid #f9f9f9;
        vertical-align: middle;
        color: #555;
    }
    .simple-table tbody tr:hover {
        background: #fdfdfd;
    }
    .nav-tabs {
        border-bottom: 1px solid #ddd;
        margin-bottom: 20px;
    }
    .nav-tabs .nav-link {
        color: #666;
        border: none;
        padding: 10px 20px;
        font-weight: 500;
        background: transparent;
    }
    .nav-tabs .nav-link.active {
        color: #000;
        border-bottom: 2px solid #000;
    }
    .action-btn {
        background: transparent;
        border: none;
        font-size: 18px;
        cursor: pointer;
    }
</style>
@endpush

@section('page-content')

<div class="row mt-4 mb-3">
    <div class="col-sm-12 d-flex justify-content-between align-items-center">
        <h4 class="fw-bold mb-0">Payments / Withdrawals</h4>
        <div>
            <button id="exportCsvBtn" style="background:#e1bb80;color:#fff;border:none;padding:6px 12px;" class="me-1 rounded-1">Export CSV</button>
            <button onclick="addPayment()" style="background:#e1bb80;color:#fff;border:none;padding:6px 12px;" class="rounded-1 px-3">
                + Add Payment
            </button>
        </div>
    </div>
</div>

<ul class="nav nav-tabs" id="paymentTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="all-tab" data-tab="all" type="button" role="tab">All Transactions</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="withdrawals-tab" data-tab="withdrawals" type="button" role="tab">Withdrawal Requests</button>
    </li>
</ul>

<div class="row mb-3 align-items-center">
    <div class="col-md-6 position-relative">
        <i class="bi bi-search position-absolute top-50 translate-middle-y ms-3 text-muted"></i>
        <input type="text" id="searchInput" class="form-control ps-5 rounded-2" placeholder="Search by Prahari, Amount, Status...">
    </div>
    <div class="col-md-2 text-end ms-auto dropdown">
        <button class="btn btn-outline-secondary rounded-2 w-100 d-flex justify-content-between align-items-center" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <span><i class="bi bi-funnel"></i> Filter</span>
            <i class="bi bi-chevron-down"></i>
        </button>
        <ul class="dropdown-menu w-100 shadow-sm" aria-labelledby="filterDropdown">
            <li><a class="dropdown-item filter-opt" href="#" data-val="">All Status</a></li>
            <li><a class="dropdown-item filter-opt" href="#" data-val="pending">Pending</a></li>
            <li><a class="dropdown-item filter-opt" href="#" data-val="success">Success</a></li>
            <li><a class="dropdown-item filter-opt" href="#" data-val="failed">Failed</a></li>
        </ul>
    </div>
</div>

<div class="table-responsive bg-white p-3 rounded-3 border">
    <table class="simple-table" id="paymentTable">
        <thead>
            <tr>
                <th>Request ID</th>
                <th>Prahari</th>
                <th>Amount</th>
                <th>Bank Account</th>
                <th>Status</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<!-- add payment modal -->
<div class="modal fade" id="addPaymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold">Add Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addPaymentForm">
                    @csrf
                    <div class="mb-3">
                        <label class="text-muted small">Prahari</label>
                        <select name="prahari_id" class="form-control" required>
                            <option value="">Select Prahari</option>
                            @foreach($praharis as $prahari)
                                <option value="{{ $prahari->id }}">{{ $prahari->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">Challan</label>
                        <select name="challan_id" class="form-control">
                            <option value="">Optional Challan</option>
                            @foreach($challans as $challan)
                                <option value="{{ $challan->id }}">CHALLAN-{{ $challan->id }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">Amount</label>
                        <input type="number" step="0.01" name="amount_paid" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">Account Number</label>
                        <input type="text" name="bank_account_number" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">Status</label>
                        <select name="status" class="form-control">
                            <option value="pending">Pending</option>
                            <option value="success">Success</option>
                            <option value="failed">Failed</option>
                        </select>
                    </div>
                    <button type="submit" style="background:grey;color:#fff;border:none;padding:6px 12px;" class="w-100 rounded-2">Save Payment</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('page-script')
<script>
$(document).ready(function() {
    let currentTab = 'all';
    let currentStatus = '';

    let table = $('#paymentTable').DataTable({
        processing: true,
        serverSide: true,
        destroy: true,
        dom: 't<"d-flex justify-content-between mt-3"ip>', // Removes default search box and aligns pagination
        buttons: [
            {
                extend: 'csv',
                text: 'Export CSV',
                className: 'd-none',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5]
                }
            }
        ],
        pageLength: 10,
        ajax: {
            url: "{{ route('admin.payments') }}",
            data: function (d) {
                d.tab = currentTab;
                d.status = currentStatus;
            }
        },
        columns: [
            { data: 'request_id', name: 'id' },
            { data: 'prahari_name', name: 'prahari.name' },
            { data: 'amount', name: 'amount_paid' },
            { data: 'bank_account', name: 'bank_account_number', orderable: false },
            { data: 'status', name: 'status' },
            { data: 'date', name: 'created_at' },
            { data: 'action', orderable: false, searchable: false }
        ]
    });

    $('#exportCsvBtn').on('click', function() {
        table.button('.buttons-csv').trigger();
    });

    // Custom Search
    $('#searchInput').on('keyup', function() {
        table.search(this.value).draw();
    });

    // Dropdown Filter
    $('.filter-opt').click(function(e) {
        e.preventDefault();
        currentStatus = $(this).data('val');
        
        let text = $(this).text();
        $('#filterDropdown span').html('<i class="bi bi-funnel"></i> ' + (currentStatus ? text : 'Filter'));
        
        table.ajax.reload();
    });

    // Tab Switching
    $('.nav-link').click(function() {
        $('.nav-link').removeClass('active');
        $(this).addClass('active');
        currentTab = $(this).data('tab');
        
        // Optionally reset status filter on tab change
        currentStatus = '';
        $('#filterDropdown span').html('<i class="bi bi-funnel"></i> Filter');
        
        table.ajax.reload();
    });

    $('#addPaymentForm').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('admin.payments.store') }}",
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#addPaymentModal').modal('hide');
                $('#addPaymentForm')[0].reset();
                table.ajax.reload();
                alert(response.message);
            },
            error: function(xhr) {
                let message = 'Unable to save payment.';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    message = Object.values(xhr.responseJSON.errors).flat().join('\n');
                }
                alert(message);
            }
        });
    });

    window.approvePayment = function(id) {
        if (!confirm('Are you sure you want to approve this withdrawal?')) return;
        $.ajax({
            url: "{{ url('account/admin/payments') }}" + '/' + id + '/approve',
            method: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                table.ajax.reload();
                alert(response.message);
            },
            error: function() { alert('Unable to approve payment.'); }
        });
    };

    window.rejectPayment = function(id) {
        if (!confirm('Are you sure you want to reject this withdrawal?')) return;
        $.ajax({
            url: "{{ url('account/admin/payments') }}" + '/' + id + '/reject',
            method: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                table.ajax.reload();
                alert(response.message);
            },
            error: function() { alert('Unable to reject payment.'); }
        });
    };

    window.deletePayment = function(id) {
        if (!confirm('Are you sure you want to delete this record?')) return;
        $.ajax({
            url: "{{ url('account/admin/payments') }}" + '/' + id,
            method: 'POST',
            data: {
                _method: 'DELETE',
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                table.ajax.reload();
                alert(response.message);
            },
            error: function() { alert('Unable to delete payment.'); }
        });
    };

});

function addPayment() {
     $('#addPaymentModal').modal('show');
}
</script>
@endpush