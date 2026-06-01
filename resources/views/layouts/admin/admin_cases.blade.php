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
</style>
@endpush

@section('page-content')

<div class="row mt-3 mb-2">
    <div class="col-sm-12 d-flex justify-content-between align-items-center">
        <h4>Cases Management </h4>
        <div>
            <button id="exportCsvBtn" style="background:#e1bb80;color:#fff;border:none;padding:6px 12px;" class="me-1">Export CSV</button>
            <button onclick="addCase()" style="background:#685634;color:#fff;border:none;padding:6px 12px;">
                + Prahari Add Case
            </button>
        </div>
    </div>
</div>

<div class="row mb-2">
    <div class="col-sm-3">
        <select id="statusFilter" class="form-control">
            <option value="">All Status</option>
            <option value="open">Open</option>
            <option value="in_progress">In Progress</option>
            <option value="closed">Closed</option>
        </select>
    </div>
</div>

<div class="table-responsive">
    <table class="simple-table" id="casesTable">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Case ID</th>
                <th>Prahari Name</th>
                <th>Type of Cases</th>
                <th>Location</th>
                <th>Status</th>
                <th>Date & Time</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<!-- add case moadal -->

<div class="modal fade" id="addCaseModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5>Add Case</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="addCaseForm" novalidate>
                    @csrf

                    <div class="mb-2">
                        <label>Prahari <span class="text-danger">*</span></label>
                        <select name="prahari_id" class="form-control" required>
                            <option value="">Select Prahari</option>
                            @foreach($praharis as $prahari)
                                <option value="{{ $prahari->id }}">{{ $prahari->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Please select a Prahari.</div>
                        <div class="valid-feedback">Field is valid.</div>
                    </div>

                    <div class="mb-2">
                        <label>Type of Cases <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-control" required>
                            <option value="">Select Type of Case</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }} (Penalty: ₹{{ $category->amount }})</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Please select a Type of Case.</div>
                        <div class="valid-feedback">Field is valid.</div>
                    </div>

                    <div class="mb-2">
                        <label>Vehicle Number <span class="text-danger">*</span></label>
                        <input type="text" name="vehicle_number" class="form-control" placeholder="e.g. MH-12-AB-1234" required>
                        <div class="invalid-feedback"></div>
                        <div class="valid-feedback">Field is valid.</div>
                    </div>

                    <div class="mb-2">
                        <label>Location <span class="text-danger">*</span></label>
                        <input type="text" name="location" class="form-control" placeholder="Enter violation location" required>
                        <div class="invalid-feedback"></div>
                        <div class="valid-feedback">Field is valid.</div>
                    </div>

                    <div class="mb-2">
                        <label>Date & Time <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="violation_datetime" class="form-control" required>
                        <div class="invalid-feedback">Please select a valid date & time.</div>
                        <div class="valid-feedback">Field is valid.</div>
                    </div>

                    <button type="submit" style="background:grey;color:white;padding:6px 12px;border:none;">
                        Save Case
                    </button>

                </form>
            </div>

        </div>
    </div>
</div>

<!-- View Evidence Modal -->
<div class="modal fade" id="viewEvidenceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">Evidence Video</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center pb-4">
                <video id="evidenceVideo" width="100%" controls playsinline muted type="video/mp4" class="rounded shadow-sm">
                    Your browser does not support the video tag.
                </video>
            </div>
        </div>
    </div>
</div>

<!-- Approve Confirmation Modal -->
<div class="modal fade" id="approveConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-body text-center p-4">
                <div class="mb-3 d-inline-flex align-items-center justify-content-center"
                     style="width: 64px; height: 64px; background: rgba(34, 197, 94, 0.12); color: #16a34a; border-radius: 50%; font-size: 30px;">
                    ✓
                </div>
                <h5 class="modal-title mb-2" style="font-weight: 700; color: #1f2937;">Approve Case</h5>
                <p class="text-muted mb-4" style="font-size: 14px;">Are you sure you want to approve this case? A challan will be generated.</p>

                <div class="d-flex justify-content-center gap-3">
                    <button type="button" class="btn" data-bs-dismiss="modal"
                        style="background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; border-radius: 8px; font-weight: 600; padding: 10px 0; width: 120px;">
                        Cancel
                    </button>
                    <button type="button" id="confirmApproveBtn"
                        style="background: linear-gradient(135deg, #22c55e, #16a34a); color: #fff; border: none; border-radius: 8px; font-weight: 600; padding: 10px 0; width: 120px; box-shadow: 0 4px 12px rgba(34, 197, 94, 0.25);">
                        Approve
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteCaseConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-body text-center p-4">
                <div class="mb-3 d-inline-flex align-items-center justify-content-center"
                     style="width: 64px; height: 64px; background: rgba(239, 68, 68, 0.1); color: #ef4444; border-radius: 50%; font-size: 28px;">
                    🗑
                </div>
                <h5 class="modal-title mb-2" style="font-weight: 700; color: #1f2937;">Delete Case</h5>
                <p class="text-muted mb-4" style="font-size: 14px;">Are you sure you want to delete this case? This action cannot be undone.</p>

                <div class="d-flex justify-content-center gap-3">
                    <button type="button" class="btn" data-bs-dismiss="modal"
                        style="background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; border-radius: 8px; font-weight: 600; padding: 10px 0; width: 120px;">
                        Cancel
                    </button>
                    <button type="button" id="confirmDeleteCaseBtn"
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

    let table = $('#casesTable').DataTable({
        processing: true,
        serverSide: true,
        destroy: true,
        dom: 'frtip',
        buttons: [
            {
                extend: 'csv',
                text: 'Export CSV',
                className: 'btn btn-sm btn-primary d-none',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6]
                }
            }
        ],

        ajax: {
            url: "{{ route('admin.cases') }}",
            data: function (d) {
                d.status = $('#statusFilter').val();
            }
        },

        columns: [
            { data: 'DT_RowIndex' },
            { data: 'id' },
            { data: 'prahari_name' },
            { data: 'category_name' },
            { data: 'location' },
            { data: 'status' },
            { data: 'violation_datetime' },
            { data: 'action', orderable: false, searchable: false }
        ]
    });

    $('#exportCsvBtn').on('click', function() {
        table.button('.buttons-csv').trigger();
    });

    // Reload on filter change
    $('#statusFilter').change(function() {
        table.ajax.reload();
    });

    // APPROVE CASE - Custom Modal
    let caseIdToApprove = null;

    window.approveCase = function(id) {
        caseIdToApprove = id;
        $('#approveConfirmModal').modal('show');
    };

    $('#confirmApproveBtn').on('click', function() {
        if (!caseIdToApprove) return;

        const confirmBtn = $(this);
        const originalHtml = confirmBtn.html();
        confirmBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');

        $.ajax({
            url: "{{ url('account/admin/cases') }}" + '/' + caseIdToApprove + '/approve',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#approveConfirmModal').modal('hide');
                table.ajax.reload();
                if (typeof showToast === 'function') {
                    showToast(response.message || 'Case approved successfully!', 'success');
                }
            },
            error: function(xhr) {
                $('#approveConfirmModal').modal('hide');
                let message = 'Unable to approve case.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                if (typeof showToast === 'function') {
                    showToast(message, 'error');
                }
            },
            complete: function() {
                confirmBtn.prop('disabled', false).html(originalHtml);
                caseIdToApprove = null;
            }
        });
    });



    // DELETE CASE - Custom Modal
    let caseIdToDelete = null;

    window.deleteCase = function(id) {
        caseIdToDelete = id;
        $('#deleteCaseConfirmModal').modal('show');
    };

    $('#confirmDeleteCaseBtn').on('click', function() {
        if (!caseIdToDelete) return;

        const confirmBtn = $(this);
        const originalHtml = confirmBtn.html();
        confirmBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');

        $.ajax({
            url: "{{ url('account/admin/cases') }}" + '/' + caseIdToDelete,
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#deleteCaseConfirmModal').modal('hide');
                table.ajax.reload();
                if (typeof showToast === 'function') {
                    showToast(response.message || 'Case deleted successfully!', 'success');
                }
            },
            error: function(xhr) {
                $('#deleteCaseConfirmModal').modal('hide');
                let message = 'Unable to delete case.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                if (typeof showToast === 'function') {
                    showToast(message, 'error');
                }
            },
            complete: function() {
                confirmBtn.prop('disabled', false).html(originalHtml);
                caseIdToDelete = null;
            }
        });
    });

    // Case Validation Rules
    var validationRules = {
        prahari_id: {
            requiredMsg: "Please select a Prahari."
        },
        category_id: {
            requiredMsg: "Please select a Category."
        },
        vehicle_number: {
            pattern: /^[a-zA-Z0-9\s\-]{4,15}$/,
            patternMsg: "Vehicle number must be alphanumeric, between 4 and 15 characters.",
            requiredMsg: "Vehicle number is required."
        },
        location: {
            minLen: 3,
            minLenMsg: "Location must be at least 3 characters.",
            requiredMsg: "Location is required."
        },
        violation_datetime: {
            requiredMsg: "Please select a valid date & time."
        }
    };

    function validateField(input, rules) {
        var value = input.val() ? input.val().toString().trim() : "";
        var isValid = true;
        var errorMsg = "";

        if (value === "") {
            if (input.prop('required')) {
                isValid = false;
                errorMsg = rules.requiredMsg || "This field is required.";
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

    // Attach listeners for real-time validation colors
    $('#addCaseForm input, #addCaseForm select').on('keyup input change', function() {
        var name = $(this).attr('name');
        if (validationRules[name]) {
            validateField($(this), validationRules[name]);
        }
    });

    // FORM SUBMIT
    $('#addCaseForm').submit(function(e) {
        e.preventDefault();

        var form = $(this);
        var isValid = true;
        var firstInvalidInput = null;

        // Validate all required fields before submitting
        form.find('input[required], select[required]').each(function() {
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

        $.ajax({
            url: "{{ route('admin.cases.store') }}",
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                $('#addCaseModal').modal('hide');
                form[0].reset();
                form.find('.is-valid, .is-invalid').removeClass('is-valid is-invalid');
                form.find('.invalid-feedback').text('');
                table.ajax.reload();
                alert(response.message);
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
                    let message = 'Error saving case';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    alert(message);
                }
            }
        });
    });

});

// VIEW EVIDENCE
window.viewEvidence = function(url) {
    var video = $('#evidenceVideo')[0];
    
    // Reset video
    video.pause();
    $(video).attr('src', url);
    video.load();

    $('#viewEvidenceModal').modal('show');

    // Play when modal is fully visible and video is ready
    $('#viewEvidenceModal').one('shown.bs.modal', function () {
        var playPromise = video.play();
        if (playPromise !== undefined) {
            playPromise.then(_ => {
                // Autoplay started!
            }).catch(error => {
                console.log("Auto-play was prevented. User interaction might be required.", error);
            });
        }
    });
};

// Stop video when modal is closed
$(document).on('hidden.bs.modal', '#viewEvidenceModal', function () {
    var video = $('#evidenceVideo')[0];
    video.pause();
    $(video).attr('src', '');
    video.load();
});

// Add Case redirect
// Add Case opening modal
function addCase() {
     var form = $('#addCaseForm');
     form[0].reset();
     form.find('.is-valid, .is-invalid').removeClass('is-valid is-invalid');
     form.find('.invalid-feedback').text('');
     $('#addCaseModal').modal('show');
}
</script>
@endpush