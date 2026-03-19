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
        Schema::create('charges', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('unit_id')->constrained('units')->restrictOnDelete();
            $table->foreignId('person_id')->constrained('people')->restrictOnDelete();
            $table->string('concept', 200);
            $table->decimal('amount', 12, 2);
            $table->date('billing_month');
            $table->enum('origin', ['external_api', 'manual'])->default('external_api');
            $table->string('external_reference', 100)->nullable()->comment('Reference ID from external API');
            $table->enum('status', ['pending', 'link_generated', 'paid', 'overdue', 'cancelled'])->default('pending');
            $table->timestamps();

            $table->index('uuid');
            $table->index(['unit_id', 'status']);
            $table->index(['person_id', 'status']);
            $table->index(['billing_month', 'status']);
            $table->index('external_reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('charges');
    }
};
