<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branch_products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('branch_id')
                ->constrained('branches')
                ->cascadeOnDelete();

            $table->foreignId('product_variant_id')
                ->constrained('product_variants')
                ->cascadeOnDelete();

            $table->decimal('selling_price', 12, 2);
            $table->decimal('min_stock', 12, 3)->default(0);
            $table->boolean('is_available')->default(true);

            $table->timestamps();

            $table->unique(['branch_id', 'product_variant_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branch_products');
    }
};
