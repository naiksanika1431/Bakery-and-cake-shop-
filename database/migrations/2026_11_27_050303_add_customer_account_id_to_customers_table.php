<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->foreignId('customer_account_id')
                ->nullable()
                ->after('company_id')
                ->constrained('customer_accounts')
                ->nullOnDelete();

            $table->unique(
                ['company_id', 'customer_account_id'],
                'customers_company_account_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropUnique('customers_company_account_unique');
            $table->dropConstrainedForeignId('customer_account_id');
        });
    }
};
