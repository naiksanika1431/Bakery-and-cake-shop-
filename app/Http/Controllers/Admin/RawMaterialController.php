<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Product;
use App\Models\Category;
use App\Models\Unit;
use App\Models\Branch;
use App\Models\ProductVariant;
use App\Models\BranchProduct;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RawMaterialController extends Controller
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
        $user = auth()->user();

        $stockQuery = BranchProduct::with([
            'branch',
            'productVariant.product.category',
            'productVariant.unit',
        ])->whereHas('productVariant.product', function ($query) use ($company) {
            $query->where('company_id', $company->id)
                ->where('product_type', 'raw_material');
        });

        if ($user) {
            if ($user->branch_id) {
                $stockQuery->where('branch_id', $user->branch_id);
            } else {
                $stockQuery->whereRaw('1 = 0');
            }
        } else {
            $stockQuery->where('branch_id', 1);
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $stockQuery->whereHas('productVariant.product', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhere('barcode', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('status')) {
            $stockQuery->whereHas('productVariant.product', function ($query) use ($request) {
                $query->where('status', $request->status);
            });
        }

        $materials = $stockQuery
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $countQuery = BranchProduct::whereHas(
            'productVariant.product',
            function ($query) use ($company) {
                $query->where('company_id', $company->id)
                    ->where('product_type', 'raw_material');
            }
        );

        if ($user) {
            if ($user->branch_id) {
                $countQuery->where('branch_id', $user->branch_id);
            } else {
                $countQuery->whereRaw('1 = 0');
            }
        } else {
            $countQuery->where('branch_id', 1);
        }

        $totalMaterials = (clone $countQuery)->count();

        $activeMaterials = (clone $countQuery)->whereHas(
            'productVariant.product',
            function ($query) use ($company) {
                $query->where('company_id', $company->id)
                    ->where('product_type', 'raw_material')
                    ->where('status', 'active');
            }
        )->count();

        return view('admin.stock.raw-materials', compact(
            'materials',
            'totalMaterials',
            'activeMaterials'
        ));
    }

    public function create()
    {
        $company = $this->activeCompany();

        $categories = Category::where('company_id', $company->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $units = Unit::where('company_id', $company->id)
            ->orderBy('name')
            ->get();

        $branches = Branch::where('company_id', $company->id)
            ->where('is_active', true)
            ->orderBy('branch_name')
            ->get();

        return view('admin.stock.create', compact(
            'categories',
            'units',
            'branches'
        ));
    }

    public function store(Request $request)
    {
        $company = $this->activeCompany();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => [
                'required',
                Rule::exists('categories', 'id')
                    ->where('company_id', $company->id),
            ],
            'base_unit_id' => [
                'required',
                Rule::exists('units', 'id')
                    ->where('company_id', $company->id),
            ],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'description' => ['nullable', 'string'],
            'branch_id' => [
                'required',
                Rule::exists('branches', 'id')
                    ->where('company_id', $company->id)
                    ->where('is_active', true),
            ],
            'opening_stock' => ['required', 'numeric', 'min:0'],
            'min_stock' => ['nullable', 'numeric', 'min:0'],
            'cost_price' => ['required', 'numeric', 'min:0'],
        ]);

        DB::transaction(function () use ($validated, $company) {

            do {
                $sku = 'RM-' . Str::upper(Str::random(8));
            } while (
                Product::where('company_id', $company->id)
                    ->where('sku', $sku)
                    ->exists()
            );

            $product = Product::create([
                'company_id' => $company->id,
                'category_id' => $validated['category_id'],
                'base_unit_id' => $validated['base_unit_id'],
                'name' => $validated['name'],
                'sku' => $sku,
                'barcode' => null,
                'product_type' => 'raw_material',
                'gst_rate' => 0,
                'description' => $validated['description'] ?? null,
                'status' => $validated['status'],
            ]);

            $variant = ProductVariant::create([
                'product_id' => $product->id,
                'variant_name' => 'Default',
                'sku' => $sku . '-DEFAULT',
                'barcode' => null,
                'unit_id' => $validated['base_unit_id'],
                'default_selling_price' => 0,
                'cost_price' => $validated['cost_price'],
            ]);

            $openingStock = (float) $validated['opening_stock'];

            $branchProduct = BranchProduct::create([
                'branch_id' => $validated['branch_id'],
                'product_variant_id' => $variant->id,
                'selling_price' => 0,
                'min_stock' => $validated['min_stock'] ?? 0,
                'stock_quantity' => $openingStock,
                'is_available' => true,
                'is_enabled' => true,
                'online_available' => false,
            ]);

            if ($openingStock > 0) {
                StockMovement::create([
                    'branch_product_id' => $branchProduct->id,
                    'movement_type' => 'opening_stock',
                    'quantity' => $openingStock,
                    'stock_before' => 0,
                    'stock_after' => $openingStock,
                    'reference_type' => 'raw_material_opening',
                    'reference_id' => $product->id,
                    'notes' => 'Opening stock for raw material: ' . $product->name,
                    'user_id' => auth()->id(),
                ]);
            }
        });

        return redirect()
            ->route('admin.stock.raw-materials')
            ->with('success', 'Raw material created successfully.');
    }
}
