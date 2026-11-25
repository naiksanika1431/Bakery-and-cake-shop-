<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Central product master fields
        Schema::table('products', function (Blueprint $table) {
            $table->string('barcode')->nullable()->after('sku');

            $table->foreignId('base_unit_id')
                ->nullable()
                ->after('category_id')
                ->constrained('units')
                ->nullOnDelete();

            $table->string('product_type', 50)
                ->default('finished_good')
                ->after('base_unit_id');

            $table->string('hsn_code', 20)->nullable()->after('product_type');

            $table->decimal('gst_rate', 5, 2)
                ->default(0)
                ->after('hsn_code');

            $table->unique(
                ['company_id', 'barcode'],
                'products_company_barcode_unique'
            );
        });

        // Central variant pricing
        Schema::table('product_variants', function (Blueprint $table) {
            $table->decimal('default_selling_price', 12, 2)
                ->nullable()
                ->after('unit_id');

            $table->decimal('cost_price', 12, 2)
                ->default(0)
                ->after('default_selling_price');
        });

        // Branch-specific controls
        Schema::table('branch_products', function (Blueprint $table) {
            $table->boolean('is_enabled')
                ->default(true)
                ->after('is_available');

            $table->boolean('online_available')
                ->default(true)
                ->after('is_enabled');
        });
    }

    public function down(): void
    {
        Schema::table('branch_products', function (Blueprint $table) {
            $table->dropColumn(['is_enabled', 'online_available']);
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn(['default_selling_price', 'cost_price']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropUnique('products_company_barcode_unique');
            $table->dropConstrainedForeignId('base_unit_id');

            $table->dropColumn([
                'barcode',
                'product_type',
                'hsn_code',
                'gst_rate',
            ]);
        });
    }
};
