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
    Schema::table('monats_abschluesse', function (Blueprint $table) {
        if (!Schema::hasColumn('monats_abschluesse', 'file_hash')) {
            $table->string('file_hash')->after('pdf_path')->nullable();
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monats_abschluesse', function (Blueprint $table) {
            //
        });
    }
};
