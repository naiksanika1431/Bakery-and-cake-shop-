<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class StockLedgerController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $baseQuery = StockMovement::with([
            'branchProduct.branch',
            'branchProduct.productVariant.product.category',
            'branchProduct.productVariant.unit',
            'user',
        ]);

        // Keep ledger visibility consistent with existing stock pages.
        if ($user) {
            if ($user->branch_id) {
                $baseQuery->whereHas('branchProduct', function ($query) use ($user) {
                    $query->where('branch_id', $user->branch_id);
                });
            } else {
                $baseQuery->whereRaw('1 = 0');
            }
        } else {
            $baseQuery->whereHas('branchProduct', function ($query) {
                $query->where('branch_id', 1);
            });
        }

        // Search by product name, SKU or barcode.
        if ($request->filled('search')) {
            $search = $request->search;

            $baseQuery->whereHas(
                'branchProduct.productVariant.product',
                function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('sku', 'like', "%{$search}%")
                            ->orWhere('barcode', 'like', "%{$search}%");
                    });
                }
            );
        }

        // Filter by movement type.
        if ($request->filled('movement_type')) {
            $baseQuery->where('movement_type', $request->movement_type);
        }

        // Filter by date range.
        if ($request->filled('date_from')) {
            $baseQuery->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $baseQuery->whereDate('created_at', '<=', $request->date_to);
        }

        // Summary based on the applied filters.
        $totalMovements = (clone $baseQuery)->count();

        $totalQuantityMoved = (clone $baseQuery)->sum('quantity');

        $affectedStockItems = (clone $baseQuery)
            ->distinct()
            ->count('branch_product_id');

        // Get actual movement types available in the database.
        $movementTypes = (clone $baseQuery)
            ->select('movement_type')
            ->distinct()
            ->orderBy('movement_type')
            ->pluck('movement_type');

        $movements = (clone $baseQuery)
            ->latest('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.stock.ledger', compact(
            'movements',
            'totalMovements',
            'totalQuantityMoved',
            'affectedStockItems',
            'movementTypes'
        ));
    }
}
