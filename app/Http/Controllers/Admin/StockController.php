<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BranchProduct;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Until authentication is implemented, show a safe read-only preview.
        $branchId = $user?->branch_id;

        $stockQuery = BranchProduct::with([
            'branch',
            'productVariant.product',
            'productVariant.unit',
        ]);

        if ($user) {
            if ($branchId) {
                $stockQuery->where('branch_id', $branchId);
            } else {
                // Logged-in users without an assigned branch see no stock.
                $stockQuery->whereRaw('1 = 0');
            }
        } else {
            // Public read-only preview: use the existing Main Branch.
            $stockQuery->where('branch_id', 1);
        }

        $stockItems = $stockQuery->get();

        $totalVariants = $stockItems->count();

        $totalStock = $stockItems->sum(
            fn ($item) => (float) $item->stock_quantity
        );

        $lowStockItems = $stockItems->filter(
            fn ($item) =>
                (float) $item->stock_quantity > 0 &&
                (float) $item->stock_quantity <= (float) $item->min_stock
        );

        $outOfStockItems = $stockItems->filter(
            fn ($item) => (float) $item->stock_quantity <= 0
        );

        $inventoryValue = $stockItems->sum(
            fn ($item) =>
                (float) $item->stock_quantity *
                (float) ($item->productVariant?->cost_price ?? 0)
        );

        return view('admin.stock.index', compact(
            'stockItems',
            'totalVariants',
            'totalStock',
            'lowStockItems',
            'outOfStockItems',
            'inventoryValue'
        ));
    }
}