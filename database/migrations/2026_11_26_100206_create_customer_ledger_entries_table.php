<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_ledger_entries', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_id')
                ->constrained('customers')
                ->restrictOnDelete();

            $table->foreignId('branch_id')
                ->nullable()
                ->constrained('branches')
                ->nullOnDelete();

            $table->string('reference_type', 50);
            $table->unsignedBigInteger('reference_id');

            $table->decimal('debit', 12, 2)->default(0);
            $table->decimal('credit', 12, 2)->default(0);

            $table->date('date');
            $table->timestamps();

            $table->index(
                ['customer_id', 'branch_id', 'date'],
                'customer_ledger_customer_branch_date_idx'
            );

            $table->index(
                ['reference_type', 'reference_id'],
                'customer_ledger_reference_idx'
            );

            $table->unique(
                ['reference_type', 'reference_id', 'customer_id'],
                'customer_ledger_reference_customer_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_ledger_entries');
    }
};
