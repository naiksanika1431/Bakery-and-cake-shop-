<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Payment;
use App\Models\BranchProduct;
use App\Models\Company;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    /**
     * Get the default bakery company and branch without requiring login.
     */
    private function adminContext(): array
    {
        $company = Company::where('company_code', 'DELIGHT')
            ->where('is_active', true)
            ->firstOrFail();

        $branch = Branch::where('company_id', $company->id)
            ->where('branch_code', 'DELIGHT-MAIN')
            ->where('is_active', true)
            ->firstOrFail();

        return [
            'user' => auth()->user(),
            'company_id' => $company->id,
            'branch_id' => $branch->id,
        ];
    }

    /**
     * Display orders.
     */
    public function index(Request $request)
    {
        $context = $this->adminContext();

        $query = Order::with(['branch', 'customer'])
            ->where('company_id', $context['company_id'])
            ->where('branch_id', $context['branch_id']);

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $baseQuery = Order::where('company_id', $context['company_id'])
            ->where('branch_id', $context['branch_id']);

        $totalOrders = (clone $baseQuery)->count();

        $pendingOrders = (clone $baseQuery)
            ->where('order_status', 'pending')
            ->count();

        $completedOrders = (clone $baseQuery)
            ->where('order_status', 'completed')
            ->count();

        $cancelledOrders = (clone $baseQuery)
            ->where('order_status', 'cancelled')
            ->count();

        $orders = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.orders.index', compact(
            'orders',
            'totalOrders',
            'pendingOrders',
            'completedOrders',
            'cancelledOrders'
        ));
    }
/**
     * Show create order form.
     */
    public function create()
    {
        $context = $this->adminContext();

        $products = BranchProduct::with([
            'productVariant.product',
            'productVariant.unit',
        ])
            ->where('branch_id', $context['branch_id'])
            ->where('is_available', true)
            ->whereHas('productVariant.product', function ($query) use ($context) {
                $query->where('company_id', $context['company_id'])
                    ->where('status', 'active');
            })
            ->get();

        return view('admin.orders.create', compact('products'));
    }

    /**
     * Store a new order.
     */
    public function store(Request $request)
    {
        $context = $this->adminContext();

        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:20'],
            'customer_email' => ['nullable', 'email', 'max:255'],

            'order_type' => [
                'required',
                'in:walk_in,online,phone,delivery,pickup',
            ],

            'delivery_address' => ['nullable', 'string'],
            'delivery_city' => ['nullable', 'string', 'max:255'],
            'delivery_state' => ['nullable', 'string', 'max:255'],
            'delivery_pincode' => ['nullable', 'string', 'max:10'],

            'placed_at' => ['nullable', 'date'],
            'scheduled_at' => ['nullable', 'date'],

            'delivery_charge' => ['nullable', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0'],

            'items' => ['required', 'array', 'min:1'],
            'items.*.product_variant_id' => [
                'required',
                'integer',
                'distinct',
            ],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.discount' => ['nullable', 'numeric', 'min:0'],
            'items.*.tax_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'items.*.customization_details' => ['nullable', 'string'],
            'items.*.special_instructions' => ['nullable', 'string'],

            'payment_method' => [
                'nullable',
                'in:cash,upi,card,online,bank_transfer',
            ],
            'payment_status' => [
                'required',
                'in:pending,paid,partial',
            ],
            'amount_paid' => ['nullable', 'numeric', 'min:0'],

            'order_status' => [
                'required',
                'in:pending,confirmed,processing,ready,completed,cancelled',
            ],

            'notes' => ['nullable', 'string'],
        ]);

        return DB::transaction(function () use ($validated, $context) {

            $calculation = $this->calculateItems(
                $validated['items'],
                $context
            );

            $orderDiscount = (float) ($validated['discount'] ?? 0);
            $deliveryCharge = (float) ($validated['delivery_charge'] ?? 0);

            if ($orderDiscount > $calculation['subtotal'] - $calculation['item_discount']) {
                throw ValidationException::withMessages([
                    'discount' => 'Order discount cannot exceed the remaining subtotal.',
                ]);
            }

            $discount = $calculation['item_discount'] + $orderDiscount;

            $grandTotal = round(
                $calculation['subtotal']
                - $discount
                + $calculation['tax_amount']
                + $deliveryCharge,
                2
            );

            $amountPaid = (float) ($validated['amount_paid'] ?? 0);

            if ($validated['payment_status'] === 'paid') {
                $amountPaid = $grandTotal;
            }

            if ($amountPaid > $grandTotal) {
                throw ValidationException::withMessages([
                    'amount_paid' => 'Received amount cannot exceed the order total.',
                ]);
            }

            if (
                $validated['payment_status'] === 'partial'
                && ($amountPaid <= 0 || $amountPaid >= $grandTotal)
            ) {
                throw ValidationException::withMessages([
                    'amount_paid' => 'Enter an amount greater than zero and less than the order total for partial payment.',
                ]);
            }

            if (
                $validated['payment_status'] === 'pending'
                && $amountPaid > 0
            ) {
                throw ValidationException::withMessages([
                    'amount_paid' => 'Pending orders cannot have a received payment amount.',
                ]);
            }

            $order = Order::create([
                'company_id' => $context['company_id'],
                'branch_id' => $context['branch_id'],
                'customer_id' => null,

                'order_number' => $this->generateOrderNumber(),

                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'customer_email' => $validated['customer_email'] ?? null,

                'order_type' => $validated['order_type'],

                'delivery_address' => $validated['delivery_address'] ?? null,
                'delivery_city' => $validated['delivery_city'] ?? null,
                'delivery_state' => $validated['delivery_state'] ?? null,
                'delivery_pincode' => $validated['delivery_pincode'] ?? null,

                'order_status' => $validated['order_status'],
                'payment_status' => $validated['payment_status'],
                'payment_method' => $validated['payment_method'] ?? null,

                'subtotal' => $calculation['subtotal'],
                'discount' => $discount,
                'tax_amount' => $calculation['tax_amount'],
                'delivery_charge' => $deliveryCharge,
                'grand_total' => $grandTotal,

                'notes' => $validated['notes'] ?? null,
                'placed_at' => $validated['placed_at'] ?? now(),
                'scheduled_at' => $validated['scheduled_at'] ?? null,
            ]);

            foreach ($calculation['items'] as $item) {
                $order->items()->create($item);
            }

            if ($amountPaid > 0) {
                Payment::create([
                    'order_id' => $order->id,
                    'payment_method' => $validated['payment_method'],
                    'payment_type' => 'payment',
                    'amount' => $amountPaid,
                    'payment_status' => 'paid',
                    'paid_at' => now(),
                    'notes' => 'Payment received during order creation.',
                ]);
            }

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'old_status' => null,
                'new_status' => $order->order_status,
                'updated_by' => $context['user']?->id,
                'notes' => 'Order created.',
            ]);

            return redirect()
                ->route('admin.orders.show', $order->id)
                ->with('success', 'Order created successfully.');
        });
    }

    /**
     * Display an individual order.
     */
    public function show(Order $order)
    {
        $context = $this->adminContext();

        $this->authorizeOrder($order, $context);

        $order->load([
            'branch',
            'customer',
            'items.product',
            'items.productVariant',
            'payments',
            'statusHistories.updatedBy',
        ]);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Show edit order form.
     */
    public function edit(Order $order)
    {
        $context = $this->adminContext();

        $this->authorizeOrder($order, $context);

        $order->load([
            'items.product',
            'items.productVariant',
            'payments',
            'statusHistories',
        ]);

        $products = BranchProduct::with([
            'productVariant.product',
            'productVariant.unit',
        ])
            ->where('branch_id', $context['branch_id'])
            ->where('is_available', true)
            ->whereHas('productVariant.product', function ($query) use ($context) {
                $query->where('company_id', $context['company_id'])
                    ->where('status', 'active');
            })
            ->get();

        return view('admin.orders.edit', compact('order', 'products'));
    }

    /**
     * Update an existing order.
     */
    public function update(Request $request, Order $order)
    {
        $context = $this->adminContext();

        $this->authorizeOrder($order, $context);

        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:20'],
            'customer_email' => ['nullable', 'email', 'max:255'],

            'order_type' => [
                'required',
                'in:walk_in,online,phone,delivery,pickup',
            ],

            'delivery_address' => ['nullable', 'string'],
            'delivery_city' => ['nullable', 'string', 'max:255'],
            'delivery_state' => ['nullable', 'string', 'max:255'],
            'delivery_pincode' => ['nullable', 'string', 'max:10'],

            'scheduled_at' => ['nullable', 'date'],

            'delivery_charge' => ['nullable', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0'],

            'items' => ['required', 'array', 'min:1'],
            'items.*.product_variant_id' => [
                'required',
                'integer',
                'distinct',
            ],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.discount' => ['nullable', 'numeric', 'min:0'],
            'items.*.tax_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'items.*.customization_details' => ['nullable', 'string'],
            'items.*.special_instructions' => ['nullable', 'string'],

            'payment_method' => [
                'nullable',
                'in:cash,upi,card,online,bank_transfer',
            ],
            'payment_status' => [
                'required',
                'in:pending,paid,partial,failed,refunded',
            ],
            'order_status' => [
                'required',
                'in:pending,confirmed,processing,ready,completed,cancelled',
            ],

            'notes' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated, $context, $order) {

            $calculation = $this->calculateItems(
                $validated['items'],
                $context
            );

            $orderDiscount = (float) ($validated['discount'] ?? 0);
            $deliveryCharge = (float) ($validated['delivery_charge'] ?? 0);

            if ($orderDiscount > $calculation['subtotal'] - $calculation['item_discount']) {
                throw ValidationException::withMessages([
                    'discount' => 'Order discount cannot exceed the remaining subtotal.',
                ]);
            }

            $oldStatus = $order->order_status;

            $discount = $calculation['item_discount'] + $orderDiscount;

            $grandTotal = round(
                $calculation['subtotal']
                - $discount
                + $calculation['tax_amount']
                + $deliveryCharge,
                2
            );

            $order->update([
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'customer_email' => $validated['customer_email'] ?? null,

                'order_type' => $validated['order_type'],

                'delivery_address' => $validated['delivery_address'] ?? null,
                'delivery_city' => $validated['delivery_city'] ?? null,
                'delivery_state' => $validated['delivery_state'] ?? null,
                'delivery_pincode' => $validated['delivery_pincode'] ?? null,

                'scheduled_at' => $validated['scheduled_at'] ?? null,

                'payment_method' => $validated['payment_method'] ?? null,
                'payment_status' => $validated['payment_status'],
                'order_status' => $validated['order_status'],

                'subtotal' => $calculation['subtotal'],
                'discount' => $discount,
                'tax_amount' => $calculation['tax_amount'],
                'delivery_charge' => $deliveryCharge,
                'grand_total' => $grandTotal,

                'notes' => $validated['notes'] ?? null,
            ]);

            $order->items()->delete();

            foreach ($calculation['items'] as $item) {
                $order->items()->create($item);
            }

            if ($oldStatus !== $order->order_status) {
                OrderStatusHistory::create([
                    'order_id' => $order->id,
                    'old_status' => $oldStatus,
                    'new_status' => $order->order_status,
                    'updated_by' => $context['user']?->id,
                    'notes' => 'Order status updated.',
                ]);
            }
        });

        return redirect()
            ->route('admin.orders.show', $order->id)
            ->with('success', 'Order updated successfully.');
    }

    /**
     * Delete an order.
     */
    public function destroy(Order $order)
    {
        $context = $this->adminContext();

        $this->authorizeOrder($order, $context);

        DB::transaction(function () use ($order) {
            $order->delete();
        });

        return redirect()
            ->route('admin.orders.index')
            ->with('success', 'Order deleted successfully.');
    }

    /**
     * Calculate order item prices using branch database prices.
     */
    private function calculateItems(array $items, array $context): array
    {
        $calculatedItems = [];

        $subtotal = 0;
        $totalDiscount = 0;
        $totalTax = 0;

        foreach ($items as $item) {

            $branchProduct = BranchProduct::with([
                'productVariant.product',
            ])
                ->where('branch_id', $context['branch_id'])
                ->where('product_variant_id', $item['product_variant_id'])
                ->where('is_available', true)
                ->whereHas('productVariant.product', function ($query) use ($context) {
                    $query->where('company_id', $context['company_id'])
                        ->where('status', 'active');
                })
                ->first();

            if (!$branchProduct) {
                throw ValidationException::withMessages([
                    'items' => 'One or more selected product variants are unavailable at this branch.',
                ]);
            }

            $variant = $branchProduct->productVariant;
            $product = $variant->product;

            $quantity = (int) $item['quantity'];
            $unitPrice = (float) $branchProduct->selling_price;

            $grossAmount = round($unitPrice * $quantity, 2);

            $itemDiscount = (float) ($item['discount'] ?? 0);

            if ($itemDiscount > $grossAmount) {
                throw ValidationException::withMessages([
                    'items' => "Discount cannot exceed the price of {$product->name}.",
                ]);
            }

            $taxRate = (float) ($item['tax_rate'] ?? 0);

            $taxableAmount = $grossAmount - $itemDiscount;

            $taxAmount = round(
                $taxableAmount * $taxRate / 100,
                2
            );

            $lineTotal = round(
                $taxableAmount + $taxAmount,
                2
            );

            $subtotal += $grossAmount;
            $totalDiscount += $itemDiscount;
            $totalTax += $taxAmount;

            $calculatedItems[] = [
                'product_id' => $product->id,
                'product_variant_id' => $variant->id,

                'product_name' => $product->name . ' - ' . $variant->variant_name,
                'product_sku' => $variant->sku ?: $product->sku,
                'product_image' => $product->image,

                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'discount' => $itemDiscount,
                'tax_rate' => $taxRate,
                'tax_amount' => $taxAmount,
                'line_total' => $lineTotal,

                'is_customized' => !empty($item['customization_details']),
                'customization_details' => $item['customization_details'] ?? null,
                'special_instructions' => $item['special_instructions'] ?? null,
            ];
        }

        return [
            'items' => $calculatedItems,
            'subtotal' => round($subtotal, 2),
            'item_discount' => round($totalDiscount, 2),
            'tax_amount' => round($totalTax, 2),
        ];
    }

    /**
     * Generate a unique order number.
     */
    private function generateOrderNumber(): string
    {
        do {
            $orderNumber = 'ORD-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
        } while (Order::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }

    /**
     * Ensure the order belongs to the logged-in user's company and branch.
     */
    private function authorizeOrder(Order $order, array $context): void
    {
        abort_unless(
            $order->company_id == $context['company_id']
            && $order->branch_id == $context['branch_id'],
            404
        );
    }
}



