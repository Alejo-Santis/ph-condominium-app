<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // PostgreSQL: drop old CHECK constraint, add new one with lowercase values
        DB::statement('ALTER TABLE people DROP CONSTRAINT IF EXISTS people_document_type_check');
        DB::statement("ALTER TABLE people ADD CONSTRAINT people_document_type_check CHECK (document_type IN ('cc','ce','nit','passport'))");
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE people DROP CONSTRAINT IF EXISTS people_document_type_check');
        DB::statement("ALTER TABLE people ADD CONSTRAINT people_document_type_check CHECK (document_type IN ('CC','CE','NIT','PASSPORT'))");
    }
};
