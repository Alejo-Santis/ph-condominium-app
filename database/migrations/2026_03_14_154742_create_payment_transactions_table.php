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
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('charge_id')->constrained('charges')->restrictOnDelete();
            $table->string('wompi_link_id', 100)->nullable()->comment('ID del enlace de pago en Wompi');
            $table->string('wompi_transaction_id', 100)->nullable()->unique();
            $table->string('wompi_reference', 100)->unique();
            $table->text('payment_url')->nullable()->comment('URL para realizar el pago');
            $table->string('wompi_status', 50)->nullable();
            $table->decimal('amount_paid', 12, 2)->nullable();
            $table->timestampTz('paid_at')->nullable();
            $table->json('webhook_payload')->nullable();
            $table->timestamps();

            $table->index('uuid');
            $table->index(['charge_id', 'wompi_status']);
            $table->index('wompi_reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
