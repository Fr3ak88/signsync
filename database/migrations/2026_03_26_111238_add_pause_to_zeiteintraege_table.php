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
        // Wir fügen die Pause als Ganzzahl (Minuten) hinzu. 
        // Default 0, damit alte Einträge nicht kaputtgehen.
        $table->integer('pause_minuten')->default(0)->after('ende_zeit');
    });
}

/**
 * Reverse the migrations.
 */
public function down(): void
{
    Schema::table('zeiteintraege', function (Blueprint $table) {
        // Falls wir die Migration rückgängig machen, löschen wir die Spalte wieder
        $table->dropColumn('pause_minuten');
    });
}
};
