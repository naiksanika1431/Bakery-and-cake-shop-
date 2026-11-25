<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')
                ->constrained('companies')
                ->cascadeOnDelete();

            $table->string('name');
            $table->string('abbreviation');
            $table->unsignedTinyInteger('precision')->default(2);

            $table->timestamps();

            $table->unique(['company_id', 'name']);
            $table->unique(['company_id', 'abbreviation']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
