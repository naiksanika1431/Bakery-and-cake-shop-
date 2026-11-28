<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_cake_pricing_rates', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')
                ->constrained('companies')
                ->restrictOnDelete();

            $table->string('rate_type');
            $table->string('name');
            $table->decimal('rate', 12, 2)->default(0);

            // per_kg or fixed charge
            $table->string('calculation_type')->default('per_kg');

            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();

            $table->timestamps();

            $table->unique(
                ['company_id', 'rate_type', 'name'],
                'custom_cake_pricing_unique'
            );

            $table->index(['company_id', 'rate_type', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_cake_pricing_rates');
    }
};
