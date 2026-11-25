<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\BranchProduct;
use App\Models\Company;
use App\Models\ProductVariant;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Models\SupplierPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SupplierPurchaseController extends Controller
{
    private function activeCompany(): Company
    {
        return Company::where('company_code', 'DELIGHT')
            ->where('is_active', true)
            ->firstOrFail();
    }

    private function allowedBranches(Company $company)
    {
        $user = auth()->user();

        abort_unless($user, 403);

        if ($user->role?->role_code === 'super_admin') {
            return Branch::where('company_id', $company->id)
                ->where('is_active', true);
        }

        abort_unless($user->branch_id, 403);

        return Branch::where('company_id', $company->id)
            ->where('id', $user->branch_id)
            ->where('is_active', true);
    }

    private function authorizePurchase(SupplierPurchase $purchase): void
    {
        $company = $this->activeCompany();
        $user = auth()->user();

        abort_unless($user, 403);
        abort_unless($purchase->company_id === $company->id, 404);

        if ($user->role?->role_code !== 'super_admin') {
            abort_unless(
                $user->branch_id &&
                (int) $purchase->branch_id === (int) $user->branch_id,
                403
            );
        }
    }

    public function index(Request $request)
    {
        $company = $this->activeCompany();
        $branches = $this->allowedBranches($company)->get();

        $branchIds = $branches->pluck('id');

        $query = SupplierPurchase::with(['supplier', 'branch'])
            ->where('company_id', $company->id)
            ->whereIn('branch_id', $branchIds);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('purchase_number', 'like', "%{$search}%")
                    ->orWhere('supplier_invoice_number', 'like', "%{$search}%")
                    ->orWhereHas('supplier', function ($supplierQuery) use ($search) {
                        $supplierQuery->where('supplier_name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('branch_id')) {
            abort_unless($branchIds->contains((int) $request->branch_id), 403);

            $query->where('branch_id', $request->branch_id);
        }

        $purchases = $query->latest('purchase_date')
            ->paginate(15)
            ->withQueryString();

        $totalPurchases = SupplierPurchase::where('company_id', $company->id)
            ->whereIn('branch_id', $branchIds)
            ->count();

        $draftPurchases = SupplierPurchase::where('company_id', $company->id)
            ->whereIn('branch_id', $branchIds)
            ->where('status', 'draft')
            ->count();

        $receivedPurchases = SupplierPurchase::where('company_id', $company->id)
            ->whereIn('branch_id', $branchIds)
            ->where('status', 'received')
            ->count();

        return view('admin.supplier-purchases.index', compact(
            'purchases',
            'branches',
            'totalPurchases',
            'draftPurchases',
            'receivedPurchases'
        ));
    }

    public function create()
    {
        $company = $this->activeCompany();

        $branches = $this->allowedBranches($company)
            ->orderBy('branch_name')
            ->get();

        $suppliers = Supplier::where('company_id', $company->id)
            ->where('status', 'active')
            ->orderBy('supplier_name')
            ->get();

        $variants = ProductVariant::with([
            'product',
            'unit',
        ])
            ->whereHas('product', function ($query) use ($company) {
                $query->where('company_id', $company->id)
                    ->where('product_type', 'raw_material')
                    ->where('status', 'active');
            })
            ->orderBy('id')
            ->get();

        return view('admin.supplier-purchases.create', compact(
            'branches',
            'suppliers',
            'variants'
        ));
    }

    public function store(Request $request)
    {
        $company = $this->activeCompany();

        $validated = $request->validate([
            'branch_id' => 'required|integer',
            'supplier_id' => 'required|integer',
            'supplier_invoice_number' => 'nullable|string|max:100',
            'purchase_date' => 'required|date',
            'expected_date' => 'nullable|date|after_or_equal:purchase_date',
            'notes' => 'nullable|string',

            'items' => 'required|array|min:1',
            'items.*.product_variant_id' => 'required|integer|distinct',
            'items.*.quantity' => 'required|numeric|gt:0',
            'items.*.unit_cost' => 'required|numeric|min:0',
            'items.*.gst_rate' => 'nullable|numeric|min:0|max:100',
            'items.*.discount_amount' => 'nullable|numeric|min:0',
            'items.*.notes' => 'nullable|string',
        ]);

        $branch = $this->allowedBranches($company)
            ->where('id', $validated['branch_id'])
            ->firstOrFail();

        $supplier = Supplier::where('company_id', $company->id)
            ->where('status', 'active')
            ->findOrFail($validated['supplier_id']);

        $variantIds = collect($validated['items'])
            ->pluck('product_variant_id');

        $variants = ProductVariant::with('product')
            ->whereIn('id', $variantIds)
            ->whereHas('product', function ($query) use ($company) {
                $query->where('company_id', $company->id)
                    ->where('product_type', 'raw_material')
                    ->where('status', 'active');
            })
            ->get()
            ->keyBy('id');

        if ($variants->count() !== $variantIds->count()) {
            throw ValidationException::withMessages([
                'items' => 'One or more selected raw materials are invalid.',
            ]);
        }

        DB::transaction(function () use (
            $validated,
            $company,
            $branch,
            $supplier
        ) {
            $purchase = SupplierPurchase::create([
                'company_id' => $company->id,
                'branch_id' => $branch->id,
                'supplier_id' => $supplier->id,
                'purchase_number' => 'PUR-' . now()->format('YmdHis') . '-' . strtoupper(substr(uniqid(), -4)),
                'supplier_invoice_number' => $validated['supplier_invoice_number'] ?? null,
                'purchase_date' => $validated['purchase_date'],
                'expected_date' => $validated['expected_date'] ?? null,
                'status' => 'draft',
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);

            $subtotal = 0;
            $totalDiscount = 0;
            $totalTax = 0;
            $grandTotal = 0;

            foreach ($validated['items'] as $item) {
                $quantity = (float) $item['quantity'];
                $unitCost = (float) $item['unit_cost'];
                $gstRate = (float) ($item['gst_rate'] ?? 0);
                $discount = (float) ($item['discount_amount'] ?? 0);

                $baseAmount = round($quantity * $unitCost, 2);

                if ($discount > $baseAmount) {
                    throw ValidationException::withMessages([
                        'items' => 'Item discount cannot exceed its base amount.',
                    ]);
                }

                $taxableAmount = $baseAmount - $discount;
                $taxAmount = round($taxableAmount * $gstRate / 100, 2);
                $lineTotal = round($taxableAmount + $taxAmount, 2);

                $purchase->items()->create([
                    'product_variant_id' => $item['product_variant_id'],
                    'quantity' => $quantity,
                    'unit_cost' => $unitCost,
                    'gst_rate' => $gstRate,
                    'tax_amount' => $taxAmount,
                    'discount_amount' => $discount,
                    'line_total' => $lineTotal,
                    'notes' => $item['notes'] ?? null,
                ]);

                $subtotal += $baseAmount;
                $totalDiscount += $discount;
                $totalTax += $taxAmount;
                $grandTotal += $lineTotal;
            }

            $purchase->update([
                'subtotal' => $subtotal,
                'discount_amount' => $totalDiscount,
                'tax_amount' => $totalTax,
                'grand_total' => $grandTotal,
            ]);
        });

        return redirect()
            ->route('admin.supplier-purchases.index')
            ->with('success', 'Supplier purchase created successfully.');
    }

    public function show(SupplierPurchase $supplierPurchase)
    {
        $this->authorizePurchase($supplierPurchase);

        $supplierPurchase->load([
            'supplier',
            'branch',
            'items.productVariant.product',
            'items.productVariant.unit',
            'creator',
        ]);

        return view('admin.supplier-purchases.show', [
            'purchase' => $supplierPurchase,
        ]);
    }

    public function receive(SupplierPurchase $supplierPurchase)
    {
        $this->authorizePurchase($supplierPurchase);

        DB::transaction(function () use ($supplierPurchase) {
            $purchase = SupplierPurchase::whereKey($supplierPurchase->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($purchase->status !== 'draft') {
                throw ValidationException::withMessages([
                    'purchase' => 'Only draft purchases can be received.',
                ]);
            }

            $purchase->load('items.productVariant');

            foreach ($purchase->items as $item) {
                $variant = $item->productVariant;

                if (!$variant) {
                    throw ValidationException::withMessages([
                        'purchase' => 'A purchased product variant no longer exists.',
                    ]);
                }

                $branchProduct = BranchProduct::where('branch_id', $purchase->branch_id)
                    ->where('product_variant_id', $variant->id)
                    ->lockForUpdate()
                    ->first();

                if (!$branchProduct) {
                    $branchProduct = BranchProduct::create([
                        'branch_id' => $purchase->branch_id,
                        'product_variant_id' => $variant->id,
                        'selling_price' => $variant->default_selling_price ?? 0,
                        'min_stock' => 0,
                        'stock_quantity' => 0,
                        'is_available' => true,
                        'is_enabled' => true,
                        'online_available' => true,
                    ]);
                }

                $stockBefore = (float) $branchProduct->stock_quantity;
                $quantity = (float) $item->quantity;
                $stockAfter = round($stockBefore + $quantity, 3);

                $branchProduct->update([
                    'stock_quantity' => $stockAfter,
                ]);

                StockMovement::create([
                    'branch_product_id' => $branchProduct->id,
                    'movement_type' => 'purchase_in',
                    'quantity' => $quantity,
                    'stock_before' => $stockBefore,
                    'stock_after' => $stockAfter,
                    'reference_type' => 'supplier_purchase',
                    'reference_id' => $purchase->id,
                    'notes' => 'Supplier purchase ' . $purchase->purchase_number,
                    'user_id' => auth()->id(),
                ]);
            }

            $purchase->update([
                'status' => 'received',
                'received_at' => now(),
            ]);
        });

        return redirect()
            ->route('admin.supplier-purchases.show', $supplierPurchase)
            ->with('success', 'Purchase received and branch stock updated successfully.');
    }
}
