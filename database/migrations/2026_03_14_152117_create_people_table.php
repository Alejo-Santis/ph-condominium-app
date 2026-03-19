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
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->enum('document_type', ['CC', 'CE', 'NIT', 'PASSPORT']);
            $table->string('document_number', 50)->unique();
            $table->string('first_name', 150);
            $table->string('last_name', 150);
            $table->string('email', 150)->unique();
            $table->string('phone', 20)->nullable();
            $table->string('alt_phone', 20)->nullable();
            $table->timestamps();

            $table->index('uuid');
            $table->index('email');
            $table->index('document_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
