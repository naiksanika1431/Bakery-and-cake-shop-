<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();

            $table->string('employee_code')->unique();
            $table->string('name');
            $table->string('photo')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender', 20)->nullable();

            $table->string('mobile', 20);
            $table->string('email')->nullable();
            $table->text('address')->nullable();

            $table->string('department')->nullable();
            $table->string('designation')->nullable();
            $table->date('joining_date')->nullable();
            $table->string('employment_type')->nullable();
            $table->string('shift')->nullable();

            $table->decimal('salary', 12, 2)->nullable();
            $table->string('payment_type')->nullable();

            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_mobile', 20)->nullable();
            $table->string('emergency_contact_relation')->nullable();

            $table->string('status')->default('active');
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
