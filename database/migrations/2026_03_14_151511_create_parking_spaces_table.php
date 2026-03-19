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
        Schema::create('parking_spaces', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
            $table->foreignId('tower_id')->nullable()->constrained('towers')->nullOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained('units')->nullOnDelete();
            $table->string('code', 20);
            $table->enum('type', ['vehicle', 'motorcycle', 'bicycle'])->default('vehicle');
            $table->enum('status', ['available', 'assigned', 'blocked'])->default('available');
            $table->timestamps();

            $table->index('uuid');
            $table->index(['property_id', 'status']);
            $table->index('unit_id');
            $table->unique(['property_id', 'code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parking_spaces');
    }
};
