<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('person_id')->nullable()->constrained('people')->nullOnDelete();
            $table->foreignId('charge_id')->nullable()->constrained('charges')->nullOnDelete();
            $table->enum('channel', ['email', 'sms', 'push']);
            $table->string('type', 100);
            $table->string('recipient', 200);
            $table->string('subject', 255)->nullable();
            $table->text('body');
            $table->enum('status', ['pending', 'sent', 'failed', 'bounced'])->default('pending');
            $table->json('data')->nullable();
            $table->timestampTz('sent_at')->nullable();
            $table->timestampTz('read_at')->nullable();
            $table->timestamps();

            $table->index('uuid');
            $table->index(['person_id', 'status']);
            $table->index(['charge_id', 'type']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
