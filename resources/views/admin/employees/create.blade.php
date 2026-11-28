@extends('layouts.admin.app')

@section('title', 'Add Employee')
@section('page-heading', 'Add Employee')

@section('content')
<style>
.employee-form-page {
    color: #4a2f26;
    font-family: 'DM Sans', sans-serif;
}
.employee-form-page .form-hero {
    background: linear-gradient(120deg, #4A2616, #79513b);
    color: #fffaf4;
    border-radius: 18px;
    padding: 25px 28px;
    margin-bottom: 22px;
}
.employee-form-page .form-hero h2 {
    font-family: 'DM Serif Display', serif;
    margin: 0 0 6px;
    font-size: 27px;
}
.employee-form-page .form-hero p {
    margin: 0;
    color: #f6dfce;
}
.employee-form-page .form-panel {
    background: #fffaf4;
    border: 1px solid #ead8c8;
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 20px;
}
.employee-form-page .section-heading {
    font-family: 'DM Serif Display', serif;
    font-size: 20px;
    color: #4A2616;
    padding-bottom: 12px;
    margin-bottom: 20px;
    border-bottom: 1px solid #ead8c8;
}
.employee-form-page .form-label {
    font-weight: 600;
    color: #5a3427;
    margin-bottom: 7px;
}
.employee-form-page .form-control,
.employee-form-page .form-select {
    border: 1px solid #e4d2c2;
    border-radius: 9px;
    padding: 10px 12px;
    background-color: #fff;
    color: #4a2f26;
}
.employee-form-page .form-control:focus,
.employee-form-page .form-select:focus {
    border-color: #d9a066;
    box-shadow: 0 0 0 3px rgba(217,160,102,.16);
}
.employee-form-page .required {
    color: #bd554b;
}
.employee-form-page .form-hint {
    color: #8d7468;
    font-size: 12px;
    margin-top: 5px;
}
.employee-form-page .btn-save {
    background: #4A2616;
    color: #fffaf4;
    border: 0;
    border-radius: 9px;
    padding: 11px 22px;
    font-weight: 600;
}
.employee-form-page .btn-save:hover {
    background: #6a3b25;
    color: #fff;
}
.employee-form-page .btn-cancel {
    background: #f6ebdd;
    color: #5a3427;
    border: 1px solid #ead8c8;
    border-radius: 9px;
    padding: 11px 22px;
    font-weight: 600;
    text-decoration: none;
}
.employee-form-page .invalid-feedback {
    display: block;
}
@media(max-width: 767px) {
    .employee-form-page .form-panel { padding: 17px; }
    .employee-form-page .form-hero { padding: 20px; }
}
</style>

<div class="employee-form-page">
    <div class="form-hero">
        <h2>Add New Employee</h2>
        <p>Register a team member and maintain their employment details.</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please correct the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.employees.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-panel">
            <h3 class="section-heading">Personal Information</h3>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Full Name <span class="required">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" placeholder="Enter employee full name" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Mobile Number <span class="required">*</span></label>
                    <input type="text" name="mobile" class="form-control @error('mobile') is-invalid @enderror"
                           value="{{ old('mobile') }}" placeholder="Enter mobile number" required>
                    @error('mobile') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" placeholder="employee@example.com">
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Date of Birth</label>
                    <input type="date" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror"
                           value="{{ old('date_of_birth') }}">
                    @error('date_of_birth') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                        <option value="">Select gender</option>
                        <option value="male" @selected(old('gender') === 'male')>Male</option>
                        <option value="female" @selected(old('gender') === 'female')>Female</option>
                        <option value="other" @selected(old('gender') === 'other')>Other</option>
                    </select>
                    @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Employee Photo</label>
                    <input type="file" name="photo" accept=".jpg,.jpeg,.png,.webp"
                           class="form-control @error('photo') is-invalid @enderror">
                    <div class="form-hint">JPG, PNG or WEBP. Maximum size: 2 MB.</div>
                    @error('photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Address</label>
                    <textarea name="address" rows="3" class="form-control"
                              placeholder="Enter complete address">{{ old('address') }}</textarea>
                </div>
            </div>
        </div>

        <div class="form-panel">
            <h3 class="section-heading">Employment Details</h3>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Branch</label>
                    <select name="branch_id" class="form-select @error('branch_id') is-invalid @enderror">
                        <option value="">Company Level / No Specific Branch</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}" @selected(old('branch_id') == $branch->id)>
                                {{ $branch->branch_name }} ({{ $branch->branch_code }})
                            </option>
                        @endforeach
                    </select>
                    @error('branch_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Department</label>
                    <select name="department" class="form-select">
                        <option value="">Select department</option>
                        @foreach ([
                            'Bakery / Cake Production',
                            'Cake Decoration',
                            'Kitchen',
                            'Sales & Counter',
                            'Delivery',
                            'Inventory',
                            'Management',
                            'Cleaning & Maintenance'
                        ] as $department)
                            <option value="{{ $department }}" @selected(old('department') === $department)>
                                {{ $department }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Designation</label>
                    <input type="text" name="designation" class="form-control"
                           value="{{ old('designation') }}" placeholder="e.g. Head Baker, Cashier">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Joining Date</label>
                    <input type="date" name="joining_date" class="form-control @error('joining_date') is-invalid @enderror"
                           value="{{ old('joining_date') }}">
                    @error('joining_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Employment Type</label>
                    <select name="employment_type" class="form-select">
                        <option value="">Select employment type</option>
                        <option value="full_time" @selected(old('employment_type') === 'full_time')>Full Time</option>
                        <option value="part_time" @selected(old('employment_type') === 'part_time')>Part Time</option>
                        <option value="contract" @selected(old('employment_type') === 'contract')>Contract</option>
                        <option value="temporary" @selected(old('employment_type') === 'temporary')>Temporary</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Shift</label>
                    <input type="text" name="shift" class="form-control"
                           value="{{ old('shift') }}" placeholder="e.g. Morning, Evening, 9 AM - 6 PM">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Employment Status <span class="required">*</span></label>
                    <select name="status" class="form-select" required>
                        <option value="active" @selected(old('status', 'active') === 'active')>Active</option>
                        <option value="inactive" @selected(old('status') === 'inactive')>Inactive</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-panel">
            <h3 class="section-heading">Salary Information</h3>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Salary / Wage Amount (₹)</label>
                    <input type="number" name="salary" step="0.01" min="0"
                           class="form-control @error('salary') is-invalid @enderror"
                           value="{{ old('salary') }}" placeholder="Enter amount">
                    @error('salary') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Payment Type</label>
                    <select name="payment_type" class="form-select">
                        <option value="">Select payment type</option>
                        <option value="monthly" @selected(old('payment_type') === 'monthly')>Monthly</option>
                        <option value="weekly" @selected(old('payment_type') === 'weekly')>Weekly</option>
                        <option value="daily" @selected(old('payment_type') === 'daily')>Daily</option>
                        <option value="hourly" @selected(old('payment_type') === 'hourly')>Hourly</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-panel">
            <h3 class="section-heading">Emergency Contact</h3>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Contact Person Name</label>
                    <input type="text" name="emergency_contact_name" class="form-control"
                           value="{{ old('emergency_contact_name') }}" placeholder="Contact person name">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Contact Mobile</label>
                    <input type="text" name="emergency_contact_mobile" class="form-control"
                           value="{{ old('emergency_contact_mobile') }}" placeholder="Mobile number">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Relationship</label>
                    <input type="text" name="emergency_contact_relation" class="form-control"
                           value="{{ old('emergency_contact_relation') }}" placeholder="e.g. Parent, Spouse">
                </div>

                <div class="col-12">
                    <label class="form-label">Additional Notes</label>
                    <textarea name="notes" rows="3" class="form-control"
                              placeholder="Any additional employee information">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2 justify-content-end mb-4">
            <a href="{{ route('admin.employees.index') }}" class="btn-cancel">Cancel</a>
            <button type="submit" class="btn-save">
                <i class="bi bi-person-plus me-1"></i> Save Employee
            </button>
        </div>
    </form>
</div>
@endsection