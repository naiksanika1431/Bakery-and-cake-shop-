<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')
                ->constrained('companies')
                ->cascadeOnDelete();

            $table->string('supplier_code');
            $table->string('supplier_name');
            $table->string('supplier_type')->nullable();

            $table->string('contact_person')->nullable();
            $table->string('phone', 20);
            $table->string('alternate_phone', 20)->nullable();
            $table->string('email')->nullable();

            $table->string('gst_number')->nullable();
            $table->string('pan_number')->nullable();

            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('pincode', 10)->nullable();

            $table->string('payment_terms')->nullable();
            $table->decimal('credit_limit', 12, 2)->default(0);

            $table->text('notes')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->timestamps();

            $table->unique(['company_id', 'supplier_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};