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

    .status-badge {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: bold;
    }

    .status-active {
        background-color: #d4edda;
        color: #155724;
    }

    .status-inactive {
        background-color: #f8d7da;
        color: #721c24;
    }
</style>
@endpush

@section('page-content')

<div class="row mt-3 mb-2">
    <div class="col-sm-12 d-flex justify-content-between align-items-center">
        <h4>Admin Management</h4>

    </div>
</div>

<div class="table-responsive">
    <table class="simple-table" id="adminsTable">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Date Created</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<!-- Add Admin Modal -->
<div class="modal fade" id="addAdminModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5>Add Admin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="addAdminForm">
                    @csrf

                    <div class="mb-2">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required placeholder="Enter admin name">
                    </div>

                    <div class="mb-2">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required placeholder="Enter email address">
                    </div>

                    <div class="mb-2">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required placeholder="Enter password">
                    </div>

                    <div class="mb-2">
                        <label>Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required placeholder="Confirm password">
                    </div>

                    <div class="mb-2">
                        <label>Role</label>
                        <select name="role" class="form-control" required>
                            <option value="">Select Role</option>
                            <option value="admin">Admin</option>
                            <option value="super_admin">Super Admin</option>
                        </select>
                    </div>

                    <button type="submit" style="background:black;color:white;padding:6px 12px;border:none;cursor:pointer;">
                        Save Admin
                    </button>

                </form>
            </div>

        </div>
    </div>
</div>

<!-- Edit Admin Modal -->
<div class="modal fade" id="editAdminModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5>Edit Admin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="editAdminForm">
                    @csrf
                    @method('PUT')

                    <input type="hidden" id="admin_id" name="admin_id">

                    <div class="mb-2">
                        <label>Name</label>
                        <input type="text" id="edit_name" name="name" class="form-control" required>
                    </div>

                    <div class="mb-2">
                        <label>Email</label>
                        <input type="email" id="edit_email" name="email" class="form-control" required>
                    </div>

                    <div class="mb-2">
                        <label>Role</label>
                        <select id="edit_role" name="role" class="form-control" required>
                            <option value="">Select Role</option>
                            <option value="admin">Admin</option>
                            <option value="super_admin">Super Admin</option>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label>New Password (Leave empty to keep current)</label>
                        <input type="password" name="new_password" class="form-control" placeholder="Leave empty to keep current password">
                    </div>

                    <button type="submit" style="background:black;color:white;padding:6px 12px;border:none;cursor:pointer;">
                        Update Admin
                    </button>

                </form>
            </div>

        </div>
    </div>
</div>

@endsection

@push('page-script')
<script>
$(document).ready(function() {

    let table = $('#adminsTable').DataTable({
        processing: true,
        serverSide: true,
        destroy: true,

        ajax: {
            url: "{{ route('admin.admins.data') }}"
        },

        columns: [
            { data: 'DT_RowIndex' },
            { data: 'name' },
            { data: 'email' },
            { data: 'role' },
            { data: 'created_at' },
            { data: 'action', orderable: false, searchable: false }
        ]
    });

    // ADD ADMIN FORM SUBMISSION
    $('#addAdminForm').on('submit', function(e) {
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: "{{ route('admin.admins.store') }}",
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                alert('Admin added successfully!');
                $('#addAdminModal').modal('hide');
                $('#addAdminForm')[0].reset();
                table.ajax.reload();
            },
            error: function(xhr) {
                let message = 'Unable to add admin.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    message = Object.values(xhr.responseJSON.errors).flat().join('\n');
                }
                alert(message);
            }
        });
    });

    // EDIT ADMIN
    window.editAdmin = function(id) {
        $.ajax({
            url: "{{ url('account/admin/admins') }}" + '/' + id + '/edit',
            method: 'GET',
            success: function(response) {
                $('#admin_id').val(response.id);
                $('#edit_name').val(response.name);
                $('#edit_email').val(response.email);
                $('#edit_role').val(response.role);
                $('#editAdminModal').modal('show');
            },
            error: function(xhr) {
                alert('Unable to load admin data');
            }
        });
    };

    // EDIT ADMIN FORM SUBMISSION
    $('#editAdminForm').on('submit', function(e) {
        e.preventDefault();

        let adminId = $('#admin_id').val();
        let formData = new FormData(this);

        $.ajax({
            url: "{{ url('account/admin/admins') }}" + '/' + adminId,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                alert('Admin updated successfully!');
                $('#editAdminModal').modal('hide');
                $('#editAdminForm')[0].reset();
                table.ajax.reload();
            },
            error: function(xhr) {
                let message = 'Unable to update admin.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    message = Object.values(xhr.responseJSON.errors).flat().join('\n');
                }
                alert(message);
            }
        });
    });

    // DELETE ADMIN
    window.deleteAdmin = function(id) {
        if (!confirm('Are you sure you want to delete this admin?')) {
            return;
        }

        $.ajax({
            url: "{{ url('account/admin/admins') }}" + '/' + id,
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                alert('Admin deleted successfully!');
                table.ajax.reload();
            },
            error: function(xhr) {
                let message = 'Unable to delete admin.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                alert(message);
            }
        });
    };

});

// Add Admin Modal Show
function addAdmin() {
    $('#addAdminForm')[0].reset();
    $('#addAdminModal').modal('show');
}
</script>
@endpush
