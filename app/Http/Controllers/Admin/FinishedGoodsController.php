<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\BranchProduct;
use App\Models\Company;
use App\Models\StockAdjustmentRequest;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinishedGoodsController extends Controller
{
    private function activeCompany(): Company
    {
        return Company::where('company_code', 'DELIGHT')
            ->where('is_active', true)
            ->firstOrFail();
    }

    private function allowedBranchIds($user, Company $company)
    {
        abort_unless($user, 403);

        if ($user->role?->role_code === 'super_admin') {
            return Branch::where('company_id', $company->id)
                ->where('is_active', true)
                ->pluck('id');
        }

        abort_unless($user->branch_id, 403);

        return Branch::where('company_id', $company->id)
            ->where('id', $user->branch_id)
            ->where('is_active', true)
            ->pluck('id');
    }

    private function finishedGoodsQuery($branchIds, Company $company)
    {
        return BranchProduct::whereIn('branch_id', $branchIds)
            ->whereHas('branch', function ($query) use ($company) {
                $query->where('company_id', $company->id)
                    ->where('is_active', true);
            })
            ->whereHas('productVariant.product', function ($query) use ($company) {
                $query->where('company_id', $company->id)
                    ->where('product_type', 'finished_good');
            });
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $company = $this->activeCompany();

        if ($user) {
            $branchIds = $this->allowedBranchIds($user, $company);
        } else {
            $branchIds = Branch::where('id', 1)
                ->where('company_id', $company->id)
                ->where('is_active', true)
                ->pluck('id');
        }

        $baseQuery = $this->finishedGoodsQuery($branchIds, $company)
            ->with([
                'branch',
                'productVariant.product.category',
                'productVariant.unit',
            ]);

        $totalProducts = (clone $baseQuery)->count();
        $totalStock = (clone $baseQuery)->sum('stock_quantity');

        $stockQuery = clone $baseQuery;

        if ($request->filled('search')) {
            $search = trim($request->search);

            $stockQuery->whereHas('productVariant', function ($query) use ($search) {
                $query->where('sku', 'like', "%{$search}%")
                    ->orWhereHas('product', function ($productQuery) use ($search) {
                        $productQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->status === 'in_stock') {
            $stockQuery->where('stock_quantity', '>', 0)
                ->whereRaw('stock_quantity > min_stock');
        } elseif ($request->status === 'low_stock') {
            $stockQuery->where('stock_quantity', '>', 0)
                ->whereRaw('stock_quantity <= min_stock');
        } elseif ($request->status === 'out_of_stock') {
            $stockQuery->where('stock_quantity', '<=', 0);
        }

        $stockItems = $stockQuery->latest()
            ->paginate(15)
            ->withQueryString();

        $canRequestStock = $user && $user->hasPermission('inventory.manage');
        $canApproveStock = $user && $user->hasPermission('inventory.approve');

        $adjustmentRequests = collect();

        if ($user) {
            $adjustmentQuery = StockAdjustmentRequest::with([
                'branchProduct.branch',
                'branchProduct.productVariant.product',
                'requester',
                'approver',
            ])->whereHas('branchProduct', function ($query) use ($branchIds, $company) {
                $query->whereIn('branch_id', $branchIds)
                    ->whereHas('productVariant.product', function ($productQuery) use ($company) {
                        $productQuery->where('company_id', $company->id)
                            ->where('product_type', 'finished_good');
                    });
            });

            if (!$canApproveStock) {
                $adjustmentQuery->where('requested_by', $user->id);
            }

            $adjustmentRequests = $adjustmentQuery
                ->latest()
                ->take(20)
                ->get();
        }

        return view('admin.stock.finished-goods', compact(
            'stockItems',
            'totalProducts',
            'totalStock',
            'canRequestStock',
            'canApproveStock',
            'adjustmentRequests'
        ));
    }

    public function storeAdjustment(Request $request)
    {
        $user = auth()->user();

        abort_unless($user, 403);
        abort_unless($user->hasPermission('inventory.manage'), 403);

        $validated = $request->validate([
            'branch_product_id' => ['required', 'integer'],
            'quantity' => ['required', 'numeric', 'gt:0', 'max:999999999'],
            'reason' => ['required', 'string', 'max:2000'],
        ]);

        $company = $this->activeCompany();
        $branchIds = $this->allowedBranchIds($user, $company);

        $branchProduct = $this->finishedGoodsQuery($branchIds, $company)
            ->whereKey($validated['branch_product_id'])
            ->firstOrFail();

        StockAdjustmentRequest::create([
            'branch_product_id' => $branchProduct->id,
            'quantity' => $validated['quantity'],
            'reason' => $validated['reason'],
            'requested_by' => $user->id,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('admin.stock.finished-goods')
            ->with('success', 'Stock adjustment request submitted for approval.');
    }

    public function approveAdjustment(Request $request, StockAdjustmentRequest $adjustment)
    {
        $user = auth()->user();

        abort_unless($user, 403);
        abort_unless($user->hasPermission('inventory.approve'), 403);

        abort_if((int) $adjustment->requested_by === (int) $user->id, 403,
            'You cannot approve your own stock request.');

        $validated = $request->validate([
            'approval_remarks' => ['nullable', 'string', 'max:1000'],
        ]);

        $company = $this->activeCompany();
        $branchIds = $this->allowedBranchIds($user, $company);

        DB::transaction(function () use ($adjustment, $user, $validated, $branchIds, $company) {

            $lockedRequest = StockAdjustmentRequest::whereKey($adjustment->id)
                ->lockForUpdate()
                ->firstOrFail();

            abort_unless($lockedRequest->status === 'pending', 422,
                'This request has already been reviewed.');

            abort_if((int) $lockedRequest->requested_by === (int) $user->id, 403,
                'You cannot approve your own stock request.');

            $branchProduct = $this->finishedGoodsQuery($branchIds, $company)
                ->whereKey($lockedRequest->branch_product_id)
                ->lockForUpdate()
                ->firstOrFail();

            $before = (float) $branchProduct->stock_quantity;
            $quantity = (float) $lockedRequest->quantity;
            $after = round($before + $quantity, 3);

            $branchProduct->stock_quantity = $after;
            $branchProduct->save();

            StockMovement::create([
                'branch_product_id' => $branchProduct->id,
                'movement_type' => 'adjustment_in',
                'quantity' => $quantity,
                'stock_before' => $before,
                'stock_after' => $after,
                'reference_type' => 'stock_adjustment_request',
                'reference_id' => $lockedRequest->id,
                'notes' => $lockedRequest->reason,
                'user_id' => $user->id,
            ]);

            $lockedRequest->update([
                'status' => 'approved',
                'approved_by' => $user->id,
                'reviewed_at' => now(),
                'approval_remarks' => $validated['approval_remarks'] ?? null,
            ]);
        });

        return redirect()
            ->route('admin.stock.finished-goods')
            ->with('success', 'Stock request approved and inventory updated successfully.');
    }

    public function rejectAdjustment(Request $request, StockAdjustmentRequest $adjustment)
    {
        $user = auth()->user();

        abort_unless($user, 403);
        abort_unless($user->hasPermission('inventory.approve'), 403);

        abort_if((int) $adjustment->requested_by === (int) $user->id, 403,
            'You cannot review your own stock request.');

        $validated = $request->validate([
            'approval_remarks' => ['required', 'string', 'max:1000'],
        ]);

        $company = $this->activeCompany();
        $branchIds = $this->allowedBranchIds($user, $company);

        DB::transaction(function () use ($adjustment, $user, $validated, $branchIds, $company) {

            $lockedRequest = StockAdjustmentRequest::whereKey($adjustment->id)
                ->lockForUpdate()
                ->firstOrFail();

            abort_unless($lockedRequest->status === 'pending', 422,
                'This request has already been reviewed.');

            abort_if((int) $lockedRequest->requested_by === (int) $user->id, 403,
                'You cannot review your own stock request.');

            $this->finishedGoodsQuery($branchIds, $company)
                ->whereKey($lockedRequest->branch_product_id)
                ->firstOrFail();

            $lockedRequest->update([
                'status' => 'rejected',
                'approved_by' => $user->id,
                'reviewed_at' => now(),
                'approval_remarks' => $validated['approval_remarks'],
            ]);
        });

        return redirect()
            ->route('admin.stock.finished-goods')
            ->with('success', 'Stock adjustment request rejected.');
    }
}

