@extends('layouts.admin.admin_master')

@section('page-content')
<div class="row mt-3 mb-3">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <h4>My Profile</h4>
            <p class="text-muted mb-0">View your admin profile details here.</p>
        </div>
        <a href="{{ route('admin.cases') }}" class="btn" style="background-color:#e1bb80">← Back to Cases</a>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <div class="mb-4">
                    @if($user->profile_image)
                        <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Profile Image" class="rounded-circle shadow" style="width:140px;height:140px;object-fit:cover;" />
                    @else
                        <div class="rounded-circle border border-secondary d-inline-flex justify-content-center align-items-center" style="width:140px;height:140px;">
                            <i class="bx bx-user-circle fs-1 text-muted"></i>
                        </div>
                    @endif
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if($errors->has('profile_image'))
                    <div class="alert alert-danger">{{ $errors->first('profile_image') }}</div>
                @endif

                <form action="{{ route('admin.profile.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="profile_image" class="form-label">Upload profile image</label>
                        <input type="file" name="profile_image" id="profile_image" class="form-control" accept="image/*" />
                    </div>
                    <button type="submit" class="btn btn-primary">Save Image</button>
                </form>

                @if($user->profile_image)
                    <form action="{{ route('admin.profile.remove') }}" method="POST" class="mt-3">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger">Remove Profile Picture</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title mb-4">Profile Information</h5>
                <div class="mb-3"><strong>Name:</strong> {{ $user->name ?? 'N/A' }}</div>
                <div class="mb-3"><strong>Email:</strong> {{ $user->email ?? 'N/A' }}</div>
                <div class="mb-3"><strong>Role:</strong> {{ ucfirst(str_replace('_', ' ', $user->role ?? 'admin')) }}</div>
                <div class="mb-3"><strong>Joined:</strong> {{ $user->created_at ? $user->created_at->format('d M Y') : 'N/A' }}</div>
            </div>
        </div>
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-body">
                <h5 class="card-title mb-4">Account Actions</h5>
                <div class="mb-3">Use the header menu to access settings or logout.</div>
                <a class="btn me-2" style="background-color:#e1bb80" href="{{ route('admin.settings') }}">Settings</a>
                <a class="btn btn-outline-danger" href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('profile-logout-form').submit();">
                    Logout
                </a>
                <form id="profile-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
