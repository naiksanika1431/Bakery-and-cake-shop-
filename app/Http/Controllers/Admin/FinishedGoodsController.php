<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BranchProduct;
use Illuminate\Http\Request;

class FinishedGoodsController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $stockQuery = BranchProduct::with([
            'branch',
            'productVariant.product.category',
            'productVariant.unit',
        ])
            ->whereHas('productVariant.product', function ($query) {
                $query->where('product_type', 'finished_good');
            });

        // Branch-level visibility
        if ($user) {
            if ($user->branch_id) {
                $stockQuery->where('branch_id', $user->branch_id);
            } else {
                $stockQuery->whereRaw('1 = 0');
            }
        } else {
            // Preview mode: existing Main Branch
            $stockQuery->where('branch_id', 1);
        }

        // Search by product name or SKU
        if ($request->filled('search')) {
            $search = $request->search;

            $stockQuery->whereHas('productVariant.product', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                });
            });
        }

        // Filter by stock status
        if ($request->status === 'in_stock') {
            $stockQuery->where('stock_quantity', '>', 0)
                ->whereRaw('stock_quantity > min_stock');
        } elseif ($request->status === 'low_stock') {
            $stockQuery->where('stock_quantity', '>', 0)
                ->whereRaw('stock_quantity <= min_stock');
        } elseif ($request->status === 'out_of_stock') {
            $stockQuery->where('stock_quantity', '<=', 0);
        }

        $stockItems = $stockQuery
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $totalProducts = BranchProduct::whereHas(
            'productVariant.product',
            fn ($query) => $query->where('product_type', 'finished_good')
        )->when($user?->branch_id, fn ($query, $branchId) =>
            $query->where('branch_id', $branchId)
        )->count();

        $totalStock = (clone $stockQuery)->sum('stock_quantity');

        return view('admin.stock.finished-goods', compact(
            'stockItems',
            'totalProducts',
            'totalStock'
        ));
    }
}
