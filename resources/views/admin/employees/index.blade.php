@extends('layouts.admin.app')

@section('title', 'Employee Management')
@section('page-heading', 'Employee Management')

@section('content')

<style>
.employee-page {
    --brown: #4A2616;
    --brown-light: #75452f;
    --cream: #f9efe3;
    --paper: #fffaf4;
    --text: #4a2f26;
    --muted: #8d7468;
    --line: #ead8c8;
    color: var(--text);
    font-family: 'DM Sans', sans-serif;
}
.employee-page * { box-sizing: border-box; }

.employee-hero {
    background: linear-gradient(130deg, #4A2616, #75452f);
    color: #fffaf4;
    padding: 27px 30px;
    border-radius: 18px;
    margin-bottom: 22px;
}
.employee-hero h2 {
    color: #fffaf4;
    font-size: 1.8rem;
    margin: 0 0 7px;
}
.employee-hero p {
    margin: 0;
    color: #f3dfd0;
}

.employee-stats {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 13px;
    margin-bottom: 22px;
}
.employee-stat {
    background: var(--paper);
    border: 1px solid var(--line);
    border-radius: 14px;
    padding: 18px;
}
.employee-stat span {
    color: var(--muted);
    font-size: .84rem;
    font-weight: 600;
}
.employee-stat h3 {
    color: var(--brown);
    font-size: 1.8rem;
    margin: 9px 0 0;
}

.employee-panel {
    background: var(--paper);
    border: 1px solid var(--line);
    border-radius: 16px;
    padding: 22px;
    margin-bottom: 22px;
}
.employee-panel-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    margin-bottom: 18px;
}
.employee-panel-header h3 {
    color: var(--brown);
    margin: 0 0 4px;
    font-size: 1.3rem;
}
.employee-panel-header p {
    color: var(--muted);
    margin: 0;
    font-size: .84rem;
}
.employee-add-btn {
    background: linear-gradient(135deg, #75452f, #4A2616);
    color: white;
    padding: 10px 17px;
    border-radius: 9px;
    text-decoration: none;
    font-weight: 700;
    font-size: .88rem;
    white-space: nowrap;
}
.employee-add-btn:hover {
    color: white;
    opacity: .9;
}

.employee-filters {
    display: flex;
    gap: 10px;
    margin-bottom: 18px;
}
.employee-filters input,
.employee-filters select {
    background: white;
    border: 1px solid var(--line);
    border-radius: 8px;
    padding: 10px 12px;
    color: var(--text);
    min-width: 0;
}
.employee-filters input { flex: 1; }
.employee-search-btn {
    border: 0;
    border-radius: 8px;
    padding: 10px 18px;
    background: var(--brown);
    color: white;
    font-weight: 600;
    cursor: pointer;
}
.employee-reset-btn {
    padding: 10px 14px;
    border: 1px solid var(--line);
    border-radius: 8px;
    color: var(--brown);
    text-decoration: none;
    background: white;
    font-size: .88rem;
}

.employee-table-wrap { overflow-x: auto; }
.employee-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 950px;
}
.employee-table th {
    background: var(--cream);
    color: var(--brown);
    padding: 13px 12px;
    text-align: left;
    font-size: .79rem;
    text-transform: uppercase;
    white-space: nowrap;
}
.employee-table td {
    padding: 13px 12px;
    border-bottom: 1px solid var(--line);
    font-size: .87rem;
    vertical-align: middle;
}
.employee-table tbody tr:hover { background: #fff5eb; }

.employee-person {
    display: flex;
    align-items: center;
    gap: 11px;
    min-width: 180px;
}
.employee-avatar {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    object-fit: cover;
    background: #f0e5d9;
    border: 1px solid var(--line);
    flex-shrink: 0;
}
.employee-avatar-placeholder {
    display: grid;
    place-items: center;
    color: var(--brown);
    font-weight: 700;
    font-size: 1rem;
}
.employee-person strong {
    display: block;
    color: var(--brown);
}
.employee-person small {
    display: block;
    color: var(--muted);
    margin-top: 3px;
    font-size: .76rem;
}

.employee-status {
    display: inline-block;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: .75rem;
    font-weight: 700;
    white-space: nowrap;
}
.employee-status.active {
    color: #287348;
    background: #e3f3e8;
}
.employee-status.inactive {
    color: #995044;
    background: #f8e5e1;
}
.employee-department {
    display: inline-block;
    padding: 5px 9px;
    border-radius: 7px;
    background: #f0e5d9;
    color: #65412d;
    font-size: .74rem;
    font-weight: 700;
}
.employee-actions {
    display: flex;
    gap: 7px;
}
.employee-actions a,
.employee-actions button {
    padding: 6px 9px;
    border-radius: 6px;
    text-decoration: none;
    font-size: .78rem;
    font-weight: 600;
    border: 0;
    cursor: pointer;
}
.employee-view { background: #f0e5d9; color: #65412d; }
.employee-edit { background: #eee4f2; color: #684879; }
.employee-delete { background: #f8e5e1; color: #995044; }

.employee-empty {
    text-align: center;
    padding: 38px 20px !important;
    color: var(--muted);
}
.employee-empty strong {
    display: block;
    color: var(--brown);
    margin-bottom: 6px;
    font-size: 1rem;
}
.employee-pagination { margin-top: 18px; }
.employee-alert {
    border-radius: 9px;
    padding: 12px 16px;
    margin-bottom: 18px;
}

@media(max-width: 768px) {
    .employee-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .employee-filters { flex-wrap: wrap; }
    .employee-filters input { flex-basis: 100%; }
    .employee-panel { padding: 15px; }
    .employee-panel-header { align-items: flex-start; }
}
@media(max-width: 480px) {
    .employee-stats { grid-template-columns: 1fr 1fr; }
    .employee-hero { padding: 22px; }
    .employee-hero h2 { font-size: 1.45rem; }
}
</style>

<div class="employee-page">

    <div class="employee-hero">
        <h2>Employee Management</h2>
        <p>Manage your bakery team, employee details, departments and employment status.</p>
    </div>

    <div class="employee-stats">
        <div class="employee-stat">
            <span>Total Employees</span>
            <h3>{{ $totalEmployees }}</h3>
        </div>

        <div class="employee-stat">
            <span>Active Employees</span>
            <h3>{{ $activeEmployees }}</h3>
        </div>

        <div class="employee-stat">
            <span>Inactive Employees</span>
            <h3>{{ $inactiveEmployees }}</h3>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success employee-alert">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger employee-alert">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="employee-panel">

        <div class="employee-panel-header">
            <div>
                <h3>All Employees</h3>
                <p>View and manage employees registered under your company.</p>
            </div>

            <a href="{{ route('admin.employees.create') }}" class="employee-add-btn">
                + Add Employee
            </a>
        </div>

        <form method="GET"
              action="{{ route('admin.employees.index') }}"
              class="employee-filters">

            <input type="text"
                   name="search"
                   placeholder="Search name, employee code, mobile or email..."
                   value="{{ request('search') }}">

            <select name="department">
                <option value="">All Departments</option>
                @foreach($departments as $department)
                    <option value="{{ $department }}"
                        {{ request('department') == $department ? 'selected' : '' }}>
                        {{ $department }}
                    </option>
                @endforeach
            </select>

            <select name="status">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>

            <button type="submit" class="employee-search-btn">Search</button>

            <a href="{{ route('admin.employees.index') }}" class="employee-reset-btn">Reset</a>
        </form>

        <div class="employee-table-wrap">
            <table class="employee-table">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Department</th>
                        <th>Designation</th>
                        <th>Mobile</th>
                        <th>Branch</th>
                        <th>Joining Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($employees as $employee)
                        <tr>
                            <td>
                                <div class="employee-person">
                                    @if($employee->photo)
                                        <img src="{{ asset('storage/' . $employee->photo) }}"
                                             alt="{{ $employee->name }}"
                                             class="employee-avatar">
                                    @else
                                        <div class="employee-avatar employee-avatar-placeholder">
                                            {{ strtoupper(substr($employee->name, 0, 1)) }}
                                        </div>
                                    @endif

                                    <div>
                                        <strong>{{ $employee->name }}</strong>
                                        <small>{{ $employee->employee_code }}</small>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="employee-department">
                                    {{ $employee->department ?? 'Not assigned' }}
                                </span>
                            </td>

                            <td>{{ $employee->designation ?? '—' }}</td>
                            <td>{{ $employee->mobile }}</td>
                            <td>{{ $employee->branch?->branch_name ?? 'Company Level' }}</td>
                            <td>{{ $employee->joining_date?->format('d M Y') ?? '—' }}</td>

                            <td>
                                <span class="employee-status {{ $employee->status }}">
                                    {{ ucfirst($employee->status) }}
                                </span>
                            </td>

                            <td>
                                <div class="employee-actions">
                                    <a href="{{ route('admin.employees.show', $employee->id) }}"
                                       class="employee-view">View</a>

                                    <a href="{{ route('admin.employees.edit', $employee->id) }}"
                                       class="employee-edit">Edit</a>

                                    <form action="{{ route('admin.employees.destroy', $employee->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this employee?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="employee-delete">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="employee-empty">
                                <strong>No employees found</strong>
                                Try changing your search or filters, or add your first employee.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="employee-pagination">
            {{ $employees->links() }}
        </div>

    </div>
</div>

@endsection