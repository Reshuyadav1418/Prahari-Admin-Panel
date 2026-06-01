@extends('layouts.admin.admin_master')

@section('page-content')
<div class="row mt-3 mb-3">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <h4>Case Details</h4>
            <p class="text-muted mb-0">Prahari information, vehicle details, incident date/time and evidence video.</p>
        </div>
        <a href="{{ route('admin.cases') }}" class="btn " style="background-color: #e1bb80;">← Back to Cases</a>
    </div>
</div>

<div class="row gy-3">
    <div class="col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title mb-3">Prahari Information</h5>
                <div class="mb-2"><strong>Name:</strong> {{ $case->prahari->name ?? 'N/A' }}</div>
                <div class="mb-2"><strong>Phone:</strong> {{ $case->prahari->phone ?? 'N/A' }}</div>
                <div class="mb-2"><strong>Aadhar:</strong> {{ $case->prahari->aadhar_number ?? 'N/A' }}</div>
                <div class="mb-2"><strong>Status:</strong> 
                    @if($case->status === 'open')
                        <span class="badge bg-success">Open</span>
                    @elseif($case->status === 'in_progress')
                        <span class="badge bg-warning text-dark">Rejected</span>
                    @else
                        <span class="badge bg-primary">Approved</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title mb-3">Incident Details</h5>
                <div class="mb-2"><strong>Case ID:</strong> CASE-{{ $case->id }}</div>
                <div class="mb-2"><strong>Vehicle Number:</strong> {{ $case->vehicle_number ?? 'N/A' }}</div>
                <div class="mb-2"><strong>Category:</strong> {{ $case->category->name ?? 'N/A' }}</div>
                <div class="mb-2"><strong>Location:</strong> {{ $case->location ?? 'N/A' }}</div>
                <div class="mb-2"><strong>Date & Time:</strong> {{ $case->violation_datetime ? date('d M Y, h:i A', strtotime($case->violation_datetime)) : 'N/A' }}</div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title mb-3">Evidence Video</h5>
                @php
                    $videoUrl = null;
                    if ($case->evidence_file && $case->evidence_file !== 'N/A') {
                        $videoUrl = str_starts_with($case->evidence_file, 'http') ? $case->evidence_file : asset('storage/' . $case->evidence_file);
                    }
                @endphp

                @if($videoUrl)
                    <video width="100%" controls playsinline muted class="rounded shadow-sm" style="max-height: 520px;">
                        <source src="{{ $videoUrl }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                @else
                    <div class="alert alert-secondary mb-0">No evidence video available for this case.</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
