<?php

namespace App\Console\Commands;

use App\Models\Branch;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateFirstSuperAdmin extends Command
{
    protected $signature = 'app:create-first-super-admin';

    protected $description = 'Securely create the first Super Admin account';

    public function handle(): int
    {
        if (User::exists()) {
            $this->error('A staff account already exists. This setup command can only run when the users table is empty.');
            return self::FAILURE;
        }

        $role = Role::where('role_code', 'super_admin')
            ->where('is_active', true)
            ->first();

        $company = Company::where('company_code', 'DELIGHT')
            ->where('is_active', true)
            ->first();

        if (!$role || !$company) {
            $this->error('Active Super Admin role or Delight company was not found.');
            return self::FAILURE;
        }

        $branch = Branch::where('company_id', $company->id)
            ->where('branch_code', 'DELIGHT-MAIN')
            ->where('is_active', true)
            ->first();

        if (!$branch) {
            $this->error('Active Main Branch was not found for Delight.');
            return self::FAILURE;
        }

        $name = trim((string) $this->ask('Enter Super Admin name'));

        if ($name === '') {
            $this->error('Name is required.');
            return self::FAILURE;
        }

        $email = strtolower(trim((string) $this->ask('Enter Super Admin email')));

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Please enter a valid email address.');
            return self::FAILURE;
        }

        if (User::where('email', $email)->exists()) {
            $this->error('This email address is already registered.');
            return self::FAILURE;
        }

        $password = (string) $this->secret('Enter password (minimum 8 characters)');
        $confirmation = (string) $this->secret('Confirm password');

        if (strlen($password) < 8 || $password !== $confirmation) {
            $this->error('Passwords must match and contain at least 8 characters.');
            return self::FAILURE;
        }

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'company_id' => $company->id,
            'branch_id' => $branch->id,
            'role_id' => $role->id,
        ]);

        $this->newLine();
        $this->info('First Super Admin account created successfully.');
        $this->line('Email: ' . $email);
        $this->line('Role: Super Admin');
        $this->line('Company: ' . $company->company_name);
        $this->line('Branch: ' . $branch->branch_name);

        return self::SUCCESS;
    }
}
