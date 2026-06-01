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
        color: black !important;
        padding: 10px;
    }
    .simple-table tbody td {
        padding: 10px;
    }
    .simple-table tbody tr:hover {
        background: #f5f5f5;
    }
    
    /* Custom Validation Visual Feedback Styles */
    .form-control.is-invalid {
        border-color: #dc3545 !important;
        background-color: #fff8f8;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545'/%3e%3c/svg%3e") !important;
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }
    .form-control.is-valid {
        border-color: #198754 !important;
        background-color: #f8fff9;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e") !important;
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }
    .invalid-feedback {
        color: #dc3545;
        font-size: 0.85rem;
        margin-top: 4px;
        display: none;
    }
    .valid-feedback {
        color: #198754;
        font-size: 0.85rem;
        margin-top: 4px;
        display: none;
    }
    .form-control.is-invalid ~ .invalid-feedback {
        display: block;
    }
    .form-control.is-valid ~ .valid-feedback {
        display: block;
    }
    
    /* Premium Design System Styles */
    .btn-premium-dark {
        background: linear-gradient(135deg, #1e293b, #685634);
        color: #fff !important;
        border: none;
        border-radius: 8px;
        padding: 8px 16px;
        font-weight: 600;
        font-size: 14px;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.15);
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-premium-dark:hover {
        background: linear-gradient(135deg, #334155, #1e293b);
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.25);
    }
    .btn-premium-dark:active {
        transform: translateY(1px);
    }

    .btn-premium-secondary {
        background: #e1bb80;
        color: #475569 !important;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 8px 16px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-premium-secondary:hover {
        background: #e2e8f0;
        color: #1e293b !important;
        transform: translateY(-1px);
    }
    .btn-premium-secondary:active {
        transform: translateY(1px);
    }

    .simple-table {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        border: 1px solid #e2e8f0;
    }
    .simple-table thead th {
        background: #f8fafc !important;
        border-bottom: 2px solid #e2e8f0;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 0.5px;
    }

    .modal-content {
        border-radius: 16px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        border: none;
    }

    /* Support dark mode theme for page components */
    [data-theme-mode="dark"] .simple-table {
        background: #1e293b;
        border-color: #334155;
    }
    [data-theme-mode="dark"] .simple-table thead th {
        background: #1e293b !important;
        color: #f1f5f9 !important;
        border-color: #334155;
    }
    [data-theme-mode="dark"] .simple-table tbody td {
        color: #cbd5e1;
        border-color: #334155;
    }
    [data-theme-mode="dark"] .simple-table tbody tr:hover {
        background: #334155;
    }
    [data-theme-mode="dark"] .btn-premium-secondary {
        background: #334155;
        color: #f1f5f9 !important;
        border-color: #475569;
    }
    [data-theme-mode="dark"] .btn-premium-secondary:hover {
        background: #475569;
    }
    [data-theme-mode="dark"] .modal-content {
        background: #1e293b;
        color: #f1f5f9;
    }
    [data-theme-mode="dark"] .modal-header {
        border-bottom: 1px solid #334155;
    }
    [data-theme-mode="dark"] .modal-header .btn-close {
        filter: invert(1) grayscale(1) brightness(2);
    }
</style>
@endpush

@section('page-content')

<div class="row mt-3">
    <div class="col-sm-12">
        <!-- RIGHT SIDE BUTTON -->
        <div style="display: flex; justify-content: space-between; margin-bottom:10px;">
            <h4>Prahari List </h4>
            <div>
                <button id="exportCsvBtn" class="btn-premium-secondary me-1">
                    Export CSV
                </button>
                <button onclick="addPrahari()" class="btn-premium-dark">
                    + Add Prahari
                </button>
            </div>
        </div>

        <!-- TABLE -->
        <div class="table-responsive">
            <table class="simple-table" id="prahariTable">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Prahari ID</th>
                        <th>Full Name</th>
                        <th>Aadhar Number</th>
                        <th>Phone Number</th>
                        <th>Bank Account</th>
                        <th>Status</th>
                        <th>Joined Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <!-- ADD PRAHARI MODAL -->
        <div class="modal fade" id="addPrahariModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Add Prahari</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <form id="addPrahariForm" novalidate>
                            @csrf

                            <div class="mb-2">
                                <label>Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" placeholder="Enter Full Name" required>
                                <div class="invalid-feedback"></div>
                                <div class="valid-feedback">Field is valid.</div>
                            </div>

                            <div class="mb-2">
                                <label>Aadhar Number <span class="text-danger">*</span></label>
                                <input type="text" name="aadhar_number" class="form-control" placeholder="Enter 12-digit Aadhar Number" required>
                                <div class="invalid-feedback"></div>
                                <div class="valid-feedback">Field is valid.</div>
                            </div>

                            <div class="mb-2">
                                <label>Phone Number <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control" placeholder="Enter 10-digit Phone Number" required>
                                <div class="invalid-feedback"></div>
                                <div class="valid-feedback">Field is valid.</div>
                            </div>

                            <div class="mb-2">
                                <label>Bank Account Number <span class="text-danger">*</span></label>
                                <input type="text" name="bank_account_number" class="form-control" placeholder="Enter Bank Account Number" required>
                                <div class="invalid-feedback"></div>
                                <div class="valid-feedback">Field is valid.</div>
                            </div>

                            <input type="hidden" name="prahari_id" id="prahari_id" value="">
                            <input type="hidden" name="_method" id="prahari_form_method" value="POST">

                            <div class="mb-2">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>

                            <button type="submit" id="prahariFormSubmit" class="btn-premium-dark w-100 justify-content-center">
                                Save Prahari
                            </button>
                       </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- DELETE CONFIRMATION MODAL -->
        <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-body text-center p-4">
                        <div class="mb-3 d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background: rgba(239, 68, 68, 0.1); color: #ef4444; border-radius: 50%; font-size: 28px;">
                            🗑
                        </div>
                        <h5 class="modal-title mb-2" style="font-weight: 700; color: #1f2937;">Delete Prahari</h5>
                        <p class="text-muted mb-4" style="font-size: 14px;">Are you sure you want to delete this Prahari? This action cannot be undone.</p>
                        
                        <div class="d-flex justify-content-center gap-3">
                            <button type="button" class="btn btn-premium-secondary px-4" data-bs-dismiss="modal" style="width: 120px;">
                                Cancel
                            </button>
                            <button type="button" id="confirmDeleteBtn" class="btn px-4" style="background: linear-gradient(135deg, #ef4444, #e11d48); color: #fff; border: none; border-radius: 8px; font-weight: 600; padding: 10px 0; width: 120px; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('page-script')
<script>
    $(document).ready(function() {
        var table = $('#prahariTable').DataTable({
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
            ajax: "{{ route('admin.praharis') }}",

            columns: [{
                    data: 'DT_RowIndex'
                },
                {
                    data: 'prahari_id'
                },
                {
                    data: 'name'
                },
                {
                    data: 'aadhar_display'
                },
                {
                    data: 'phone_display'
                },
                {
                    data: 'bank_account_number'
                },
                {
                    data: 'status'
                },
                {
                    data: 'joined_date'
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
        // Dynamic Validation Rules
        var validationRules = {
            name: {
                pattern: /^[a-zA-Z\s]+$/,
                patternMsg: "Name must contain only letters and spaces.",
                minLen: 3,
                minLenMsg: "Name must be at least 3 characters."
            },
            aadhar_number: {
                pattern: /^\d{12}$/,
                patternMsg: "Aadhar number must be exactly 12 digits."
            },
            phone: {
                pattern: /^\d{10}$/,
                patternMsg: "Phone number must be exactly 10 digits."
            },
            bank_account_number: {
                pattern: /^\d{9,18}$/,
                patternMsg: "Bank account must be between 9 and 18 digits (numbers only)."
            }
        };

        function validateField(input, rules) {
            var value = input.val().trim();
            var isValid = true;
            var errorMsg = "";

            if (value === "") {
                if (input.prop('required')) {
                    isValid = false;
                    errorMsg = "This field is required.";
                }
            } else {
                if (rules.pattern && !rules.pattern.test(value)) {
                    isValid = false;
                    errorMsg = rules.patternMsg;
                } else if (rules.minLen && value.length < rules.minLen) {
                    isValid = false;
                    errorMsg = rules.minLenMsg;
                } else if (rules.maxLen && value.length > rules.maxLen) {
                    isValid = false;
                    errorMsg = rules.maxLenMsg;
                }
            }

            if (isValid) {
                input.removeClass('is-invalid').addClass('is-valid');
                input.siblings('.invalid-feedback').text('');
            } else {
                input.removeClass('is-valid').addClass('is-invalid');
                input.siblings('.invalid-feedback').text(errorMsg);
            }

            return isValid;
        }

        // Attach keyup/change listeners for real-time colors
        $('#addPrahariForm input[required]').on('keyup input change', function() {
            var name = $(this).attr('name');
            if (validationRules[name]) {
                validateField($(this), validationRules[name]);
            }
        });

       // FORM SUBMIT
        $('#addPrahariForm').submit(function(e) {
            e.preventDefault();

            var form = $(this);
            var isValid = true;
            var firstInvalidInput = null;

            // Validate all required inputs before submitting
            form.find('input[required]').each(function() {
                var name = $(this).attr('name');
                if (validationRules[name]) {
                    if (!validateField($(this), validationRules[name])) {
                        isValid = false;
                        if (!firstInvalidInput) {
                            firstInvalidInput = $(this);
                        }
                    }
                }
            });

            if (!isValid) {
                if (firstInvalidInput) {
                    firstInvalidInput.focus();
                }
                return false;
            }

            var actionUrl = "{{ route('admin.praharis.store') }}";

            if ($('#prahari_id').val()) {
                var id = $('#prahari_id').val();
                actionUrl = "{{ url('account/admin/praharis') }}" + '/' + id;
            }
            $.ajax({
                url: actionUrl,
                method: 'POST',
                data: form.serialize(),

                success: function(response) {
                    $('#addPrahariModal').modal('hide');
                    form[0].reset();
                    form.find('.is-valid, .is-invalid').removeClass('is-valid is-invalid');
                    form.find('.invalid-feedback').text('');
                    $('#prahari_id').val('');
                    $('#prahari_form_method').val('POST');
                    $('#addPrahariModal .modal-title').text('Add Prahari');
                    $('#prahariFormSubmit').text('Save Prahari');
                    table.ajax.reload();
                    showToast(response.message, 'success');
                },
                error: function(xhr) {
                    if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                        var errors = xhr.responseJSON.errors;
                        form.find('.is-valid, .is-invalid').removeClass('is-valid is-invalid');
                        
                        $.each(errors, function(field, messages) {
                            var input = form.find('[name="' + field + '"]');
                            if (input.length) {
                                input.addClass('is-invalid');
                                input.siblings('.invalid-feedback').text(messages.join(' '));
                            }
                        });
                        form.find('.is-invalid').first().focus();
                    } else {
                        let message = 'Error saving prahari';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            message = Object.values(xhr.responseJSON.errors).flat().join('\n');
                        }
                        showToast(message, 'error');
                    }
                }
            });
        });
        window.editPrahari = function(id) {
            $.ajax({
                url: "{{ url('account/admin/praharis') }}" + '/' + id + '/edit',
                method: 'GET',
                success: function(response) {
                    $('#prahari_id').val(response.id);
                    $('input[name="name"]').val(response.name);
                    $('input[name="aadhar_number"]').val(response.aadhar_number);
                    $('input[name="phone"]').val(response.phone);
                    $('input[name="bank_account_number"]').val(response.bank_account_number);
                    $('select[name="status"]').val(response.status ? '1' : '0');
                    $('#prahari_form_method').val('PUT');
                    
                    var form = $('#addPrahariForm');
                    form.find('.is-valid, .is-invalid').removeClass('is-valid is-invalid');
                    form.find('.invalid-feedback').text('');
                    
                    $('#addPrahariModal .modal-title').text('Edit Prahari');
                    $('#prahariFormSubmit').text('Update Prahari');
                    $('#addPrahariModal').modal('show');
                }
            });
        };
        let prahariIdToDelete = null;
        window.deletePrahari = function(id) {
            prahariIdToDelete = id;
            $('#deleteConfirmModal').modal('show');
        };

        $('#confirmDeleteBtn').on('click', function() {
            if (!prahariIdToDelete) return;

            const confirmBtn = $(this);
            const originalHtml = confirmBtn.html();
            confirmBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');

            $.ajax({
                url: "{{ url('account/admin/praharis') }}" + '/' + prahariIdToDelete,
                method: 'POST',
                data: {
                    _method: 'DELETE',
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#deleteConfirmModal').modal('hide');
                    table.ajax.reload();
                    showToast(response.message, 'success');
                },
                error: function(xhr) {
                    $('#deleteConfirmModal').modal('hide');
                    showToast('Unable to delete prahari.', 'error');
                },
                complete: function() {
                    confirmBtn.prop('disabled', false).html(originalHtml);
                    prahariIdToDelete = null;
                }
            });
        });
    });
    //delete prahari 
    // OUTSIDE ready (IMPORTANT)
    function addPrahari() {
        var form = $('#addPrahariForm');
        form[0].reset();
        form.find('.is-valid, .is-invalid').removeClass('is-valid is-invalid');
        form.find('.invalid-feedback').text('');
        $('#prahari_id').val('');
        $('#prahari_form_method').val('POST');
        $('#addPrahariModal .modal-title').text('Add Prahari');
        $('#prahariFormSubmit').text('Save Prahari');
        $('#addPrahariModal').modal('show');
    }
</script>
@endpush