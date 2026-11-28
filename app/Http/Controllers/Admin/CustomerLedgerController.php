<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Customer;
use App\Models\CustomerLedgerEntry;
use Illuminate\Http\Request;

class CustomerLedgerController extends Controller
{
    private function activeCompany(): Company
    {
        return Company::where('company_code', 'DELIGHT')
            ->where('is_active', true)
            ->firstOrFail();
    }

    private function allowedBranchIds(Company $company): ?array
    {
        $user = auth()->user();

        abort_unless($user, 403);

        if ($user->role?->role_code === 'super_admin') {
            return null;
        }

        abort_unless($user->branch_id, 403);

        $branch = Branch::where('company_id', $company->id)
            ->where('id', $user->branch_id)
            ->where('is_active', true)
            ->firstOrFail();

        return [$branch->id];
    }

    public function index(Request $request)
    {
        $company = $this->activeCompany();
        $branchIds = $this->allowedBranchIds($company);
        $search = $request->input('search');

        $customers = Customer::where('company_id', $company->id)
            ->whereHas('orders', function ($query) use ($branchIds) {
                if ($branchIds !== null) {
                    $query->whereIn('branch_id', $branchIds);
                }
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('customer_code', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->withSum([
                'orders as ledger_sales_total' => function ($query) use ($branchIds) {
                    if ($branchIds !== null) {
                        $query->whereIn('branch_id', $branchIds);
                    }
                },
            ], 'grand_total')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $customers->getCollection()->transform(function ($customer) use ($branchIds) {
            $entries = CustomerLedgerEntry::where('customer_id', $customer->id);

            if ($branchIds !== null) {
                $entries->whereIn('branch_id', $branchIds);
            }

            $customer->ledger_debit = (clone $entries)->sum('debit');
            $customer->ledger_credit = (clone $entries)->sum('credit');
            $customer->outstanding_balance =
                $customer->ledger_debit - $customer->ledger_credit;

            return $customer;
        });

        return view('admin.customer-ledger.index', compact(
            'company',
            'customers',
            'search'
        ));
    }

    public function show(string $id)
    {
        $company = $this->activeCompany();
        $branchIds = $this->allowedBranchIds($company);

        $customer = Customer::where('company_id', $company->id)
            ->findOrFail($id);

        $entriesQuery = CustomerLedgerEntry::with('branch')
            ->where('customer_id', $customer->id);

        if ($branchIds !== null) {
            $entriesQuery->whereIn('branch_id', $branchIds);
        }

        $entries = $entriesQuery
            ->orderBy('date')
            ->orderBy('id')
            ->paginate(25);

        $totalDebit = (clone $entriesQuery)->sum('debit');
        $totalCredit = (clone $entriesQuery)->sum('credit');
        $balance = $totalDebit - $totalCredit;

        return view('admin.customer-ledger.show', compact(
            'company',
            'customer',
            'entries',
            'totalDebit',
            'totalCredit',
            'balance'
        ));
    }
}
