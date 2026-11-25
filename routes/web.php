
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\ShopController;

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RawMaterialController;
use App\Http\Controllers\Admin\FinishedGoodsController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\POSController;
use App\Http\Controllers\Admin\StockController;



use App\Http\Controllers\Admin\StockLedgerController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\SupplierPurchaseController;
// ==========================================
// SUPER ADMIN
// ==========================================

Route::get('/super-admin/dashboard', function () {
    return view('super-admin.dashboard');
})->name('super-admin.dashboard');


// ==========================================
// BRANCH ADMIN
// ==========================================

Route::prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Admin Dashboard
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // STOCK MANAGEMENT - READ ONLY OVERVIEW
        Route::get('/stock', [StockController::class, 'index'])
            ->name('stock.index');
// STOCK LEDGER
Route::get('/stock-ledger', [StockLedgerController::class, 'index'])
    ->name('stock.ledger');


        // ==========================================
        // CATEGORY MANAGEMENT
        // ==========================================

        Route::resource('categories', CategoryController::class);


        // ==========================================
        // PRODUCT MANAGEMENT
        // ==========================================

        Route::resource('products', ProductController::class);


// FINISHED GOODS STOCK
Route::get('/finished-goods', [FinishedGoodsController::class, 'index'])
    ->name('stock.finished-goods');
// RAW MATERIAL MANAGEMENT
Route::get('/raw-materials', [RawMaterialController::class, 'index'])
    ->name('stock.raw-materials');

Route::get('/raw-materials/create', [RawMaterialController::class, 'create'])
    ->name('stock.raw-materials.create');

Route::post('/raw-materials', [RawMaterialController::class, 'store'])
    ->name('stock.raw-materials.store');

Route::get('/raw-materials/{id}', [RawMaterialController::class, 'show'])
    ->name('stock.raw-materials.show');

Route::get('/raw-materials/{id}/edit', [RawMaterialController::class, 'edit'])
    ->name('stock.raw-materials.edit');

Route::put('/raw-materials/{id}', [RawMaterialController::class, 'update'])
    ->name('stock.raw-materials.update');


        // ==========================================
        // ORDER MANAGEMENT
        // ==========================================

        Route::resource('orders', OrderController::class);


        // ==========================================
        // POS BILLING MODULE
        // ==========================================

        Route::prefix('pos')
            ->name('pos.')
            ->controller(POSController::class)
            ->group(function () {

                // POS Dashboard
                Route::get('/', 'index')->name('index');

              // Create Walk-in Customer
          Route::post('/customers', 'storeCustomer')->name('customers.store');

                  // POS Checkout
                  Route::post('/checkout', 'checkout')->name('checkout');

                // POS Invoice
                Route::get('/invoice/{order}', 'invoice')->name('invoice');

                // New Bill
                Route::get('/new-bill', 'newBill')->name('new-bill');

                // Sales History
                Route::get('/sales-history', 'sales')->name('sales');

                // Held Bills
                Route::get('/held-bills', 'heldBills')->name('held-bills');

                // POS Settings
                Route::get('/settings', 'settings')->name('settings');

            });

        // SUPPLIER MANAGEMENT
        Route::resource('suppliers', SupplierController::class)->except(['destroy']);

// SUPPLIER PURCHASE MANAGEMENT
Route::prefix('supplier-purchases')
    ->name('supplier-purchases.')
    ->controller(SupplierPurchaseController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::post('/{supplierPurchase}/receive', 'receive')->name('receive');
        Route::get('/{supplierPurchase}', 'show')->name('show');
    });

    });


// ==========================================
// CUSTOMER WEBSITE
// ==========================================

// Home Page
Route::get('/shop', [ShopController::class, 'home'])->name('customer.home');
// Customer Branch Selection
Route::post('/shop/select-branch', [ShopController::class, 'selectBranch'])
    ->name('customer.branch.select');

// Categories Page
Route::get('/shop/categories', [ShopController::class, 'categories'])->name('customer.categories');

// Products Page
Route::get('/shop/products', [ShopController::class, 'products'])
    ->name('customer.products');

// About Us Page
Route::get('/shop/about', function () {
    return view('customer.about');
})->name('customer.about');

// Contact Page
Route::get('/shop/contact', function () {
    return view('customer.contact');
})->name('customer.contact');


// ==========================================
// DEFAULT ROUTE
// ==========================================

Route::get('/', function () {
    return redirect()->route('super-admin.dashboard');
});











