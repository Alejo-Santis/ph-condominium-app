<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * La tabla system_users fue consolidada en la tabla users.
 * Esta migración agrega los FK constraints de person_id y property_id
 * a users, ahora que ambas tablas ya existen.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('person_id')
                ->references('id')->on('people')
                ->nullOnDelete();

            $table->foreign('property_id')
                ->references('id')->on('properties')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['person_id']);
            $table->dropForeign(['property_id']);
        });
    }
};
