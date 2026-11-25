<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            // Parent order
            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();

            // Product reference (Products module will be created later)
            $table->unsignedBigInteger('product_id')->nullable()->index();

            // Product snapshot
            $table->string('product_name');
            $table->string('product_sku')->nullable();
            $table->string('product_image')->nullable();

            // Quantity and pricing
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('unit_price', 12, 2);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('line_total', 12, 2);

            // Custom cake details
            $table->boolean('is_customized')->default(false);
            $table->text('customization_details')->nullable();
            $table->text('special_instructions')->nullable();

            $table->timestamps();

            $table->index(['order_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
