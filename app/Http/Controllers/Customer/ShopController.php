<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Company;
use App\Models\Product;

class ShopController extends Controller
{
    public function home()
    {
        // Get the active DELIGHT company
        $company = Company::where('company_code', 'DELIGHT')
            ->where('is_active', true)
            ->firstOrFail();

        // Get active branches of this company
        $branches = Branch::where('company_id', $company->id)
            ->where('is_active', true)
            ->orderBy('branch_name')
            ->get();

        // Get the customer's selected branch from session
        $selectedBranch = null;

        if (session()->has('customer_branch_id')) {
            $selectedBranch = Branch::where('company_id', $company->id)
                ->where('is_active', true)
                ->find(session('customer_branch_id'));
        }

        // Load active categories
        $categories = Category::where('company_id', $company->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        // Initially, no branch-specific products are displayed
        $featuredProducts = collect();

        // Load products only after a branch is selected
        if ($selectedBranch) {

            $featuredProducts = Product::with([
                'category',

                'productVariants' => function ($query) use ($selectedBranch) {
                    $query->whereHas('branchProducts', function ($branchQuery) use ($selectedBranch) {
                        $branchQuery->where('branch_id', $selectedBranch->id)
                            ->where('is_enabled', true)
                            ->where('is_available', true)
                            ->where('online_available', true);
                    });

                    $query->with([
                        'unit',
                        'branchProducts' => function ($branchQuery) use ($selectedBranch) {
                            $branchQuery->where('branch_id', $selectedBranch->id)
                                ->where('is_enabled', true)
                                ->where('is_available', true)
                                ->where('online_available', true);
                        }
                    ]);
                }
            ])
                ->where('company_id', $company->id)
                ->where('status', 'active')
                ->where('product_type', 'finished_good')
                ->whereHas('category', function ($query) use ($company) {
                    $query->where('company_id', $company->id)
                        ->where('status', 'active');
                })
                ->whereHas('productVariants.branchProducts', function ($query) use ($selectedBranch) {
                    $query->where('branch_id', $selectedBranch->id)
                        ->where('is_enabled', true)
                        ->where('is_available', true)
                        ->where('online_available', true);
                })
                ->latest()
                ->take(8)
                ->get();
        }

        return view('customer.home', compact(
            'company',
            'branches',
            'selectedBranch',
            'categories',
            'featuredProducts'
        ));
    }

    /**
     * Display all active customer categories.
     */
    public function categories()
    {
        // Get the active DELIGHT company
        $company = Company::where('company_code', 'DELIGHT')
            ->where('is_active', true)
            ->firstOrFail();

        // Fetch active categories from the database
        $categories = Category::where('company_id', $company->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('customer.categories', compact(
            'company',
            'categories'
        ));
    }

    /**
     * Save the customer's selected branch.
     */
    public function selectBranch(Request $request)
    {
        $request->validate([
            'branch_id' => ['required', 'integer'],
        ]);

        $company = Company::where('company_code', 'DELIGHT')
            ->where('is_active', true)
            ->firstOrFail();

        $branch = Branch::where('company_id', $company->id)
            ->where('is_active', true)
            ->findOrFail($request->branch_id);

        session(['customer_branch_id' => $branch->id]);

        return redirect()->back()->with(
            'success',
            'Branch changed to ' . $branch->branch_name
        );
    }

    /**
     * Display dynamic customer products.
     */
    public function products(Request $request)
    {
        $company = Company::where('company_code', 'DELIGHT')
            ->where('is_active', true)
            ->firstOrFail();

        $categories = Category::where('company_id', $company->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $selectedBranch = null;

        if (session()->has('customer_branch_id')) {
            $selectedBranch = Branch::where('company_id', $company->id)
                ->where('is_active', true)
                ->find(session('customer_branch_id'));
        }

        $products = collect();

        if ($selectedBranch) {
            $query = Product::with([
                'category',
                'productVariants' => function ($query) use ($selectedBranch) {
                    $query->whereHas('branchProducts', function ($branchQuery) use ($selectedBranch) {
                        $branchQuery->where('branch_id', $selectedBranch->id)
                            ->where('is_enabled', true)
                            ->where('is_available', true)
                            ->where('online_available', true);
                    })->with([
                        'unit',
                        'branchProducts' => function ($branchQuery) use ($selectedBranch) {
                            $branchQuery->where('branch_id', $selectedBranch->id)
                                ->where('is_enabled', true)
                                ->where('is_available', true)
                                ->where('online_available', true);
                        }
                    ]);
                }
            ])
                ->where('company_id', $company->id)
                ->where('status', 'active')
                ->where('product_type', 'finished_good')
                ->whereHas('category', function ($categoryQuery) use ($company) {
                    $categoryQuery->where('company_id', $company->id)
                        ->where('status', 'active');
                })
                ->whereHas('productVariants.branchProducts', function ($branchQuery) use ($selectedBranch) {
                    $branchQuery->where('branch_id', $selectedBranch->id)
                        ->where('is_enabled', true)
                        ->where('is_available', true)
                        ->where('online_available', true);
                });

            if ($request->filled('category')) {
                $query->where('category_id', $request->category);
            }

            $sort = $request->get('sort', 'featured');

            if ($sort === 'name') {
                $query->orderBy('name');
            } else {
                $query->latest();
            }

            $products = $query->get();

            if ($sort === 'price_low') {
                $products = $products->sortBy(function ($product) {
                    return optional(optional($product->productVariants->first())
                        ->branchProducts->first())->selling_price;
                })->values();
            } elseif ($sort === 'price_high') {
                $products = $products->sortByDesc(function ($product) {
                    return optional(optional($product->productVariants->first())
                        ->branchProducts->first())->selling_price;
                })->values();
            }
        }

        return view('customer.products', compact(
            'company',
            'categories',
            'selectedBranch',
            'products'
        ));
    }
}



