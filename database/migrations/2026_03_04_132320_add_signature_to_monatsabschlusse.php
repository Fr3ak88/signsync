<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('monatsabschlusse', function (Blueprint $table) {
        $table->longText('schule_signatur')->nullable(); // Speichert das Bild als Base64
        $table->string('schule_unterzeichner')->nullable(); // Name der Lehrkraft
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
