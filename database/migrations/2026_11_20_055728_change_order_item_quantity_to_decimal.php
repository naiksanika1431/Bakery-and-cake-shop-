<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->decimal('quantity', 12, 3)
                ->default(1)
                ->change();
        });
    }

    public function down(): void
    {
        $hasFractionalQuantities = DB::table('order_items')
            ->whereRaw('quantity <> FLOOR(quantity)')
            ->exists();

        if ($hasFractionalQuantities) {
            throw new RuntimeException(
                'Cannot roll back: fractional order item quantities exist.'
            );
        }

        Schema::table('order_items', function (Blueprint $table) {
            $table->unsignedInteger('quantity')
                ->default(1)
                ->change();
        });
    }
};