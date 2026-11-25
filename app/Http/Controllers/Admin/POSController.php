<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class POSController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $company = $user && $user->company_id
            ? Company::whereKey($user->company_id)
                ->where('is_active', true)
                ->first()
            : Company::where('is_active', true)
                ->orderBy('id')
                ->first();

        abort_unless($company, 404, 'No active company found.');

        // POS currently operates at company level without branch selection.
        $branch = null;
        $catalogPreview = true;

        $customers = Customer::where('company_id', $company->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $categories = Category::where('company_id', $company->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $products = Product::with([
            'category',
            'productVariants.unit',
        ])
            ->where('company_id', $company->id)
            ->where('status', 'active')
            ->whereHas('productVariants')
            ->orderBy('name')
            ->get();

        return view('admin.pos.index', compact(
            'company',
            'branch',
            'customers',
            'categories',
            'products',
            'catalogPreview'
        ));
    }
    /**
     * Create a new walk-in customer.
     */
    public function storeCustomer(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'phone' => [
                'required',
                'string',
                'regex:/^[6-9][0-9]{9}$/',
            ],
            'email' => [
                'nullable',
                'string',
                'email:rfc',
                'max:255',
            ],
        ], [
            'name.required' => 'Please enter the customer name.',
            'phone.required' => 'Please enter the customer mobile number.',
            'phone.regex' => 'Enter a valid 10-digit Indian mobile number starting with 6, 7, 8, or 9.',
            'email.email' => 'Please enter a valid email address.',
        ]);

        $user = auth()->user();

$companyId = $user?->company_id
    ?? Company::where('is_active', true)->value('id');

$company = Company::whereKey($companyId)
    ->where('is_active', true)
    ->first();

abort_unless($company, 403, 'No active company is available to create customers.');

        // Generate a customer code.
        $customerCode = 'CUS-' . strtoupper(Str::random(8));

        $customer = Customer::create([
            'company_id' => $company->id,
            'customer_code' => $customerCode,
            'name' => trim($validated['name']),
            'phone' => $validated['phone'],
            'email' => !empty($validated['email'])
                ? trim($validated['email'])
                : null,
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Customer added successfully.',
            'customer' => [
                'id' => $customer->id,
                'name' => $customer->name,
                'phone' => $customer->phone,
                'email' => $customer->email,
            ],
        ], 201);
    }

    /**
     * Create a POS order and record manually received payments.
     * POS currently operates at company level without branch inventory.
     */
    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.variant_id' => ['required', 'integer', 'distinct'],
            'items.*.quantity' => ['required', 'numeric', 'gt:0', 'max:999999'],

            'customer_id' => ['nullable', 'integer'],
            'customer_name' => ['nullable', 'string', 'max:255'],

            'invoice_type' => ['required', 'in:gst,non_gst'],
            'discount_type' => ['required', 'in:percentage,fixed'],
            'discount_value' => ['nullable', 'numeric', 'min:0'],

            'payment_method' => ['required', 'in:cash,upi,card,split,due'],
            'amount_received' => ['nullable', 'numeric', 'min:0'],
            'payment_reference' => ['nullable', 'string', 'max:255'],

            'split_cash_amount' => ['nullable', 'numeric', 'min:0'],
            'split_upi_amount' => ['nullable', 'numeric', 'min:0'],
            'split_card_amount' => ['nullable', 'numeric', 'min:0'],
            'split_upi_reference' => ['nullable', 'string', 'max:255'],
            'split_card_reference' => ['nullable', 'string', 'max:255'],
        ]);

        $user = auth()->user();

        $company = $user && $user->company_id
            ? Company::whereKey($user->company_id)
                ->where('is_active', true)
                ->first()
            : Company::where('is_active', true)
                ->orderBy('id')
                ->first();

        abort_unless($company, 403, 'No active company is available for checkout.');

        if (
            $validated['discount_type'] === 'percentage' &&
            ($validated['discount_value'] ?? 0) > 100
        ) {
            throw ValidationException::withMessages([
                'discount_value' => 'Percentage discount cannot exceed 100%.',
            ]);
        }

        $customer = null;

        if (!empty($validated['customer_id'])) {
            $customer = Customer::where('company_id', $company->id)
                ->where('is_active', true)
                ->find($validated['customer_id']);

            if (!$customer) {
                throw ValidationException::withMessages([
                    'customer_id' => 'The selected customer is invalid.',
                ]);
            }
        }

        $variantIds = collect($validated['items'])
            ->pluck('variant_id')
            ->unique()
            ->values();

        $variants = ProductVariant::with('product')
            ->whereIn('id', $variantIds)
            ->whereHas('product', function ($query) use ($company) {
                $query->where('company_id', $company->id)
                    ->where('status', 'active');
            })
            ->get()
            ->keyBy('id');

        if ($variants->count() !== $variantIds->count()) {
            throw ValidationException::withMessages([
                'items' => 'One or more selected products are unavailable.',
            ]);
        }

        return DB::transaction(function () use (
            $validated,
            $company,
            $customer,
            $variants
        ) {
            $items = [];
            $subtotalCents = 0;

            foreach ($validated['items'] as $requestedItem) {
                $variant = $variants->get($requestedItem['variant_id']);
                $product = $variant->product;

                $quantity = (float) $requestedItem['quantity'];
                $unitPrice = (float) $variant->default_selling_price;

                if (!is_finite($unitPrice) || $unitPrice < 0) {
                    throw ValidationException::withMessages([
                        'items' => 'A selected product has an invalid selling price.',
                    ]);
                }

                $grossCents = (int) round($unitPrice * $quantity * 100);

                $items[] = [
                    'variant' => $variant,
                    'product' => $product,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'gross_cents' => $grossCents,
                ];

                $subtotalCents += $grossCents;
            }

            $discountValue = (float) ($validated['discount_value'] ?? 0);

            $discountCents = $validated['discount_type'] === 'percentage'
                ? (int) round($subtotalCents * $discountValue / 100)
                : (int) round($discountValue * 100);

            $discountCents = min($discountCents, $subtotalCents);

            $taxCents = 0;
            $orderItems = [];
            $allocatedDiscount = 0;
            $lastIndex = count($items) - 1;

            foreach ($items as $index => $item) {
                if ($index === $lastIndex) {
                    $itemDiscountCents = $discountCents - $allocatedDiscount;
                } else {
                    $itemDiscountCents = $subtotalCents > 0
                        ? (int) round(
                            $discountCents * $item['gross_cents'] / $subtotalCents
                        )
                        : 0;

                    $itemDiscountCents = min(
                        $itemDiscountCents,
                        $item['gross_cents'],
                        $discountCents - $allocatedDiscount
                    );
                }

                $allocatedDiscount += $itemDiscountCents;

                $taxRate = $validated['invoice_type'] === 'gst'
                    ? (float) $item['product']->gst_rate
                    : 0;

                $taxableCents = $item['gross_cents'] - $itemDiscountCents;
                $itemTaxCents = (int) round($taxableCents * $taxRate / 100);

                $taxCents += $itemTaxCents;

                $productName = $item['product']->name;

                if (!empty($item['variant']->variant_name)) {
                    $productName .= ' - ' . $item['variant']->variant_name;
                }

                $orderItems[] = [
                    'product_id' => $item['product']->id,
                    'product_variant_id' => $item['variant']->id,
                    'product_name' => $productName,
                    'product_sku' => $item['variant']->sku
                        ?: $item['product']->sku,
                    'product_image' => $item['product']->image,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount' => $itemDiscountCents / 100,
                    'tax_rate' => $taxRate,
                    'tax_amount' => $itemTaxCents / 100,
                    'line_total' => ($taxableCents + $itemTaxCents) / 100,
                    'is_customized' => false,
                ];
            }

            $grandTotalCents = $subtotalCents - $discountCents + $taxCents;

            $paymentMethod = $validated['payment_method'];
            $totalPaidCents = 0;
            $paymentRows = [];

            $toCents = function ($amount) {
                $amount = (float) ($amount ?? 0);

                if (
                    !is_finite($amount) ||
                    abs($amount * 100 - round($amount * 100)) > 0.000001
                ) {
                    throw ValidationException::withMessages([
                        'payment' => 'Payment amounts must have up to two decimal places.',
                    ]);
                }

                return (int) round($amount * 100);
            };

            if ($paymentMethod === 'cash') {
                $receivedCents = $toCents($validated['amount_received'] ?? 0);

                if ($receivedCents < $grandTotalCents || $receivedCents <= 0) {
                    throw ValidationException::withMessages([
                        'amount_received' => 'Cash received must be at least the bill total.',
                    ]);
                }

                $totalPaidCents = $grandTotalCents;

                $paymentRows[] = [
                    'method' => 'cash',
                    'amount' => $grandTotalCents,
                    'reference' => null,
                    'notes' => 'Cash received: ' . number_format($receivedCents / 100, 2)
                        . '; Change returned: '
                        . number_format(($receivedCents - $grandTotalCents) / 100, 2),
                ];

            } elseif (in_array($paymentMethod, ['upi', 'card'], true)) {
                $receivedCents = $toCents($validated['amount_received'] ?? 0);
                $reference = trim($validated['payment_reference'] ?? '');

                if ($receivedCents !== $grandTotalCents || $receivedCents <= 0) {
                    throw ValidationException::withMessages([
                        'amount_received' => 'Payment amount must exactly match the bill total.',
                    ]);
                }

                if ($reference === '') {
                    throw ValidationException::withMessages([
                        'payment_reference' => 'Transaction reference is required.',
                    ]);
                }

                $totalPaidCents = $grandTotalCents;

                $paymentRows[] = [
                    'method' => $paymentMethod,
                    'amount' => $grandTotalCents,
                    'reference' => $reference,
                    'notes' => 'Manually recorded payment. Gateway verification not performed.',
                ];

            } elseif ($paymentMethod === 'split') {
                $cashCents = $toCents($validated['split_cash_amount'] ?? 0);
                $upiCents = $toCents($validated['split_upi_amount'] ?? 0);
                $cardCents = $toCents($validated['split_card_amount'] ?? 0);

                if ($cashCents + $upiCents + $cardCents !== $grandTotalCents) {
                    throw ValidationException::withMessages([
                        'payment' => 'Split payment amounts must exactly equal the bill total.',
                    ]);
                }

                $upiReference = trim($validated['split_upi_reference'] ?? '');
                $cardReference = trim($validated['split_card_reference'] ?? '');

                if ($upiCents > 0 && $upiReference === '') {
                    throw ValidationException::withMessages([
                        'split_upi_reference' => 'UPI transaction reference is required.',
                    ]);
                }

                if ($cardCents > 0 && $cardReference === '') {
                    throw ValidationException::withMessages([
                        'split_card_reference' => 'Card transaction reference is required.',
                    ]);
                }

                foreach ([
                    ['method' => 'cash', 'amount' => $cashCents, 'reference' => null],
                    ['method' => 'upi', 'amount' => $upiCents, 'reference' => $upiReference],
                    ['method' => 'card', 'amount' => $cardCents, 'reference' => $cardReference],
                ] as $splitPayment) {
                    if ($splitPayment['amount'] > 0) {
                        $paymentRows[] = [
                            'method' => $splitPayment['method'],
                            'amount' => $splitPayment['amount'],
                            'reference' => $splitPayment['reference'],
                            'notes' => 'Manually recorded split payment. Gateway verification not performed.',
                        ];
                    }
                }

                $totalPaidCents = $grandTotalCents;
            }

            $paymentStatus = $totalPaidCents >= $grandTotalCents
                ? 'paid'
                : ($totalPaidCents > 0 ? 'partial' : 'pending');

            do {
                $orderNumber = 'POS-' . now()->format('Ymd')
                    . '-' . strtoupper(Str::random(6));
            } while (Order::where('order_number', $orderNumber)->exists());

            $order = Order::create([
                'company_id' => $company->id,
                'branch_id' => null,
                'customer_id' => $customer?->id,
                'order_number' => $orderNumber,
                'customer_name' => $customer?->name
                    ?: trim($validated['customer_name'] ?? '')
                    ?: 'Walk-in Customer',
                'customer_email' => $customer?->email,
                'customer_phone' => $customer?->phone,
                'order_type' => 'pos',
                'invoice_type' => $validated['invoice_type'],
                'order_status' => 'completed',
                'payment_status' => $paymentStatus,
                'payment_method' => $paymentMethod,
                'subtotal' => $subtotalCents / 100,
                'discount' => $discountCents / 100,
                'tax_amount' => $taxCents / 100,
                'delivery_charge' => 0,
                'grand_total' => $grandTotalCents / 100,
                'placed_at' => now(),
                'notes' => $paymentMethod === 'due'
                    ? 'Unpaid POS sale. Amount recorded as Due / Credit.'
                    : null,
            ]);

            foreach ($orderItems as $orderItem) {
                $order->items()->create($orderItem);
            }

            foreach ($paymentRows as $paymentRow) {
                $order->payments()->create([
                    'payment_method' => $paymentRow['method'],
                    'payment_type' => 'payment',
                    'amount' => $paymentRow['amount'] / 100,
                    'payment_reference' => $paymentRow['reference'],
                    'payment_status' => 'paid',
                    'notes' => $paymentRow['notes'],
                    'paid_at' => now(),
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'POS sale completed successfully.',
            'invoice_url' => route('admin.pos.invoice', $order->id),
                'order' => [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'subtotal' => $order->subtotal,
                    'discount' => $order->discount,
                    'tax_amount' => $order->tax_amount,
                    'grand_total' => $order->grand_total,
                    'payment_status' => $order->payment_status,
                    'amount_paid' => $totalPaidCents / 100,
                    'amount_due' => ($grandTotalCents - $totalPaidCents) / 100,
                ],
            ], 201);
        });
    }
    public function invoice($orderId)
    {
        $user = auth()->user();

        $company = $user && $user->company_id
            ? Company::whereKey($user->company_id)
                ->where('is_active', true)
                ->first()
            : Company::where('is_active', true)
                ->orderBy('id')
                ->first();

        abort_unless($company, 404, 'No active company found.');

        $order = Order::with([
            'company',
            'items',
            'payments',
            'customer',
        ])
            ->where('company_id', $company->id)
            ->findOrFail($orderId);

        return view('admin.pos.invoice', compact('order'));
    }
    public function newBill()
    {
        return view('admin.pos.new-bill');
    }

    public function sales()
    {
        return view('admin.pos.sales');
    }

    public function heldBills()
    {
        return view('admin.pos.held-bills');
    }

    public function settings()
    {
        return view('admin.pos.settings');
    }
}





