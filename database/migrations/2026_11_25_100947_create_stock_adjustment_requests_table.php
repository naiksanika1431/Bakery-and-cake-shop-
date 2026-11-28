<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_adjustment_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('branch_product_id')
                ->constrained('branch_products')
                ->restrictOnDelete();

            $table->decimal('quantity', 12, 3);
            $table->text('reason');

            $table->foreignId('requested_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->string('status', 20)->default('pending');

            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('reviewed_at')->nullable();
            $table->text('approval_remarks')->nullable();

            $table->timestamps();

            $table->index(['branch_product_id', 'status']);
            $table->index(['requested_by', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_adjustment_requests');
    }
};
