<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomerAccount;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class CustomerAuthController extends Controller
{
    public function showRegister()
    {
        if (Auth::guard('customer')->check()) {
            return redirect()->route('customer.home');
        }

        return view('customer.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:customer_accounts,email'],
            'phone' => ['required', 'digits:10'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'terms' => ['accepted'],
        ]);

        CustomerAccount::create([
            'name' => $validated['name'],
            'email' => strtolower($validated['email']),
            'phone' => $validated['phone'],
            'password' => $validated['password'],
        ]);

        return redirect()
            ->route('customer.login')
            ->with('success', 'Your account has been created successfully. Please log in.');
    }

    public function showLogin()
    {
        if (Auth::guard('customer')->check()) {
            return redirect()->route('customer.home');
        }

        return view('customer.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $credentials['email'] = strtolower($credentials['email']);

        if (Auth::guard('customer')->attempt(
            $credentials,
            $request->boolean('remember')
        )) {
            $request->session()->regenerate();

            return redirect()->intended(route('customer.home'));
        }

        return back()
            ->withErrors([
                'email' => 'The email address or password is incorrect.',
            ])
            ->onlyInput('email');
    }

    public function account()
    {
        $customer = Auth::guard('customer')->user();

        $orders = Order::query()
            ->where('customer_account_id', $customer->id)
            ->whereHas('customCakeDetail')
            ->with([
                'customCakeDetail',
                'payments',
            ])
            ->latest('placed_at')
            ->get();

        return view('customer.account', compact('customer', 'orders'));
    }

    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('customer.home')
            ->with('success', 'You have been logged out successfully.');
    }
}