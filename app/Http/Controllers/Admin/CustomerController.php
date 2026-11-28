<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Customer;
use App\Models\CustomerAccount;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class CustomerController extends Controller
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
        $search = $request->input('search');

        // Admin / POS Business Customers
        $customers = Customer::where('company_id', $company->id)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('customer_code', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($request->status === 'active', fn ($query) => $query->where('is_active', true))
            ->when($request->status === 'inactive', fn ($query) => $query->where('is_active', false))
            ->latest()
            ->paginate(10, ['*'], 'business_page')
            ->withQueryString();

        // Website Registered Customers
        $websiteCustomers = CustomerAccount::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->when($request->website_status === 'verified', fn ($query) => $query->whereNotNull('email_verified_at'))
            ->when($request->website_status === 'unverified', fn ($query) => $query->whereNull('email_verified_at'))
            ->latest()
            ->paginate(10, ['*'], 'website_page')
            ->withQueryString();

        // Business Customer Statistics
        $totalCustomers = Customer::where('company_id', $company->id)->count();

        $activeCustomers = Customer::where('company_id', $company->id)
            ->where('is_active', true)
            ->count();

        $inactiveCustomers = $totalCustomers - $activeCustomers;

        // Website Customer Statistics
        $totalWebsiteCustomers = CustomerAccount::count();

        $verifiedWebsiteCustomers = CustomerAccount::whereNotNull('email_verified_at')
            ->count();

        return view('admin.customers.index', compact(
            'customers',
            'websiteCustomers',
            'totalCustomers',
            'activeCustomers',
            'inactiveCustomers',
            'totalWebsiteCustomers',
            'verifiedWebsiteCustomers'
        ));
    }

    public function create()
    {
        return view('admin.customers.create');
    }

    public function store(Request $request)
    {
        $company = $this->activeCompany();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => [
                'required',
                'string',
                'max:20',
                Rule::unique('customers', 'phone')
                    ->where('company_id', $company->id),
            ],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'pincode' => ['nullable', 'string', 'max:10'],
            'gst_number' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        do {
            $customerCode = 'CUS-' . Str::upper(Str::random(8));
        } while (
            Customer::where('company_id', $company->id)
                ->where('customer_code', $customerCode)
                ->exists()
        );

        $validated['company_id'] = $company->id;
        $validated['customer_code'] = $customerCode;
        $validated['is_active'] = $request->has('is_active')
            ? $request->boolean('is_active')
            : true;

        $customer = Customer::create($validated);

        return redirect()
            ->route('admin.customers.show', $customer->id)
            ->with('success', 'Customer added successfully.');
    }

    public function show(string $id)
    {
        $company = $this->activeCompany();

        $customer = Customer::where('company_id', $company->id)
            ->with('orders')
            ->findOrFail($id);

        return view('admin.customers.show', compact('customer'));
    }

    public function edit(string $id)
    {
        $company = $this->activeCompany();

        $customer = Customer::where('company_id', $company->id)
            ->findOrFail($id);

        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, string $id)
    {
        $company = $this->activeCompany();

        $customer = Customer::where('company_id', $company->id)
            ->findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => [
                'required',
                'string',
                'max:20',
                Rule::unique('customers', 'phone')
                    ->where('company_id', $company->id)
                    ->ignore($customer->id),
            ],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'pincode' => ['nullable', 'string', 'max:10'],
            'gst_number' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $customer->update($validated);

        return redirect()
            ->route('admin.customers.show', $customer->id)
            ->with('success', 'Customer updated successfully.');
    }
}
