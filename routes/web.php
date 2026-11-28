
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\ShopController;
use App\Http\Controllers\Customer\CustomerAuthController;
use App\Http\Controllers\Customer\CustomCakeOrderController as CustomerCustomCakeOrderController;

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RawMaterialController;
use App\Http\Controllers\Admin\FinishedGoodsController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\POSController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\AdminAuthController;



use App\Http\Controllers\Admin\StockLedgerController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\SupplierPurchaseController;
use App\Http\Controllers\Admin\CustomCakeOrderController;
use App\Http\Controllers\Admin\EmployeeController;
// ==========================================
// ADMIN / STAFF AUTHENTICATION
// ==========================================

Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])
    ->name('admin.login');

Route::post('/admin/login', [AdminAuthController::class, 'login'])
    ->name('admin.login.submit');

Route::post('/admin/logout', [AdminAuthController::class, 'logout'])
    ->middleware('auth:web')
    ->name('admin.logout');


// ==========================================
// SUPER ADMIN
// ==========================================

Route::get('/super-admin/dashboard', function () {
    if (auth('web')->user()?->role?->role_code !== 'super_admin') {
        abort(403, 'Only Super Admin can access this page.');
    }

    return view('super-admin.dashboard');
})->middleware('auth:web')->name('super-admin.dashboard');


// ==========================================
// BRANCH ADMIN
// ==========================================

Route::prefix('admin')
    ->name('admin.')
    ->middleware('auth:web')
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
Route::post('/finished-goods/adjustments', [FinishedGoodsController::class, 'storeAdjustment'])
    ->name('stock.finished-goods.adjustments.store');

Route::post('/finished-goods/adjustments/{adjustment}/approve', [FinishedGoodsController::class, 'approveAdjustment'])
    ->name('stock.finished-goods.adjustments.approve');

Route::post('/finished-goods/adjustments/{adjustment}/reject', [FinishedGoodsController::class, 'rejectAdjustment'])
    ->name('stock.finished-goods.adjustments.reject');
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

        // CUSTOM CAKE ORDER MANAGEMENT
        Route::get('/custom-cake-orders', [CustomCakeOrderController::class, 'index'])
            ->name('custom-cake-orders.index');
        Route::get('/custom-cake-orders/create', [CustomCakeOrderController::class, 'create'])
            ->name('custom-cake-orders.create');
        Route::post('/custom-cake-orders', [CustomCakeOrderController::class, 'store'])
            ->name('custom-cake-orders.store');

        Route::get('/custom-cake-orders/{id}/edit', [CustomCakeOrderController::class, 'edit'])
            ->name('custom-cake-orders.edit');

        Route::put('/custom-cake-orders/{id}', [CustomCakeOrderController::class, 'update'])
            ->name('custom-cake-orders.update');
        Route::get('/custom-cake-orders/{id}', [CustomCakeOrderController::class, 'show'])
            ->name('custom-cake-orders.show');
        Route::post('/custom-cake-orders/{id}/quotation', [CustomCakeOrderController::class, 'saveQuotation'])
            ->name('custom-cake-orders.quotation.save');

        Route::post('/custom-cake-orders/{id}/quotation/send', [CustomCakeOrderController::class, 'sendQuotation'])
            ->name('custom-cake-orders.quotation.send');

        Route::patch('/custom-cake-orders/{id}/status', [CustomCakeOrderController::class, 'updateStatus'])
            ->name('custom-cake-orders.status.update');


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

        // CUSTOMER MANAGEMENT
        Route::get('/customers', [\App\Http\Controllers\Admin\CustomerController::class, 'index'])
            ->middleware('permission:customers.view')
            ->name('customers.index');

        Route::get('/customers/create', [\App\Http\Controllers\Admin\CustomerController::class, 'create'])
            ->middleware('permission:customers.create')
            ->name('customers.create');

        Route::post('/customers', [\App\Http\Controllers\Admin\CustomerController::class, 'store'])
            ->middleware('permission:customers.create')
            ->name('customers.store');

        Route::get('/customers/{id}/edit', [\App\Http\Controllers\Admin\CustomerController::class, 'edit'])
            ->middleware('permission:customers.update')
            ->name('customers.edit');

        Route::put('/customers/{id}', [\App\Http\Controllers\Admin\CustomerController::class, 'update'])
            ->middleware('permission:customers.update')
            ->name('customers.update');

        Route::get('/customers/{id}', [\App\Http\Controllers\Admin\CustomerController::class, 'show'])
            ->middleware('permission:customers.view')
            ->name('customers.show');

        // CUSTOMER LEDGER
        Route::get('/customer-ledger', [\App\Http\Controllers\Admin\CustomerLedgerController::class, 'index'])
            ->middleware('permission:customers.view')
            ->name('customer-ledger.index');

        Route::get('/customer-ledger/{id}', [\App\Http\Controllers\Admin\CustomerLedgerController::class, 'show'])
            ->middleware('permission:customers.view')
            ->name('customer-ledger.show');
        // CUSTOMER PAYMENTS
        Route::get('/customer-payments', [\App\Http\Controllers\Admin\CustomerPaymentController::class, 'index'])
            ->middleware('permission:customers.view')
            ->name('customer-payments.index');

        Route::post('/customer-payments', [\App\Http\Controllers\Admin\CustomerPaymentController::class, 'store'])
            ->middleware('permission:customers.update')
            ->name('customer-payments.store');

        Route::get('/customer-payments/history', [\App\Http\Controllers\Admin\CustomerPaymentController::class, 'history'])
            ->middleware('permission:customers.view')
            ->name('customer-payments.history');
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
        // ==========================================
        // EMPLOYEE MANAGEMENT
        // ==========================================

        Route::resource('employees', EmployeeController::class);
        // ADMIN PROFILE
        Route::get('/profile', [\App\Http\Controllers\Admin\AdminProfileController::class, 'edit'])
            ->name('profile.edit');

        Route::put('/profile', [\App\Http\Controllers\Admin\AdminProfileController::class, 'update'])
            ->name('profile.update');
});
// ==========================================
// CUSTOMER WEBSITE
// ==========================================

// Home Page
Route::get('/shop', [ShopController::class, 'home'])->name('customer.home');
// Custom Cake Booking
Route::get('/custom-cake-booking', function () {
    return view('customer.custom-cake.booking');
})->middleware('auth:customer')->name('customer.custom-cake-orders.create');
Route::post('/custom-cake-booking', [CustomerCustomCakeOrderController::class, 'store'])->middleware('auth:customer')->name('customer.custom-cake-orders.store');
// Customer Account
Route::get('/shop/account', [CustomerAuthController::class, 'account'])
    ->middleware('auth:customer')
    ->name('customer.account');

// Customer Custom Cake Quotation Response
Route::post('/shop/account/custom-cake-orders/{id}/accept', [CustomerCustomCakeOrderController::class, 'acceptQuotation'])
    ->middleware('auth:customer')
    ->name('customer.custom-cake-orders.accept');

Route::post('/shop/account/custom-cake-orders/{id}/reject', [CustomerCustomCakeOrderController::class, 'rejectQuotation'])
    ->middleware('auth:customer')
    ->name('customer.custom-cake-orders.reject');
// Customer Authentication
Route::get('/shop/login', [CustomerAuthController::class, 'showLogin'])
    ->name('customer.login');

Route::post('/shop/login', [CustomerAuthController::class, 'login'])
    ->name('customer.login.submit');

Route::get('/shop/register', [CustomerAuthController::class, 'showRegister'])
    ->name('customer.register');

Route::post('/shop/register', [CustomerAuthController::class, 'register'])
    ->name('customer.register.submit');

Route::post('/shop/logout', [CustomerAuthController::class, 'logout'])
    ->middleware('auth:customer')
    ->name('customer.logout');
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
