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
    Schema::table('zeiteintraege', function (Blueprint $table) {
        $table->boolean('is_locked')->default(false); // GoBD Sperre
        $table->string('content_hash')->nullable();   // Fingerabdruck der Daten
        $table->softDeletes();                        // deleted_at Spalte
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('zeiteintraege', function (Blueprint $table) {
            //
        });
    }
};
