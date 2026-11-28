<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminProfileController extends Controller
{
    public function edit()
    {
        return view('admin.profile.edit', [
            'user' => auth('web')->user(),
        ]);
    }

    public function update(Request $request)
    {
        $user = auth('web')->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'phone' => [
                'nullable',
                'string',
                'regex:/^(?:\+91[\s-]?)?[6-9]\d{9}$/',
            ],
        ], [
            'phone.regex' => 'Enter a valid Indian mobile number with optional +91 country code.',
        ]);

        $phone = $validated['phone'] ?? null;

        if ($phone !== null) {
            $phone = preg_replace('/[\s-]/', '', $phone);

            if (str_starts_with($phone, '+91')) {
                $phone = substr($phone, 3);
            }
        }

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $phone,
        ]);

        return redirect()
            ->route('admin.profile.edit')
            ->with('success', 'Profile updated successfully.');
    }
}