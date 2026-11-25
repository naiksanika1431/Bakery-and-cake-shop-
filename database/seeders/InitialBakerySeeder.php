<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\Branch;

class InitialBakerySeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::firstOrCreate(
            ['company_code' => 'DELIGHT'],
            [
                'company_name' => 'Delight',
                'is_active' => true,
            ]
        );

        Branch::firstOrCreate(
            [
                'company_id' => $company->id,
                'branch_code' => 'DELIGHT-MAIN',
            ],
            [
                'branch_name' => 'Main Branch',
                'is_active' => true,
            ]
        );

        $this->command->info('Delight company and Main Branch are ready.');
    }
}
