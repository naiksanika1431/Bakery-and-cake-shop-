<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_cake_details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->unique()
                ->constrained('orders')
                ->cascadeOnDelete();

            // Booking requirements
            $table->string('occasion');
            $table->date('required_date');
            $table->time('required_time');
            $table->string('fulfilment_type');
            $table->unsignedInteger('servings');

            // Cake specifications
            $table->string('flavour');
            $table->decimal('weight_kg', 8, 2);
            $table->string('cake_size')->nullable();
            $table->string('shape');
            $table->text('dimensions')->nullable();
            $table->decimal('dimension_cm', 8, 2)->nullable();
            $table->string('shape_complexity')->nullable();
            $table->string('theme')->nullable();
            $table->string('colour')->nullable();
            $table->string('cream_type')->nullable();
            $table->text('decorations')->nullable();
            $table->string('cake_message')->nullable();
            $table->string('reference_image_path')->nullable();
            $table->text('special_instructions')->nullable();

            // Calculated pricing snapshot
            $table->decimal('flavour_charge', 12, 2)->default(0);
            $table->decimal('shape_charge', 12, 2)->default(0);
            $table->decimal('theme_decoration_charge', 12, 2)->default(0);
            $table->decimal('cream_charge', 12, 2)->default(0);
            $table->decimal('delivery_charge', 12, 2)->default(0);
            $table->decimal('calculated_total', 12, 2)->default(0);
            $table->json('pricing_snapshot')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_cake_details');
    }
};
