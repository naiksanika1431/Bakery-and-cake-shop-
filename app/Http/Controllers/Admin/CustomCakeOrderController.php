<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Customer;
use App\Models\CustomerAccount;
use App\Models\CustomCakeDetail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CustomCakeOrderController extends Controller
{
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
            'company_id' => $company->id,
            'branch_id' => $branch->id,
        ];
    }

    private function customCakeOrdersQuery(array $context)
    {
        return Order::query()
            ->where('company_id', $context['company_id'])
            ->where('branch_id', $context['branch_id'])
            ->whereHas('items', function ($query) {
                $query->where('is_customized', true);
            });
    }

    private function customerAccounts()
    {
        return CustomerAccount::query()
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'phone']);
    }

    private function cakeValidationRules(): array
    {
        return [
            'customer_account_id' => [
                'nullable',
                'integer',
                'exists:customer_accounts,id',
            ],
            'customer_name' => 'required_without:customer_account_id|nullable|string|max:255',
            'customer_phone' => 'required_without:customer_account_id|nullable|string|max:20',
            'customer_email' => 'nullable|email|max:255',

            'occasion' => 'required|string|max:255',
            'required_date' => 'required|date|after_or_equal:today',
            'required_time' => 'required|date_format:H:i',
            'fulfilment_type' => 'required|in:pickup,delivery',
            'servings' => 'required|integer|min:1|max:10000',
            'flavour' => 'required|string|max:255',
            'weight' => 'required|numeric|min:0.5|max:100',
            'cake_size' => 'nullable|string|max:255',
            'shape' => 'required|string|max:255',
            'dimensions' => 'nullable|string|max:255',
            'shape_complexity' => 'nullable|string|max:100',

            'theme' => 'nullable|string|max:255',
            'colour' => 'nullable|string|max:255',
            'cream_type' => 'nullable|string|max:255',
            'decorations' => 'nullable|string|max:2000',
            'cake_message' => 'nullable|string|max:500',
            'special_instructions' => 'nullable|string|max:5000',

            'reference_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',

            'delivery_address' => 'required_if:fulfilment_type,delivery|nullable|string|max:1000',
            'delivery_city' => 'required_if:fulfilment_type,delivery|nullable|string|max:255',
            'delivery_state' => 'required_if:fulfilment_type,delivery|nullable|string|max:255',
            'delivery_pincode' => 'required_if:fulfilment_type,delivery|nullable|string|max:20',
        ];
    }

    private function resolveCustomer(array $data, array $context, ?Order $existingOrder = null): array
    {
        if (!empty($data['customer_account_id'])) {
            $account = CustomerAccount::findOrFail($data['customer_account_id']);

            $customer = Customer::firstOrCreate(
                [
                    'company_id' => $context['company_id'],
                    'customer_account_id' => $account->id,
                ],
                [
                    'customer_code' => 'WEB-' . strtoupper(Str::random(8)),
                    'name' => $account->name,
                    'phone' => $account->phone,
                    'email' => $account->email,
                    'is_active' => true,
                ]
            );

            return [
                'customer_id' => $customer->id,
                'customer_account_id' => $account->id,
                'customer_name' => $account->name,
                'customer_phone' => $account->phone,
                'customer_email' => $account->email,
            ];
        }

        $customer = null;

        if ($existingOrder && $existingOrder->customer_id) {
            $existingCustomer = Customer::where('company_id', $context['company_id'])
                ->whereNull('customer_account_id')
                ->find($existingOrder->customer_id);

            if ($existingCustomer) {
                $otherOrders = Order::where('customer_id', $existingCustomer->id)
                    ->where('id', '!=', $existingOrder->id)
                    ->exists();

                if (!$otherOrders) {
                    $customer = $existingCustomer;
                }
            }
        }

        $customerData = [
            'name' => $data['customer_name'],
            'phone' => $data['customer_phone'],
            'email' => $data['customer_email'] ?? null,
            'is_active' => true,
        ];

        if ($customer) {
            $customer->update($customerData);
        } else {
            $customer = Customer::create(array_merge(
                [
                    'company_id' => $context['company_id'],
                    'customer_account_id' => null,
                    'customer_code' => 'ADM-' . strtoupper(Str::random(8)),
                ],
                $customerData
            ));
        }

        return [
            'customer_id' => $customer->id,
            'customer_account_id' => null,
            'customer_name' => $data['customer_name'],
            'customer_phone' => $data['customer_phone'],
            'customer_email' => $data['customer_email'] ?? null,
        ];
    }

    private function cakeDetailData(array $data, ?string $imagePath = null): array
    {
        $weight = (float) $data['weight'];

        $detail = [
            'occasion' => $data['occasion'],
            'required_date' => $data['required_date'],
            'required_time' => $data['required_time'],
            'fulfilment_type' => $data['fulfilment_type'],
            'servings' => $data['servings'],
            'flavour' => $data['flavour'],
            'weight_kg' => $weight,
            'cake_size' => $data['cake_size'] ?? null,
            'shape' => $data['shape'],
            'dimensions' => $data['dimensions'] ?? null,
            'dimension_cm' => null,
            'shape_complexity' => $data['shape_complexity'] ?? null,
            'theme' => $data['theme'] ?? null,
            'colour' => $data['colour'] ?? null,
            'cream_type' => $data['cream_type'] ?? null,
            'decorations' => $data['decorations'] ?? null,
            'cake_message' => $data['cake_message'] ?? null,
            'special_instructions' => $data['special_instructions'] ?? null,
            'flavour_charge' => 0,
            'shape_charge' => 0,
            'theme_decoration_charge' => 0,
            'cream_charge' => 0,
            'delivery_charge' => 0,
            'calculated_total' => 0,
            'pricing_snapshot' => null,
        ];

        if ($imagePath !== null) {
            $detail['reference_image_path'] = $imagePath;
        }

        return $detail;
    }

    private function orderItemCustomization(array $data): string
    {
        return json_encode([
            'occasion' => $data['occasion'],
            'shape' => $data['shape'],
            'weight_kg' => (float) $data['weight'],
            'cake_size' => $data['cake_size'] ?? null,
            'dimensions' => $data['dimensions'] ?? null,
            'theme' => $data['theme'] ?? null,
            'colour' => $data['colour'] ?? null,
            'cream_type' => $data['cream_type'] ?? null,
            'decorations' => $data['decorations'] ?? null,
            'cake_message' => $data['cake_message'] ?? null,
        ], JSON_UNESCAPED_UNICODE);
    }

    public function index(Request $request)
    {
        $context = $this->adminContext();

        $baseQuery = $this->customCakeOrdersQuery($context);
        $query = (clone $baseQuery)->with(['items', 'payments']);

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

        $totalOrders = (clone $baseQuery)->count();
        $pendingOrders = (clone $baseQuery)->whereIn('order_status', ['pending', 'new'])->count();
        $processingOrders = (clone $baseQuery)->whereIn('order_status', ['processing', 'preparation'])->count();
        $completedOrders = (clone $baseQuery)->where('order_status', 'completed')->count();

        $orders = $query->latest()->paginate(10)->withQueryString();

        return view('admin.custom-cake-orders.index', compact(
            'orders',
            'totalOrders',
            'pendingOrders',
            'processingOrders',
            'completedOrders'
        ));
    }

    public function create()
    {
        $customerAccounts = $this->customerAccounts();

        return view('admin.custom-cake-orders.create', [
            'customerAccounts' => $customerAccounts,
            'order' => null,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->cakeValidationRules());
        $context = $this->adminContext();

        $imagePath = $request->hasFile('reference_image')
            ? $request->file('reference_image')->store('custom-cakes/references', 'public')
            : null;

        try {
            $order = DB::transaction(function () use ($data, $context, $imagePath) {
                $customer = $this->resolveCustomer($data, $context);

                do {
                    $orderNumber = 'ORD-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
                } while (Order::where('order_number', $orderNumber)->exists());

                $order = Order::create(array_merge($customer, [
                    'company_id' => $context['company_id'],
                    'branch_id' => $context['branch_id'],
                    'order_number' => $orderNumber,
                    'order_type' => $data['fulfilment_type'],
                    'delivery_address' => $data['delivery_address'] ?? null,
                    'delivery_city' => $data['delivery_city'] ?? null,
                    'delivery_state' => $data['delivery_state'] ?? null,
                    'delivery_pincode' => $data['delivery_pincode'] ?? null,
                    'order_status' => 'pending',
                    'payment_status' => 'pending',
                    'payment_method' => null,
                    'subtotal' => 0,
                    'discount' => 0,
                    'tax_amount' => 0,
                    'delivery_charge' => 0,
                    'grand_total' => 0,
                    'advance_requested' => 0,
                    'quotation_status' => 'draft',
                    'notes' => $data['special_instructions'] ?? null,
                    'scheduled_at' => $data['required_date'] . ' ' . $data['required_time'],
                    'placed_at' => now(),
                ]));

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => null,
                    'product_variant_id' => null,
                    'product_name' => 'Custom Cake - ' . $data['flavour'],
                    'quantity' => 1,
                    'unit_price' => 0,
                    'discount' => 0,
                    'tax_rate' => 0,
                    'tax_amount' => 0,
                    'line_total' => 0,
                    'is_customized' => true,
                    'customization_details' => $this->orderItemCustomization($data),
                    'special_instructions' => $data['special_instructions'] ?? null,
                ]);

                CustomCakeDetail::create(array_merge(
                    ['order_id' => $order->id],
                    $this->cakeDetailData($data, $imagePath)
                ));

                return $order;
            });
        } catch (\Throwable $exception) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }

            throw $exception;
        }

        return redirect()
            ->route('admin.custom-cake-orders.show', $order->id)
            ->with('success', 'Custom cake order created successfully as a draft. No quotation has been sent.');
    }

    public function edit($id)
    {
        $context = $this->adminContext();

        $order = $this->customCakeOrdersQuery($context)
            ->with(['customerAccount', 'customCakeDetail', 'items', 'payments'])
            ->findOrFail($id);

        $this->ensureEditable($order);

        $customerAccounts = $this->customerAccounts();

        return view('admin.custom-cake-orders.create', compact('order', 'customerAccounts'));
    }

    private function ensureEditable(Order $order): void
    {
        if (
            ($order->quotation_status ?? 'draft') !== 'draft' ||
            $order->order_status !== 'pending' ||
            $order->payments()->exists()
        ) {
            abort(403, 'This order can no longer be edited. Only unpaid orders with a draft quotation can be changed.');
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate($this->cakeValidationRules());
        $context = $this->adminContext();

        $newImagePath = $request->hasFile('reference_image')
            ? $request->file('reference_image')->store('custom-cakes/references', 'public')
            : null;

        $oldImagePath = null;

        try {
            $order = DB::transaction(function () use (
                $context,
                $id,
                $data,
                $newImagePath,
                &$oldImagePath
            ) {
                $order = $this->customCakeOrdersQuery($context)
                    ->lockForUpdate()
                    ->with('customCakeDetail')
                    ->findOrFail($id);

                $this->ensureEditable($order);

                $customer = $this->resolveCustomer($data, $context, $order);

                $order->fill(array_merge($customer, [
                    'order_type' => $data['fulfilment_type'],
                    'delivery_address' => $data['delivery_address'] ?? null,
                    'delivery_city' => $data['delivery_city'] ?? null,
                    'delivery_state' => $data['delivery_state'] ?? null,
                    'delivery_pincode' => $data['delivery_pincode'] ?? null,
                    'notes' => $data['special_instructions'] ?? null,
                    'scheduled_at' => $data['required_date'] . ' ' . $data['required_time'],
                ]));

                $order->save();

                $detail = $order->customCakeDetail;

                if (!$detail) {
                    $detail = new CustomCakeDetail();
                    $detail->order_id = $order->id;
                }

                if ($newImagePath !== null) {
                    $oldImagePath = $detail->reference_image_path;
                }

                $detail->fill($this->cakeDetailData($data, $newImagePath));
                $detail->save();

                $item = $order->items()->where('is_customized', true)->first();

                if (!$item) {
                    $item = new OrderItem();
                    $item->order_id = $order->id;
                    $item->is_customized = true;
                }

                $item->fill([
                    'product_name' => 'Custom Cake - ' . $data['flavour'],
                    'quantity' => 1,
                    'unit_price' => 0,
                    'discount' => 0,
                    'tax_rate' => 0,
                    'tax_amount' => 0,
                    'line_total' => 0,
                    'customization_details' => $this->orderItemCustomization($data),
                    'special_instructions' => $data['special_instructions'] ?? null,
                ]);

                $item->save();

                return $order;
            });
        } catch (\Throwable $exception) {
            if ($newImagePath) {
                Storage::disk('public')->delete($newImagePath);
            }

            throw $exception;
        }

        if ($newImagePath && $oldImagePath && $oldImagePath !== $newImagePath) {
            Storage::disk('public')->delete($oldImagePath);
        }

        return redirect()
            ->route('admin.custom-cake-orders.show', $order->id)
            ->with('success', 'Custom cake order updated successfully. Its quotation remains a draft.');
    }

    public function show($id)
    {
        $context = $this->adminContext();

        $order = $this->customCakeOrdersQuery($context)
            ->with([
                'customer',
                'customerAccount',
                'customCakeDetail',
                'items',
                'payments',
                'statusHistories.updatedBy',
            ])
            ->findOrFail($id);

        return view('admin.custom-cake-orders.show', compact('order'));
    }

    public function saveQuotation(Request $request, $id)
    {
        $validated = $request->validate([
            'final_price' => ['required', 'numeric', 'min:0.01', 'max:9999999999.99'],
            'advance_requested' => ['required', 'numeric', 'min:0', 'lte:final_price'],
            'expected_completion_at' => ['required', 'date', 'after_or_equal:now'],
            'admin_notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $context = $this->adminContext();

        DB::transaction(function () use ($context, $id, $validated) {
            $order = $this->customCakeOrdersQuery($context)
                ->lockForUpdate()
                ->findOrFail($id);

            if (($order->quotation_status ?? 'draft') !== 'draft') {
                abort(422, 'Only draft quotations can be edited.');
            }

            $order->grand_total = $validated['final_price'];
            $order->advance_requested = $validated['advance_requested'];
            $order->expected_completion_at = $validated['expected_completion_at'];
            $order->admin_notes = $validated['admin_notes'] ?? null;
            $order->quotation_status = 'draft';
            $order->save();
        });

        return redirect()
            ->route('admin.custom-cake-orders.show', $id)
            ->with('success', 'Quotation saved successfully as draft. It has not been sent to the customer.');
    }

    public function sendQuotation(Request $request, $id)
    {
        $context = $this->adminContext();

        DB::transaction(function () use ($context, $id) {
            $order = $this->customCakeOrdersQuery($context)
                ->lockForUpdate()
                ->findOrFail($id);

            if (($order->quotation_status ?? 'draft') !== 'draft') {
                abort(422, 'Only draft quotations can be marked as sent.');
            }

            if (
                !$order->grand_total ||
                $order->grand_total <= 0 ||
                !$order->expected_completion_at
            ) {
                abort(422, 'Save the quotation price and expected completion date before sending.');
            }

            $order->quotation_status = 'sent';
            $order->save();

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'old_status' => 'draft',
                'new_status' => 'sent',
                'updated_by' => auth('web')->id(),
                'notes' => 'Quotation manually sent to customer via an external channel.',
            ]);
        });

        return redirect()
            ->route('admin.custom-cake-orders.show', $id)
            ->with('success', 'Quotation marked as sent. The customer can now accept or reject it.');
    }
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'order_status' => ['required', 'in:processing,ready,completed'],
            'status_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $context = $this->adminContext();

        DB::transaction(function () use ($context, $id, $validated) {
            $order = $this->customCakeOrdersQuery($context)
                ->lockForUpdate()
                ->findOrFail($id);

            $oldStatus = $order->order_status;
            $newStatus = $validated['order_status'];

            $allowedTransitions = [
                'confirmed' => 'processing',
                'processing' => 'ready',
                'ready' => 'completed',
            ];

            if (
                !isset($allowedTransitions[$oldStatus]) ||
                $allowedTransitions[$oldStatus] !== $newStatus
            ) {
                abort(422, 'This order status transition is not allowed.');
            }

            if (
                $oldStatus === 'confirmed' &&
                ($order->quotation_status ?? null) !== 'accepted'
            ) {
                abort(422, 'The customer must accept the quotation before production starts.');
            }

            $order->order_status = $newStatus;
            $order->save();

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'updated_by' => auth('web')->id(),
                'notes' => $validated['status_note'] ?? 'Custom cake order status updated.',
            ]);
        });

        return redirect()
            ->route('admin.custom-cake-orders.show', $id)
            ->with('success', 'Order status updated successfully.');
    }
}