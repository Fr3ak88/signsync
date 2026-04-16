<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
    Schema::table('monatsabschlusse', function (Blueprint $table) {
        $table->string('pdf_path')->after('ist_abgeschlossen')->nullable();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monatsabschlusse', function (Blueprint $table) {
            //
        });
    }
};
