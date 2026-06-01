@extends('layouts.admin.admin_master')

@push('page-style')
<style>
    .settings-container {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        overflow: hidden;
    }
    
    .settings-tabs {
        border-right: 1px solid #e5e7eb;
        background: #f9fafb;
    }
    
    .nav-pills .nav-link {
        color: #4b5563;
        font-weight: 500;
        border-radius: 0;
        padding: 15px 20px;
        text-align: left;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .nav-pills .nav-link.active {
        background: #fff;
        color: #111827;
        border-right: 2px solid #111827;
        font-weight: 600;
    }
    
    .nav-pills .nav-link:hover:not(.active) {
        background: #f3f4f6;
    }

    .settings-content {
        padding: 30px;
    }

    .form-group {
        margin-bottom: 20px;
    }
    
    .form-group label {
        font-weight: 500;
        color: #374151;
        margin-bottom: 8px;
        display: block;
    }

    .form-control {
        border: 1px solid #d1d5db;
        border-radius: 6px;
        padding: 10px 12px;
    }

    .form-control:focus {
        border-color: #9ca3af;
        box-shadow: 0 0 0 2px rgba(156, 163, 175, 0.2);
    }

    /* Toggle Switch Styling */
    .switch {
        position: relative;
        display: inline-block;
        width: 44px;
        height: 24px;
    }
    
    .switch input { 
        opacity: 0;
        width: 0;
        height: 0;
    }
    
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 24px;
    }
    
    .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }
    
    input:checked + .slider {
        background-color: #111827;
    }
    
    input:checked + .slider:before {
        transform: translateX(20px);
    }

    .setting-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 0;
        border-bottom: 1px solid #f3f4f6;
    }
    .setting-item:last-child {
        border-bottom: none;
    }
    .setting-info h6 {
        margin: 0;
        font-weight: 600;
        color: #111827;
    }
    .setting-info p {
        margin: 0;
        font-size: 13px;
        color: #6b7280;
    }

    .btn-save {
        background: #e1bb80;
        color: #fff;
        border: none;
        padding: 10px 24px;
        border-radius: 6px;
        font-weight: 500;
    }
    .btn-save:hover {
        background: #374151;
        color: #fff;
    }
</style>
@endpush

@section('page-content')

<div class="d-flex justify-content-between align-items-center mt-4 mb-4">
    <h4 class="fw-bold mb-0">System Settings</h4>
</div>

<form id="settingsForm">
    @csrf
    <div class="settings-container row g-0">
        <!-- Sidebar Tabs -->
        <div class="col-md-3 settings-tabs">
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <button class="nav-link active" id="v-pills-general-tab" data-bs-toggle="pill" data-bs-target="#v-pills-general" type="button" role="tab">General Settings</button>
                <button class="nav-link" id="v-pills-user-tab" data-bs-toggle="pill" data-bs-target="#v-pills-user" type="button" role="tab">User Management</button>
                <button class="nav-link" id="v-pills-payment-tab" data-bs-toggle="pill" data-bs-target="#v-pills-payment" type="button" role="tab">Payment Settings</button>
                <button class="nav-link" id="v-pills-notification-tab" data-bs-toggle="pill" data-bs-target="#v-pills-notification" type="button" role="tab">Notifications</button>
                <button class="nav-link" id="v-pills-security-tab" data-bs-toggle="pill" data-bs-target="#v-pills-security" type="button" role="tab">Security</button>
                <button class="nav-link" id="v-pills-report-tab" data-bs-toggle="pill" data-bs-target="#v-pills-report" type="button" role="tab">Reports</button>
                <button class="nav-link" id="v-pills-cases-tab" data-bs-toggle="pill" data-bs-target="#v-pills-cases" type="button" role="tab">Case Types</button>
                <button class="nav-link" id="v-pills-sidebar-tab" data-bs-toggle="pill" data-bs-target="#v-pills-sidebar" type="button" role="tab">Sidebar Menu</button>
            </div>
        </div>

        <!-- Tab Content -->
        <div class="col-md-9 settings-content">
            <div class="tab-content" id="v-pills-tabContent">
                
                <!-- General Settings -->
                <div class="tab-pane fade show active" id="v-pills-general" role="tabpanel">
                    <h5 class="fw-bold mb-4">General Settings</h5>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>App Name</label>
                            <input type="text" name="app_name" class="form-control" value="Prahari App">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Contact Email</label>
                            <input type="email" name="contact_email" class="form-control" value="support@prahari.com">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Support Number</label>
                            <input type="text" name="support_number" class="form-control" value="+91 9876543210">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>App Logo</label>
                            <input type="file" name="app_logo" class="form-control">
                        </div>
                    </div>
                </div>

                <!-- User Management -->
                <div class="tab-pane fade" id="v-pills-user" role="tabpanel">
                    <h5 class="fw-bold mb-4">User Management Settings</h5>
                    
                    <div class="setting-item">
                        <div class="setting-info">
                            <h6>Aadhaar Verification Toggle</h6>
                            <p>Require Aadhaar verification before Prahari account activation.</p>
                        </div>
                        <label class="switch">
                            <input type="checkbox" name="aadhaar_verification" checked>
                            <span class="slider"></span>
                        </label>
                    </div>

                    <div class="setting-item">
                        <div class="setting-info">
                            <h6>User Approval System</h6>
                            <p>Admin must manually approve new user registrations.</p>
                        </div>
                        <label class="switch">
                            <input type="checkbox" name="manual_approval" checked>
                            <span class="slider"></span>
                        </label>
                    </div>

                    <div class="setting-item">
                        <div class="setting-info">
                            <h6>Account Deactivation</h6>
                            <p>Allow users to temporarily deactivate their accounts.</p>
                        </div>
                        <label class="switch">
                            <input type="checkbox" name="allow_deactivation">
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>

                <!-- Payment Settings -->
                <div class="tab-pane fade" id="v-pills-payment" role="tabpanel">
                    <h5 class="fw-bold mb-4">Payment Settings</h5>
                    
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Minimum Withdrawal Limit (₹)</label>
                            <input type="number" name="min_withdrawal" class="form-control" value="500">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Maximum Withdrawal Limit (₹)</label>
                            <input type="number" name="max_withdrawal" class="form-control" value="50000">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Commission Percentage (%)</label>
                            <input type="number" step="0.1" name="commission_percent" class="form-control" value="5.0">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Flat Transaction Charge (₹)</label>
                            <input type="number" name="transaction_charge" class="form-control" value="10">
                        </div>
                    </div>
                </div>

                <!-- Notifications -->
                <div class="tab-pane fade" id="v-pills-notification" role="tabpanel">
                    <h5 class="fw-bold mb-4">Notification Settings</h5>
                    
                    <div class="setting-item">
                        <div class="setting-info">
                            <h6>Email Notifications</h6>
                            <p>Send system alerts and reports via Email.</p>
                        </div>
                        <label class="switch">
                            <input type="checkbox" name="email_notif" checked>
                            <span class="slider"></span>
                        </label>
                    </div>

                    <div class="setting-item">
                        <div class="setting-info">
                            <h6>SMS Notifications</h6>
                            <p>Send OTP and critical alerts via SMS.</p>
                        </div>
                        <label class="switch">
                            <input type="checkbox" name="sms_notif" checked>
                            <span class="slider"></span>
                        </label>
                    </div>

                    <div class="setting-item">
                        <div class="setting-info">
                            <h6>Push Notifications</h6>
                            <p>Enable in-app push notifications for Praharis.</p>
                        </div>
                        <label class="switch">
                            <input type="checkbox" name="push_notif" checked>
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>

                <!-- Security Settings -->
                <div class="tab-pane fade" id="v-pills-security" role="tabpanel">
                    <h5 class="fw-bold mb-4">Security Settings</h5>
                    
                    <div class="row mb-4">
                        <div class="col-md-6 form-group">
                            <label>Update Admin Password</label>
                            <input type="password" name="new_password" class="form-control" placeholder="New Password">
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Default Role Permissions</label>
                            <select name="default_role" class="form-control">
                                <option value="admin">Super Admin</option>
                                <option value="manager">Manager</option>
                                <option value="viewer">Viewer</option>
                            </select>
                        </div>
                    </div>

                    <div class="setting-item">
                        <div class="setting-info">
                            <h6>Two-Factor Authentication (2FA)</h6>
                            <p>Require OTP for Admin logins.</p>
                        </div>
                        <label class="switch">
                            <input type="checkbox" name="two_factor_auth">
                            <span class="slider"></span>
                        </label>
                    </div>

                    <div class="setting-item">
                        <div class="setting-info">
                            <h6>System Login Logs</h6>
                            <p>View history of all admin access attempts.</p>
                        </div>
                        <button type="button" class="btn btn-outline-dark btn-sm">View Logs</button>
                    </div>
                </div>

                <!-- Report Settings -->
                <div class="tab-pane fade" id="v-pills-report" role="tabpanel">
                    <h5 class="fw-bold mb-4">Report Settings</h5>
                    
                    <div class="setting-item">
                        <div class="setting-info">
                            <h6>Auto Report Generation</h6>
                            <p>Automatically generate and email weekly reports.</p>
                        </div>
                        <label class="switch">
                            <input type="checkbox" name="auto_report">
                            <span class="slider"></span>
                        </label>
                    </div>

                    <div class="setting-item">
                        <div class="setting-info">
                            <h6>Default Export Format</h6>
                            <p>Select the standard format for downloading reports.</p>
                        </div>
                        <select name="export_format" class="form-control w-auto">
                            <option value="csv">CSV (Spreadsheet)</option>
                            <option value="pdf">PDF Document</option>
                        </select>
                    </div>

                    <div class="setting-item">
                        <div class="setting-info">
                            <h6>Analytics Filters</h6>
                            <p>Enable advanced metrics in dashboard graphs.</p>
                        </div>
                        <label class="switch">
                            <input type="checkbox" name="advanced_analytics" checked>
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>

                <!-- Case Types Settings -->
                <div class="tab-pane fade" id="v-pills-cases" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <h5 class="fw-bold mb-0">Case Types (Penalties)</h5>
                        <button type="button" class="btn btn-dark btn-sm" onclick="addCategory()">+ Add Case Type</button>
                    </div>
                    <p class="text-muted mb-4" style="font-size: 14px;">Manage different types of traffic violations and define their corresponding penalty charges.</p>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped w-100" id="categoriesTable">
                            <thead style="background:#f3f4f6;">
                                <tr>
                                    <th>S.No</th>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Penalty (₹)</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                <!-- Sidebar Settings -->
                <div class="tab-pane fade" id="v-pills-sidebar" role="tabpanel">
                    <h5 class="fw-bold mb-4">Sidebar Menu Visibility</h5>
                    <p class="text-muted mb-4" style="font-size: 14px;">Toggle which menus are visible in the left sidebar navigation.</p>
                    
                    <div class="setting-item">
                        <div class="setting-info"><h6>Dashboard</h6></div>
                        <label class="switch">
                            <input type="checkbox" name="show_dashboard" {{ $sidebarSettings['show_dashboard'] ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                    </div>

                    <div class="setting-item">
                        <div class="setting-info"><h6>Praharis</h6></div>
                        <label class="switch">
                            <input type="checkbox" name="show_praharis" {{ $sidebarSettings['show_praharis'] ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                    </div>

                    <div class="setting-item">
                        <div class="setting-info"><h6>Cases</h6></div>
                        <label class="switch">
                            <input type="checkbox" name="show_cases" {{ $sidebarSettings['show_cases'] ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                    </div>

                    <div class="setting-item">
                        <div class="setting-info"><h6>Challans</h6></div>
                        <label class="switch">
                            <input type="checkbox" name="show_challans" {{ $sidebarSettings['show_challans'] ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                    </div>

                    <div class="setting-item">
                        <div class="setting-info"><h6>Payments</h6></div>
                        <label class="switch">
                            <input type="checkbox" name="show_payments" {{ $sidebarSettings['show_payments'] ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                    </div>

                    <div class="setting-item">
                        <div class="setting-info"><h6>Reports</h6></div>
                        <label class="switch">
                            <input type="checkbox" name="show_reports" {{ $sidebarSettings['show_reports'] ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                    </div>

                    <div class="setting-item">
                        <div class="setting-info"><h6>Admin Management</h6></div>
                        <label class="switch">
                            <input type="checkbox" name="show_admins" {{ $sidebarSettings['show_admins'] ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                    </div>

                    <div class="setting-item">
                        <div class="setting-info"><h6>Settings</h6></div>
                        <label class="switch">
                            <input type="checkbox" name="show_settings" {{ $sidebarSettings['show_settings'] ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>

            </div>

            <div class="mt-4 pt-3 border-top text-end">
                <button type="submit" class="btn-save">Save All Settings</button>
            </div>
        </div>
    </div>
</form>

<!-- Add/Edit Category Modal -->
<div class="modal fade" id="categoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoryModalTitle">Add Case Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="categoryForm">
                    <input type="hidden" id="category_id" name="id">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Case Type Name</label>
                        <input type="text" class="form-control" id="category_name" name="name" required placeholder="e.g. Without Helmet">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Penalty Amount (₹)</label>
                        <input type="number" class="form-control" id="category_amount" name="amount" required placeholder="e.g. 500">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Description</label>
                        <textarea class="form-control" id="category_description" name="description" rows="3" placeholder="Optional details..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Status</label>
                        <select class="form-control" id="category_status" name="status">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-dark" onclick="saveCategory()">Save Case Type</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('page-script')
<script>
$(document).ready(function() {
    $('#settingsForm').on('submit', function(e) {
        e.preventDefault();
        
        let formData = new FormData(this);
        
        $.ajax({
            url: "{{ route('admin.settings.save') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                alert(response.message);
            },
            error: function() {
                alert("An error occurred while saving settings.");
            }
        });
    });

    // Categories DataTable
    let categoriesTable = $('#categoriesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.categories') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'category_id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'amount', name: 'amount' },
            { data: 'description', name: 'description' },
            { 
                data: 'status', 
                name: 'status',
                render: function(data) {
                    return data == 1 ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
                }
            },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
});

function addCategory() {
    $('#categoryForm')[0].reset();
    $('#category_id').val('');
    $('#categoryModalTitle').text('Add Case Type');
    $('#categoryModal').modal('show');
}

function editCategory(id) {
    $.get("{{ url('account/admin/categories') }}/" + id + "/edit", function(data) {
        $('#category_id').val(data.id);
        $('#category_name').val(data.name);
        $('#category_amount').val(data.amount);
        $('#category_description').val(data.description);
        $('#category_status').val(data.status);
        $('#categoryModalTitle').text('Edit Case Type');
        $('#categoryModal').modal('show');
    }).fail(function(xhr) {
        alert("Unable to fetch category details.");
    });
}

function saveCategory() {
    let id = $('#category_id').val();
    let url = id ? "{{ url('account/admin/categories') }}/" + id : "{{ route('admin.categories.store') }}";
    let method = id ? "PUT" : "POST";
    let data = {
        _token: '{{ csrf_token() }}',
        name: $('#category_name').val(),
        amount: $('#category_amount').val(),
        description: $('#category_description').val(),
        status: $('#category_status').val()
    };

    $.ajax({
        url: url,
        method: method,
        data: data,
        success: function(response) {
            $('#categoryModal').modal('hide');
            $('#categoriesTable').DataTable().ajax.reload();
            alert(response.message);
        },
        error: function(xhr) {
            let message = "Error saving category.";
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }
            alert(message);
        }
    });
}

function deleteCategory(id) {
    if (confirm("Are you sure you want to delete this case type?")) {
        $.ajax({
            url: "{{ url('account/admin/categories') }}/" + id,
            method: "DELETE",
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#categoriesTable').DataTable().ajax.reload();
                alert(response.message);
            },
            error: function(xhr) {
                alert("Unable to delete case type.");
            }
        });
    }
}
</script>
@endpush
