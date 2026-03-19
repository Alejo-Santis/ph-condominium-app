<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unit_people', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('unit_id')->constrained('units')->cascadeOnDelete();
            $table->foreignId('person_id')->constrained('people')->cascadeOnDelete();
            $table->enum('role', ['owner', 'tenant']);
            $table->dateTimeTz('start_date')->nullable();
            $table->dateTimeTz('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('uuid');
            $table->index(['unit_id', 'role', 'is_active']);
            $table->index(['person_id', 'is_active']);
        });
        // Nota: la restricción de un solo owner/tenant activo por unidad
        // se valida a nivel de aplicación en el modelo UnitPerson::booted().
        // MySQL no soporta índices únicos parciales (WHERE is_active = true).
    }

    public function down(): void
    {
        Schema::dropIfExists('unit_people');
    }
};
