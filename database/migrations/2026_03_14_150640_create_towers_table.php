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
        Schema::create('towers', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
            $table->string('name', 200);
            $table->unsignedSmallInteger('floors')->default(1);
            $table->boolean('has_elevator')->default(false);
            $table->boolean('has_parking')->default(false);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('description', 500)->nullable();
            $table->timestamps();

            $table->index('uuid');
            $table->index(['property_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('towers');
    }
};
