<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\BranchProduct;
use App\Models\Category;
use App\Models\Company;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Unit;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use RuntimeException;
use Throwable;

class ProductController extends Controller
{
    private function company(): Company
    {
        return Company::where('company_code', 'DELIGHT')
            ->where('is_active', true)
            ->firstOrFail();
    }

    /**
     * Get all active branches belonging to the company.
     */
    private function branches(Company $company)
    {
        return Branch::where('company_id', $company->id)
            ->where('is_active', true)
            ->orderBy('branch_name')
            ->get();
    }

    private function findProduct(Company $company, int $id): Product
    {
        return Product::where('company_id', $company->id)
            ->findOrFail($id);
    }

    private function deleteCloudinaryImage(?string $publicId): void
    {
        if (!$publicId) {
            return;
        }

        try {
            app(CloudinaryService::class)->delete($publicId);
        } catch (Throwable $e) {
            Log::warning('Cloudinary image deletion failed.', [
                'public_id' => $publicId,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function deleteLegacyLocalImage(?string $image): void
    {
        if ($image && !Str::startsWith($image, ['http://', 'https://'])) {
            Storage::disk('public')->delete($image);
        }
    }

    private function units(Company $company)
    {
        return Unit::where('company_id', $company->id)
            ->orderBy('name')
            ->get();
    }


    /**
     * Generate a unique 13-digit internal EAN-13 barcode.
     */
    private function generateBarcode(Company $company): string
    {
        do {
            $base = '2' . str_pad(
                (string) random_int(0, 99999999999),
                11,
                '0',
                STR_PAD_LEFT
            );

            $sum = 0;

            for ($i = 0; $i < 12; $i++) {
                $sum += (int) $base[$i] * ($i % 2 === 0 ? 1 : 3);
            }

            $barcode = $base . ((10 - ($sum % 10)) % 10);

            $productExists = Product::where('company_id', $company->id)
                ->where('barcode', $barcode)
                ->exists();

            $variantExists = ProductVariant::whereHas('product', function ($query) use ($company) {
                $query->where('company_id', $company->id);
            })->where('barcode', $barcode)->exists();

        } while ($productExists || $variantExists);

        return $barcode;
    }

    /**
     * Validate duplicate variant SKUs and barcodes.
     */
    private function validateVariants(
        array $variants,
        ?Product $product = null
    ): void {
        $errors = [];
        $seenSkus = [];
        $seenBarcodes = [];

        foreach ($variants as $index => $row) {
            $sku = strtolower(trim($row['sku']));

            if (isset($seenSkus[$sku])) {
                $errors["variants.$index.sku"] =
                    'Variant SKUs must be different.';
            }

            $seenSkus[$sku] = true;

            if ($product) {
                $query = ProductVariant::where('product_id', $product->id)
                    ->where('sku', $row['sku']);

                if (!empty($row['id'])) {
                    $query->where('id', '!=', $row['id']);
                }

                if ($query->exists()) {
                    $errors["variants.$index.sku"] =
                        'This SKU already belongs to another variant.';
                }
            }

            $barcode = trim($row['barcode'] ?? '');

            if ($barcode !== '') {
                if (isset($seenBarcodes[$barcode])) {
                    $errors["variants.$index.barcode"] =
                        'Variant barcodes must be different.';
                }

                $seenBarcodes[$barcode] = true;

                if ($product) {
                    $query = ProductVariant::where('product_id', $product->id)
                        ->where('barcode', $barcode);

                    if (!empty($row['id'])) {
                        $query->where('id', '!=', $row['id']);
                    }

                    if ($query->exists()) {
                        $errors["variants.$index.barcode"] =
                            'This barcode already belongs to another variant.';
                    }
                }
            }
        }

        if ($errors) {
            throw ValidationException::withMessages($errors);
        }
    }

    /**
     * Validate that every variant contains configuration
     * for every active branch, exactly once.
     */
    private function validateBranchConfigurations(
        array $variants,
        $branches
    ): void {
        $activeBranchIds = $branches->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->sort()
            ->values()
            ->all();

        foreach ($variants as $index => $variant) {
            $submittedIds = collect($variant['branches'] ?? [])
                ->pluck('branch_id')
                ->map(fn ($id) => (int) $id)
                ->sort()
                ->values()
                ->all();

            if (
                count($submittedIds) !== count(array_unique($submittedIds))
                || $submittedIds !== $activeBranchIds
            ) {
                throw ValidationException::withMessages([
                    "variants.$index.branches" =>
                        'Please provide exactly one configuration for every active branch.',
                ]);
            }
        }
    }

    /**
     * Product variant and branch configuration validation rules.
     */
    private function variantRules(
        Company $company,
        ?Product $product = null
    ): array {
        $rules = [
            'variants' => ['required', 'array', 'min:1'],

            'variants.*.variant_name' => [
                'required',
                'string',
                'max:255',
            ],

            'variants.*.sku' => [
                'required',
                'string',
                'max:255',
            ],

            'variants.*.barcode' => [
                'nullable',
                'string',
                'max:255',
            ],

            'variants.*.unit_id' => [
                'required',
                'integer',
                Rule::exists('units', 'id')
                    ->where('company_id', $company->id),
            ],

            'variants.*.default_selling_price' => [
                'required',
                'numeric',
                'min:0',
                'max:9999999999.99',
            ],

            'variants.*.cost_price' => [
                'required',
                'numeric',
                'min:0',
                'max:9999999999.99',
            ],

            'variants.*.branches' => [
                'required',
                'array',
                'min:1',
            ],

            'variants.*.branches.*.branch_id' => [
                'required',
                'integer',
                Rule::exists('branches', 'id')
                    ->where('company_id', $company->id)
                    ->where('is_active', true),
            ],

            'variants.*.branches.*.selling_price' => [
                'required',
                'numeric',
                'min:0',
                'max:9999999999.99',
            ],

            'variants.*.branches.*.min_stock' => [
                'nullable',
                'numeric',
                'min:0',
                'max:999999999.999',
            ],

            'variants.*.branches.*.is_enabled' => [
                'required',
                'boolean',
            ],

            'variants.*.branches.*.is_available' => [
                'required',
                'boolean',
            ],

            'variants.*.branches.*.online_available' => [
                'required',
                'boolean',
            ],
        ];

        if ($product) {
            $rules['variants.*.id'] = [
                'nullable',
                'integer',
                Rule::exists('product_variants', 'id')
                    ->where('product_id', $product->id),
            ];
        }

        return $rules;
    }

    /**
     * Create or update variants and their branch-specific settings.
     */
    private function syncVariants(
        Product $product,
        array $variants,
        $branches,
        bool $deactivateMissing = false
    ): void {
        $savedIds = [];

        foreach ($variants as $row) {
            if (!empty($row['id'])) {
                $variant = $product->productVariants()
                    ->whereKey($row['id'])
                    ->firstOrFail();

                $variant->update([
                    'variant_name' => $row['variant_name'],
                    'sku' => $row['sku'],
                    'barcode' => $row['barcode'] ?: ($variant->barcode ?: $this->generateBarcode($product->company)),
                    'unit_id' => $row['unit_id'],
                    'default_selling_price' => $row['default_selling_price'],
                    'cost_price' => $row['cost_price'],
                ]);
            } else {
                $variant = $product->productVariants()->create([
                    'variant_name' => $row['variant_name'],
                    'sku' => $row['sku'],
                    'barcode' => $row['barcode'] ?: $this->generateBarcode($product->company),
                    'unit_id' => $row['unit_id'],
                    'default_selling_price' => $row['default_selling_price'],
                    'cost_price' => $row['cost_price'],
                ]);
            }

            $savedIds[] = $variant->id;

            foreach ($row['branches'] as $branchConfig) {
                BranchProduct::updateOrCreate(
                    [
                        'branch_id' => $branchConfig['branch_id'],
                        'product_variant_id' => $variant->id,
                    ],
                    [
                        'selling_price' => $branchConfig['selling_price'],
                        'min_stock' => $branchConfig['min_stock'] ?? 0,
                        'is_enabled' => (bool) $branchConfig['is_enabled'],
                        'is_available' => (bool) $branchConfig['is_available'],
                        'online_available' => (bool) $branchConfig['online_available'],
                    ]
                );
            }
        }

        if ($deactivateMissing) {
            $missingVariants = $product->productVariants()
                ->whereNotIn('id', $savedIds)
                ->pluck('id');

            if ($missingVariants->isNotEmpty()) {
                BranchProduct::whereIn('branch_id', $branches->pluck('id'))
                    ->whereIn('product_variant_id', $missingVariants)
                    ->update([
                        'is_enabled' => false,
                        'is_available' => false,
                        'online_available' => false,
                    ]);
            }
        }
    }

    /**
     * Product listing.
     */
    public function index(Request $request)
    {
        $company = $this->company();

        $query = Product::with('category')
            ->where('company_id', $company->id)
            ->where('product_type', 'finished_good');

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $baseQuery = Product::where('company_id', $company->id)
            ->where('product_type', 'finished_good');

        $totalProducts = (clone $baseQuery)->count();

        $activeProducts = (clone $baseQuery)
            ->where('status', 'active')
            ->count();

        $inactiveProducts = (clone $baseQuery)
            ->where('status', 'inactive')
            ->count();

        $products = $query->latest()
            ->paginate(10)
            ->withQueryString();

        $categories = Category::where('company_id', $company->id)
            ->orderBy('name')
            ->get();

        return view('admin.products.index', compact(
            'products',
            'categories',
            'totalProducts',
            'activeProducts',
            'inactiveProducts'
        ));
    }

    /**
     * Show the Add Product page.
     */
    public function create()
    {
        $company = $this->company();

        $categories = Category::where('company_id', $company->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $units = $this->units($company);

        $branches = $this->branches($company);

        return view('admin.products.create', compact(
            'categories',
            'units',
            'branches'
        ));
    }

    /**
     * Store a new product.
     */
    public function store(Request $request)
    {
        $company = $this->company();
        $branches = $this->branches($company);

        $validated = $request->validate(array_merge([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'sku' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'sku')
                    ->where('company_id', $company->id),
            ],

            'barcode' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('products', 'barcode')
                    ->where('company_id', $company->id),
            ],

            'category_id' => [
                'required',
                'integer',
                Rule::exists('categories', 'id')
                    ->where('company_id', $company->id)
                    ->where('status', 'active'),
            ],

            'base_unit_id' => [
                'required',
                'integer',
                Rule::exists('units', 'id')
                    ->where('company_id', $company->id),
            ],

            'product_type' => [
                'required',
                Rule::in([
                    'finished_good',
                    'raw_material',
                    'semi_finished',
                    'packaging_material',
                ]),
            ],

            'hsn_code' => [
                'nullable',
                'string',
                'max:20',
            ],

            'gst_rate' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
            ],

            'status' => [
                'required',
                Rule::in(['active', 'inactive']),
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ], $this->variantRules($company)));

        $this->validateVariants($validated['variants']);

        $this->validateBranchConfigurations(
            $validated['variants'],
            $branches
        );

        $uploadedPublicId = null;

        if ($request->hasFile('image')) {
            $upload = app(CloudinaryService::class)->upload(
                $request->file('image')->getRealPath()
            );

            $validated['image'] = $upload['secure_url'] ?? null;
            $uploadedPublicId = $upload['public_id'] ?? null;

            if (!$validated['image'] || !$uploadedPublicId) {
                $this->deleteCloudinaryImage($uploadedPublicId);

                throw new RuntimeException(
                    'Cloudinary did not return the image URL and public ID.'
                );
            }

            $validated['image_public_id'] = $uploadedPublicId;
        }

        $variants = $validated['variants'];
        unset($validated['variants']);

        $validated['company_id'] = $company->id;

        if (empty($validated['barcode'])) {
            $validated['barcode'] = $this->generateBarcode($company);
        }

        try {
            DB::transaction(function () use (
                $validated,
                $variants,
                $branches
            ) {
                $product = Product::create($validated);

                $this->syncVariants(
                    $product,
                    $variants,
                    $branches
                );
            });
        } catch (Throwable $e) {
            $this->deleteCloudinaryImage($uploadedPublicId);
            throw $e;
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product and variants created successfully.');
    }

    /**
     * Show product details.
     */
    public function show(string $id)
    {
        $product = $this->findProduct(
            $this->company(),
            (int) $id
        );

        $product->load([
            'category',
            'baseUnit',
            'productVariants.unit',
            'productVariants.branchProducts.branch',
        ]);

        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the Edit Product page.
     */
    public function edit(string $id)
    {
        $company = $this->company();

        $branches = $this->branches($company);

        $product = $this->findProduct($company, (int) $id);

        $branchIds = $branches->pluck('id');

        $product->load([
            'category',
            'baseUnit',
            'productVariants.unit',
            'productVariants.branchProducts' => function ($query) use ($branchIds) {
                $query->whereIn('branch_id', $branchIds);
            },
        ]);

        $categories = Category::where('company_id', $company->id)
            ->orderBy('name')
            ->get();

        $units = $this->units($company);

        return view('admin.products.edit', compact(
            'product',
            'categories',
            'units',
            'branches'
        ));
    }

    /**
     * Update an existing product.
     */
    public function update(Request $request, string $id)
    {
        $company = $this->company();

        $branches = $this->branches($company);

        $product = $this->findProduct($company, (int) $id);

        $validated = $request->validate(array_merge([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'sku' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'sku')
                    ->where('company_id', $company->id)
                    ->ignore($product->id),
            ],

            'barcode' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('products', 'barcode')
                    ->where('company_id', $company->id)
                    ->ignore($product->id),
            ],

            'category_id' => [
                'required',
                'integer',
                Rule::exists('categories', 'id')
                    ->where('company_id', $company->id),
            ],

            'base_unit_id' => [
                'required',
                'integer',
                Rule::exists('units', 'id')
                    ->where('company_id', $company->id),
            ],

            'product_type' => [
                'required',
                Rule::in([
                    'finished_good',
                    'raw_material',
                    'semi_finished',
                    'packaging_material',
                ]),
            ],

            'hsn_code' => [
                'nullable',
                'string',
                'max:20',
            ],

            'gst_rate' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
            ],

            'status' => [
                'required',
                Rule::in(['active', 'inactive']),
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ], $this->variantRules($company, $product)));

        $this->validateVariants(
            $validated['variants'],
            $product
        );

        $this->validateBranchConfigurations(
            $validated['variants'],
            $branches
        );

        $oldImage = $product->image;
        $oldPublicId = $product->image_public_id;
        $newPublicId = null;

        if ($request->hasFile('image')) {
            $upload = app(CloudinaryService::class)->upload(
                $request->file('image')->getRealPath()
            );

            $validated['image'] = $upload['secure_url'] ?? null;
            $newPublicId = $upload['public_id'] ?? null;

            if (!$validated['image'] || !$newPublicId) {
                $this->deleteCloudinaryImage($newPublicId);

                throw new RuntimeException(
                    'Cloudinary did not return the image URL and public ID.'
                );
            }

            $validated['image_public_id'] = $newPublicId;
        }

        $variants = $validated['variants'];
        unset($validated['variants']);

        if (empty($validated['barcode'])) {
            $validated['barcode'] = $product->barcode
                ?: $this->generateBarcode($company);
        }

        try {
            DB::transaction(function () use (
                $product,
                $validated,
                $variants,
                $branches
            ) {
                $product->update($validated);

                $this->syncVariants(
                    $product,
                    $variants,
                    $branches,
                    true
                );
            });
        } catch (Throwable $e) {
            $this->deleteCloudinaryImage($newPublicId);
            throw $e;
        }

        if ($newPublicId) {
            if ($oldPublicId) {
                $this->deleteCloudinaryImage($oldPublicId);
            } else {
                $this->deleteLegacyLocalImage($oldImage);
            }
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product and variants updated successfully.');
    }

    /**
     * Delete a product only when it has no variants.
     */
    public function destroy(string $id)
{
    $product = $this->findProduct(
        $this->company(),
        (int) $id
    );

    $image = $product->image;
    $publicId = $product->image_public_id;

    try {
        \Illuminate\Support\Facades\DB::transaction(function () use ($product) {

            $variantIds = $product->productVariants()
                ->pluck('id');

            if ($variantIds->isNotEmpty()) {
                \Illuminate\Support\Facades\DB::table('order_items')
                    ->whereIn('product_variant_id', $variantIds)
                    ->update([
                        'product_variant_id' => null,
                        'product_id' => null,
                    ]);
            }

            $product->delete();
        });

        if ($publicId) {
            $this->deleteCloudinaryImage($publicId);
        } else {
            $this->deleteLegacyLocalImage($image);
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product and its variants deleted successfully.');

    } catch (\Throwable $e) {
        report($e);

        return redirect()
            ->route('admin.products.index')
            ->with('error', 'Unable to delete this product. Please try again.');
    }
}
}


