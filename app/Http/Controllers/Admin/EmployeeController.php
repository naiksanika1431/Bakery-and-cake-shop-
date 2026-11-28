<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    private function activeCompany(): Company
    {
        return Company::where('company_code', 'DELIGHT')
            ->where('is_active', true)
            ->firstOrFail();
    }

    public function index(Request $request)
    {
        $company = $this->activeCompany();

        $employees = Employee::where('company_id', $company->id)
            ->with('branch')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('employee_code', 'like', "%{$search}%")
                        ->orWhere('mobile', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('department'), function ($query) use ($request) {
                $query->where('department', $request->department);
            })
            ->when($request->status === 'active', fn ($query) => $query->where('status', 'active'))
            ->when($request->status === 'inactive', fn ($query) => $query->where('status', 'inactive'))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $totalEmployees = Employee::where('company_id', $company->id)->count();

        $activeEmployees = Employee::where('company_id', $company->id)
            ->where('status', 'active')
            ->count();

        $inactiveEmployees = Employee::where('company_id', $company->id)
            ->where('status', 'inactive')
            ->count();

        $departments = Employee::where('company_id', $company->id)
            ->whereNotNull('department')
            ->distinct()
            ->orderBy('department')
            ->pluck('department');

        return view('admin.employees.index', compact(
            'employees',
            'totalEmployees',
            'activeEmployees',
            'inactiveEmployees',
            'departments'
        ));
    }

    public function create()
    {
        $company = $this->activeCompany();

        $branches = Branch::where('company_id', $company->id)
            ->where('is_active', true)
            ->orderBy('branch_name')
            ->get();

        return view('admin.employees.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $company = $this->activeCompany();

        $validated = $request->validate([
            'branch_id' => [
                'nullable',
                'integer',
                Rule::exists('branches', 'id')
                    ->where('company_id', $company->id),
            ],
            'name' => ['required', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'mobile' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string'],
            'department' => ['nullable', 'string', 'max:100'],
            'designation' => ['nullable', 'string', 'max:100'],
            'joining_date' => ['nullable', 'date'],
            'employment_type' => ['nullable', Rule::in(['full_time', 'part_time', 'contract', 'temporary'])],
            'shift' => ['nullable', 'string', 'max:100'],
            'salary' => ['nullable', 'numeric', 'min:0', 'max:9999999999.99'],
            'payment_type' => ['nullable', Rule::in(['monthly', 'weekly', 'daily', 'hourly'])],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_mobile' => ['nullable', 'string', 'max:20'],
            'emergency_contact_relation' => ['nullable', 'string', 'max:100'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'notes' => ['nullable', 'string'],
        ]);

        do {
            $employeeCode = 'EMP-' . Str::upper(Str::random(8));
        } while (Employee::where('employee_code', $employeeCode)->exists());

        $validated['company_id'] = $company->id;
        $validated['employee_code'] = $employeeCode;

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')
                ->store('employees', 'public');
        }

        $employee = Employee::create($validated);

        return redirect()
            ->route('admin.employees.show', $employee->id)
            ->with('success', 'Employee added successfully.');
    }

    public function show(string $id)
    {
        $company = $this->activeCompany();

        $employee = Employee::where('company_id', $company->id)
            ->with(['company', 'branch'])
            ->findOrFail($id);

        return view('admin.employees.show', compact('employee'));
    }

    public function edit(string $id)
    {
        $company = $this->activeCompany();

        $employee = Employee::where('company_id', $company->id)
            ->findOrFail($id);

        $branches = Branch::where('company_id', $company->id)
            ->where('is_active', true)
            ->orderBy('branch_name')
            ->get();

        return view('admin.employees.edit', compact('employee', 'branches'));
    }

    public function update(Request $request, string $id)
    {
        $company = $this->activeCompany();

        $employee = Employee::where('company_id', $company->id)
            ->findOrFail($id);

        $validated = $request->validate([
            'branch_id' => [
                'nullable',
                'integer',
                Rule::exists('branches', 'id')
                    ->where('company_id', $company->id),
            ],
            'name' => ['required', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'mobile' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string'],
            'department' => ['nullable', 'string', 'max:100'],
            'designation' => ['nullable', 'string', 'max:100'],
            'joining_date' => ['nullable', 'date'],
            'employment_type' => ['nullable', Rule::in(['full_time', 'part_time', 'contract', 'temporary'])],
            'shift' => ['nullable', 'string', 'max:100'],
            'salary' => ['nullable', 'numeric', 'min:0', 'max:9999999999.99'],
            'payment_type' => ['nullable', Rule::in(['monthly', 'weekly', 'daily', 'hourly'])],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_mobile' => ['nullable', 'string', 'max:20'],
            'emergency_contact_relation' => ['nullable', 'string', 'max:100'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'notes' => ['nullable', 'string'],
        ]);

        if ($request->hasFile('photo')) {
            if ($employee->photo) {
                Storage::disk('public')->delete($employee->photo);
            }

            $validated['photo'] = $request->file('photo')
                ->store('employees', 'public');
        }

        $employee->update($validated);

        return redirect()
            ->route('admin.employees.show', $employee->id)
            ->with('success', 'Employee updated successfully.');
    }

    public function destroy(string $id)
    {
        $company = $this->activeCompany();

        $employee = Employee::where('company_id', $company->id)
            ->findOrFail($id);

        if ($employee->photo) {
            Storage::disk('public')->delete($employee->photo);
        }

        $employee->delete();

        return redirect()
            ->route('admin.employees.index')
            ->with('success', 'Employee deleted successfully.');
    }
}
