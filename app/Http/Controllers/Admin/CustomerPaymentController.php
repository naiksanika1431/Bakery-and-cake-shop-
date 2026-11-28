<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Customer;
use App\Models\CustomerLedgerEntry;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CustomerPaymentController extends Controller
{
    private function activeCompany(): Company
    {
        return Company::where('company_code', 'DELIGHT')
            ->where('is_active', true)
            ->firstOrFail();
    }

    private function allowedBranchIds(Company $company): ?array
    {
        $user = auth()->user();

        abort_unless($user, 403);

        if ($user->role?->role_code === 'super_admin') {
            return null;
        }

        abort_unless($user->branch_id, 403);

        $branch = Branch::where('company_id', $company->id)
            ->where('id', $user->branch_id)
            ->where('is_active', true)
            ->firstOrFail();

        return [$branch->id];
    }

    /**
     * Display customer invoices with outstanding balances.
     */
    public function index(Request $request)
    {
        $company = $this->activeCompany();
        $branchIds = $this->allowedBranchIds($company);
        $search = $request->input('search');

        $ordersQuery = Order::with('customer', 'branch')
            ->where('company_id', $company->id)
            ->whereNotNull('customer_id')
            ->whereIn('payment_status', ['pending', 'partial']);

        if ($branchIds !== null) {
            $ordersQuery->whereIn('branch_id', $branchIds);
        }

        $ordersQuery->when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($customerQuery) use ($search) {
                        $customerQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
            });
        });

        $orders = $ordersQuery->latest('placed_at')
            ->paginate(15)
            ->withQueryString();

        $orders->getCollection()->transform(function ($order) {
            $paid = $order->payments()
                ->where('payment_status', 'paid')
                ->where('payment_type', 'payment')
                ->selectRaw('COALESCE(SUM(amount - refund_amount), 0) as total')
                ->value('total');

            $order->total_paid = (float) $paid;
            $order->outstanding = max(
                0,
                round((float) $order->grand_total - $order->total_paid, 2)
            );

            return $order;
        });

        // Hide invoices whose actual outstanding balance is zero.
        $orders->setCollection(
            $orders->getCollection()
                ->filter(fn ($order) => $order->outstanding > 0)
                ->values()
        );

        return view('admin.customer-payments.index', compact(
            'company',
            'orders',
            'search'
        ));
    }

    /**
     * Record a payment against a customer invoice.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => ['required', 'integer', 'exists:orders,id'],
            'amount' => ['required', 'numeric', 'gt:0', 'decimal:0,2'],
            'payment_method' => ['required', 'in:cash,upi,card'],
            'payment_reference' => ['nullable', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $company = $this->activeCompany();
        $branchIds = $this->allowedBranchIds($company);

        DB::transaction(function () use ($validated, $company, $branchIds) {
            $order = Order::where('company_id', $company->id)
                ->whereNotNull('customer_id')
                ->when($branchIds !== null, function ($query) use ($branchIds) {
                    $query->whereIn('branch_id', $branchIds);
                })
                ->lockForUpdate()
                ->findOrFail($validated['order_id']);

            $customer = Customer::where('company_id', $company->id)
                ->findOrFail($order->customer_id);

            $alreadyPaid = (float) $order->payments()
                ->where('payment_status', 'paid')
                ->where('payment_type', 'payment')
                ->selectRaw('COALESCE(SUM(amount - refund_amount), 0) as total')
                ->value('total');

            $grandTotal = (float) $order->grand_total;
            $outstanding = round($grandTotal - $alreadyPaid, 2);
            $amount = round((float) $validated['amount'], 2);

            if ($outstanding <= 0) {
                throw ValidationException::withMessages([
                    'amount' => 'This invoice is already fully paid.',
                ]);
            }

            if ($amount > $outstanding) {
                throw ValidationException::withMessages([
                    'amount' => 'Payment cannot exceed the outstanding amount of INR '
                        . number_format($outstanding, 2) . '.',
                ]);
            }

            $payment = $order->payments()->create([
                'payment_method' => $validated['payment_method'],
                'payment_type' => 'payment',
                'amount' => $amount,
                'payment_reference' => $validated['payment_reference'] ?? null,
                'payment_status' => 'paid',
                'notes' => $validated['notes'] ?? null,
                'paid_at' => $validated['date'] . ' 00:00:00',
            ]);

            CustomerLedgerEntry::create([
                'customer_id' => $customer->id,
                'branch_id' => $order->branch_id,
                'reference_type' => 'payment',
                'reference_id' => $payment->id,
                'debit' => 0,
                'credit' => $amount,
                'date' => $validated['date'],
            ]);

            $newPaidTotal = round($alreadyPaid + $amount, 2);

            $order->payment_status = $newPaidTotal >= $grandTotal
                ? 'paid'
                : 'partial';

            $order->save();
        });

        return redirect()
            ->route('admin.customer-payments.index')
            ->with('success', 'Customer payment recorded successfully.');
    }

    /**
     * Display recorded customer payment history.
     */
    public function history(Request $request)
    {
        $company = $this->activeCompany();
        $branchIds = $this->allowedBranchIds($company);
        $search = $request->input('search');

        $paymentsQuery = \App\Models\Payment::with([
            'order.customer',
            'order.branch',
        ])
            ->where('payment_status', 'paid')
            ->where('payment_type', 'payment')
            ->whereHas('order', function ($query) use ($company, $branchIds) {
                $query->where('company_id', $company->id)
                    ->whereNotNull('customer_id');

                if ($branchIds !== null) {
                    $query->whereIn('branch_id', $branchIds);
                }
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('payment_reference', 'like', "%{$search}%")
                        ->orWhereHas('order', function ($orderQuery) use ($search) {
                            $orderQuery->where('order_number', 'like', "%{$search}%")
                                ->orWhereHas('customer', function ($customerQuery) use ($search) {
                                    $customerQuery->where('name', 'like', "%{$search}%")
                                        ->orWhere('phone', 'like', "%{$search}%");
                                });
                        });
                });
            });

        $payments = $paymentsQuery
            ->latest('paid_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.customer-payments.history', compact(
            'company',
            'payments',
            'search'
        ));
    }
}
