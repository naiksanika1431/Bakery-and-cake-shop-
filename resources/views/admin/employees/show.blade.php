@extends('layouts.admin.app')

@section('title', 'Employee Profile')
@section('page-heading', 'Employee Profile')

@section('content')
<style>
.employee-profile-page {
    color: #4a2f26;
    font-family: 'DM Sans', sans-serif;
}
.employee-profile-page .profile-hero {
    background: linear-gradient(120deg, #4A2616, #79513b);
    color: #fffaf4;
    border-radius: 18px;
    padding: 26px;
    margin-bottom: 22px;
}
.employee-profile-page .profile-photo {
    width: 105px;
    height: 105px;
    border-radius: 18px;
    object-fit: cover;
    border: 3px solid #f6c9b9;
}
.employee-profile-page .profile-avatar {
    width: 105px;
    height: 105px;
    border-radius: 18px;
    background: #f6c9b9;
    color: #4A2616;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 38px;
    font-weight: 700;
}
.employee-profile-page .profile-hero h2 {
    font-family: 'DM Serif Display', serif;
    margin: 0 0 5px;
    font-size: 28px;
}
.employee-profile-page .profile-code {
    color: #f6dfce;
    font-size: 14px;
}
.employee-profile-page .profile-panel {
    background: #fffaf4;
    border: 1px solid #ead8c8;
    border-radius: 16px;
    padding: 23px;
    margin-bottom: 20px;
}
.employee-profile-page .section-heading {
    font-family: 'DM Serif Display', serif;
    font-size: 20px;
    color: #4A2616;
    padding-bottom: 12px;
    margin-bottom: 20px;
    border-bottom: 1px solid #ead8c8;
}
.employee-profile-page .detail-label {
    color: #8d7468;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .5px;
    margin-bottom: 5px;
}
.employee-profile-page .detail-value {
    color: #4a2f26;
    font-size: 15px;
    font-weight: 600;
    overflow-wrap: anywhere;
}
.employee-profile-page .detail-item {
    margin-bottom: 20px;
}
.employee-profile-page .status-badge {
    display: inline-block;
    border-radius: 20px;
    padding: 6px 13px;
    font-size: 12px;
    font-weight: 700;
    text-transform: capitalize;
}
.employee-profile-page .status-active {
    background: #e2f1e7;
    color: #28623c;
}
.employee-profile-page .status-inactive {
    background: #f8e3df;
    color: #a04438;
}
.employee-profile-page .btn-edit {
    background: #f6c9b9;
    color: #4A2616;
    border: 0;
    border-radius: 9px;
    padding: 10px 17px;
    text-decoration: none;
    font-weight: 700;
}
.employee-profile-page .btn-back {
    background: #fffaf4;
    color: #4A2616;
    border: 1px solid #ead8c8;
    border-radius: 9px;
    padding: 10px 17px;
    text-decoration: none;
    font-weight: 600;
}
.employee-profile-page .btn-delete {
    background: #fff0ed;
    color: #a04438;
    border: 1px solid #f0c8c1;
    border-radius: 9px;
    padding: 10px 17px;
    font-weight: 600;
}
@media(max-width: 767px) {
    .employee-profile-page .profile-panel { padding: 17px; }
    .employee-profile-page .profile-hero { padding: 20px; }
}
</style>

<div class="employee-profile-page">

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="profile-hero">
        <div class="d-flex flex-wrap align-items-center gap-4">
            @if ($employee->photo)
                <img src="{{ asset('storage/' . $employee->photo) }}"
                     alt="{{ $employee->name }}" class="profile-photo">
            @else
                <div class="profile-avatar">
                    {{ strtoupper(substr($employee->name, 0, 1)) }}
                </div>
            @endif

            <div class="flex-grow-1">
                <h2>{{ $employee->name }}</h2>
                <div class="profile-code">{{ $employee->employee_code }}</div>
                <div class="mt-2">
                    <span class="status-badge {{ $employee->status === 'active' ? 'status-active' : 'status-inactive' }}">
                        {{ ucfirst($employee->status) }}
                    </span>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.employees.edit', $employee->id) }}" class="btn-edit">
                    <i class="bi bi-pencil-square me-1"></i> Edit Employee
                </a>
                <a href="{{ route('admin.employees.index') }}" class="btn-back">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>
    </div>

    <div class="profile-panel">
        <h3 class="section-heading">Personal Information</h3>
        <div class="row">
            <div class="col-md-4 detail-item">
                <div class="detail-label">Full Name</div>
                <div class="detail-value">{{ $employee->name }}</div>
            </div>
            <div class="col-md-4 detail-item">
                <div class="detail-label">Mobile Number</div>
                <div class="detail-value">{{ $employee->mobile }}</div>
            </div>
            <div class="col-md-4 detail-item">
                <div class="detail-label">Email Address</div>
                <div class="detail-value">{{ $employee->email ?: '—' }}</div>
            </div>
            <div class="col-md-4 detail-item">
                <div class="detail-label">Date of Birth</div>
                <div class="detail-value">{{ $employee->date_of_birth?->format('d M Y') ?? '—' }}</div>
            </div>
            <div class="col-md-4 detail-item">
                <div class="detail-label">Gender</div>
                <div class="detail-value">{{ $employee->gender ? ucfirst($employee->gender) : '—' }}</div>
            </div>
            <div class="col-md-12 detail-item">
                <div class="detail-label">Address</div>
                <div class="detail-value">{{ $employee->address ?: '—' }}</div>
            </div>
        </div>
    </div>

    <div class="profile-panel">
        <h3 class="section-heading">Employment Details</h3>
        <div class="row">
            <div class="col-md-4 detail-item">
                <div class="detail-label">Employee Code</div>
                <div class="detail-value">{{ $employee->employee_code }}</div>
            </div>
            <div class="col-md-4 detail-item">
                <div class="detail-label">Department</div>
                <div class="detail-value">{{ $employee->department ?: '—' }}</div>
            </div>
            <div class="col-md-4 detail-item">
                <div class="detail-label">Designation</div>
                <div class="detail-value">{{ $employee->designation ?: '—' }}</div>
            </div>
            <div class="col-md-4 detail-item">
                <div class="detail-label">Branch</div>
                <div class="detail-value">{{ $employee->branch?->branch_name ?? 'Company Level' }}</div>
            </div>
            <div class="col-md-4 detail-item">
                <div class="detail-label">Joining Date</div>
                <div class="detail-value">{{ $employee->joining_date?->format('d M Y') ?? '—' }}</div>
            </div>
            <div class="col-md-4 detail-item">
                <div class="detail-label">Employment Type</div>
                <div class="detail-value">
                    {{ $employee->employment_type ? ucwords(str_replace('_', ' ', $employee->employment_type)) : '—' }}
                </div>
            </div>
            <div class="col-md-4 detail-item">
                <div class="detail-label">Shift</div>
                <div class="detail-value">{{ $employee->shift ?: '—' }}</div>
            </div>
            <div class="col-md-4 detail-item">
                <div class="detail-label">Status</div>
                <div class="detail-value">{{ ucfirst($employee->status) }}</div>
            </div>
        </div>
    </div>

    <div class="profile-panel">
        <h3 class="section-heading">Salary Information</h3>
        <div class="row">
            <div class="col-md-4 detail-item">
                <div class="detail-label">Salary / Wage</div>
                <div class="detail-value">
                    {{ $employee->salary !== null ? '₹' . number_format((float) $employee->salary, 2) : '—' }}
                </div>
            </div>
            <div class="col-md-4 detail-item">
                <div class="detail-label">Payment Type</div>
                <div class="detail-value">{{ $employee->payment_type ? ucfirst($employee->payment_type) : '—' }}</div>
            </div>
        </div>
    </div>

    <div class="profile-panel">
        <h3 class="section-heading">Emergency Contact</h3>
        <div class="row">
            <div class="col-md-4 detail-item">
                <div class="detail-label">Contact Person</div>
                <div class="detail-value">{{ $employee->emergency_contact_name ?: '—' }}</div>
            </div>
            <div class="col-md-4 detail-item">
                <div class="detail-label">Contact Mobile</div>
                <div class="detail-value">{{ $employee->emergency_contact_mobile ?: '—' }}</div>
            </div>
            <div class="col-md-4 detail-item">
                <div class="detail-label">Relationship</div>
                <div class="detail-value">{{ $employee->emergency_contact_relation ?: '—' }}</div>
            </div>
        </div>
    </div>

    @if ($employee->notes)
        <div class="profile-panel">
            <h3 class="section-heading">Additional Notes</h3>
            <div class="detail-value">{{ $employee->notes }}</div>
        </div>
    @endif

    <div class="d-flex justify-content-end mb-4">
        <form action="{{ route('admin.employees.destroy', $employee->id) }}"
              method="POST"
              onsubmit="return confirm('Are you sure you want to delete this employee? This action cannot be undone.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-delete">
                <i class="bi bi-trash me-1"></i> Delete Employee
            </button>
        </form>
    </div>

</div>
@endsection