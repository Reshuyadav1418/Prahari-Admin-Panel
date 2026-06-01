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
        background: #eeeeee !important;
        color: #000 !important;
        padding: 12px 10px;
        text-align: left;
        border-bottom: 2px solid #ddd;
    }
    .simple-table tbody td {
        padding: 12px 10px;
        border-bottom: 1px solid #f0f0f0;
    }

   .simple-table tbody tr:hover {
        background: #f5f5f5;
    }

    .action-button {
        background: transparent;
        border: none;
        cursor: pointer;
        color: #dc3545;
        font-size: 16px;
    }
</style>
@endpush
@section('page-content')
<div class="row mt-3 mb-2">
    <div class="col-sm-12 d-flex justify-content-between align-items-center">
        <h4>Challan List </h4>
        <button id="exportCsvBtn" style="background:#e1bb80;color:#fff;border:none;padding:6px 12px;">Export CSV</button>
    </div>
</div>
<div class="row mb-2">
    <div class="col-sm-3">
        <select id="statusFilter" class="form-control">
            <option value="">All Status</option>
            <option value="pending">Pending</option>
            <option value="paid">Paid</option>
            <option value="cancelled">Cancelled</option>
        </select>
    </div>
</div>
<div class="table-responsive">
    <table class="simple-table" id="challanTable">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Challan ID</th>
                <th>Case ID</th>
                <th>Prahari ID</th>
                <th>Prahari Name</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>
<!-- add case moadal -->
<div class="modal fade" id="addChallanModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Add Challan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="addChallanForm">
                    @csrf

                    <div class="mb-2">
                        <label>Prahari Name</label>
                        <input type="text" name="prahari_name" class="form-control" required>
                    </div>

                    <div class="mb-2">
                        <label>Category</label>
                        <input type="text" name="category_name" class="form-control" required>
                    </div>


                    <div class="mb-2">
                        <label>Location</label>
                        <input type="text" name="location" class="form-control" required>
                    </div>

                    <div class="mb-2">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="open">Open</option>
                            <option value="in_progress">In Progress</option>
                            <option value="closed">Closed</option>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label>Date & Time</label>
                        <input type="datetime-local" name="violation_datetime" class="form-control">
                    </div>

                    <button type="submit" style="background:grey;color:white;padding:6px 12px;border:none;">
                        Save Challan
                    </button>

                </form>
            </div>

        </div>
    </div>
</div>

<!-- Delete Challan Confirmation Modal -->
<div class="modal fade" id="deleteChallanConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-body text-center p-4">
                <div class="mb-3 d-inline-flex align-items-center justify-content-center"
                    style="width: 64px; height: 64px; background: rgba(239, 68, 68, 0.1); color: #ef4444; border-radius: 50%; font-size: 28px;">
                    🗑
                </div>
                <h5 class="modal-title mb-2" style="font-weight: 700; color: #1f2937;">Delete Challan</h5>
                <p class="text-muted mb-4" style="font-size: 14px;">Are you sure you want to delete this challan? This action cannot be undone.</p>

                <div class="d-flex justify-content-center gap-3">
                    <button type="button" class="btn" data-bs-dismiss="modal"
                        style="background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; border-radius: 8px; font-weight: 600; padding: 10px 0; width: 120px;">
                        Cancel
                    </button>
                    <button type="button" id="confirmDeleteChallanBtn"
                        style="background: linear-gradient(135deg, #ef4444, #e11d48); color: #fff; border: none; border-radius: 8px; font-weight: 600; padding: 10px 0; width: 120px; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('page-script')
<script>
    $(document).ready(function() {

        let table = $('#challanTable').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            dom: 'frtip',
            buttons: [{
                extend: 'csv',
                text: 'Export CSV',
                className: 'btn btn-sm btn-primary d-none',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7]
                }
            }],

            ajax: {
                url: "{{ route('admin.challan') }}",
                data: function(d) {
                    d.status = $('#statusFilter').val();
                }
            },

            columns: [{
                    data: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'challan_id'
                },
                {
                    data: 'case_id'
                },
                {
                    data: 'prahari_id'
                },
                {
                    data: 'prahari_name'
                },
                {
                    data: 'amount'
                },
                {
                    data: 'status_display'
                },
                {
                    data: 'date'
                },
                {
                    data: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        $('#exportCsvBtn').on('click', function() {
            table.button('.buttons-csv').trigger();
        });

        $('#statusFilter').change(function() {
            table.ajax.reload();
        });

        // DELETE CHALLAN - Custom Modal
        let challanIdToDelete = null;

        window.deleteChallanRecord = function(id) {
            challanIdToDelete = id;
            $('#deleteChallanConfirmModal').modal('show');
        };

        $('#confirmDeleteChallanBtn').on('click', function() {
            if (!challanIdToDelete) return;

            const confirmBtn = $(this);
            const originalHtml = confirmBtn.html();
            confirmBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');

            $.ajax({
                url: "{{ url('account/admin/challan') }}" + '/' + challanIdToDelete + '/record',
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#deleteChallanConfirmModal').modal('hide');
                    table.ajax.reload();
                    if (typeof showToast === 'function') {
                        showToast(response.message || 'Challan deleted successfully!', 'success');
                    }
                },
                error: function(xhr) {
                    $('#deleteChallanConfirmModal').modal('hide');
                    let message = 'Unable to delete challan.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    if (typeof showToast === 'function') {
                        showToast(message, 'error');
                    }
                },
                complete: function() {
                    confirmBtn.prop('disabled', false).html(originalHtml);
                    challanIdToDelete = null;
                }
            });
        });

    });

    function addChallan() {
        $('#addChallanModal').modal('show');
    }

    function markChallanPaid(id) {
        if (!confirm('Mark this challan as paid?')) return;
        const url = "{{ url('account/admin/challan') }}" + '/' + id + '/mark-paid';
        $.post(url, {_token: '{{ csrf_token() }}'})
            .done(function(res) {
                $('#challanTable').DataTable().ajax.reload();
                if (typeof showToast === 'function') showToast(res.message || 'Challan marked paid', 'success');
            })
            .fail(function(xhr) {
                let msg = 'Unable to mark challan paid.';
                if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                if (typeof showToast === 'function') showToast(msg, 'error');
            });
    }

    function markChallanPending(id) {
        if (!confirm('Mark this challan as pending?')) return;
        const url = "{{ url('account/admin/challan') }}" + '/' + id + '/mark-pending';
        $.post(url, {_token: '{{ csrf_token() }}'})
            .done(function(res) {
                $('#challanTable').DataTable().ajax.reload();
                if (typeof showToast === 'function') showToast(res.message || 'Challan marked pending', 'success');
            })
            .fail(function(xhr) {
                let msg = 'Unable to mark challan pending.';
                if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                if (typeof showToast === 'function') showToast(msg, 'error');
            });
    }
</script>
@endpush