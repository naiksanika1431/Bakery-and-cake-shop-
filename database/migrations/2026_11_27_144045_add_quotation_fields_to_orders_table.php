<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Admin quotation details
            $table->decimal('advance_requested', 12, 2)->default(0)->after('grand_total');
            $table->timestamp('expected_completion_at')->nullable()->after('advance_requested');
            $table->text('admin_notes')->nullable()->after('expected_completion_at');

            // Customer quotation response
            $table->string('quotation_status')->default('draft')->index()->after('admin_notes');
            $table->timestamp('customer_responded_at')->nullable()->after('quotation_status');
            $table->text('customer_rejection_reason')->nullable()->after('customer_responded_at');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['quotation_status']);

            $table->dropColumn([
                'advance_requested',
                'expected_completion_at',
                'admin_notes',
                'quotation_status',
                'customer_responded_at',
                'customer_rejection_reason',
            ]);
        });
    }
};