<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supplier_purchase_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('supplier_purchase_id')
                ->constrained('supplier_purchases')
                ->cascadeOnDelete();

            $table->foreignId('product_variant_id')
                ->constrained('product_variants')
                ->restrictOnDelete();

            $table->decimal('quantity', 12, 3);
            $table->decimal('unit_cost', 12, 2);

            $table->decimal('gst_rate', 5, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);

            $table->decimal('line_total', 12, 2);

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['supplier_purchase_id', 'product_variant_id'], 'purchase_items_lookup_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supplier_purchase_items');
    }
};

