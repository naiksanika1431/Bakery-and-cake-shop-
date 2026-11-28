<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_cake_shape_rates', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')
                ->constrained('companies')
                ->restrictOnDelete();

            $table->string('shape_name');
            $table->decimal('min_weight_kg', 8, 2)->default(0);
            $table->decimal('max_weight_kg', 8, 2)->nullable();

            $table->decimal('min_dimension_cm', 8, 2)->default(0);
            $table->decimal('max_dimension_cm', 8, 2)->nullable();

            $table->string('complexity_level')->default('simple');
            $table->decimal('shape_charge', 12, 2)->default(0);

            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();

            $table->timestamps();

            $table->index(
                ['company_id', 'shape_name', 'is_active'],
                'custom_cake_shape_lookup'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_cake_shape_rates');
    }
};
