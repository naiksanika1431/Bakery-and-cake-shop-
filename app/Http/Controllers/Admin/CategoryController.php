<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Company;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    private function company(): Company
    {
        return Company::where('company_code', 'DELIGHT')
            ->where('is_active', true)
            ->firstOrFail();
    }

    private function findCategory(Company $company, int $id): Category
    {
        return Category::where('company_id', $company->id)
            ->findOrFail($id);
    }

    private function descendantIds(Category $category): array
    {
        $ids = [];
        $pending = [$category->id];

        while (!empty($pending)) {
            $parentId = array_pop($pending);

            $children = Category::where('company_id', $category->company_id)
                ->where('parent_id', $parentId)
                ->get(['id']);

            foreach ($children as $child) {
                $ids[] = $child->id;
                $pending[] = $child->id;
            }
        }

        return $ids;
    }

    public function index(Request $request)
    {
        $company = $this->company();

        $query = Category::with('parent')
            ->withCount('products')
            ->where('company_id', $company->id);

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where('name', 'like', "%{$search}%");
        }

        $baseQuery = Category::where('company_id', $company->id);

        $totalCategories = (clone $baseQuery)->count();

        $activeCategories = (clone $baseQuery)
            ->where('status', 'active')
            ->count();

        $inactiveCategories = (clone $baseQuery)
            ->where('status', 'inactive')
            ->count();

        $categories = $query
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.categories.index', compact(
            'categories',
            'totalCategories',
            'activeCategories',
            'inactiveCategories'
        ));
    }

    public function create()
    {
        $company = $this->company();

        $parentCategories = Category::where('company_id', $company->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('admin.categories.create', compact('parentCategories'));
    }

    public function store(Request $request)
    {
        $company = $this->company();

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')
                    ->where('company_id', $company->id),
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'parent_id' => [
                'nullable',
                Rule::exists('categories', 'id')
                    ->where('company_id', $company->id),
            ],

            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        if ($request->hasFile('image')) {
            $upload = app(CloudinaryService::class)->upload(
                $request->file('image')->getRealPath()
            );

            $imageUrl = $upload['secure_url'] ?? null;

            if (!$imageUrl) {
                throw new \RuntimeException(
                    'Cloudinary did not return the category image URL.'
                );
            }

            $validated['image'] = $imageUrl;
        }

        $validated['company_id'] = $company->id;

        Category::create($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function show(string $id)
    {
        $category = $this->findCategory($this->company(), (int) $id);

        $category->load([
            'parent',
            'products' => function ($query) {
                $query->orderBy('name');
            },
        ]);

        $category->loadCount('products');

        return view('admin.categories.show', compact('category'));
    }

    public function edit(string $id)
    {
        $company = $this->company();

        $category = $this->findCategory($company, (int) $id);

        $excludedIds = array_merge(
            [$category->id],
            $this->descendantIds($category)
        );

        $parentCategories = Category::where('company_id', $company->id)
            ->whereNotIn('id', $excludedIds)
            ->orderBy('name')
            ->get();

        return view('admin.categories.edit', compact(
            'category',
            'parentCategories'
        ));
    }

    public function update(Request $request, string $id)
    {
        $company = $this->company();

        $category = $this->findCategory($company, (int) $id);

        $excludedIds = array_merge(
            [$category->id],
            $this->descendantIds($category)
        );

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')
                    ->where('company_id', $company->id)
                    ->ignore($category->id),
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'parent_id' => [
                'nullable',
                Rule::exists('categories', 'id')
                    ->where('company_id', $company->id)
                    ->whereNotIn('id', $excludedIds),
            ],

            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        if ($request->hasFile('image')) {
            $upload = app(CloudinaryService::class)->upload(
                $request->file('image')->getRealPath()
            );

            $imageUrl = $upload['secure_url'] ?? null;

            if (!$imageUrl) {
                throw new \RuntimeException(
                    'Cloudinary did not return the category image URL.'
                );
            }

            $validated['image'] = $imageUrl;
        }

        $category->update($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(string $id)
    {
        $category = $this->findCategory($this->company(), (int) $id);

        if ($category->products()->exists()) {
            return redirect()
                ->route('admin.categories.index')
                ->with('error', 'This category contains products. Reassign or remove those products before deleting it.');
        }

        if ($category->children()->exists()) {
            return redirect()
                ->route('admin.categories.index')
                ->with('error', 'This category contains subcategories. Reassign or delete them before deleting this category.');
        }

        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
