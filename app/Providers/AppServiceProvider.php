<?php

namespace App\Providers;

use App\Models\Branch;
use App\Models\Company;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('layouts.customer.app', function ($view) {

            $company = Company::where('company_code', 'DELIGHT')
                ->where('is_active', true)
                ->first();

            $branches = collect();
            $selectedBranch = null;

            if ($company) {
                $branches = Branch::where('company_id', $company->id)
                    ->where('is_active', true)
                    ->orderBy('branch_name')
                    ->get();

                if (session()->has('customer_branch_id')) {
                    $selectedBranch = $branches->firstWhere(
                        'id',
                        (int) session('customer_branch_id')
                    );
                }
            }

            $view->with([
                'customerCompany' => $company,
                'customerBranches' => $branches,
                'customerSelectedBranch' => $selectedBranch,
            ]);
        });
    }
}
