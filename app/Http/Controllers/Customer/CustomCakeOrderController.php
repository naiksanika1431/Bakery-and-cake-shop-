<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\OrderItem;
use App\Models\CustomCakeDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CustomCakeOrderController extends Controller
{
    public function store(Request $request)
    {
        $customerAccount = auth('customer')->user();

        abort_unless($customerAccount, 401);

        $data = $request->validate([
            'occasion' => 'required|string|max:255',
            'required_date' => 'required|date|after_or_equal:today',
            'required_time' => 'required',
            'fulfilment_type' => 'required|in:pickup,delivery',
            'servings' => 'required|integer|min:1',
            'flavour' => 'required|string|max:255',
            'weight' => 'required|numeric|min:0.5|max:100',
            'cake_size' => 'nullable|string|max:255',
            'shape' => 'required|string|max:255',
            'dimensions' => 'nullable|string|max:255',
            'theme' => 'nullable|string|max:255',
            'colour' => 'nullable|string|max:255',
            'cream_type' => 'nullable|string|max:255',
            'decorations' => 'nullable|string',
            'cake_message' => 'nullable|string|max:255',
            'reference_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'special_instructions' => 'nullable|string',
            'delivery_address' => 'required_if:fulfilment_type,delivery|nullable|string|max:1000',
            'delivery_city' => 'required_if:fulfilment_type,delivery|nullable|string|max:255',
            'delivery_state' => 'required_if:fulfilment_type,delivery|nullable|string|max:255',
            'delivery_pincode' => 'required_if:fulfilment_type,delivery|nullable|string|max:20',
        ]);

        $company = Company::where('company_code', 'DELIGHT')
            ->where('is_active', true)
            ->firstOrFail();

        $branch = Branch::where('company_id', $company->id)
            ->where('branch_code', 'DELIGHT-MAIN')
            ->where('is_active', true)
            ->firstOrFail();

        $weight = (float) $data['weight'];

        preg_match_all('/\d+(?:\.\d+)?/', $data['dimensions'] ?? '', $matches);

        $dimension = !empty($matches[0])
            ? max(array_map('floatval', $matches[0]))
            : null;

        $imagePath = null;

        try {
            $order = DB::transaction(function () use (
                $request,
                $customerAccount,
                $company,
                $branch,
                $data,
                $weight,
                $dimension,
                &$imagePath
            ) {
                $linkedCustomer = Customer::firstOrCreate(
                    [
                        'company_id' => $company->id,
                        'customer_account_id' => $customerAccount->id,
                    ],
                    [
                        'customer_code' => 'WEB-' . Str::upper(Str::random(8)),
                        'name' => $customerAccount->name,
                        'phone' => $customerAccount->phone,
                        'email' => $customerAccount->email,
                        'is_active' => true,
                    ]
                );

                $linkedCustomer->update([
                    'name' => $customerAccount->name,
                    'phone' => $customerAccount->phone,
                    'email' => $customerAccount->email,
                ]);

                if ($request->hasFile('reference_image')) {
                    $imagePath = $request->file('reference_image')
                        ->store('custom-cakes/references', 'public');
                }

                do {
                    $orderNumber = 'ORD-' . now()->format('Ymd')
                        . '-' . strtoupper(Str::random(6));
                } while (Order::where('order_number', $orderNumber)->exists());

                $order = Order::create([
                    'company_id' => $company->id,
                    'branch_id' => $branch->id,
                    'customer_id' => $linkedCustomer->id,
                    'customer_account_id' => $customerAccount->id,
                    'order_number' => $orderNumber,
                    'customer_name' => $customerAccount->name,
                    'customer_email' => $customerAccount->email,
                    'customer_phone' => $customerAccount->phone,
                    'order_type' => $data['fulfilment_type'],
                    'delivery_address' => $data['delivery_address'] ?? null,
                    'delivery_city' => $data['delivery_city'] ?? null,
                    'delivery_state' => $data['delivery_state'] ?? null,
                    'delivery_pincode' => $data['delivery_pincode'] ?? null,
                    'order_status' => 'pending',
                    'payment_status' => 'pending',
                    'subtotal' => 0,
                    'discount' => 0,
                    'tax_amount' => 0,
                    'delivery_charge' => 0,
                    'grand_total' => 0,
                    'notes' => $data['special_instructions'] ?? null,
                    'scheduled_at' => $data['required_date'] . ' ' . $data['required_time'],
                    'placed_at' => now(),
                ]);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => null,
                    'product_variant_id' => null,
                    'product_name' => 'Custom Cake - ' . $data['flavour'],
                    'product_sku' => null,
                    'quantity' => 1,
                    'unit_price' => 0,
                    'discount' => 0,
                    'tax_rate' => 0,
                    'tax_amount' => 0,
                    'line_total' => 0,
                    'is_customized' => true,
                    'customization_details' => json_encode([
                        'occasion' => $data['occasion'],
                        'shape' => $data['shape'],
                        'weight_kg' => $weight,
                        'cake_size' => $data['cake_size'] ?? null,
                        'dimensions' => $data['dimensions'] ?? null,
                        'theme' => $data['theme'] ?? null,
                        'colour' => $data['colour'] ?? null,
                        'cream_type' => $data['cream_type'] ?? null,
                    ]),
                    'special_instructions' => $data['special_instructions'] ?? null,
                ]);

                CustomCakeDetail::create([
                    'order_id' => $order->id,
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
                    'dimension_cm' => $dimension,
                    'shape_complexity' => null,
                    'theme' => $data['theme'] ?? null,
                    'colour' => $data['colour'] ?? null,
                    'cream_type' => $data['cream_type'] ?? null,
                    'decorations' => $data['decorations'] ?? null,
                    'cake_message' => $data['cake_message'] ?? null,
                    'reference_image_path' => $imagePath,
                    'special_instructions' => $data['special_instructions'] ?? null,
                    'flavour_charge' => 0,
                    'shape_charge' => 0,
                    'theme_decoration_charge' => 0,
                    'cream_charge' => 0,
                    'delivery_charge' => 0,
                    'calculated_total' => 0,
                    'pricing_snapshot' => null,
                ]);

                return $order;
            });
        } catch (\Throwable $e) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }

            throw $e;
        }

        return redirect()
            ->route('customer.custom-cake-orders.create')
            ->with(
                'success',
                'Your custom cake request was submitted successfully. The bakery will review it and share a quotation. Order: ' . $order->order_number
            );
    }

    /**
     * Customer accepts a sent custom cake quotation.
     */
    public function acceptQuotation($id)
    {
        $customerAccount = auth('customer')->user();

        abort_unless($customerAccount, 401);

        DB::transaction(function () use ($id, $customerAccount) {
            $order = Order::query()
                ->where('id', $id)
                ->where('customer_account_id', $customerAccount->id)
                ->whereHas('customCakeDetail')
                ->lockForUpdate()
                ->firstOrFail();

            abort_unless(
                $order->quotation_status === 'sent',
                403,
                'This quotation is not available for acceptance.'
            );

            $oldStatus = $order->order_status;

            $order->update([
                'quotation_status' => 'accepted',
                'order_status' => 'confirmed',
                'customer_responded_at' => now(),
                'customer_rejection_reason' => null,
            ]);

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'old_status' => $oldStatus,
                'new_status' => 'confirmed',
                'updated_by' => null,
                'notes' => 'Customer accepted quotation (customer account #' . $customerAccount->id . ').',
            ]);
        });

        return redirect()
            ->route('customer.account')
            ->with('success', 'You accepted the cake quotation. Your order is confirmed.');
    }

    /**
     * Customer rejects a sent custom cake quotation.
     */
    public function rejectQuotation(Request $request, $id)
    {
        $customerAccount = auth('customer')->user();

        abort_unless($customerAccount, 401);

        $data = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        DB::transaction(function () use ($id, $customerAccount, $data) {
            $order = Order::query()
                ->where('id', $id)
                ->where('customer_account_id', $customerAccount->id)
                ->whereHas('customCakeDetail')
                ->lockForUpdate()
                ->firstOrFail();

            abort_unless(
                $order->quotation_status === 'sent',
                403,
                'This quotation is not available for rejection.'
            );

            $oldStatus = $order->order_status;

            $order->update([
                'quotation_status' => 'rejected',
                'order_status' => 'rejected',
                'customer_responded_at' => now(),
                'customer_rejection_reason' => $data['rejection_reason'],
            ]);

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'old_status' => $oldStatus,
                'new_status' => 'rejected',
                'updated_by' => null,
                'notes' => 'Customer rejected quotation (customer account #' . $customerAccount->id . '). Reason: ' . $data['rejection_reason'],
            ]);
        });

        return redirect()
            ->route('customer.account')
            ->with('success', 'You rejected the cake quotation.');
    }}
