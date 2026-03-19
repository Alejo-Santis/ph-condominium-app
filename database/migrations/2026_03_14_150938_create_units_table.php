<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('tower_id')->constrained('towers')->cascadeOnDelete();
            $table->string('number', 50);
            $table->unsignedSmallInteger('floor');
            $table->decimal('area_sqm', 8, 2)->nullable()->comment('Area in square meters');
            $table->decimal('coefficient', 10, 6)->nullable()->comment('Coefficient for common expenses');
            $table->enum('type', ['residential', 'commercial', 'mixed'])->default('residential');
            $table->enum('status', ['occupied', 'vacant', 'for_sale'])->default('vacant');
            $table->timestamps();

            $table->index('uuid');
            $table->index(['tower_id', 'status']);
            $table->unique(['tower_id', 'number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
